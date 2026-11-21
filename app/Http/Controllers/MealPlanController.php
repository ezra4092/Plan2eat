<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Models\DetailMealPlan;
use App\Models\Menu; // Pastikan model Menu sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealPlanController extends Controller
{
    public function index()
    {
        // Muat meal plan beserta detail dan data menu dari relasi detail
        $mealPlans = MealPlan::with('detailMealPlan.menu')
            ->where('id_user', Auth::id())
            ->get();
            
        // Ambil semua daftar menu aktif untuk dropdown pilihan makanan
        $daftarMenu = Menu::where('status', true)->get();

        return view('pelanggan.meal_plan', compact('mealPlans', 'daftarMenu'));
    }

    public function store(Request $request)
    {
        $tomorrow = now('Asia/Jakarta')->addDay()->toDateString();

        $request->validate([
            'judul_plan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date|after_or_equal:' . $tomorrow,
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ], [
            'tanggal_mulai.after_or_equal' => 'Pemesanan Meal Plan hanya dapat diatur minimal untuk esok hari (H+1).',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        MealPlan::create([
            'id_user' => Auth::id(),
            'judul_plan' => $request->judul_plan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->route('pelanggan.meal_plan')->with('success', 'Meal Plan berhasil dibuat.');
    }

    public function update(Request $request, $id_plan)
    {
        $request->validate([
            'judul_plan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $mealPlan = MealPlan::where('id_user', Auth::id())->findOrFail($id_plan);
        $mealPlan->update($request->only(['judul_plan', 'tanggal_mulai', 'tanggal_selesai']));

        return redirect()->route('pelanggan.meal_plan')->with('success', 'Informasi Meal Plan berhasil diperbarui.');
    }

    public function destroy($id_plan)
    {
        $mealPlan = MealPlan::where('id_user', Auth::id())->findOrFail($id_plan);
        // Pastikan relasi onDelete cascade diaktifkan di tabel database
        $mealPlan->delete(); 

        return redirect()->route('pelanggan.meal_plan')->with('deleted', 'Meal Plan berhasil dihapus.');
    }

    // --- FUNGSI DETAIL ---
    public function storeDetail(Request $request, $id_plan)
    {
        // 1. Ambil data meal plan-nya dulu untuk mendapatkan batas tanggal
        $mealPlan = MealPlan::where('id_user', Auth::id())->findOrFail($id_plan);
        $tomorrow = now('Asia/Jakarta')->addDay()->toDateString();
        $minJadwal = max($mealPlan->tanggal_mulai->toDateString(), $tomorrow);

        // 2. Tambahkan validasi batas tanggal (minimal H+1 dan dalam rentang plan)
        $request->validate([
            'id_menu'      => 'required|exists:menu,id_menu', 
            'jadwal_makan' => 'required|date|after_or_equal:' . $minJadwal . '|before_or_equal:' . $mealPlan->tanggal_selesai->toDateString(),
            'waktu_makan'  => 'required|string|max:255',
        ], [
            'jadwal_makan.after_or_equal' => 'Jadwal makan hanya dapat diatur minimal untuk esok hari (H+1) dan tidak boleh sebelum tanggal mulai plan.',
            'jadwal_makan.before_or_equal' => 'Jadwal makan tidak boleh melewati tanggal selesai plan.'
        ]);

        // 3. Simpan data jika validasi lolos
        DetailMealPlan::create([
            'id_plan'      => $mealPlan->id_plan,
            'id_menu'      => $request->id_menu,
            'jadwal_makan' => $request->jadwal_makan,
            'waktu_makan'  => $request->waktu_makan,
        ]);

        return redirect()->route('pelanggan.meal_plan')->with('success', 'Menu berhasil ditambahkan ke jadwal.');
    }

    public function destroyDetail($id_detail_plan)
    {
        $detail = DetailMealPlan::whereHas('mealPlan', function($query) {
            $query->where('id_user', Auth::id());
        })->findOrFail($id_detail_plan);
        
        $detail->delete();

        return redirect()->route('pelanggan.meal_plan')->with('deleted', 'Menu berhasil dihapus dari jadwal.');
    }

    public function updateDetail(Request $request, $id_detail_plan)
    {
        $request->validate([
            'id_menu' => 'required|exists:menu,id_menu',
            'waktu_makan' => 'required|string|max:255',
        ]);

        $detail = DetailMealPlan::whereHas('mealPlan', function($query) {
            $query->where('id_user', Auth::id());
        })->findOrFail($id_detail_plan);

        // Update waktu dan menu (jadwal_makan sengaja tidak di-update agar tanggal terkunci)
        $detail->update([
            'id_menu' => $request->id_menu,
            'waktu_makan' => $request->waktu_makan,
        ]);

        return redirect()->route('pelanggan.meal_plan')->with('success', 'Menu pada jadwal berhasil diperbarui.');
    }
}