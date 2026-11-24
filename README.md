# Plan2Eat

**Plan2Eat** (*Plan It, Order It, Irit*) adalah platform katering harian, perencanaan makan (*meal plan*), dan pemesanan makanan lokal berbasis web yang menghubungkan **Pelanggan** dengan **Mitra Dapur UMKM**. Aplikasi ini dirancang untuk membantu pengguna mengatur pola makan harian sesuai preferensi kesehatan dan anggaran secara lebih terstruktur sekaligus mendukung konsep *zero-waste* melalui fitur **Night Flash Sale** untuk menu berlebih dari mitra UMKM.

---

## Fitur Utama

### 1. Halaman Publik & Autentikasi
- **Landing Page**: Menyajikan informasi layanan Plan2Eat, menu pilihan, highlight Flash Sale, keunggulan platform, serta akses menuju halaman masuk dan pendaftaran.
- **Registrasi Berbasis Role & Verifikasi OTP**: Pengguna dapat mendaftar sebagai **Pelanggan** atau **Mitra UMKM** dengan verifikasi kode OTP melalui email.
- **Personalisasi Awal Pelanggan**: Pengaturan preferensi alergi/gaya hidup (seperti *No Seafood*, *Nut-Free*, *Halal Only*, *Low Calorie*, *Vegetarian*, *Bebas Gluten*) serta batas budget harian.
- **Pendaftaran Mitra UMKM**: Pengisian profil dapur/warung dan alamat pengambilan untuk proses verifikasi mitra.

### 2. Fitur Pelanggan
- **Beranda & Rekomendasi Menu**: Menampilkan daftar menu aktif dari UMKM terverifikasi yang disaring berdasarkan profil kesehatan dan batas budget harian.
- **Eksplorasi Menu, Mitra & Flash Sale**: Pencarian menu, detail menu, daftar mitra UMKM, serta penawaran promo Flash Sale.
- **Meal Plan**: Pembuatan dan pengelolaan rencana makan harian beserta detail item menu dan checkout langsung dari meal plan.
- **Keranjang, Checkout & Riwayat Pesanan**: Pengelolaan keranjang belanja, checkout menggunakan saldo akun, pembatalan pesanan, fitur pesan ulang (*reorder*), dan pelacakan riwayat pesanan.
- **Profil Kesehatan**: Pengaturan data akun, foto profil, nomor WhatsApp, riwayat alergi, jenis diet, catatan khusus, batas budget harian, serta informasi saldo akun.
- **Ulasan & Rating**: Memberikan, memperbarui, dan menghapus ulasan terhadap pesanan yang telah dilakukan.

### 3. Fitur Mitra UMKM
- **Dashboard UMKM**: Ringkasan aktivitas penjualan dan operasional dapur mitra.
- **Manajemen Menu (CRUD)**: Menambah, mengubah, menghapus menu, mengunggah foto menu (disimpan ke folder `public/menu`), dan mengatur stok harian.
- **Manajemen Flash Sale (CRUD)**: Mengatur promo Flash Sale untuk mengurangi potensi makanan sisa.
- **Manajemen Pesanan Masuk**: Memantau detail pesanan pelanggan dan memperbarui status pesanan.
- **Laporan Keuangan & Pencairan Dana**: Memantau ringkasan pendapatan, mengajukan pencairan dana, dan mencetak laporan keuangan.
- **Profil UMKM**: Mengelola informasi toko serta mengubah status operasional buka/tutup.

### 4. Fitur Admin
- **Dashboard Admin**: Ringkasan data operasional platform secara menyeluruh.
- **Verifikasi UMKM**: Meninjau dan menyetujui/menolak pengajuan Mitra UMKM.
- **Data Master & Pengelolaan Pengguna**: Mengelola status aktif UMKM, suspend/hapus akun pelanggan, serta CRUD kategori **Jenis Menu**.
- **Laporan & Ekspor CSV**: Melihat laporan transaksi platform dan mengunduh laporan dalam format **CSV**.
- **Ulasan**: Memantau daftar ulasan yang masuk di platform.

---

## Role Pengguna

Aplikasi memiliki 3 peran (*role*) utama yang dilindungi middleware autentikasi dan otorisasi berbasis role:

| Role | Deskripsi Singkat |
| --- | --- |
| **Pelanggan** (`pelanggan`) | Mengatur profil kesehatan & budget, menyusun *meal plan*, memesan menu/flash sale, melihat riwayat pesanan, dan memberi ulasan. |
| **Mitra UMKM** (`umkm`) | Mengelola profil dapur/warung, menu makanan, promo *flash sale*, pesanan masuk, dan laporan keuangan mitra. |
| **Admin** (`admin`) | Memverifikasi pendaftaran UMKM, mengelola data master & pengguna, memantau ulasan, serta mengunduh laporan platform (CSV). |

