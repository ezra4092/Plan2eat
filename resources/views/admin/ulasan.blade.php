<!DOCTYPE html>
<html lang="id">
<head><title>Daftar Ulasan</title></head>
<body>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a> | 
        <a href="{{ route('admin.umkm') }}">Verifikasi UMKM</a> | 
        <a href="{{ route('admin.pengguna') }}">Data Master (Pengguna)</a>
    </nav>

    <h2>Daftar Semua Ulasan</h2>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Nama Pengguna</th>
                <th>UMKM yang Diulas</th>
                <th>Rating</th>
                <th>Isi Ulasan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detailUlasan as $ulasan)
            <tr>
                <td><strong>{{ $ulasan->nama_pengguna }}</strong></td>
                <td>{{ $ulasan->nama_vendor }}</td>
                <td>⭐ {{ $ulasan->rating }}</td>
                <td>{{ $ulasan->ulasan }}</td>
                <td>{{ \Carbon\Carbon::parse($ulasan->timestamp)->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>