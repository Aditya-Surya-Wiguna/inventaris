<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fakultas;
use App\Models\Gedung;
use App\Models\Ruang;

class LokasiController extends Controller
{
    // INDEX
    public function index()
    {
        $fakultas = Fakultas::with('gedung.ruang')->get();
        return view('lokasi.index', compact('fakultas'));
    }

    // CREATE
    public function create()
    {
        $fakultas = Fakultas::all();
        $gedung = Gedung::with('fakultas')->get();
        return view('lokasi.create', compact('fakultas', 'gedung'));
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:fakultas,gedung,ruang',
        ]);

        // --------- TAMBAH FAKULTAS ---------
        if ($request->tipe === 'fakultas') {
            $request->validate([
                'kode_fakultas' => 'required|unique:fakultas,kode_fakultas',
                'nama_fakultas' => 'required|string|max:100',
            ]);

            Fakultas::create([
                'kode_fakultas' => $request->kode_fakultas,
                'nama_fakultas' => $request->nama_fakultas,
            ]);

            return redirect()->route('lokasi.index')->with('success', 'Fakultas berhasil ditambahkan.');
        }

        // --------- TAMBAH GEDUNG ---------
        if ($request->tipe === 'gedung') {
            $request->validate([
                'id_fakultas' => 'required|exists:fakultas,id_fakultas',
                'kode_gedung' => 'required|string|max:10',
            ]);

            Gedung::create([
                'id_fakultas' => $request->id_fakultas,
                'kode_gedung' => $request->kode_gedung,
            ]);

            return redirect()->route('lokasi.index')->with('success', 'Gedung berhasil ditambahkan.');
        }

        // --------- TAMBAH BANYAK RUANG SEKALIGUS ---------
        if ($request->tipe === 'ruang') {
            $request->validate([
                'id_gedung' => 'required|exists:gedung,id_gedung',
                'nama_ruang' => 'required|array|min:1',
                'nama_ruang.*' => 'required|string|max:100',
            ]);

            $duplikat = [];

            foreach ($request->nama_ruang as $nama) {
                $namaTrim = trim($nama);

                // Cek apakah sudah ada ruang dengan nama sama di gedung ini
                $cek = Ruang::where('id_gedung', $request->id_gedung)
                    ->where('nama_ruang', $namaTrim)
                    ->first();

                if ($cek) {
                    $duplikat[] = $namaTrim;
                    continue;
                }

                Ruang::create([
                    'id_gedung' => $request->id_gedung,
                    'nama_ruang' => $namaTrim,
                ]);
            }

            if (!empty($duplikat)) {
                return redirect()->route('lokasi.index')
                    ->with('warning', 'Beberapa ruang tidak disimpan karena duplikat: ' . implode(', ', $duplikat));
            }

            return redirect()->route('lokasi.index')->with('success', 'Beberapa ruang berhasil ditambahkan sekaligus.');
        }
    }

    // DESTROY
    public function destroy($id)
    {
        $tipe = request('tipe');

        switch ($tipe) {
            case 'fakultas':
                Fakultas::findOrFail($id)->delete();
                return back()->with('success', 'Fakultas berhasil dihapus.');
            case 'gedung':
                Gedung::findOrFail($id)->delete();
                return back()->with('success', 'Gedung berhasil dihapus.');
            case 'ruang':
                Ruang::findOrFail($id)->delete();
                return back()->with('success', 'Ruang berhasil dihapus.');
            default:
                return back()->with('error', 'Gagal menghapus lokasi.');
        }
    }

    // AJAX DYNAMIC DATA
    public function getGedung($id_fakultas)
    {
        return response()->json(
            Gedung::where('id_fakultas', $id_fakultas)->get()
        );
    }

    public function getRuang($id_gedung)
    {
        return response()->json(
            Ruang::where('id_gedung', $id_gedung)->get()
        );
    }
}
