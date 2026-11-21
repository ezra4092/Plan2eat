<!DOCTYPE html>
<html lang="id">
<head>
    <title>Manajemen Pengguna</title>
</head>
<body>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a> | 
        <a href="{{ route('admin.umkm') }}">Verifikasi UMKM</a> | 
        <a href="{{ route('admin.pengguna') }}">Data Master (Pengguna)</a>
    </nav>

    <h2>Data Pengguna (Konsumen & UMKM)</h2>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status Akun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id_user }}</td>
                <td>
                    {{ $user->nama_lengkap }} <br>
                    @if($user->role == 'umkm') 
                        <small>Vendor: {{ $user->nama_vendor }}</small>
                    @endif
                </td>
                <td>{{ $user->email }}</td>
                <td>{{ strtoupper($user->role) }}</td>
                <td>{{ $user->is_suspended ?? false ? 'Ditangguhkan' : 'Aktif' }}</td>
                <td>
                    <!-- Tombol Suspend/Aktivasi -->
                    <form action="{{ route('admin.pengguna.suspend', $user->id_user) }}" method="POST" style="display:inline;" data-confirm="Yakin ingin {{ $user->is_suspended ?? false ? 'mengaktivasi' : 'menangguhkan' }} akun ini?" data-confirm-title="Konfirmasi Status Akun" data-confirm-danger="{{ ($user->is_suspended ?? false) ? 'false' : 'true' }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit">{{ $user->is_suspended ?? false ? 'Aktivasi' : 'Suspend' }}</button>
                    </form>

                    <!-- Tombol Hapus -->
                    <form action="{{ route('admin.pengguna.delete', $user->id_user) }}" method="POST" style="display:inline;" data-confirm="Yakin hapus pengguna ini secara permanen?" data-confirm-title="Hapus Pengguna" data-confirm-ok="Ya, Hapus" data-confirm-danger="true">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="color: red;">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @include('partials.popup_notification')
</body>
</html>