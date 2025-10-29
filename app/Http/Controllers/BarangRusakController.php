<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangRusak;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BarangRusakController extends Controller
{
    // ===============================
    // TAMPIL DATA BARANG RUSAK
    // ===============================
    public function index()
    {
        $barangRusak = BarangRusak::with('barang.ruang.gedung.fakultas')->latest()->get();
        return view('barang_rusak.index', compact('barangRusak'));
    }

    // ===============================
    // FORM TAMBAH BARANG RUSAK
    // ===============================
    public function create()
    {
        $barang = Barang::with('ruang.gedung.fakultas')->get();
        return view('barang_rusak.create', compact('barang'));
    }

    // ===============================
    // SIMPAN DATA BARANG RUSAK
    // ===============================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'kondisi_baru' => 'required|in:RR,RB',
            'foto_bukti' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // ambil data barang terkait
        $barang = Barang::findOrFail($request->id_barang);

        // siapkan data untuk tabel barang_rusak
        $data = [
            'id_barang'     => $barang->id_barang,
            'kondisi_awal'  => $barang->kondisi,
            'kondisi_baru'  => $validated['kondisi_baru'],
            'tanggal_catat' => Carbon::now(),
        ];

        // kalau upload foto bukti kerusakan
        if ($request->hasFile('foto_bukti')) {
            $fotoBaru = $request->file('foto_bukti')->store('rusak', 'public');
            $data['foto_bukti'] = $fotoBaru;

            // 🔄 update foto_barang di tabel barang
            if ($barang->foto_barang) {
                Storage::disk('public')->delete($barang->foto_barang);
            }
            $barang->update(['foto_barang' => $fotoBaru]);
        }

        // simpan ke tabel barang_rusak
        BarangRusak::create($data);

        // update kondisi barang utama
        $barang->update(['kondisi' => $validated['kondisi_baru']]);

        return redirect()->route('barang-rusak.index')
            ->with('success', 'Data barang rusak berhasil disimpan, kondisi dan foto barang telah diperbarui.');
    }
}
