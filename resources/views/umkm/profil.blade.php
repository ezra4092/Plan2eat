<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil UMKM - Plan2Eat</title>
</head>
<body>

    <h2>Pengaturan Profil UMKM</h2>
    <p>Akun Pemilik: <strong>{{ Auth::user()->nama_lengkap }}</strong> (ID User: {{ Auth::id() }})</p>

    <!-- Tombol Logout -->
    <form action="{{ route('umkm.logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <hr>

    <!-- Notifikasi Sukses / Error -->
    @if(session('success'))
        <p style="color: green;"><strong>[SUKSES]</strong> {{ session('success') }}</p>
    @endif

    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    @endif

    @if(!$umkm)
        <p style="color: orange;"><strong>Data toko UMKM belum ditemukan untuk akun ini.</strong></p>
    @else
        <!-- Informasi Saldo (Hanya Tampil / Read-Only) -->
        <p><strong>Saldo Toko Saat Ini:</strong> Rp {{ number_format($umkm->saldo ?? 0, 2, ',', '.') }}</p>

        <!-- Form Update Profil UMKM -->
        <h3>Perbarui Data Toko (ID UMKM: {{ $umkm->id_umkm }})</h3>
        <form action="{{ route('umkm.profil.update', $umkm->id_umkm) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label>Nama Vendor / Toko:</label><br>
                <input type="text" name="nama_vendor" value="{{ old('nama_vendor', $umkm->nama_vendor) }}" required>
            </div><br>

            <div>
                <label>Deskripsi Toko:</label><br>
                <textarea name="deskripsi_toko" rows="3">{{ old('deskripsi_toko', $umkm->deskripsi_toko) }}</textarea>
            </div><br>

            <div>
                <label>Alamat Lengkap:</label><br>
                <textarea name="alamat_lengkap" rows="3">{{ old('alamat_lengkap', $umkm->alamat_lengkap) }}</textarea>
            </div><br>

            <div>
                <label>Jam Buka:</label><br>
                <input type="time" name="jam_buka" value="{{ old('jam_buka', $umkm->jam_buka ? substr($umkm->jam_buka, 0, 5) : '') }}">
            </div><br>

            <div>
                <label>Jam Tutup:</label><br>
                <input type="time" name="jam_tutup" value="{{ old('jam_tutup', $umkm->jam_tutup ? substr($umkm->jam_tutup, 0, 5) : '') }}">
            </div><br>

            <div>
                <label>Status Operasional:</label><br>
                <select name="status_operasional">
                    <option value="Buka" {{ old('status_operasional', $umkm->status_operasional) == 'Buka' ? 'selected' : '' }}>Buka</option>
                    <option value="Tutup" {{ old('status_operasional', $umkm->status_operasional) == 'Tutup' ? 'selected' : '' }}>Tutup</option>
                </select>
            </div><br>

            <div>
                <label>Metode Pembayaran yang Diterima:</label><br>
                <input type="text" name="metode_pembayaran" value="{{ old('metode_pembayaran', $umkm->metode_pembayaran) }}" placeholder="Contoh: QRIS, Tunai, Transfer BCA">
            </div><br>

            <button type="submit">Simpan Perubahan</button>
        </form>
    @endif

    <br><br>
    <a href="{{ route('umkm.dashboard') }}">&laquo; Kembali ke Dashboard UMKM</a>

</body>
</html>