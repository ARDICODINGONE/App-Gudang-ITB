<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Stok;
use App\Models\Gudang;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $gudangKode = $request->query('gudang');

        if ($gudangKode) {
            $barangs = Barang::with(['kategori', 'stok' => function ($q) use ($gudangKode) {
                $q->where('kode_gudang', $gudangKode);
            }])->whereHas('stok', function ($q) use ($gudangKode) {
                $q->where('kode_gudang', $gudangKode);
            })->get();
        } else {
            $barangs = Barang::with('kategori')->get();
        }

        $kategoris = Kategori::all();

        // compute next kode_barang so modal can render it server-side (no client delay)
        $last = Barang::where('kode_barang', 'like', 'BR%')
            ->orderByRaw('LENGTH(kode_barang) desc, kode_barang desc')
            ->first();

        $num = 0;
        if ($last && preg_match('/BR0*([0-9]+)$/', $last->kode_barang, $m)) {
            $num = (int) $m[1];
        }
        $nextKode = 'BR' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);

        return view('content.barang.index', compact('barangs', 'kategoris', 'gudangKode', 'nextKode'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $gudangs = Gudang::all();
        // compute next kode_barang for direct create page as well
        $last = Barang::where('kode_barang', 'like', 'BR%')
            ->orderByRaw('LENGTH(kode_barang) desc, kode_barang desc')
            ->first();

        $num = 0;
        if ($last && preg_match('/BR0*([0-9]+)$/', $last->kode_barang, $m)) {
            $num = (int) $m[1];
        }
        $nextKode = 'BR' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);

        return view('content.barang.create', compact('kategoris', 'gudangs', 'nextKode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang|max:10',
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'gudang' => 'nullable|exists:gudang,kode_gudang',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ], [
            'kode_barang.unique' => 'Kode Barang sudah ada, gunakan kode lain.',
            'kode_barang.required' => 'Kode Barang wajib diisi.',
        ]);

        $data = $request->only(['kode_barang', 'nama_barang', 'kategori_id', 'satuan', 'deskripsi', 'harga']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images/barang', 'public');
        }

        $new = barang::create($data);

        if ($request->filled('gudang')) {
            stok::create([
                'id_barang' => $new->id,
                'kode_gudang' => $request->gudang,
                // initial_stock removed from form: set initial stok to 0 by default
                'stok' => 0,
            ]);
            // Redirect back to the gudang page (uses query param 'kode') so the shop view for that gudang
            // shows the newly added item immediately after redirect.
            return redirect()->route('barang-index', ['gudang' => $request->gudang])->with('success', 'Barang berhasil ditambahkan!');

        }

        return redirect()->route('barang-index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(Request $request, $kode_barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $barang = barang::where('kode_barang', $kode_barang)->firstOrFail();

        $data = $request->only(['nama_barang', 'kategori_id', 'satuan', 'deskripsi', 'harga']);

        if ($request->hasFile('image')) {
            if ($barang->image) {
                Storage::disk('public')->delete($barang->image);
            }
            $data['image'] = $request->file('image')->store('images/barang', 'public');
        }

        $barang->update($data);

        // Update stok for given gudang if provided
        if ($request->filled('gudang') && $request->filled('stok')) {
            stok::updateOrCreate([
                'id_barang' => $barang->id,
                'kode_gudang' => $request->gudang,
            ], [
                'stok' => (int) $request->stok,
            ]);
        }

        return redirect()->route('barang-index')->with('success', 'Data barang berhasil diperbarui!');

        $request->validate([
            'nama_barang' => 'required|string',
            'deskripsi' => 'required|string',
            'kategori_id' => 'required',
            'satuan' => 'required',
            'harga' => 'required|numeric|min:1',
        ]);

    }

    public function destroy($kode_barang)
    {
        $barang = barang::where('kode_barang', $kode_barang)->firstOrFail();
        if ($barang->image) {
            Storage::disk('public')->delete($barang->image);
        }
        $barang->delete();
        return redirect()->route('barang-index')->with('success', 'Data barang berhasil dihapus!');
    }

    /**
     * Return JSON list of products for the shop frontend.
     */
    public function apiIndex(Request $request)
    {
        $gudangKode = $request->query('gudang');
        if ($gudangKode) {
            // Aggregate masuk per barang and read current stok per barang for this gudang
            $masukAgg = BarangMasuk::select('id_barang', DB::raw('SUM(jumlah) as total_masuk'))
                ->where('kode_gudang', $gudangKode)
                ->groupBy('id_barang')
                ->get()
                ->keyBy('id_barang');

            $stokItems = Stok::where('kode_gudang', $gudangKode)->get()->keyBy('id_barang');

            $ids = $masukAgg->keys()->all();

            $barangs = barang::with('kategori')->whereIn('id', $ids)->get();
        } else {
            $barangs = barang::with('kategori')->get();
            $masukAgg = collect();
            // Ensure $stokItems exists when no gudang filter is provided
            $stokItems = collect();
        }

        $data = $barangs->map(function ($b) use ($masukAgg, $gudangKode, $stokItems) {
            $totalMasuk = null;
            if ($gudangKode && isset($masukAgg[$b->id])) {
                $totalMasuk = (int) $masukAgg[$b->id]->total_masuk;
            }

            // Prefer actual stok table value when available, otherwise fallback to total_masuk
            $stokVal = null;
            if ($gudangKode && isset($stokItems[$b->id])) {
                $stokVal = (int) $stokItems[$b->id]->stok;
            } elseif ($totalMasuk !== null) {
                $stokVal = $totalMasuk;
            }
            return [
                'id' => $b->id,
                'kode' => $b->kode_barang,
                'name' => $b->nama_barang,
                'price' => $b->harga,
                'image' => $b->image ? asset('storage/' . $b->image) : asset('img/product-1.png'),
                'satuan' => $b->satuan,
                'deskripsi' => $b->deskripsi,
                'kategori' => $b->kategori ? ($b->kategori->kategori ?? null) : null,
                'kategori_slug' => $b->kategori ? \Illuminate\Support\Str::slug($b->kategori->kategori) : null,
                // When filtering by gudang, include total_masuk as 'stok' so shop shows quantities based on barang_masuk
                'stok' => $stokVal,
            ];
        });

        return response()->json($data);
    }

    /**
     * Return next kode_barang in format BRnnn (e.g. BR001)
     */
    public function nextKode()
    {
        // Find the last kode_barang that starts with BR and extract numeric suffix
        $last = barang::where('kode_barang', 'like', 'BR%')
            ->orderByRaw('LENGTH(kode_barang) desc, kode_barang desc')
            ->first();

        $num = 0;
        if ($last && preg_match('/BR0*([0-9]+)$/', $last->kode_barang, $m)) {
            $num = (int) $m[1];
        }

        $next = 'BR' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);

        return response()->json(['kode' => $next]);
    }

    /**
     * Check for duplicate barang by name (+ optional kategori and satuan).
     * Returns JSON { exists: bool, message: string, kode: string|null }
     */
    public function checkDuplicate(Request $request)
    {
        $name = trim($request->query('nama_barang', ''));
        $kategori = $request->query('kategori_id');
        $satuan = $request->query('satuan');

        if ($name === '') {
            return response()->json(['exists' => false]);
        }

        $query = barang::whereRaw('LOWER(nama_barang) = ?', [mb_strtolower($name)]);

        if ($kategori) {
            $query->where('kategori_id', $kategori);
        }

        if ($satuan) {
            $query->whereRaw('LOWER(satuan) = ?', [mb_strtolower($satuan)]);
        }

        $found = $query->first();

        if ($found) {
            return response()->json([
                'exists' => true,
                'message' => 'Barang dengan nama yang sama sudah ada' . ($found->kode_barang ? " (kode: {$found->kode_barang})" : ''),
                'kode' => $found->kode_barang ?? null,
            ]);
        }

        return response()->json(['exists' => false]);
    }

    /**
     * Download a CSV template for importing barang
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="barang-template.csv"',
        ];

        $columns = ['kode_barang', 'nama_barang', 'kategori', 'satuan', 'deskripsi', 'harga', 'image'];

        $callback = function () use ($columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            // sample row (image can be URL or path relative to public/ or storage/app/public/)
            fputcsv($handle, ['BR001', 'Contoh Barang', 'Umum', 'pcs', 'Deskripsi contoh', '10000', 'https://example.com/sample.jpg']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export all barang to XLSX
     */
    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $columns = ['kode_barang', 'nama_barang', 'kategori', 'satuan', 'deskripsi', 'harga', 'image'];
        $sheet->fromArray([$columns], null, 'A1');

        // Set header style
        $headerStyle = $sheet->getStyle('A1:G1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
        $headerStyle->getFill()->setFillType('solid');
        $headerStyle->getFill()->getStartColor()->setARGB('FF366092');

        // Get all barang with kategori
        $barangs = Barang::with('kategori')->get();

        $row = 2;
        foreach ($barangs as $barang) {
            $sheet->setCellValue('A' . $row, $barang->kode_barang);
            $sheet->setCellValue('B' . $row, $barang->nama_barang);
            $sheet->setCellValue('C' . $row, $barang->kategori ? $barang->kategori->kategori : '');
            $sheet->setCellValue('D' . $row, $barang->satuan);
            $sheet->setCellValue('E' . $row, $barang->deskripsi ?? '');
            $sheet->setCellValue('F' . $row, $barang->harga);
            $sheet->setCellValue('G' . $row, $barang->image ?? '');
            $row++;
        }

        // Auto-fit columns
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new XlsxWriter($spreadsheet);
        $filename = 'barang-' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Import barang from uploaded XLSX or CSV file.
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
     * Import barang from XLSX file
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
            $required = ['nama_barang', 'kategori', 'satuan', 'harga'];
            $missing = array_filter($required, function ($r) use ($map) {
                return !array_key_exists($r, $map);
            });
            if (!empty($missing)) {
                $missingList = implode(', ', $missing);
                return redirect()->back()->with('error', "Header XLSX tidak lengkap. Kolom yang diperlukan: {$missingList}.");
            }

            // Find last numeric suffix for kode generation
            $last = Barang::where('kode_barang', 'like', 'BR%')
                ->orderByRaw('LENGTH(kode_barang) desc, kode_barang desc')
                ->first();
            $num = 0;
            if ($last && preg_match('/BR0*([0-9]+)$/', $last->kode_barang, $m)) {
                $num = (int) $m[1];
            }

            $imported = 0;
            $errors = [];

            // Process data rows (skip header)
            for ($rowNumber = 2; $rowNumber <= count($rows); $rowNumber++) {
                if (!isset($rows[$rowNumber - 1])) {
                    break;
                }

                $row = $rows[$rowNumber - 1];

                $kode = isset($map['kode_barang']) && isset($row[$map['kode_barang']]) ? trim($row[$map['kode_barang']]) : '';
                $nama = isset($map['nama_barang']) && isset($row[$map['nama_barang']]) ? trim($row[$map['nama_barang']]) : '';
                $kategoriVal = isset($map['kategori']) && isset($row[$map['kategori']]) ? trim($row[$map['kategori']]) : '';
                $satuan = isset($map['satuan']) && isset($row[$map['satuan']]) ? trim($row[$map['satuan']]) : '';
                $deskripsi = isset($map['deskripsi']) && isset($row[$map['deskripsi']]) ? trim($row[$map['deskripsi']]) : '';
                $harga = isset($map['harga']) && isset($row[$map['harga']]) ? trim($row[$map['harga']]) : '';
                $imageVal = isset($map['image']) && isset($row[$map['image']]) ? trim($row[$map['image']]) : '';

                // Skip header row if it appears in data (detect by column name)
                if (strtolower($nama) === 'nama_barang' || strtolower($kode) === 'kode_barang') {
                    continue;
                }

                // Skip empty rows
                if ($nama === '' && $kategoriVal === '' && $satuan === '') {
                    continue;
                }

                // Validations
                if ($nama === '') {
                    $errors[] = "Baris {$rowNumber}: nama_barang kosong.";
                    continue;
                }

                if ($satuan === '') {
                    $errors[] = "Baris {$rowNumber}: satuan kosong.";
                    continue;
                }

                $hargaNumeric = preg_replace('/[^0-9\-\.]/', '', $harga);
                $hargaVal = is_numeric($hargaNumeric) ? (float) $hargaNumeric : null;
                if ($hargaVal === null) {
                    $errors[] = "Baris {$rowNumber}: harga tidak valid.";
                    continue;
                }

                // Resolve or create kategori
                $kategori_id = null;
                if ($kategoriVal !== '') {
                    if (is_numeric($kategoriVal)) {
                        $k = Kategori::find((int)$kategoriVal);
                        if ($k) {
                            $kategori_id = $k->id;
                        }
                    } else {
                        $k = Kategori::whereRaw('LOWER(kategori) = ?', [mb_strtolower($kategoriVal)])->first();
                        if (!$k) {
                            $k = Kategori::create(['kategori' => $kategoriVal]);
                        }
                        $kategori_id = $k->id;
                    }
                }

                if (!$kategori_id) {
                    $errors[] = "Baris {$rowNumber}: kategori tidak valid atau kosong.";
                    continue;
                }

                // Generate kode if empty
                if ($kode === '') {
                    $num++;
                    $kode = 'BR' . str_pad($num, 3, '0', STR_PAD_LEFT);
                } else {
                    // Ensure kode uniqueness
                    if (Barang::where('kode_barang', $kode)->exists()) {
                        $errors[] = "Baris {$rowNumber}: kode_barang '{$kode}' sudah ada, dilewati.";
                        continue;
                    }
                }

                // Insert barang
                try {
                    $new = Barang::create([
                        'kode_barang' => $kode,
                        'nama_barang' => $nama,
                        'kategori_id' => $kategori_id,
                        'satuan' => $satuan,
                        'deskripsi' => $deskripsi,
                        'harga' => $hargaVal,
                    ]);

                    // Handle optional image
                    if ($imageVal) {
                        try {
                            $storedPath = null;
                            // Remote URL - download and save
                            if (preg_match('/^https?:\/\//i', $imageVal)) {
                                $contents = @file_get_contents($imageVal);
                                if ($contents !== false && !empty($contents)) {
                                    $ext = pathinfo(parse_url($imageVal, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                                    $filename = 'images/barang/' . time() . '_' . Str::random(8) . '.' . $ext;
                                    Storage::disk('public')->put($filename, $contents);
                                    $storedPath = $filename;
                                } else {
                                    // If download fails, skip image
                                    $errors[] = "Baris {$rowNumber}: gagal mengunduh image dari URL: {$imageVal}";
                                }
                            } else {
                                // Local file path or filename - save as is (user must ensure file exists in storage)
                                $storedPath = $imageVal;
                            }

                            if (!empty($storedPath)) {
                                $new->image = $storedPath;
                                $new->save();
                            }
                        } catch (\Exception $ex) {
                            $errors[] = "Baris {$rowNumber}: gagal menyimpan image ({$ex->getMessage()}).";
                        }
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
     * Import barang from CSV file
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
        $required = ['nama_barang', 'kategori', 'satuan', 'harga'];
        $missing = array_filter($required, function ($r) use ($map) {
            return !array_key_exists($r, $map);
        });
        if (!empty($missing)) {
            fclose($handle);
            $missingList = implode(', ', $missing);
            return redirect()->back()->with('error', "Header CSV tidak lengkap. Kolom yang diperlukan: {$missingList}.");
        }

        // Find last numeric suffix for kode generation
        $last = Barang::where('kode_barang', 'like', 'BR%')
            ->orderByRaw('LENGTH(kode_barang) desc, kode_barang desc')
            ->first();
        $num = 0;
        if ($last && preg_match('/BR0*([0-9]+)$/', $last->kode_barang, $m)) {
            $num = (int) $m[1];
        }

        $rowNumber = 1;
        $imported = 0;
        $errors = [];

        // Process rows
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rowNumber++;
            $kode = isset($map['kode_barang']) && isset($row[$map['kode_barang']]) ? trim($row[$map['kode_barang']]) : '';
            $nama = isset($map['nama_barang']) && isset($row[$map['nama_barang']]) ? trim($row[$map['nama_barang']]) : '';
            $kategoriVal = isset($map['kategori']) && isset($row[$map['kategori']]) ? trim($row[$map['kategori']]) : '';
            $satuan = isset($map['satuan']) && isset($row[$map['satuan']]) ? trim($row[$map['satuan']]) : '';
            $deskripsi = isset($map['deskripsi']) && isset($row[$map['deskripsi']]) ? trim($row[$map['deskripsi']]) : '';
            $harga = isset($map['harga']) && isset($row[$map['harga']]) ? trim($row[$map['harga']]) : '';
            $imageVal = isset($map['image']) && isset($row[$map['image']]) ? trim($row[$map['image']]) : '';

            // Skip header row if it appears in data (detect by column name)
            if (strtolower($nama) === 'nama_barang' || strtolower($kode) === 'kode_barang') {
                continue;
            }

            // Validations
            if ($nama === '') {
                $errors[] = "Baris {$rowNumber}: nama_barang kosong.";
                continue;
            }

            if ($satuan === '') {
                $errors[] = "Baris {$rowNumber}: satuan kosong.";
                continue;
            }

            $hargaNumeric = preg_replace('/[^0-9\-\.]/', '', $harga);
            $hargaVal = is_numeric($hargaNumeric) ? (float) $hargaNumeric : null;
            if ($hargaVal === null) {
                $errors[] = "Baris {$rowNumber}: harga tidak valid.";
                continue;
            }

            // Resolve or create kategori
            $kategori_id = null;
            if ($kategoriVal !== '') {
                if (is_numeric($kategoriVal)) {
                    $k = Kategori::find((int)$kategoriVal);
                    if ($k) {
                        $kategori_id = $k->id;
                    }
                } else {
                    $k = Kategori::whereRaw('LOWER(kategori) = ?', [mb_strtolower($kategoriVal)])->first();
                    if (!$k) {
                        $k = Kategori::create(['kategori' => $kategoriVal]);
                    }
                    $kategori_id = $k->id;
                }
            }

            if (!$kategori_id) {
                $errors[] = "Baris {$rowNumber}: kategori tidak valid atau kosong.";
                continue;
            }

            // Generate kode if empty
            if ($kode === '') {
                $num++;
                $kode = 'BR' . str_pad($num, 3, '0', STR_PAD_LEFT);
            } else {
                // Ensure kode uniqueness
                if (Barang::where('kode_barang', $kode)->exists()) {
                    $errors[] = "Baris {$rowNumber}: kode_barang '{$kode}' sudah ada, dilewati.";
                    continue;
                }
            }

            // Insert barang
            try {
                $new = Barang::create([
                    'kode_barang' => $kode,
                    'nama_barang' => $nama,
                    'kategori_id' => $kategori_id,
                    'satuan' => $satuan,
                    'deskripsi' => $deskripsi,
                    'harga' => $hargaVal,
                ]);

                // Handle optional image
                if ($imageVal) {
                    try {
                        $storedPath = null;
                        // Remote URL - download and save
                        if (preg_match('/^https?:\/\//i', $imageVal)) {
                            $contents = @file_get_contents($imageVal);
                            if ($contents !== false && !empty($contents)) {
                                $ext = pathinfo(parse_url($imageVal, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                                $filename = 'images/barang/' . time() . '_' . Str::random(8) . '.' . $ext;
                                Storage::disk('public')->put($filename, $contents);
                                $storedPath = $filename;
                            } else {
                                // If download fails, skip image
                                $errors[] = "Baris {$rowNumber}: gagal mengunduh image dari URL: {$imageVal}";
                            }
                        } else {
                            // Local file path or filename - save as is (user must ensure file exists in storage)
                            $storedPath = $imageVal;
                        }

                        if (!empty($storedPath)) {
                            $new->image = $storedPath;
                            $new->save();
                        }
                    } catch (\Exception $ex) {
                        $errors[] = "Baris {$rowNumber}: gagal menyimpan image ({$ex->getMessage()}).";
                    }
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
