<!DOCTYPE html>
<html>
<head>
    <title>Ulasan Pesanan Saya</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f9f9f9; }
        .card { background: white; border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 8px; }
        .success-msg { color: green; font-weight: bold; background: #e6ffed; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .error-msg { color: red; font-weight: bold; background: #ffe6e6; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        
        .order-info { margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #ccc; }
        .rating-stars select { font-size: 16px; padding: 5px; }
        textarea { width: 100%; height: 80px; padding: 10px; margin-top: 10px; border: 1px solid #ccc; border-radius: 4px; }
        
        .btn-primary { background: blue; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px;}
        .btn-edit { background: orange; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-danger { background: red; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; }
        
        .review-box { background: #f4f6f8; padding: 15px; border-radius: 5px; margin-top: 10px; }
        .review-img { max-width: 200px; border-radius: 8px; margin-top: 10px; display: block; }
        
        .edit-form { display: none; margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    <h2>Pesanan Selesai & Ulasan</h2>

    @if(session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="error-msg">{{ session('error') }}</div>
    @endif

    @forelse($pesananSelesai as $pesanan)
        <div class="card">
            <!-- Info Pesanan -->
            <div class="order-info">
                <strong>ID Pesanan:</strong> #{{ $pesanan->id_pesanan }} <br>
                <strong>Toko:</strong> {{ $pesanan->umkm->nama_vendor ?? 'UMKM Tidak Diketahui' }} <br>
                <strong>Tgl Selesai:</strong> {{ \Carbon\Carbon::parse($pesanan->tgl_selesai)->format('d M Y, H:i') }} <br>
                <strong>Total Pembayaran:</strong> Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}
            </div>

            <!-- JIKA BELUM DIULAS -->
            @if(empty($pesanan->ulasan))
                <form action="{{ route('pelanggan.ulasan-create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_pesanan" value="{{ $pesanan->id_pesanan }}">
                    
                    <label><strong>Berikan Rating (Wajib):</strong></label>
                    <div class="rating-stars">
                        <select name="rating" required>
                            <option value="" disabled selected>Pilih Bintang...</option>
                            <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                            <option value="4">⭐⭐⭐⭐ (4/5)</option>
                            <option value="3">⭐⭐⭐ (3/5)</option>
                            <option value="2">⭐⭐ (2/5)</option>
                            <option value="1">⭐ (1/5)</option>
                        </select>
                    </div>

                    <textarea name="ulasan" placeholder="Ceritakan pengalamanmu dengan makanan ini (Opsional)..."></textarea>
                    
                    <label style="display:block; margin-top:10px;"><strong>Unggah Foto (Opsional):</strong></label>
                    <input type="file" name="foto_ulasan" accept="image/*">
                    
                    <br>
                    <button type="submit" class="btn-primary">Kirim Ulasan</button>
                </form>

            <!-- JIKA SUDAH DIULAS -->
            @else
                <div class="review-box">
                    <strong>Rating Anda:</strong> {{ str_repeat('⭐', $pesanan->ulasan->rating) }} ({{ $pesanan->ulasan->rating }}/5)<br>
                    <strong>Ulasan:</strong> {{ $pesanan->ulasan->ulasan ?? 'Tidak ada teks ulasan.' }}
                    
                    @if($pesanan->ulasan->foto_ulasan)
                        <img src="{{ asset('storage/' . $pesanan->ulasan->foto_ulasan) }}" alt="Foto Ulasan" class="review-img">
                    @endif
                    
                    <div style="margin-top: 15px;">
                        <button class="btn-edit" onclick="toggleEdit({{ $pesanan->ulasan->id_ulasan }})">Edit Ulasan</button>
                        
                        <form action="{{ route('pelanggan.ulasan-delete', $pesanan->ulasan->id_ulasan) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger" onclick="return confirm('Hapus ulasan ini?')">Hapus</button>
                        </form>
                    </div>
                </div>

                <!-- Form Edit Tersembunyi -->
                <div class="edit-form" id="edit-form-{{ $pesanan->ulasan->id_ulasan }}">
                    <form action="{{ route('pelanggan.ulasan-update', $pesanan->ulasan->id_ulasan) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <label><strong>Edit Rating:</strong></label>
                        <select name="rating" required>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ $pesanan->ulasan->rating == $i ? 'selected' : '' }}>
                                    {{ str_repeat('⭐', $i) }} ({{ $i }}/5)
                                </option>
                            @endfor
                        </select>

                        <textarea name="ulasan">{{ $pesanan->ulasan->ulasan }}</textarea>
                        
                        <label style="display:block; margin-top:10px;"><strong>Ganti Foto (Opsional):</strong></label>
                        <input type="file" name="foto_ulasan" accept="image/*">
                        
                        <br>
                        <button type="submit" class="btn-primary" style="background: orange;">Simpan Perubahan</button>
                        <button type="button" class="btn-danger" style="background: grey;" onclick="toggleEdit({{ $pesanan->ulasan->id_ulasan }})">Batal</button>
                    </form>
                </div>
            @endif
        </div>
    @empty
        <p>Anda belum memiliki pesanan yang diselesaikan.</p>
    @endforelse

    <script>
        // Fungsi untuk buka-tutup form edit
        function toggleEdit(id) {
            var form = document.getElementById('edit-form-' + id);
            if (form.style.display === "none" || form.style.display === "") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }
    </script>
</body>
</html>