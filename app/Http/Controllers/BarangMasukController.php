<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Stok;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

class BarangMasukController extends Controller
{
    public function index()
    {
        $items = BarangMasuk::with(['barang', 'gudang', 'user'])->orderBy('tanggal', 'desc')->paginate(15);
        $barangs = Barang::orderBy('nama_barang')->get();
        $gudangs = Gudang::orderBy('kode_gudang')->get();

        return view('content.barang_masuk.index', compact('items', 'barangs', 'gudangs'));
    }

    public function create(Request $request)
    {
        $gudangKode = $request->query('gudang');
        $selectedBarang = $request->query('barang');

        $barangs = Barang::orderBy('nama_barang')->get();
        $gudangs = Gudang::orderBy('kode_gudang')->get();

        // If accessed from a gudang page (gudang query present), show a full page form
        if ($gudangKode) {
            return view('content.barang_masuk.create_page', compact('barangs', 'gudangs', 'gudangKode', 'selectedBarang'));
        }

        // Default: return the modal partial as before
        return view('content.barang_masuk.create', compact('barangs', 'gudangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'kode_gudang' => 'required|exists:gudang,kode_gudang',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $created = BarangMasuk::create([
            'id_barang' => $request->id_barang,
            'kode_gudang' => $request->kode_gudang,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'id_users' => Auth::id() ?? 1,
        ]);

        // Update stok: increment existing stok for the gudang, or create a new stok record
        $existing = Stok::where('id_barang', $request->id_barang)
            ->where('kode_gudang', $request->kode_gudang)
            ->first();

        if ($existing) {
            $existing->increment('stok', (int) $request->jumlah);
        } else {
            Stok::create([
                'id_barang' => $request->id_barang,
                'kode_gudang' => $request->kode_gudang,
                'stok' => (int) $request->jumlah,
            ]);
        }

        // If request expects JSON (AJAX), return JSON so client fetch can proceed reliably
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'barang_masuk_id' => $created->id,
                'kode_gudang' => $request->kode_gudang,
            ], 201);
        }

        return redirect()->route('barang-masuk-index')->with('success', 'Barang masuk berhasil ditambahkan!');
    }

    public function update(Request $request, BarangMasuk $barang_masuk)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'kode_gudang' => 'required|exists:gudang,kode_gudang',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $barang_masuk->update([
            'id_barang' => $request->id_barang,
            'kode_gudang' => $request->kode_gudang,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('barang-masuk-index')->with('success', 'Data barang masuk berhasil diperbarui!');
    }

    public function destroy(BarangMasuk $barang_masuk)
    {
        $barang_masuk->delete();
        return redirect()->route('barang-masuk-index')->with('success', 'Data barang masuk berhasil dihapus!');
    }

    /**
     * Bulk delete barang_masuk entries.
     */
    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:barang_masuk,id'
        ]);

        \DB::table('barang_masuk')->whereIn('id', $data['ids'])->delete();

        return redirect()->route('barang-masuk-index')->with('success', 'Data barang masuk terpilih berhasil dihapus.');
    }

    /**
     * Download XLSX template for importing barang masuk
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $columns = ['kode_barang', 'nama_barang', 'nama_gudang', 'jumlah', 'tanggal'];
        $sheet->fromArray([$columns], null, 'A1');

        // Set header style
        $headerStyle = $sheet->getStyle('A1:E1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
        $headerStyle->getFill()->setFillType('solid');
        $headerStyle->getFill()->getStartColor()->setARGB('FF366092');

        // Add sample data
        $sheet->fromArray([
            ['BR001', 'Kertas', 'Gudang Utama', 100, '2026-01-01'],
            ['BR002', 'Pensil', 'Gudang Cabang', 50, '2026-01-02'],
        ], null, 'A2');

        // Auto-fit columns
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Set date format for tanggal column
        $sheet->getStyle('E2:E1000')->getNumberFormat()->setFormatCode('YYYY-MM-DD');

        $writer = new XlsxWriter($spreadsheet);
        $filename = 'barang-masuk-template-' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Export all barang masuk to XLSX
     */
    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $columns = ['id', 'kode_barang', 'nama_barang', 'kode_gudang', 'nama_gudang', 'jumlah', 'tanggal', 'user'];
        $sheet->fromArray([$columns], null, 'A1');

        // Set header style
        $headerStyle = $sheet->getStyle('A1:H1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
        $headerStyle->getFill()->setFillType('solid');
        $headerStyle->getFill()->getStartColor()->setARGB('FF366092');

        // Get all barang masuk with relations
        $items = BarangMasuk::with(['barang', 'gudang', 'user'])->orderBy('tanggal', 'desc')->get();

        $row = 2;
        foreach ($items as $item) {
            $sheet->setCellValue('A' . $row, $item->id);
            $sheet->setCellValue('B' . $row, $item->barang->kode_barang ?? '');
            $sheet->setCellValue('C' . $row, $item->barang->nama_barang ?? '');
            $sheet->setCellValue('D' . $row, $item->kode_gudang);
            $sheet->setCellValue('E' . $row, $item->gudang->nama_gudang ?? '');
            $sheet->setCellValue('F' . $row, $item->jumlah);
            $sheet->setCellValue('G' . $row, $item->tanggal);
            $sheet->setCellValue('H' . $row, $item->user->nama ?? $item->user->username ?? '');
            $row++;
        }

        // Auto-fit columns
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new XlsxWriter($spreadsheet);
        $filename = 'barang-masuk-' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Import barang masuk from uploaded XLSX or CSV file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,txt',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        // Handle XLSX file
        if ($extension === 'xlsx') {
            return $this->importFromXlsx($file);
        }

        // Handle CSV file
        return $this->importFromCsv($file);
    }

    /**
     * Import barang masuk from XLSX file
     */
    private function importFromXlsx($file)
    {
        try {
            $reader = new XlsxReader();
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (empty($rows)) {
                return redirect()->back()->with('error', 'File XLSX kosong atau tidak valid.');
            }

            // Get headers from first row
            $headers = array_map('strtolower', array_map('trim', $rows[0]));

            // Find column indices
            $map = [];
            foreach ($headers as $i => $h) {
                $map[$h] = $i;
            }

            // Check required columns
            $required = ['nama_gudang', 'jumlah', 'tanggal'];
            $missing = array_filter($required, function ($r) use ($map) {
                return !array_key_exists($r, $map);
            });
            // Check if at least one of kode_barang or nama_barang exists
            if (!isset($map['kode_barang']) && !isset($map['nama_barang'])) {
                $missing[] = 'kode_barang atau nama_barang';
            }
            if (!empty($missing)) {
                $missingList = implode(', ', $missing);
                return redirect()->back()->with('error', "Header XLSX tidak lengkap. Kolom yang diperlukan: {$missingList}.");
            }

            $imported = 0;
            $errors = [];

            // Process data rows (skip header)
            for ($rowNumber = 2; $rowNumber <= count($rows); $rowNumber++) {
                if (!isset($rows[$rowNumber - 1])) {
                    break;
                }

                $row = $rows[$rowNumber - 1];

                $kodeBarang = isset($map['kode_barang']) && isset($row[$map['kode_barang']]) ? trim($row[$map['kode_barang']]) : '';
                $namaBarang = isset($map['nama_barang']) && isset($row[$map['nama_barang']]) ? trim($row[$map['nama_barang']]) : '';
                $namaGudang = isset($map['nama_gudang']) && isset($row[$map['nama_gudang']]) ? trim($row[$map['nama_gudang']]) : '';
                $jumlah = isset($map['jumlah']) && isset($row[$map['jumlah']]) ? trim($row[$map['jumlah']]) : '';
                $tanggal = isset($map['tanggal']) && isset($row[$map['tanggal']]) ? trim($row[$map['tanggal']]) : '';

                // Skip completely empty rows
                if ($kodeBarang === '' && $namaBarang === '' && $namaGudang === '' && $jumlah === '' && $tanggal === '') {
                    continue;
                }

                // Skip header row if it appears in data
                if (strtolower($namaBarang) === 'nama_barang' || strtolower($kodeBarang) === 'kode_barang') {
                    continue;
                }

                // Validations
                if (($kodeBarang === '' && $namaBarang === '') || $namaGudang === '' || $jumlah === '' || $tanggal === '') {
                    $errors[] = "Baris {$rowNumber}: ada kolom yang kosong (kode_barang atau nama_barang harus diisi).";
                    continue;
                }

                // Validate jumlah
                $jumlahVal = (int) $jumlah;
                if ($jumlahVal <= 0) {
                    $errors[] = "Baris {$rowNumber}: jumlah harus lebih dari 0.";
                    continue;
                }

                // Find barang by kode_barang first, then by nama_barang
                $barang = null;
                if ($kodeBarang !== '') {
                    $barang = Barang::where('kode_barang', $kodeBarang)->first();
                }
                if (!$barang && $namaBarang !== '') {
                    $barang = Barang::whereRaw('LOWER(nama_barang) = ?', [mb_strtolower($namaBarang)])->first();
                }
                if (!$barang) {
                    $ref = $kodeBarang !== '' ? "kode '{$kodeBarang}'" : "nama '{$namaBarang}'";
                    $errors[] = "Baris {$rowNumber}: barang dengan {$ref} tidak ditemukan.";
                    continue;
                }

                // Find gudang by nama
                $gudang = Gudang::whereRaw('LOWER(nama_gudang) = ?', [mb_strtolower($namaGudang)])->first();
                if (!$gudang) {
                    $errors[] = "Baris {$rowNumber}: gudang '{$namaGudang}' tidak ditemukan.";
                    continue;
                }

                // Create barang masuk
                try {
                    BarangMasuk::create([
                        'id_barang' => $barang->id,
                        'kode_gudang' => $gudang->kode_gudang,
                        'jumlah' => $jumlahVal,
                        'tanggal' => $tanggal,
                        'id_users' => Auth::id() ?? 1,
                    ]);

                    // Update stok
                    $existing = Stok::where('id_barang', $barang->id)
                        ->where('kode_gudang', $gudang->kode_gudang)
                        ->first();

                    if ($existing) {
                        $existing->increment('stok', $jumlahVal);
                    } else {
                        Stok::create([
                            'id_barang' => $barang->id,
                            'kode_gudang' => $gudang->kode_gudang,
                            'stok' => $jumlahVal,
                        ]);
                    }

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNumber}: gagal disimpan ({$e->getMessage()}).";
                }
            }

            $msg = "Import selesai. Berhasil: {$imported}.";
            if (count($errors) > 0) {
                return redirect()->back()->with('success', $msg)->with('import_errors', $errors);
            }

            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca file XLSX: ' . $e->getMessage());
        }
    }

    /**
     * Import barang masuk from CSV file
     */
    private function importFromCsv($file)
    {
        $filePathToRead = $file->getRealPath();

        $handle = fopen($filePathToRead, 'r');
        if (!$handle) {
            return redirect()->back()->with('error', 'Gagal membuka file.');
        }

        // Read raw header line to detect delimiter
        $rawHeaderLine = fgets($handle);
        if ($rawHeaderLine === false) {
            fclose($handle);
            return redirect()->back()->with('error', 'File CSV kosong atau tidak valid.');
        }

        // Detect delimiter
        $delimiter = (substr_count($rawHeaderLine, ';') > substr_count($rawHeaderLine, ',')) ? ';' : ',';

        // Parse header
        $header = str_getcsv(trim($rawHeaderLine), $delimiter);

        // Remove BOM from first header cell if present
        if (!empty($header) && is_string($header[0])) {
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        }

        $map = [];
        foreach ($header as $i => $h) {
            $map[strtolower(trim($h))] = $i;
        }

        // Check required columns
        $required = ['nama_gudang', 'jumlah', 'tanggal'];
        $missing = array_filter($required, function ($r) use ($map) {
            return !array_key_exists($r, $map);
        });
        // Check if at least one of kode_barang or nama_barang exists
        if (!isset($map['kode_barang']) && !isset($map['nama_barang'])) {
            $missing[] = 'kode_barang atau nama_barang';
        }
        if (!empty($missing)) {
            fclose($handle);
            $missingList = implode(', ', $missing);
            return redirect()->back()->with('error', "Header CSV tidak lengkap. Kolom yang diperlukan: {$missingList}.");
        }

        $rowNumber = 1;
        $imported = 0;
        $errors = [];

        // Process rows
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rowNumber++;
            $kodeBarang = isset($map['kode_barang']) && isset($row[$map['kode_barang']]) ? trim($row[$map['kode_barang']]) : '';
            $namaBarang = isset($map['nama_barang']) && isset($row[$map['nama_barang']]) ? trim($row[$map['nama_barang']]) : '';
            $namaGudang = isset($map['nama_gudang']) && isset($row[$map['nama_gudang']]) ? trim($row[$map['nama_gudang']]) : '';
            $jumlah = isset($map['jumlah']) && isset($row[$map['jumlah']]) ? trim($row[$map['jumlah']]) : '';
            $tanggal = isset($map['tanggal']) && isset($row[$map['tanggal']]) ? trim($row[$map['tanggal']]) : '';

            // Skip completely empty rows
            if ($kodeBarang === '' && $namaBarang === '' && $namaGudang === '' && $jumlah === '' && $tanggal === '') {
                continue;
            }

            // Skip header row if it appears in data
            if (strtolower($namaBarang) === 'nama_barang' || strtolower($kodeBarang) === 'kode_barang') {
                continue;
            }

            // Validations
            if (($kodeBarang === '' && $namaBarang === '') || $namaGudang === '' || $jumlah === '' || $tanggal === '') {
                $errors[] = "Baris {$rowNumber}: ada kolom yang kosong (kode_barang atau nama_barang harus diisi).";
                continue;
            }

            // Validate jumlah
            $jumlahVal = (int) $jumlah;
            if ($jumlahVal <= 0) {
                $errors[] = "Baris {$rowNumber}: jumlah harus lebih dari 0.";
                continue;
            }

            // Find barang by kode_barang first, then by nama_barang
            $barang = null;
            if ($kodeBarang !== '') {
                $barang = Barang::where('kode_barang', $kodeBarang)->first();
            }
            if (!$barang && $namaBarang !== '') {
                $barang = Barang::whereRaw('LOWER(nama_barang) = ?', [mb_strtolower($namaBarang)])->first();
            }
            if (!$barang) {
                $ref = $kodeBarang !== '' ? "kode '{$kodeBarang}'" : "nama '{$namaBarang}'";
                $errors[] = "Baris {$rowNumber}: barang dengan {$ref} tidak ditemukan.";
                continue;
            }

            // Find gudang by nama
            $gudang = Gudang::whereRaw('LOWER(nama_gudang) = ?', [mb_strtolower($namaGudang)])->first();
            if (!$gudang) {
                $errors[] = "Baris {$rowNumber}: gudang '{$namaGudang}' tidak ditemukan.";
                continue;
            }

            // Create barang masuk
            try {
                BarangMasuk::create([
                    'id_barang' => $barang->id,
                    'kode_gudang' => $gudang->kode_gudang,
                    'jumlah' => $jumlahVal,
                    'tanggal' => $tanggal,
                    'id_users' => Auth::id() ?? 1,
                ]);

                // Update stok
                $existing = Stok::where('id_barang', $barang->id)
                    ->where('kode_gudang', $gudang->kode_gudang)
                    ->first();

                if ($existing) {
                    $existing->increment('stok', $jumlahVal);
                } else {
                    Stok::create([
                        'id_barang' => $barang->id,
                        'kode_gudang' => $gudang->kode_gudang,
                        'stok' => $jumlahVal,
                    ]);
                }

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowNumber}: gagal disimpan ({$e->getMessage()}).";
            }
        }

        fclose($handle);

        $msg = "Import selesai. Berhasil: {$imported}.";
        if (count($errors) > 0) {
            return redirect()->back()->with('success', $msg)->with('import_errors', $errors);
        }

        return redirect()->back()->with('success', $msg);
    }
}
