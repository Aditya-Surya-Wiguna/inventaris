<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Fakultas;
use App\Models\Gedung;
use App\Models\Ruang;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    // =======================
    // INDEX
    // =======================
    public function index()
    {
        $barang = Barang::with('ruang.gedung.fakultas')->orderBy('id_barang', 'desc')->get();
        return view('barang.index', compact('barang'));
    }

    // =======================
    // CREATE
    // =======================
    public function create()
    {
        $fakultas = Fakultas::all();
        return view('barang.create', compact('fakultas'));
    }

    // =======================
    // STORE
    // =======================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string',
            'merek_tipe' => 'nullable|string',
            'tanggal_masuk' => 'nullable|date',
            'jumlah' => 'required|integer|min:1',
            'nomor_bmn' => 'nullable|string',
            'kondisi' => 'required|in:B,RR,RB',
            'foto_surat' => 'nullable|file|mimes:jpg,png,pdf',
            'foto_barang' => 'nullable|image|mimes:jpg,png',
            'id_ruang' => 'required|exists:ruang,id_ruang',
        ]);

        // generate kode otomatis
        $validated['kode_barang'] = 'BRG-' . str_pad(Barang::count() + 1, 6, '0', STR_PAD_LEFT);

        // simpan file surat
        if ($request->hasFile('foto_surat')) {
            $validated['foto_surat'] = $request->file('foto_surat')->store('surat', 'public');
        }

        // simpan foto barang
        if ($request->hasFile('foto_barang')) {
            $validated['foto_barang'] = $request->file('foto_barang')->store('barang', 'public');
        }

        // buat barcode url detail barang
        $validated['kode_barcode'] = route('barang.show', $validated['kode_barang']);

        Barang::create($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    // =======================
    // EDIT
    // =======================
    public function edit($id)
    {
        $barang = Barang::with('ruang.gedung.fakultas')->findOrFail($id);
        $fakultas = Fakultas::all();

        // data gedung & ruang sesuai lokasi aktif barang
        $gedung = $barang->ruang ? Gedung::where('id_fakultas', $barang->ruang->gedung->id_fakultas)->get() : collect();
        $ruang = $barang->ruang ? Ruang::where('id_gedung', $barang->ruang->id_gedung)->get() : collect();

        return view('barang.edit', compact('barang', 'fakultas', 'gedung', 'ruang'));
    }

    // =======================
    // UPDATE
    // =======================
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $validated = $request->validate([
            'nama_barang' => 'required|string',
            'merek_tipe' => 'nullable|string',
            'tanggal_masuk' => 'nullable|date',
            'jumlah' => 'required|integer|min:1',
            'nomor_bmn' => 'nullable|string',
            'kondisi' => 'required|in:B,RR,RB',
            'foto_surat' => 'nullable|file|mimes:jpg,png,pdf',
            'foto_barang' => 'nullable|image|mimes:jpg,png',
            'id_ruang' => 'required|exists:ruang,id_ruang',
        ]);

        // ganti surat baru
        if ($request->hasFile('foto_surat')) {
            if ($barang->foto_surat) Storage::disk('public')->delete($barang->foto_surat);
            $validated['foto_surat'] = $request->file('foto_surat')->store('surat', 'public');
        }

        // ganti foto baru
        if ($request->hasFile('foto_barang')) {
            if ($barang->foto_barang) Storage::disk('public')->delete($barang->foto_barang);
            $validated['foto_barang'] = $request->file('foto_barang')->store('barang', 'public');
        }

        $barang->update($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    // =======================
    // DESTROY
    // =======================
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->foto_barang) Storage::disk('public')->delete($barang->foto_barang);
        if ($barang->foto_surat) Storage::disk('public')->delete($barang->foto_surat);

        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    // =======================
    // SHOW
    // =======================
    public function show($id)
    {
        $barang = Barang::with('ruang.gedung.fakultas')->findOrFail($id);
        return view('barang.show', compact('barang'));
    }

    // =======================
    // BARCODE
    // =======================
    public function barcode($id)
    {
        $barang = Barang::findOrFail($id);
        $qr = QrCode::size(200)->generate(route('barang.show', $barang->id_barang));
        return view('barang.barcode', compact('barang', 'qr'));
    }
}
