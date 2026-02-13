<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengajuan</title>
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
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            color: white;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }
        .badge-approved {
            background-color: #28a745;
        }
        .badge-rejected {
            background-color: #dc3545;
        }
        .badge-completed {
            background-color: #17a2b8;
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
        <h1>LAPORAN PENGAJUAN</h1>
        <p>GUDIT - Gudang Digital</p>
        <p>Tanggal: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Pengajuan:</strong><br>
            <span class="value">{{ number_format($totalPengajuan, 0, ',', '.') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>User</th>
                <th class="text-center">Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $p->user->name ?? $p->user->nama ?? '-' }}</td>
                    <td class="text-center">
                        @php
                            $statusClass = 'badge-pending';
                            $statusLabel = 'Menunggu';
                            
                            if ($p->status === 'approved') {
                                $statusClass = 'badge-approved';
                                $statusLabel = 'Disetujui';
                            } elseif ($p->status === 'rejected') {
                                $statusClass = 'badge-rejected';
                                $statusLabel = 'Ditolak';
                            } elseif ($p->status === 'completed') {
                                $statusClass = 'badge-completed';
                                $statusLabel = 'Selesai';
                            }
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>{{ \Str::limit($p->note ?? '-', 50) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan oleh sistem GUDIT pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
