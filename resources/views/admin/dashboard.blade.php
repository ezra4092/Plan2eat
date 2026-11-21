<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Admin</title>
</head>
<body>
    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a> | 
        <a href="{{ route('admin.umkm') }}">Verifikasi UMKM</a> | 
        <a href="{{ route('admin.pengguna') }}">Data Master (Pengguna)</a>
    </nav>

    <h2>Dashboard Admin</h2>

    <!-- FILTER WAKTU (Otomatis submit saat diubah) -->
    <form action="{{ route('admin.dashboard') }}" method="GET">
        <label>Filter Waktu: </label>
        <select name="filter" onchange="this.form.submit()">
            <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Semua Waktu</option>
            <option value="7_days" {{ $filter == '7_days' ? 'selected' : '' }}>7 Hari Terakhir</option>
            <option value="1_month" {{ $filter == '1_month' ? 'selected' : '' }}>1 Bulan Terakhir</option>
            <option value="4_months" {{ $filter == '4_months' ? 'selected' : '' }}>4 Bulan Terakhir</option>
        </select>
    </form>

    <!-- KARTU KPI -->
    <div style="display: flex; gap: 20px; margin-top: 20px;">
        
        <!-- Card 1: Mengarah ke Data Master Pengguna (Daftar UMKM terdaftar) -->
        <a href="{{ route('admin.pengguna', ['role' => 'umkm']) }}" style="text-decoration: none; color: black; flex: 1;">
            <div style="border: 1px solid black; padding: 15px; cursor: pointer; height: 100%;">
                <h3>Total UMKM Terdaftar</h3>
                <h1>{{ $totalUmkm }}</h1>
                <p><small><i>Klik untuk lihat daftar UMKM</i></small></p>
            </div>
        </a>

        <!-- Card 2: Mengarah ke halaman Verifikasi UMKM -->
        <a href="{{ route('admin.umkm') }}" style="text-decoration: none; color: black; flex: 1;">
            <div style="border: 1px solid black; padding: 15px; cursor: pointer; height: 100%;">
                <h3>Menunggu Verifikasi</h3>
                <h1>{{ $pendingUmkm }}</h1>
                <p><small><i>Klik untuk verifikasi UMKM</i></small></p>
            </div>
        </a>

        <!-- Card 3: Pindah ke halaman Ulasan -->
        <a href="{{ route('admin.ulasan') }}" style="text-decoration: none; color: black; flex: 1;">
            <div style="border: 1px solid black; padding: 15px; cursor: pointer; height: 100%;">
                <h3>Total Ulasan & Rating</h3>
                <h1>{{ $totalUlasan }}</h1>
                <p>Rata-rata: ⭐ {{ number_format($avgRating, 1) }}</p>
                <p><small><i>Klik untuk lihat detail ulasan</i></small></p>
            </div>
        </a>

        <!-- Card 4: Pindah ke halaman Transaksi -->
        <a href="{{ route('admin.transaksi') }}" style="text-decoration: none; color: black; flex: 1;">
            <div style="border: 1px solid black; padding: 15px; cursor: pointer; height: 100%;">
                <h3>Total Transaksi</h3>
                <h1>{{ $totalTransaksiCount }} Pesanan</h1>
                <p>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                <p><small><i>Klik untuk lihat detail transaksi per UMKM</i></small></p>
            </div>
        </a>

    </div>

    <!-- ============================================================== -->
    <!-- TAMBAHKAN BAGIAN INI DI BAWAH CARD UNTUK MENAMPILKAN DETAILNYA -->
    <!-- ============================================================== -->
    
    <!-- Bagian Chart & Transaksi (Target Card 4) -->
    <div id="bagian-transaksi" style="margin-top: 50px; border: 1px dashed gray; padding: 20px; text-align: center;">
        <h3>Grafik Transaksi</h3>
        <p>Gunakan library seperti Chart.js di sini. Data dari Controller sudah siap.</p>
        <code>{{ json_encode($chartData) }}</code>
    </div>

</body>
</html>