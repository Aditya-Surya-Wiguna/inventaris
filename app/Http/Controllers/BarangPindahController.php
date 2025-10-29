<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangPindah;
use App\Models\Ruang;
use App\Models\Gedung;
use App\Models\Fakultas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BarangPindahController extends Controller
{
    /**
     * Tampilkan daftar semua barang pindah
     */
    public function index()
    {
        $barangPindah = BarangPindah::with(['barang.ruang.gedung.fakultas', 'asal.gedung.fakultas', 'tujuan.gedung.fakultas'])
            ->latest()
            ->get();

        return view('barang_pindah.index', compact('barangPindah'));
    }

    /**
     * Form tambah data pemindahan barang
     */
    public function create()
    {
        // Ambil semua barang beserta lokasi lengkap (ruang->gedung->fakultas)
        $barang = Barang::with('ruang.gedung.fakultas')->get();

        // Ambil semua fakultas untuk dropdown lokasi tujuan
        $fakultas = Fakultas::all();

        return view('barang_pindah.create', compact('barang', 'fakultas'));
    }

    /**
     * Simpan data pemindahan barang
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'id_ruang_tujuan' => 'required|exists:ruang,id_ruang',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,png',
        ]);

        $barang = Barang::with('ruang')->findOrFail($request->id_barang);

        // Lokasi asal otomatis dari data barang
        $validated['id_ruang_asal'] = $barang->id_ruang;
        $validated['tanggal_pindah'] = Carbon::now();

        // Upload file surat jika ada
        if ($request->hasFile('file_surat')) {
            $validated['file_surat'] = $request->file('file_surat')->store('surat_pindah', 'public');
        }

        // Simpan data pemindahan
        BarangPindah::create($validated);

        // Update lokasi barang ke ruang tujuan
        $barang->update(['id_ruang' => $request->id_ruang_tujuan]);

        return redirect()->route('barang-pindah.index')->with('success', 'Data pemindahan barang berhasil disimpan.');
    }

    /**
     * AJAX: Ambil gedung berdasarkan fakultas
     */
    public function getGedung($id_fakultas)
    {
        $gedung = Gedung::where('id_fakultas', $id_fakultas)->get();
        return response()->json($gedung);
    }

    /**
     * AJAX: Ambil ruang berdasarkan gedung
     */
    public function getRuang($id_gedung)
    {
        $ruang = Ruang::where('id_gedung', $id_gedung)->get();
        return response()->json($ruang);
    }
}
