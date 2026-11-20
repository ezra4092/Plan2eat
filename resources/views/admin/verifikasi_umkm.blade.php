@extends('layouts.main') <!-- Sesuaikan dengan nama layout utamamu -->

@section('title', 'Verifikasi UMKM')
@section('page_badge', 'Verifikasi UMKM')

@section('content')
<div class="verifikasi-container">
    <h2>Daftar Pengajuan UMKM</h2>

    <!-- Pesan Sukses -->
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Vendor</th>
                    <th>Pemilik (User)</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($umkmList as $umkm)
                <tr>
                    <td>#{{ $umkm->id_umkm }}</td>
                    <td>{{ $umkm->nama_vendor }}</td>
                    <td>{{ $umkm->user->nama_lengkap ?? 'User Dihapus' }}</td>
                    <td>
                        <!-- BAGIAN INI YANG DIUBAH UNTUK ENUM -->
                        @if($umkm->status_verifikasi == 'disetujui')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($umkm->status_verifikasi == 'ditolak')
                            <span class="badge badge-danger" style="background: #dc3545; color: white;">Ditolak</span>
                        @else
                            <span class="badge badge-warning">Menunggu</span>
                        @endif
                    </td>
                    <td>
                        <!-- Tombol Buka Modal -->
                        <button type="button" class="btn-detail" 
                            onclick="bukaModalVerifikasi(
                                {{ $umkm->id_umkm }}, 
                                '{{ addslashes($umkm->nama_vendor) }}', 
                                '{{ addslashes($umkm->deskripsi_toko) }}',
                                '{{ addslashes($umkm->alamat_lengkap) }}',
                                '{{ $umkm->status_verifikasi }}' 
                            )">
                            Detail & Tinjau
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada data pengajuan UMKM.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DETAIL VERIFIKASI UMKM -->
<!-- ========================================== -->
<div id="modalVerifikasi" class="modal-overlay" style="display: none;">
    <div class="modal-content modal-lg">
        <h3 style="margin-top: 0;">Detail Peninjauan Berkas UMKM</h3>
        
        <div class="detail-berkas">
            <p><strong>Nama Vendor:</strong> <span id="modal-nama"></span></p>
            <p><strong>Deskripsi:</strong> <span id="modal-deskripsi"></span></p>
            <p><strong>Alamat:</strong> <span id="modal-alamat"></span></p>
            
            <div class="foto-toko-box">
                <p><strong>Foto Toko / Berkas:</strong></p>
                <!-- Asumsi foto statis, ganti src dengan asset dari DB jika ada -->
                <img src="{{ asset('img/dummy-store.jpg') }}" alt="Foto Toko" class="img-preview">
            </div>
        </div>

        <hr style="margin: 20px 0;">

        <!-- Area ini hanya akan muncul jika status = menunggu -->
        <div id="area-aksi-verifikasi">
            <form id="formVerifikasi" method="POST" action="">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label>Catatan Verifikasi (Alasan):</label>
                    <textarea name="catatan" rows="3" placeholder="Masukkan alasan jika ditolak..."></textarea>
                </div>

                <!-- Input tersembunyi untuk menyimpan status yang diklik -->
                <input type="hidden" name="status_verifikasi" id="inputStatus">

                <div class="modal-actions" style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="button" class="btn-batal" onclick="tutupModalVerifikasi()">Batal Tutup</button>
                    
                    <div class="action-buttons" style="margin-left: auto;">
                        <button type="submit" class="btn-reject" onclick="document.getElementById('inputStatus').value='ditolak'">Tolak Berkas</button>
                        <button type="submit" class="btn-approve" onclick="document.getElementById('inputStatus').value='disetujui'">Setujui UMKM</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tombol Tutup Saja (Muncul jika status selain menunggu) -->
        <div id="area-tutup-saja" style="margin-top: 20px; display: none; text-align: right;">
             <button type="button" class="btn-batal" onclick="tutupModalVerifikasi()">Tutup Detail</button>
        </div>
@endsection

@push('styles')
<style>
    /* CSS Tabel & Tombol Standar */
    .verifikasi-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .table-responsive { overflow-x: auto; margin-top: 20px; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th, .data-table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
    .data-table th { background-color: #f8f9fa; }
    
    .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    .badge-success { background: #d4edda; color: #155724; }
    .badge-warning { background: #fff3cd; color: #856404; }
    .alert-success { background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 4px; }
    
    .btn-detail { background: #0d6efd; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
    
    /* CSS Modal Blur */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); z-index: 9999; display: flex; align-items: center; justify-content: center; }
    .modal-content.modal-lg { background: white; padding: 25px; border-radius: 8px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; }
    
    .img-preview { width: 100%; max-height: 250px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; margin-top: 10px; }
    
    .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; }
    
    .btn-batal { background: #6c757d; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; }
    .btn-reject { background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; }
    .btn-approve { background: #198754; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; }
</style>
@endpush

@push('scripts')
<script>
    // Fungsi untuk membuka modal dan menyisipkan data dinamis dari tabel
    function bukaModalVerifikasi(id, nama, deskripsi, alamat, status) {
        document.getElementById('modal-nama').innerText = nama;
        document.getElementById('modal-deskripsi').innerText = deskripsi || 'Tidak ada deskripsi';
        document.getElementById('modal-alamat').innerText = alamat;
        
        // Ubah action form agar mengarah ke ID UMKM yang tepat
        let form = document.getElementById('formVerifikasi');
        form.action = "/admin/verifikasi-umkm/" + id;

        // --- Logika untuk menyembunyikan/menampilkan aksi ---
        let areaAksi = document.getElementById('area-aksi-verifikasi');
        let areaTutup = document.getElementById('area-tutup-saja');

        if (status === 'menunggu') {
            areaAksi.style.display = 'block'; // Tampilkan form & tombol approve/reject
            areaTutup.style.display = 'none'; // Sembunyikan tombol tutup saja
        } else {
            areaAksi.style.display = 'none';  // Sembunyikan form
            areaTutup.style.display = 'block'; // Tampilkan tombol tutup saja
        }

        document.getElementById('modalVerifikasi').style.display = 'flex';
    }

    function tutupModalVerifikasi() {
        document.getElementById('modalVerifikasi').style.display = 'none';
    }
</script>
@endpush