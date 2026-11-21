<!DOCTYPE html>
<html lang="id">
<head><title>Monitoring Ulasan</title></head>
<body>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a> | 
        <a href="{{ route('admin.umkm') }}">Verifikasi UMKM</a> | 
        <a href="{{ route('admin.pengguna') }}">Data Master (Pengguna)</a>
    </nav>

    <h2>Monitoring & Moderasi Ulasan</h2>

    <!-- FILTER RATING RENDAH -->
    <form action="{{ route('admin.ulasan') }}" method="GET" style="margin-bottom: 15px;">
        <label>Filter Ulasan: </label>
        <select name="filter" onchange="this.form.submit()">
            <option value="semua" {{ request('filter') != 'rendah' ? 'selected' : '' }}>Semua Rating</option>
            <option value="rendah" {{ request('filter') == 'rendah' ? 'selected' : '' }}>Rating Rendah (Komplain / <= 3 Bintang)</option>
        </select>
    </form>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Nama Pengguna</th>
                <th>UMKM yang Diulas</th>
                <th>Rating</th>
                <th>Isi Ulasan</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detailUlasan as $ulasan)
            <tr>
                <td><strong>{{ $ulasan->nama_pengguna }}</strong></td>
                <td>{{ $ulasan->nama_vendor }}</td>
                <td>⭐ {{ $ulasan->rating }}</td>
                <td>{{ $ulasan->ulasan }}</td>
                <td>{{ \Carbon\Carbon::parse($ulasan->timestamp)->format('d M Y') }}</td>
                <td>
                    <!-- Tombol Hapus Ulasan -->
                    <form action="{{ route('admin.ulasan.delete', $ulasan->id_ulasan) }}" method="POST" style="display:inline;" data-confirm="Hapus ulasan ini secara permanen?" data-confirm-title="Hapus Ulasan" data-confirm-ok="Ya, Hapus" data-confirm-danger="true">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="color: red; margin-bottom: 5px;">Hapus Ulasan</button>
                    </form>
                    <br>
                    <!-- Tombol Suspend UMKM (Gunakan route suspend pengguna) -->
                    <form action="{{ route('admin.pengguna.suspend', $ulasan->id_user_umkm) }}" method="POST" style="display:inline;" data-confirm="Yakin ingin {{ $ulasan->is_suspended ? 'mengaktivasi' : 'menangguhkan' }} UMKM ini karena rating rendah?" data-confirm-title="Konfirmasi Status UMKM" data-confirm-danger="{{ $ulasan->is_suspended ? 'false' : 'true' }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" style="color: {{ $ulasan->is_suspended ? 'green' : 'orange' }};">
                            {{ $ulasan->is_suspended ? 'Aktivasi UMKM' : 'Suspend UMKM' }}
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" align="center">Tidak ada ulasan ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @include('partials.popup_notification')
</body>
</html>