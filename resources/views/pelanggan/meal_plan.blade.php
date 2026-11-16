<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Meal Plan</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .plan-card { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
        .detail-section { background-color: #f4f6f8; padding: 15px; margin-top: 15px; border-radius: 5px; }
        .success-msg { color: green; font-weight: bold; background: #e6ffed; padding: 10px; border: 1px solid #b3ffc2;}
        input, select, button { padding: 6px; margin-right: 5px; }
    </style>
</head>
<body>
    <h2>Manajemen Meal Plan</h2>
    
    @if(session('success'))
        <div class="success-msg">{{ session('success') }}</div><br>
    @endif

    <!-- Form Tambah Meal Plan Baru -->
    <div style="border: 2px dashed #999; padding: 15px; margin-bottom: 30px; border-radius: 8px;">
        <h3>+ Buat Meal Plan Baru</h3>
        <form action="{{ route('pelanggan.meal_plan.store') }}" method="POST">
            @csrf
            <label>Judul:</label>
            <input type="text" name="judul_plan" placeholder="Contoh: Diet Keto" required>
            <label>Mulai:</label>
            <input type="date" name="tanggal_mulai" required>
            <label>Selesai:</label>
            <input type="date" name="tanggal_selesai" required>
            <button type="submit" style="background: blue; color: white;">Simpan Plan</button>
        </form>
    </div>

    <!-- Daftar Meal Plan Anda -->
    <h3>Daftar Meal Plan Anda</h3>
    @forelse($mealPlans as $plan)
    <div class="plan-card">
        
        <!-- Baris Edit & Hapus Meal Plan Utama -->
        <form action="{{ route('pelanggan.meal_plan.update', $plan->id_plan) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('PUT')
            <input type="text" name="judul_plan" value="{{ $plan->judul_plan }}" required>
            <input type="date" name="tanggal_mulai" value="{{ $plan->tanggal_mulai }}" required>
            <input type="date" name="tanggal_selesai" value="{{ $plan->tanggal_selesai }}" required>
            <button type="submit">Update Utama</button>
        </form>

        <form action="{{ route('pelanggan.meal_plan.destroy', $plan->id_plan) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Hapus seluruh plan ini?')" style="color:red;">Hapus Plan</button>
        </form>

        <!-- Bagian Kelola Menu Harian -->
        <div class="detail-section">
            <h4>Jadwal & Menu Makanan</h4>
            
            <!-- Tambah Menu ke Plan ini -->
            <form action="{{ route('pelanggan.meal_plan.detail.store', $plan->id_plan) }}" method="POST" style="margin-bottom: 10px;">
                @csrf
                <label>Menu:</label>
                <select name="id_menu" required>
                    <option value="" disabled selected>Pilih Makanan...</option>
                    @foreach($daftarMenu as $menu)
                        <option value="{{ $menu->id_menu }}">{{ $menu->nama_makanan }}</option>
                    @endforeach
                </select>

                <label>Jadwal:</label>
                <input type="date" name="jadwal_makan" min="{{ $plan->tanggal_mulai }}" max="{{ $plan->tanggal_selesai }}" required>
                
                <label>Waktu:</label>
                <select name="waktu_makan" required>
                    <option value="Sarapan">Sarapan</option>
                    <option value="Makan Siang">Makan Siang</option>
                    <option value="Makan Malam">Makan Malam</option>
                    <option value="Cemilan">Cemilan</option>
                </select>
                <button type="submit">+ Tambah ke Jadwal</button>
            </form>

            <!-- Tabel Daftar Menu di Plan ini -->
            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <thead style="background: #ddd;">
                    <tr>
                        <th>Jadwal</th>
                        <th>Waktu</th>
                        <th>Menu Makanan</th>
                        <th>Kalori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plan->detailMealPlan as $detail)
                    <tr>
                        <!-- Format Tanggal menjadi dd-mm-yyyy (Permintaan 1 & 3) -->
                        <td>{{ \Carbon\Carbon::parse($detail->jadwal_makan)->format('d-m-Y') }}</td>
                        
                        <!-- Baris Tampilan Normal -->
                        <td class="view-mode-{{ $detail->id_detail_plan }}">{{ $detail->waktu_makan }}</td>
                        <td class="view-mode-{{ $detail->id_detail_plan }}">{{ $detail->menu->nama_makanan ?? 'Menu tidak ditemukan' }}</td>
                        <td class="view-mode-{{ $detail->id_detail_plan }}">{{ $detail->menu->kandungan_kalori ?? '-' }} kkal</td>
                        <td class="view-mode-{{ $detail->id_detail_plan }}">
                            <button type="button" onclick="toggleEdit({{ $detail->id_detail_plan }})" style="color: blue;">Edit</button>
                            <form action="{{ route('pelanggan.meal_plan.detail.destroy', $detail->id_detail_plan) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus menu dari jadwal ini?')" style="color:red;">Hapus</button>
                            </form>
                        </td>

                        <!-- Baris Mode Edit (Awalnya Disembunyikan) -->
                        <td colspan="4" class="edit-mode-{{ $detail->id_detail_plan }}" style="display: none; background: #fffde7;">
                            <form action="{{ route('pelanggan.meal_plan.detail.update', $detail->id_detail_plan) }}" method="POST" style="display: flex; gap: 10px; align-items: center; margin: 0;">
                                @csrf
                                @method('PUT')
                                
                                <select name="waktu_makan" required>
                                    <option value="Sarapan" {{ $detail->waktu_makan == 'Sarapan' ? 'selected' : '' }}>Sarapan</option>
                                    <option value="Makan Siang" {{ $detail->waktu_makan == 'Makan Siang' ? 'selected' : '' }}>Makan Siang</option>
                                    <option value="Makan Malam" {{ $detail->waktu_makan == 'Makan Malam' ? 'selected' : '' }}>Makan Malam</option>
                                    <option value="Cemilan" {{ $detail->waktu_makan == 'Cemilan' ? 'selected' : '' }}>Cemilan</option>
                                </select>

                                <select name="id_menu" required>
                                    @foreach($daftarMenu as $menu)
                                        <option value="{{ $menu->id_menu }}" {{ $detail->id_menu == $menu->id_menu ? 'selected' : '' }}>
                                            {{ $menu->nama_makanan }}
                                        </option>
                                    @endforeach
                                </select>

                                <button type="submit" style="color: green;">Simpan</button>
                                <button type="button" onclick="toggleEdit({{ $detail->id_detail_plan }})">Batal</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Belum ada menu di jadwal ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
    @empty
        <p>Anda belum memiliki Meal Plan.</p>
    @endforelse

    <script>
        function toggleEdit(id) {
            const viewElements = document.querySelectorAll('.view-mode-' + id);
            const editElement = document.querySelector('.edit-mode-' + id);

            if (editElement.style.display === 'none') {
                viewElements.forEach(el => el.style.display = 'none');
                editElement.style.display = 'table-cell';
            } else {
                viewElements.forEach(el => el.style.display = 'table-cell');
                editElement.style.display = 'none';
            }
        }
    </script>
</body>
</html>