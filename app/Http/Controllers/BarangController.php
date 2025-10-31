<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Fakultas;
use App\Models\Gedung;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Milon\Barcode\Facades\DNS2D; // ✅ gunakan milon/barcode


class BarangController extends Controller
{
    // =======================
    // INDEX
    // =======================
    public function index(Request $request)
    {
        $query = Barang::with('ruang.gedung.fakultas');

        // 🔍 Filter Fakultas
        if ($request->filled('fakultas')) {
            $query->whereHas('ruang.gedung.fakultas', function ($q) use ($request) {
                $q->where('id_fakultas', $request->fakultas);
            });
        }

        // 🔍 Filter Gedung
        if ($request->filled('gedung')) {
            $query->whereHas('ruang.gedung', function ($q) use ($request) {
                $q->where('id_gedung', $request->gedung);
            });
        }

        // 🔍 Filter Ruang
        if ($request->filled('ruang')) {
            $query->where('id_ruang', $request->ruang);
        }

        // 🔍 Filter Kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // 🔍 Pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', "%{$request->search}%")
                    ->orWhere('kode_barang', 'like', "%{$request->search}%");
            });
        }

        $barang = $query->orderBy('id_barang', 'desc')->get();

        // ✅ Data filter dinamis
        $fakultas = Fakultas::whereHas('gedung.ruang.barang')->get();
        $gedung = Gedung::whereHas('ruang.barang')->get();

        // 🧩 Tambahkan label ruang yang lebih informatif
        $ruang = Ruang::with('gedung.fakultas')
            ->whereHas('barang')
            ->get()
            ->map(function ($r) {
                $fak = $r->gedung->fakultas->kode_fakultas ?? '-';
                $ged = $r->gedung->kode_gedung ?? '-';
                $r->label_ruang = "{$r->nama_ruang} ({$fak} - Gedung {$ged})";
                return $r;
            });

        // 🔸 Ambil kondisi unik
        $kondisiList = Barang::select('kondisi')->distinct()->pluck('kondisi');

        return view('barang.index', compact('barang', 'fakultas', 'gedung', 'ruang', 'kondisiList'));
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

        if ($request->hasFile('foto_surat')) {
            if ($barang->foto_surat) Storage::disk('public')->delete($barang->foto_surat);
            $validated['foto_surat'] = $request->file('foto_surat')->store('surat', 'public');
        }

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
        $barang = Barang::with([
            'ruang.gedung.fakultas',
            'riwayatRusak',
            'riwayatPindah.tujuan.gedung.fakultas',
            'riwayatPindah.asal.gedung.fakultas'
        ])->findOrFail($id);

        return view('barang.show', compact('barang'));
    }

    // =======================
    // BARCODE (1 item)
    // =======================
    public function barcode($id)
    {
        $barang = Barang::findOrFail($id);
        $qr = \Milon\Barcode\DNS2D::getBarcodePNG(route('barang.show', $barang->id_barang), 'QRCODE');
        return view('barang.barcode', compact('barang', 'qr'));
    }

    // =======================
    // CETAK BARCODE (semua / hasil filter)
    // =======================
    public function cetakBarcode(Request $request)
    {
        $query = Barang::query();

        // Filter sama seperti index()
        if ($request->filled('fakultas')) {
            $query->whereHas('ruang.gedung.fakultas', function ($q) use ($request) {
                $q->where('id_fakultas', $request->fakultas);
            });
        }

        if ($request->filled('gedung')) {
            $query->whereHas('ruang.gedung', function ($q) use ($request) {
                $q->where('id_gedung', $request->gedung);
            });
        }

        if ($request->filled('ruang')) {
            $query->where('id_ruang', $request->ruang);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', "%{$request->search}%")
                    ->orWhere('kode_barang', 'like', "%{$request->search}%");
            });
        }

        $barang = $query->orderBy('id_barang', 'asc')->get();

        // Buat PDF berisi semua barcode
        $pdf = Pdf::loadView('barang.cetak-barcode', compact('barang'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Daftar-Barcode-Barang.pdf');
    }

    // =======================
    // CETAK PDF DATA BARANG
    // =======================
    public function cetakPdf(Request $request)
    {
        $query = Barang::with('ruang.gedung.fakultas');

        // Filter fakultas
        if ($request->filled('fakultas')) {
            $query->whereHas('ruang.gedung.fakultas', function ($q) use ($request) {
                $q->where('id_fakultas', $request->fakultas);
            });
        }

        // Filter gedung
        if ($request->filled('gedung')) {
            $query->whereHas('ruang.gedung', function ($q) use ($request) {
                $q->where('id_gedung', $request->gedung);
            });
        }

        // Filter ruang
        if ($request->filled('ruang')) {
            $query->where('id_ruang', $request->ruang);
        }

        // Filter kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', "%{$request->search}%")
                ->orWhere('kode_barang', 'like', "%{$request->search}%");
            });
        }

        $barang = $query->orderBy('id_barang', 'asc')->get();

        // Render PDF
        $pdf = Pdf::loadView('barang.cetak-pdf', [
            'barang' => $barang,
            'judul' => 'Laporan Data Barang Inventaris UIN Raden Fatah',
            'tanggal' => now()->translatedFormat('d F Y')
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Data-Barang.pdf');
    }

    public function cetakLaporan(Request $request)
    {
        $query = Barang::with('ruang.gedung.fakultas');

        // Filter fakultas, gedung, ruang (optional)
        if ($request->filled('fakultas')) {
            $query->whereHas('ruang.gedung.fakultas', fn($q) => $q->where('id_fakultas', $request->fakultas));
        }
        if ($request->filled('gedung')) {
            $query->whereHas('ruang.gedung', fn($q) => $q->where('id_gedung', $request->gedung));
        }
        if ($request->filled('ruang')) {
            $query->where('id_ruang', $request->ruang);
        }

        $barang = $query->orderBy('id_ruang')->get()->groupBy('id_ruang');

        $pdf = Pdf::loadView('barang.cetak-laporan', [
            'barangGroup' => $barang,
            'tanggalCetak' => now()->translatedFormat('F Y'),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Barang-Ruangan.pdf');
    }

    // ==========================
    // CREATE MULTIPLE
    // ==========================
    public function createMultiple()
    {
        $fakultas = Fakultas::all();
        return view('barang.create-multiple', compact('fakultas'));
    }

    // ==========================
    // STORE MULTIPLE
    // ==========================
    public function storeMultiple(Request $request)
    {
        $validated = $request->validate([
            'id_ruang' => 'required|exists:ruang,id_ruang',
            'foto_surat' => 'required|file|mimes:jpg,png,pdf',
            'barang.*.nama_barang' => 'required|string',
            'barang.*.merek_tipe' => 'nullable|string',
            'barang.*.tanggal_masuk' => 'nullable|date',
            'barang.*.jumlah' => 'required|integer|min:1',
            'barang.*.nomor_bmn' => 'nullable|string',
            'barang.*.kondisi' => 'required|in:B,RR,RB',
            'barang.*.foto_barang' => 'nullable|image|mimes:jpg,png'
        ]);

        // Upload surat sekali
        $pathSurat = $request->file('foto_surat')->store('surat', 'public');

        foreach ($request->barang as $data) {
            $fotoBarangPath = null;
            if (isset($data['foto_barang'])) {
                $fotoBarangPath = $data['foto_barang']->store('barang', 'public');
            }

            Barang::create([
                'kode_barang' => 'BRG-' . str_pad(Barang::count() + 1, 6, '0', STR_PAD_LEFT),
                'nama_barang' => $data['nama_barang'],
                'merek_tipe' => $data['merek_tipe'] ?? null,
                'tanggal_masuk' => $data['tanggal_masuk'] ?? null,
                'jumlah' => $data['jumlah'],
                'nomor_bmn' => $data['nomor_bmn'] ?? null,
                'kondisi' => $data['kondisi'],
                'id_ruang' => $request->id_ruang,
                'foto_surat' => $pathSurat,
                'foto_barang' => $fotoBarangPath,
            ]);
        }

        return redirect()->route('barang.index')->with('success', 'Semua barang berhasil ditambahkan!');
    }




}
