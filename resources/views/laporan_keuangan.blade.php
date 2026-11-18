<!-- Tombol di Dashboard UMKM -->
<button onclick="bukaModalLaporan()" class="btn-laporan">
    📊 Laporan Keuangan
</button>

<!-- Modal/Dialog Background Blur (Awalnya Disembunyikan) -->
<div id="modalLaporan" class="modal-overlay">
    <div class="modal-content">
        <h3 style="margin-top: 0;">Unduh Laporan Keuangan</h3>
        <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
            Pilih rentang waktu dan status pesanan yang ingin direkap.
        </p>
        
        <!-- Form mengirim GET request ke LaporanKeuanganController -->
        <form action="{{ route('umkm.laporan_keuangan') }}" method="GET">
            
            <div class="form-group">
                <label>Mulai Tanggal:</label>
                <input type="date" name="tanggal_awal" required>
            </div>
            
            <div class="form-group">
                <label>Sampai Tanggal:</label>
                <input type="date" name="tanggal_akhir" required>
            </div>
            
            <div class="form-group">
                <label>Status Pesanan:</label>
                <select name="status" required>
                    <option value="semua">Semua Status</option>
                    <option value="selesai">Selesai Saja</option>
                    <option value="berlangsung">Sedang Berlangsung (Persiapan/Masak/Diambil)</option>
                </select>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-batal" onclick="tutupModalLaporan()">Batal</button>
                <!-- Tombol type="submit" akan memicu form action dan langsung mengunduh file -->
                <button type="submit" class="btn-pilih" onclick="tutupModalLaporan()">Pilih & Unduh</button>
            </div>
        </form>
    </div>
</div>

<!-- Gaya CSS untuk tampilan Modal Blur -->
<style>
    .btn-laporan { 
        padding: 10px 15px; background-color: #28a745; color: white; 
        border: none; border-radius: 5px; cursor: pointer; font-weight: bold;
    }
    
    .modal-overlay {
        display: none; /* Disembunyikan secara default */
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.4); 
        backdrop-filter: blur(6px); /* Efek Blur di belakang modal */
        align-items: center; justify-content: center; z-index: 9999;
    }
    
    .modal-content {
        background: white; padding: 25px; border-radius: 10px; 
        width: 100%; max-width: 400px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3); font-family: sans-serif;
    }
    
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-size: 14px; font-weight: bold; }
    .form-group input, .form-group select { 
        width: 100%; padding: 10px; border: 1px solid #ccc; 
        border-radius: 5px; box-sizing: border-box;
    }
    
    .modal-actions { display: flex; justify-content: space-between; margin-top: 25px; }
    .btn-batal { padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; }
    .btn-pilih { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
</style>

<!-- Script Javascript untuk buka/tutup modal -->
<script>
    function bukaModalLaporan() {
        document.getElementById('modalLaporan').style.display = 'flex';
    }
    function tutupModalLaporan() {
        document.getElementById('modalLaporan').style.display = 'none';
    }
</script>