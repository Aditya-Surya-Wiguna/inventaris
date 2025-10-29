<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fakultas;
use App\Models\Gedung;
use App\Models\Ruang;

class LokasiController extends Controller
{
    public function index()
    {
        $fakultas = Fakultas::with('gedung.ruang')->get();
        return view('lokasi.index', compact('fakultas'));
    }

    public function create()
    {
        $fakultas = Fakultas::all();
        $gedung = Gedung::all();
        return view('lokasi.create', compact('fakultas', 'gedung'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:fakultas,gedung,ruang',
        ]);

        if ($request->tipe === 'fakultas') {
            $request->validate([
                'kode_fakultas' => 'required|unique:fakultas,kode_fakultas',
                'nama_fakultas' => 'required|string',
            ]);

            Fakultas::create([
                'kode_fakultas' => $request->kode_fakultas,
                'nama_fakultas' => $request->nama_fakultas,
            ]);
            return redirect()->route('lokasi.index')->with('success', 'Fakultas berhasil ditambahkan.');
        }

        if ($request->tipe === 'gedung') {
            $request->validate([
                'id_fakultas' => 'required|exists:fakultas,id_fakultas',
                'kode_gedung' => 'required|in:A,B',
            ]);

            Gedung::create([
                'id_fakultas' => $request->id_fakultas,
                'kode_gedung' => $request->kode_gedung,
            ]);
            return redirect()->route('lokasi.index')->with('success', 'Gedung berhasil ditambahkan.');
        }

        if ($request->tipe === 'ruang') {
            $request->validate([
                'id_gedung' => 'required|exists:gedung,id_gedung',
                'nama_ruang' => 'required|string',
            ]);

            Ruang::create([
                'id_gedung' => $request->id_gedung,
                'nama_ruang' => $request->nama_ruang,
            ]);
            return redirect()->route('lokasi.index')->with('success', 'Ruang berhasil ditambahkan.');
        }
    }

    public function destroy($id)
    {
        // hapus otomatis berdasarkan tabel yang dikirim dari form
        $tipe = request('tipe');

        if ($tipe === 'fakultas') {
            Fakultas::findOrFail($id)->delete();
            return back()->with('success', 'Fakultas berhasil dihapus.');
        } elseif ($tipe === 'gedung') {
            Gedung::findOrFail($id)->delete();
            return back()->with('success', 'Gedung berhasil dihapus.');
        } elseif ($tipe === 'ruang') {
            Ruang::findOrFail($id)->delete();
            return back()->with('success', 'Ruang berhasil dihapus.');
        }

        return back()->with('error', 'Gagal menghapus lokasi.');
    }

    public function getGedung($id_fakultas)
    {
        return response()->json(
            \App\Models\Gedung::where('id_fakultas', $id_fakultas)->get()
        );
    }

    public function getRuang($id_gedung)
    {
        return response()->json(
            \App\Models\Ruang::where('id_gedung', $id_gedung)->get()
        );
    }

}
