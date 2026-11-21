<!DOCTYPE html>
<html lang="id">
<head><title>Total Transaksi UMKM</title></head>
<body>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a> | 
        <a href="{{ route('admin.umkm') }}">Verifikasi UMKM</a> | 
        <a href="{{ route('admin.pengguna') }}">Data Master (Pengguna)</a>
    </nav>

    <h2>Total Transaksi Berdasarkan UMKM</h2>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Nama UMKM</th>
                <th>Total Pesanan Selesai</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksiPerUmkm as $trx)
            <tr>
                <td><strong>{{ $trx->nama_vendor }}</strong></td>
                <td>{{ $trx->jumlah_pesanan }} Pesanan</td>
                <td>Rp {{ number_format($trx->total_pendapatan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @include('partials.popup_notification')
</body>
</html>