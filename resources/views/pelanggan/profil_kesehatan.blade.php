<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test CRUD Profil Kesehatan - Plan2Eat</title>
</head>
<body>

    <h2>Pengaturan Profil Kesehatan</h2>
    <p>User Login: <strong>{{ Auth::user()->nama_lengkap }}</strong> (ID: {{ Auth::id() }})</p>
    <hr>

    <!-- Notifikasi Sukses / Error -->
    @if(session('success'))
        <p style="color: green;"><strong>[SUKSES]</strong> {{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;"><strong>[ERROR]</strong> {{ session('error') }}</p>
    @endif

    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    @endif

    <!-- KONDISI 1: JIKA DATA PROFIL KESEHATAN BELUM ADA (FORM CREATE) -->
    @if(!$profil)
        <p style="color: orange;"><strong>{{ $pesan }}</strong></p>
        <h3>Tambah Profil Kesehatan Baru</h3>
        <form action="{{ route('pelanggan.profil_kesehatan.store') }}" method="POST">
            @csrf
            <div>
                <label>Riwayat Alergi:</label><br>
                <input type="text" name="riwayat_alergi" placeholder="Contoh: Kacang, Udang, Susu">
            </div><br>

            <div>
                <label>Jenis Diet:</label><br>
                <input type="text" name="jenis_diet" placeholder="Contoh: Defisit Kalori, Vegetarian">
            </div><br>

            <div>
                <label>Catatan Khusus:</label><br>
                <textarea name="catatan_khusus" rows="3" placeholder="Contoh: Tidak suka terlalu pedas"></textarea>
            </div><br>

            <div>
                <label>Batas Budget Harian (Rp):</label><br>
                <input type="number" name="batas_budget" placeholder="Contoh: 35000">
            </div><br>

            <button type="submit">Simpan Profil Kesehatan</button>
        </form>

    <!-- KONDISI 2: JIKA DATA PROFIL KESEHATAN SUDAH ADA (READ, UPDATE, DELETE) -->
    @else
        <h3>Data Profil Kesehatan Tersimpan (ID Profil: {{ $profil->id_profil }})</h3>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>Riwayat Alergi</th>
                <td>{{ $profil->riwayat_alergi ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jenis Diet</th>
                <td>{{ $profil->jenis_diet ?? '-' }}</td>
            </tr>
            <tr>
                <th>Catatan Khusus</th>
                <td>{{ $profil->catatan_khusus ?? '-' }}</td>
            </tr>
            <tr>
                <th>Batas Budget</th>
                <td>Rp {{ number_format($profil->batas_budget ?? 0, 0, ',', '.') }}</td>
            </tr>
        </table>

        <br>
        <hr>

        <!-- FORM UPDATE -->
        <h3>Perbarui Profil Kesehatan</h3>
        <form action="{{ route('pelanggan.profil_kesehatan.update', $profil->id_profil) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label>Riwayat Alergi:</label><br>
                <input type="text" name="riwayat_alergi" value="{{ old('riwayat_alergi', $profil->riwayat_alergi) }}">
            </div><br>

            <div>
                <label>Jenis Diet:</label><br>
                <input type="text" name="jenis_diet" value="{{ old('jenis_diet', $profil->jenis_diet) }}">
            </div><br>

            <div>
                <label>Catatan Khusus:</label><br>
                <textarea name="catatan_khusus" rows="3">{{ old('catatan_khusus', $profil->catatan_khusus) }}</textarea>
            </div><br>

            <div>
                <label>Batas Budget Harian (Rp):</label><br>
                <input type="number" name="batas_budget" value="{{ old('batas_budget', $profil->batas_budget) }}">
            </div><br>

            <button type="submit">Perbarui Data</button>
        </form>

        <br>
        <hr>

        <!-- FORM DELETE -->
        <h3>Hapus Profil Kesehatan</h3>
        <form action="{{ route('pelanggan.profil_kesehatan.destroy', $profil->id_profil) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data profil kesehatan ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="color: red;">Hapus Data Profil</button>
        </form>
    @endif

    <br><br>
    <a href="{{ route('pelanggan.beranda') }}">&laquo; Kembali ke Beranda</a>

</body>
</html>