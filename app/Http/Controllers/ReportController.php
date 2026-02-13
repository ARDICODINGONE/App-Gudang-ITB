<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stok;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Pengajuan;
use App\Models\Gudang;
use App\Models\Barang;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // Main report page
    public function index()
    {
        return view('content.laporan.index');
    }

    // Laporan Stok Gudang
    public function stokGudang(Request $request)
    {
        $gudangs = Gudang::orderBy('kode_gudang')->get();
        $selectedGudang = $request->query('gudang');
        
        $query = Stok::with(['barang', 'gudang']);
        
        if ($selectedGudang) {
            $query->where('kode_gudang', $selectedGudang);
        }
        
        // Get all data for total calculations (before pagination)
        $allStoks = $query->orderBy('kode_gudang')->orderBy('id_barang')->get();
        
        // Calculate totals from all data
        $totalStok = $allStoks->sum('stok');
        $totalValue = $allStoks->sum(function($stok) {
            return $stok->stok * ($stok->barang->harga ?? 0);
        });
        
        // Get paginated data with calculated values
        $stokWithValue = $query->orderBy('kode_gudang')->orderBy('id_barang')->paginate(20)->through(function ($stok) {
            $stok->total_value = $stok->stok * ($stok->barang->harga ?? 0);
            return $stok;
        });
        
        return view('content.laporan.stok-gudang', compact('stokWithValue', 'gudangs', 'selectedGudang', 'totalStok', 'totalValue'));
    }

    // Laporan Barang Masuk
    public function barangMasuk(Request $request)
    {
        $query = BarangMasuk::with(['barang', 'gudang', 'user']);
        
        // Filter by date range
        if ($request->query('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->query('dari_tanggal'));
        }
        
        if ($request->query('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->query('sampai_tanggal'));
        }
        
        // Filter by gudang
        if ($request->query('gudang')) {
            $query->where('kode_gudang', $request->query('gudang'));
        }
        
        // Get all data for total calculations (before pagination)
        $allBarangMasuk = $query->orderBy('tanggal', 'desc')->get();
        
        $totalJumlah = $allBarangMasuk->sum('jumlah');
        $totalNilai = $allBarangMasuk->sum(function($item) {
            return $item->jumlah * ($item->barang->harga ?? 0);
        });
        
        // Get paginated data
        $barangMasuk = $query->orderBy('tanggal', 'desc')->paginate(20);
        
        $gudangs = Gudang::orderBy('kode_gudang')->get();
        
        return view('content.laporan.barang-masuk', compact('barangMasuk', 'gudangs', 'totalJumlah', 'totalNilai'));
    }

    // Laporan Barang Keluar
    public function barangKeluar(Request $request)
    {
        $query = BarangKeluar::with(['barang', 'gudang']);
        
        // Filter by date range
        if ($request->query('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->query('dari_tanggal'));
        }
        
        if ($request->query('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->query('sampai_tanggal'));
        }
        
        // Filter by gudang
        if ($request->query('gudang')) {
            $query->where('kode_gudang', $request->query('gudang'));
        }
        
        // Get all data for total calculations (before pagination)
        $allBarangKeluar = $query->orderBy('tanggal', 'desc')->get();
        
        $totalJumlah = $allBarangKeluar->sum('jumlah');
        $totalNilai = $allBarangKeluar->sum(function($item) {
            return $item->jumlah * ($item->barang->harga ?? 0);
        });
        
        // Get paginated data
        $barangKeluar = $query->orderBy('tanggal', 'desc')->paginate(20);
        
        $gudangs = Gudang::orderBy('kode_gudang')->get();
        
        return view('content.laporan.barang-keluar', compact('barangKeluar', 'gudangs', 'totalJumlah', 'totalNilai'));
    }

    // Laporan Pengajuan
    public function pengajuan(Request $request)
    {
        $query = Pengajuan::with(['user']);
        
        // Filter by date range
        if ($request->query('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->query('dari_tanggal'));
        }
        
        if ($request->query('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->query('sampai_tanggal'));
        }
        
        // Filter by status
        if ($request->query('status')) {
            $query->where('status', $request->query('status'));
        }
        
        // Get total count before pagination
        $totalPengajuan = $query->count();
        
        // Get paginated data
        $pengajuans = $query->orderBy('tanggal', 'desc')->paginate(20);
        
        // Load details for each pengajuan on current page
        $pengajuans->getCollection()->each(function($pengajuan) {
            $pengajuan->details = $pengajuan->details ?? [];
            $pengajuan->total_items = count($pengajuan->details);
        });
        
        $statusOptions = ['pending', 'approved', 'rejected', 'completed'];
        
        return view('content.laporan.pengajuan', compact('pengajuans', 'totalPengajuan', 'statusOptions'));
    }

    // Export methods
    public function exportStokGudangExcel(Request $request)
    {
        $query = Stok::with(['barang', 'gudang']);
        
        if ($request->query('gudang')) {
            $query->where('kode_gudang', $request->query('gudang'));
        }
        
        $stoks = $query->orderBy('kode_gudang')->orderBy('id_barang')->get();
        $stokWithValue = $stoks->map(function ($stok) {
            $stok->total_value = $stok->stok * ($stok->barang->harga ?? 0);
            return $stok;
        });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header row
        $columns = ['Gudang', 'Kode Barang', 'Nama Barang', 'Stok', 'Harga Satuan', 'Nilai Total'];
        $sheet->fromArray([$columns], null, 'A1');

        // Set header style
        $headerStyle = $sheet->getStyle('A1:F1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
        $headerStyle->getFill()->setFillType('solid');
        $headerStyle->getFill()->getStartColor()->setARGB('FF366092');

        $row = 2;
        foreach ($stokWithValue as $stok) {
            $sheet->setCellValue('A' . $row, $stok->gudang->nama_gudang ?? '-');
            $sheet->setCellValue('B' . $row, $stok->barang->kode_barang ?? '-');
            $sheet->setCellValue('C' . $row, $stok->barang->nama_barang ?? '-');
            $sheet->setCellValue('D' . $row, $stok->stok);
            $sheet->setCellValue('E' . $row, $stok->barang->harga ?? 0);
            $sheet->setCellValue('F' . $row, $stok->total_value);
            $row++;
        }

        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new XlsxWriter($spreadsheet);
        $filename = 'laporan-stok-gudang-' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportBarangMasukExcel(Request $request)
    {
        $query = BarangMasuk::with(['barang', 'gudang', 'user']);
        
        if ($request->query('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->query('dari_tanggal'));
        }
        if ($request->query('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->query('sampai_tanggal'));
        }
        if ($request->query('gudang')) {
            $query->where('kode_gudang', $request->query('gudang'));
        }

        $barangMasuk = $query->orderBy('tanggal', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $columns = ['Tanggal', 'Gudang', 'Kode Barang', 'Nama Barang', 'Jumlah', 'Harga Satuan', 'Nilai Total', 'User Input'];
        $sheet->fromArray([$columns], null, 'A1');

        $headerStyle = $sheet->getStyle('A1:H1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
        $headerStyle->getFill()->setFillType('solid');
        $headerStyle->getFill()->getStartColor()->setARGB('FF366092');

        $row = 2;
        foreach ($barangMasuk as $item) {
            $sheet->setCellValue('A' . $row, $item->tanggal);
            $sheet->setCellValue('B' . $row, $item->gudang->nama_gudang ?? '-');
            $sheet->setCellValue('C' . $row, $item->barang->kode_barang ?? '-');
            $sheet->setCellValue('D' . $row, $item->barang->nama_barang ?? '-');
            $sheet->setCellValue('E' . $row, $item->jumlah);
            $sheet->setCellValue('F' . $row, $item->barang->harga ?? 0);
            $sheet->setCellValue('G' . $row, $item->jumlah * ($item->barang->harga ?? 0));
            $sheet->setCellValue('H' . $row, $item->user->name ?? $item->user->nama ?? '-');
            $row++;
        }

        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new XlsxWriter($spreadsheet);
        $filename = 'laporan-barang-masuk-' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportBarangKeluarExcel(Request $request)
    {
        $query = BarangKeluar::with(['barang', 'gudang']);
        
        if ($request->query('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->query('dari_tanggal'));
        }
        if ($request->query('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->query('sampai_tanggal'));
        }
        if ($request->query('gudang')) {
            $query->where('kode_gudang', $request->query('gudang'));
        }

        $barangKeluar = $query->orderBy('tanggal', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $columns = ['Tanggal', 'Gudang', 'Kode Barang', 'Nama Barang', 'Jumlah', 'Harga Satuan', 'Nilai Total'];
        $sheet->fromArray([$columns], null, 'A1');

        $headerStyle = $sheet->getStyle('A1:G1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
        $headerStyle->getFill()->setFillType('solid');
        $headerStyle->getFill()->getStartColor()->setARGB('FF366092');

        $row = 2;
        foreach ($barangKeluar as $item) {
            $sheet->setCellValue('A' . $row, $item->tanggal);
            $sheet->setCellValue('B' . $row, $item->gudang->nama_gudang ?? '-');
            $sheet->setCellValue('C' . $row, $item->barang->kode_barang ?? '-');
            $sheet->setCellValue('D' . $row, $item->barang->nama_barang ?? '-');
            $sheet->setCellValue('E' . $row, $item->jumlah);
            $sheet->setCellValue('F' . $row, $item->barang->harga ?? 0);
            $sheet->setCellValue('G' . $row, $item->jumlah * ($item->barang->harga ?? 0));
            $row++;
        }

        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new XlsxWriter($spreadsheet);
        $filename = 'laporan-barang-keluar-' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportPengajuanExcel(Request $request)
    {
        $query = Pengajuan::with(['user']);
        
        if ($request->query('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->query('dari_tanggal'));
        }
        if ($request->query('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->query('sampai_tanggal'));
        }
        if ($request->query('status')) {
            $query->where('status', $request->query('status'));
        }

        $pengajuans = $query->orderBy('tanggal', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $columns = ['ID', 'Tanggal', 'User', 'Status', 'Catatan'];
        $sheet->fromArray([$columns], null, 'A1');

        $headerStyle = $sheet->getStyle('A1:E1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
        $headerStyle->getFill()->setFillType('solid');
        $headerStyle->getFill()->getStartColor()->setARGB('FF366092');

        $row = 2;
        foreach ($pengajuans as $p) {
            $sheet->setCellValue('A' . $row, $p->id);
            $sheet->setCellValue('B' . $row, $p->tanggal);
            $sheet->setCellValue('C' . $row, $p->user->name ?? $p->user->nama ?? '-');
            $sheet->setCellValue('D' . $row, ucfirst($p->status ?? 'pending'));
            $sheet->setCellValue('E' . $row, $p->note ?? '-');
            $row++;
        }

        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new XlsxWriter($spreadsheet);
        $filename = 'laporan-pengajuan-' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    // PDF Export methods
    public function exportStokGudangPdf(Request $request)
    {
        $query = Stok::with(['barang', 'gudang']);
        
        if ($request->query('gudang')) {
            $query->where('kode_gudang', $request->query('gudang'));
        }
        
        $stoks = $query->orderBy('kode_gudang')->orderBy('id_barang')->get();
        $stokWithValue = $stoks->map(function ($stok) {
            $stok->total_value = $stok->stok * ($stok->barang->harga ?? 0);
            return $stok;
        });
        
        $totalStok = $stokWithValue->sum('stok');
        $totalValue = $stokWithValue->sum('total_value');

        $pdf = Pdf::loadView('content.laporan.pdf.stok-gudang', compact('stokWithValue', 'totalStok', 'totalValue'));
        return $pdf->download('laporan-stok-gudang-' . date('Y-m-d-His') . '.pdf');
    }

    public function exportBarangMasukPdf(Request $request)
    {
        $query = BarangMasuk::with(['barang', 'gudang', 'user']);
        
        if ($request->query('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->query('dari_tanggal'));
        }
        if ($request->query('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->query('sampai_tanggal'));
        }
        if ($request->query('gudang')) {
            $query->where('kode_gudang', $request->query('gudang'));
        }

        $barangMasuk = $query->orderBy('tanggal', 'desc')->get();
        $totalJumlah = $barangMasuk->sum('jumlah');
        $totalNilai = $barangMasuk->sum(function($item) {
            return $item->jumlah * ($item->barang->harga ?? 0);
        });

        $pdf = Pdf::loadView('content.laporan.pdf.barang-masuk', compact('barangMasuk', 'totalJumlah', 'totalNilai'));
        return $pdf->download('laporan-barang-masuk-' . date('Y-m-d-His') . '.pdf');
    }

    public function exportBarangKeluarPdf(Request $request)
    {
        $query = BarangKeluar::with(['barang', 'gudang']);
        
        if ($request->query('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->query('dari_tanggal'));
        }
        if ($request->query('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->query('sampai_tanggal'));
        }
        if ($request->query('gudang')) {
            $query->where('kode_gudang', $request->query('gudang'));
        }

        $barangKeluar = $query->orderBy('tanggal', 'desc')->get();
        $totalJumlah = $barangKeluar->sum('jumlah');
        $totalNilai = $barangKeluar->sum(function($item) {
            return $item->jumlah * ($item->barang->harga ?? 0);
        });

        $pdf = Pdf::loadView('content.laporan.pdf.barang-keluar', compact('barangKeluar', 'totalJumlah', 'totalNilai'));
        return $pdf->download('laporan-barang-keluar-' . date('Y-m-d-His') . '.pdf');
    }

    public function exportPengajuanPdf(Request $request)
    {
        $query = Pengajuan::with(['user']);
        
        if ($request->query('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->query('dari_tanggal'));
        }
        if ($request->query('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->query('sampai_tanggal'));
        }
        if ($request->query('status')) {
            $query->where('status', $request->query('status'));
        }

        $pengajuans = $query->orderBy('tanggal', 'desc')->get();
        $totalPengajuan = $pengajuans->count();

        $pdf = Pdf::loadView('content.laporan.pdf.pengajuan', compact('pengajuans', 'totalPengajuan'));
        return $pdf->download('laporan-pengajuan-' . date('Y-m-d-His') . '.pdf');
    }

    // Laporan Riwayat Pengajuan dengan kontrol akses per user
    public function riwayatPengajuan(Request $request)
    {
        $user = auth()->user();
        $query = Pengajuan::with(['user', 'details.barang', 'gudang']);
        
        // Kontrol akses berdasarkan role
        if ($user && $user->role !== 'admin' && $user->role !== 'supervisor') {
            // User biasa hanya bisa melihat pengajuan mereka sendiri
            $query->where('user_id', $user->id);
        }
        
        // Filter by date range
        if ($request->query('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->query('dari_tanggal'));
        }
        
        if ($request->query('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->query('sampai_tanggal'));
        }
        
        // Filter by status
        if ($request->query('status')) {
            $query->where('status', $request->query('status'));
        }

        // Filter by user (hanya untuk admin/supervisor)
        if ($request->query('user') && ($user->role === 'admin' || $user->role === 'supervisor')) {
            $query->where('user_id', $request->query('user'));
        }
        
        $pengajuans = $query->orderBy('tanggal', 'desc')->paginate(15);
        
        $totalPengajuan = $query->count();
        $statusOptions = ['pending', 'approved', 'rejected', 'completed'];
        
        // Get users untuk dropdown (hanya untuk admin)
        $users = [];
        if ($user && ($user->role === 'admin' || $user->role === 'supervisor')) {
            $users = \App\Models\User::orderBy('nama')->get();
        }
        
        return view('content.laporan.riwayat-pengajuan', compact('pengajuans', 'totalPengajuan', 'statusOptions', 'users', 'user'));
    }

    // Export Riwayat Pengajuan ke Excel
    public function exportRiwayatPengajuanExcel(Request $request)
    {
        $user = auth()->user();
        $query = Pengajuan::with(['user', 'details.barang', 'gudang']);
        
        // Kontrol akses
        if ($user && $user->role !== 'admin' && $user->role !== 'supervisor') {
            $query->where('user_id', $user->id);
        }
        
        if ($request->query('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->query('dari_tanggal'));
        }
        if ($request->query('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->query('sampai_tanggal'));
        }
        if ($request->query('status')) {
            $query->where('status', $request->query('status'));
        }
        if ($request->query('user') && ($user->role === 'admin' || $user->role === 'supervisor')) {
            $query->where('user_id', $request->query('user'));
        }

        $pengajuans = $query->orderBy('tanggal', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $columns = ['Tanggal', 'Kode Pengajuan', 'User', 'Gudang', 'Nama Barang', 'Jumlah', 'Status', 'Catatan'];
        $sheet->fromArray([$columns], null, 'A1');

        $headerStyle = $sheet->getStyle('A1:H1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
        $headerStyle->getFill()->setFillType('solid');
        $headerStyle->getFill()->getStartColor()->setARGB('FF366092');

        $row = 2;
        foreach ($pengajuans as $item) {
            $details = $item->details ?? [];
            if ($details->isEmpty()) {
                // Jika tidak ada detail, tetap tampilkan pengajuan dengan barang kosong
                $sheet->setCellValue('A' . $row, $item->tanggal);
                $sheet->setCellValue('B' . $row, $item->kode_pengajuan);
                $sheet->setCellValue('C' . $row, $item->user->nama ?? '-');
                $sheet->setCellValue('D' . $row, $item->gudang->nama_gudang ?? '-');
                $sheet->setCellValue('E' . $row, '-');
                $sheet->setCellValue('F' . $row, 0);
                $sheet->setCellValue('G' . $row, ucfirst($item->status));
                $sheet->setCellValue('H' . $row, $item->note ?? '-');
                $row++;
            } else {
                // Untuk setiap detail barang, buat baris baru
                foreach ($details as $index => $detail) {
                    if ($index == 0) {
                        // Baris pertama, masukkan info pengajuan
                        $sheet->setCellValue('A' . $row, $item->tanggal);
                        $sheet->setCellValue('B' . $row, $item->kode_pengajuan);
                        $sheet->setCellValue('C' . $row, $item->user->nama ?? '-');
                        $sheet->setCellValue('D' . $row, $item->gudang->nama_gudang ?? '-');
                    } else {
                        // Baris selanjutnya, kosongkan kolom pengajuan
                        $sheet->setCellValue('A' . $row, '');
                        $sheet->setCellValue('B' . $row, '');
                        $sheet->setCellValue('C' . $row, '');
                        $sheet->setCellValue('D' . $row, '');
                    }
                    
                    // Info barang
                    $sheet->setCellValue('E' . $row, $detail->barang->nama_barang ?? '-');
                    $sheet->setCellValue('F' . $row, $detail->jumlah);
                    
                    if ($index == 0) {
                        // Hanya tampilkan status dan catatan di baris pertama
                        $sheet->setCellValue('G' . $row, ucfirst($item->status));
                        $sheet->setCellValue('H' . $row, $item->note ?? '-');
                    }
                    $row++;
                }
            }
        }

        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new XlsxWriter($spreadsheet);
        $filename = 'laporan-riwayat-pengajuan-' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    // Export Riwayat Pengajuan ke PDF
    public function exportRiwayatPengajuanPdf(Request $request)
    {
        $user = auth()->user();
        $query = Pengajuan::with(['user', 'details.barang', 'gudang']);
        
        // Kontrol akses
        if ($user && $user->role !== 'admin' && $user->role !== 'supervisor') {
            $query->where('user_id', $user->id);
        }
        
        if ($request->query('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->query('dari_tanggal'));
        }
        if ($request->query('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->query('sampai_tanggal'));
        }
        if ($request->query('status')) {
            $query->where('status', $request->query('status'));
        }
        if ($request->query('user') && ($user->role === 'admin' || $user->role === 'supervisor')) {
            $query->where('user_id', $request->query('user'));
        }

        $pengajuans = $query->orderBy('tanggal', 'desc')->get();
        $totalPengajuan = $pengajuans->count();

        $pdf = Pdf::loadView('content.laporan.pdf.riwayat-pengajuan', compact('pengajuans', 'totalPengajuan'));
        return $pdf->download('laporan-riwayat-pengajuan-' . date('Y-m-d-His') . '.pdf');
    }
}
