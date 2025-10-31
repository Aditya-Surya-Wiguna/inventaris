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
use Barryvdh\DomPDF\Facade\Pdf;

class BarangPindahController extends Controller
{
    /**
     * Tampilkan daftar semua barang pindah
     */
    public function index(Request $request)
    {
        $query = BarangPindah::with(['barang.ruang.gedung.fakultas', 'asal.gedung.fakultas', 'tujuan.gedung.fakultas']);

        // 🔍 Filter berdasarkan tanggal
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_pindah', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        // 🔍 Filter berdasarkan nama barang
        if ($request->filled('nama_barang')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
            });
        }

        // 🔍 Filter berdasarkan ruang asal
        if ($request->filled('ruang_asal')) {
            $query->whereHas('asal', function ($q) use ($request) {
                $q->where('nama_ruang', 'like', '%' . $request->ruang_asal . '%');
            });
        }

        // 🔍 Filter berdasarkan ruang tujuan
        if ($request->filled('ruang_tujuan')) {
            $query->whereHas('tujuan', function ($q) use ($request) {
                $q->where('nama_ruang', 'like', '%' . $request->ruang_tujuan . '%');
            });
        }

        $barangPindah = $query->latest()->get();

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


    public function cetak(Request $request)
    {
        $query = BarangPindah::with(['barang.ruang.gedung.fakultas', 'asal.gedung.fakultas', 'tujuan.gedung.fakultas']);

        // 🔍 Filter berdasarkan tanggal
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_pindah', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        // 🔍 Filter nama barang
        if ($request->filled('nama_barang')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
            });
        }

        // 🔍 Filter ruang asal
        if ($request->filled('ruang_asal')) {
            $query->whereHas('asal', function ($q) use ($request) {
                $q->where('nama_ruang', 'like', '%' . $request->ruang_asal . '%');
            });
        }

        // 🔍 Filter ruang tujuan
        if ($request->filled('ruang_tujuan')) {
            $query->whereHas('tujuan', function ($q) use ($request) {
                $q->where('nama_ruang', 'like', '%' . $request->ruang_tujuan . '%');
            });
        }

        $barangPindah = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('barang_pindah.cetak', compact('barangPindah'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Barang_Pindah.pdf');
    }


}
