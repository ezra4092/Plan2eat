@extends('layouts.main', ['activeMenu' => 'profil'])

@section('title', 'Profil Kesehatan - Plan2Eat')
@section('page_badge', 'Profil Kesehatan')

@section('content')
<div style="padding: 20px;">
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

    @php
        // Ubah format riwayat_alergi jika berupa array agar dapat ditampilkan di input teks biasa
        $riwayatAlergiText = '';
        if ($profil && !empty($profil->riwayat_alergi)) {
            $riwayatAlergiText = is_array($profil->riwayat_alergi) 
                ? implode(', ', $profil->riwayat_alergi) 
                : $profil->riwayat_alergi;
        }
    @endphp

    <!-- KONDISI 1: JIKA DATA BELUM ADA (Form Tambah Baru) -->
    @if(!$profil)
        <p style="color: orange;"><strong>{{ $pesan ?? 'Data profil kesehatan belum ada' }}</strong></p>
        <h3>Tambah Profil Kesehatan</h3>
        <form action="{{ route('pelanggan.profil_kesehatan-create') }}" method="POST">
            @csrf
            <div>
                <label>Riwayat Alergi (pisahkan dengan koma jika lebih dari satu):</label><br>
                <input type="text" name="riwayat_alergi" placeholder="Contoh: Udang, Kacang, Susu" value="{{ old('riwayat_alergi') }}">
            </div><br>

            <div>
                <label>Jenis Diet:</label><br>
                <input type="text" name="jenis_diet" placeholder="Contoh: Defisit Kalori, Rendah Karbohidrat" value="{{ old('jenis_diet') }}">
            </div><br>

            <div>
                <label>Catatan Khusus:</label><br>
                <textarea name="catatan_khusus" rows="3" placeholder="Contoh: Tidak suka terlalu pedas">{{ old('catatan_khusus') }}</textarea>
            </div><br>

            <div>
                <label>Batas Budget Harian (Rp):</label><br>
                <input type="number" name="batas_budget" placeholder="Contoh: 35000" value="{{ old('batas_budget') }}">
            </div><br>

            <button type="submit">Simpan Profil Kesehatan</button>
        </form>

    <!-- KONDISI 2: JIKA DATA SUDAH ADA (Tampilkan Data & Form Ubah) -->
    @else
        <h3>Data Profil Kesehatan Anda</h3>
        <table border="1" cellpadding="8" cellspacing="0" style="margin-bottom: 20px;">
            <tr>
                <th>Riwayat Alergi</th>
                <td>{{ !empty($riwayatAlergiText) ? $riwayatAlergiText : '-' }}</td>
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
                <th>Batas Budget Harian</th>
                <td>{{ $profil->batas_budget ? 'Rp ' . number_format($profil->batas_budget, 0, ',', '.') : '-' }}</td>
            </tr>
        </table>

        <h3>Ubah Profil Kesehatan</h3>
        <p><em>Catatan: Jika ingin menghapus data tertentu, kosongkan saja kolom input di bawah ini lalu klik Simpan Perubahan.</em></p>
        
        <form action="{{ route('pelanggan.profil_kesehatan-update', $profil->id_profil) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label>Riwayat Alergi (kosongkan jika tidak ada / ingin dihapus):</label><br>
                <input type="text" name="riwayat_alergi" value="{{ old('riwayat_alergi', $riwayatAlergiText) }}" placeholder="Contoh: Udang, Kacang">
            </div><br>

            <div>
                <label>Jenis Diet (kosongkan jika tidak ada):</label><br>
                <input type="text" name="jenis_diet" value="{{ old('jenis_diet', $profil->jenis_diet) }}" placeholder="Contoh: Defisit Kalori">
            </div><br>

            <div>
                <label>Catatan Khusus (kosongkan jika tidak ada):</label><br>
                <textarea name="catatan_khusus" rows="3" placeholder="Catatan makanan Anda...">{{ old('catatan_khusus', $profil->catatan_khusus) }}</textarea>
            </div><br>

            <div>
                <label>Batas Budget Harian (Rp) (kosongkan jika tidak dibatasi):</label><br>
                <input type="number" name="batas_budget" value="{{ old('batas_budget', $profil->batas_budget) }}" placeholder="Contoh: 35000">
            </div><br>

            <button type="submit">Simpan Perubahan</button>
        </form>

        <br>
        <hr>
        <!-- Opsi Hapus Total (Opsional jika ingin reset semua) -->
        <form action="{{ route('pelanggan.profil_kesehatan-delete', $profil->id_profil) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset/menghapus seluruh data profil kesehatan?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="color: red; background: none; border: 1px solid red; padding: 4px 8px; cursor: pointer;">Reset Seluruh Profil Kesehatan</button>
        </form>
    @endif

    <br>
    <a href="{{ route('pelanggan.beranda') }}">&laquo; Kembali ke Beranda</a>
</div>
@endsection