---

## Batasan & Scope Proyek

1. **Aplikasi Berbasis Web Responsif**: Plan2Eat berjalan di browser desktop maupun mobile; belum tersedia sebagai aplikasi mobile native (Android/iOS).
2. **Pembayaran Internal Sistem**: Alur pembayaran dan pencairan dana disimulasikan melalui saldo akun pengguna dan pencatatan internal aplikasi tanpa integrasi *payment gateway* pihak ketiga secara *real-time*.
3. **Verifikasi OTP & Email**: Pengiriman kode OTP bergantung pada konfigurasi `MAIL_MAILER` Laravel (secara default di `.env.example` menggunakan driver `log` untuk lingkungan pengembangan lokal).
4. **Verifikasi Mitra**: Mitra UMKM yang baru mendaftar memerlukan proses verifikasi oleh Admin agar operasionalnya penuh di dalam sistem.
5. **Rekomendasi Berbasis Filter Profil**: Penyesuaian menu pada beranda pelanggan bekerja dengan menyaring menu aktif menggunakan preferensi alergi, diet, dan batas budget harian yang tersimpan pada profil kesehatan.

---

## Teknologi yang Digunakan

- **Backend**: PHP `^8.3`, Laravel Framework `^13.17`
- **Frontend**: Laravel Blade, CSS (`globals.css` & `style.css`), Tailwind CSS `^4.0`, Vite `^8.0`, Vanilla JavaScript, Google Fonts (*Plus Jakarta Sans* & *Inter*), Font Awesome
- **Database**: SQLite (default `.env.example`) / MySQL-compatible melalui Laravel Eloquent ORM & Migrations
- **Testing & Developer Tools**: PHPUnit `^12.5`, Laravel Pint, Laravel Pail, Laravel Tinker, FakerPHP

---

## Cara Konfigurasi dan Menjalankan Proyek

### Prasyarat
- PHP `>= 8.3` beserta ekstensi standar Laravel (`pdo`, `mbstring`, `openssl`, `fileinfo`, dll.)
- Composer
- Node.js & npm
- Database SQLite atau MySQL/MariaDB

### Langkah Instalasi

1. **Clone repositori dan masuk ke folder proyek**
   ```bash
   git clone <url-repositori>
   cd Plan2eat
   ```

2. **Install dependensi PHP dan JavaScript**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi file environment (`.env`)**
   ```bash
   cp .env.example .env
   ```
   Sesuaikan isi `.env` jika diperlukan:
   - Jika menggunakan **SQLite** (default di `.env.example`), pastikan file `database/database.sqlite` tersedia.
   - Jika menggunakan **MySQL**, ubah `DB_CONNECTION=mysql` beserta `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`.
   - Untuk pengujian OTP secara lokal tanpa SMTP eksternal, biarkan `MAIL_MAILER=log` (kode OTP akan tercatat di `storage/logs/laravel.log`), atau isi kredensial SMTP jika ingin mengirim ke email asli.

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan migrasi database**
   ```bash
   php artisan migrate
   ```
   *(Opsional)* Jika ingin mengisi data awal, jalankan seeder yang tersedia sesuai kebutuhan, misalnya:
   ```bash
   php artisan db:seed --class=UserSeeder
   php artisan db:seed --class=UmkmSeeder
   php artisan db:seed --class=JenisMenuSeeder
   php artisan db:seed --class=MenuSeeder
   php artisan db:seed --class=FlashSaleSeeder
   ```

6. **Buat symbolic link storage & pastikan folder foto menu siap**
   ```bash
   php artisan storage:link
   ```

7. **Build aset frontend**
   ```bash
   npm run build
   ```

8. **Jalankan server aplikasi**
   ```bash
   php artisan serve
   ```
   Atau jalankan mode dev terintegrasi dengan `composer run dev`, lalu akses aplikasi melalui browser di `http://127.0.0.1:8000`.

---

## Tim Pengembang

| No | Nama | NIM | Peran |
| --- | --- | --- | --- |
| 1 | **Ezra Faira Azzahra Faisal** | `J0403251030` | **Pengembang Utama** |
| 2 | Baytul Rafli | `J0403251027` | Anggota Pengembang |
| 3 | Gabriella Anggi Apriliana Tambun | `J0403251072` | Anggota Pengembang |
| 4 | Alyya Putri Sekarwangi | `J0403251109` | Anggota Pengembang |
| 5 | Nandana Ahza Fakhrullah | `J0403251167` | Anggota Pengembang |
