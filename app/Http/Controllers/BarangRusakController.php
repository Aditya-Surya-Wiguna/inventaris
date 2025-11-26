<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangRusak;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangRusakController extends Controller
{

    // TAMPIL DATA BARANG RUSAK + FILTER
    public function index(Request $request)
    {
        $query = BarangRusak::with('barang.ruang.gedung.fakultas');

        // Filter tanggal
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_catat', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        //  Filter nama barang
        if ($request->filled('nama_barang')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
            });
        }

        // Filter kondisi baru
        if ($request->filled('kondisi_baru')) {
            $query->where('kondisi_baru', $request->kondisi_baru);
        }

        $barangRusak = $query->latest()->get();

        return view('barang_rusak.index', compact('barangRusak'));
    }

    // FORM TAMBAH BARANG RUSAK
    public function create()
    {
        $barang = Barang::with('ruang.gedung.fakultas')->get();
        return view('barang_rusak.create', compact('barang'));
    }

    // SIMPAN DATA BARANG RUSAK
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'kondisi_baru' => 'required|in:RR,RB',
            'foto_bukti' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $barang = Barang::findOrFail($request->id_barang);

        $data = [
            'id_barang'     => $barang->id_barang,
            'kondisi_awal'  => $barang->kondisi,
            'kondisi_baru'  => $validated['kondisi_baru'],
            'tanggal_catat' => Carbon::now(),
        ];

        if ($request->hasFile('foto_bukti')) {
            $fotoBaru = $request->file('foto_bukti')->store('rusak', 'public');
            $data['foto_bukti'] = $fotoBaru;

            if ($barang->foto_barang) {
                Storage::disk('public')->delete($barang->foto_barang);
            }
            $barang->update(['foto_barang' => $fotoBaru]);
        }

        BarangRusak::create($data);
        $barang->update(['kondisi' => $validated['kondisi_baru']]);

        return redirect()->route('barang-rusak.index')
            ->with('success', 'Data barang rusak berhasil disimpan.');
    }

    // CETAK PDF BERDASARKAN FILTER
    public function cetak(Request $request)
    {
        $query = BarangRusak::with('barang.ruang.gedung.fakultas');

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_catat', [$request->tanggal_awal, $request->tanggal_akhir]);
        }
        if ($request->filled('nama_barang')) {
            $query->whereHas('barang', function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
            });
        }
        if ($request->filled('kondisi_baru')) {
            $query->where('kondisi_baru', $request->kondisi_baru);
        }

        $barangRusak = $query->get();

        $pdf = Pdf::loadView('barang_rusak.cetak', compact('barangRusak'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Barang_Rusak.pdf');
    }
}
