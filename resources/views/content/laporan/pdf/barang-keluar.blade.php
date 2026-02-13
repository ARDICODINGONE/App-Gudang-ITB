<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Keluar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #366092;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 40px;
            margin-bottom: 15px;
        }
        .summary-item strong {
            color: #366092;
            font-size: 16px;
        }
        .summary-item .value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 13px;
        }
        table thead {
            background-color: #366092;
            color: white;
        }
        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #e8eef7;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN BARANG KELUAR</h1>
        <p>GUDIT - Gudang Digital</p>
        <p>Tanggal: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Barang:</strong><br>
            <span class="value">{{ number_format($totalJumlah, 0, ',', '.') }}</span> unit
        </div>
        <div class="summary-item">
            <strong>Nilai Total:</strong><br>
            <span class="value">Rp {{ number_format($totalNilai, 0, ',', '.') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Gudang</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Nilai Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangKeluar as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $item->gudang->nama_gudang ?? '-' }}</td>
                    <td>{{ $item->barang->kode_barang ?? '-' }}</td>
                    <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                    <td class="text-right">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->barang->harga ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah * ($item->barang->harga ?? 0), 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
            @if($barangKeluar->count() > 0)
                <tr class="total-row">
                    <td colspan="4" class="text-right">TOTAL</td>
                    <td class="text-right">{{ number_format($totalJumlah, 0, ',', '.') }}</td>
                    <td></td>
                    <td class="text-right">Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan oleh sistem GUDIT pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
