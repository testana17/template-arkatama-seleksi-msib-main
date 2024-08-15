<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Penumpang;
use App\Models\Travel;

class PenumpangController extends Controller
{
    public function index()
    {
        $penumpangs = Penumpang::with('travel')->get();
        return view('penumpangs.index', compact('penumpangs'));
    }

    public function create()
    {
        $travels = Travel::where('tanggal_keberangkatan', '>', now())
                         ->where('sisa_kuota', '>', 0)
                         ->get();
        return view('penumpangs.create', compact('travels'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'id_travel' => 'required|exists:travel,id',
            'nama' => 'required|string',
            'usia' => 'required|integer',
            'tahun_lahir' => 'nullable|integer|between:1900,' . date('Y'), // Ensure valid year range
            'kota' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
        ]);
    
        // Process the input data
        $nama = strtoupper($request->input('nama'));
        $kota = strtoupper($request->input('kota'));
        $usia = $request->input('usia');
    
        // Calculate or retrieve year of birth
        $tahun_lahir = $request->input('tahun_lahir');
        if (!$tahun_lahir) {
            $tahun_lahir = date('Y') - $usia; // Calculate year of birth if not provided
        }
    
        // Generate a unique 12-character alphanumeric booking code
        $kode_booking = $this->generateUniqueBookingCode();
    
        // Save the new passenger
        Penumpang::create([
            'id_travel' => $request->input('id_travel'),
            'kode_booking' => $kode_booking,
            'nama' => $nama,
            'jenis_kelamin' => $request->input('jenis_kelamin'),
            'kota' => $kota,
            'usia' => $usia,
            'tahun_lahir' => $tahun_lahir,
        ]);
    
        // Update the remaining quota for the travel
        $travel = Travel::find($request->input('id_travel'));
        $travel->decrement('sisa_kuota');
    
        return redirect()->route('penumpangs.index')->with('success', 'Penumpang berhasil ditambahkan.');
    }
    
    /**
     * Generate a unique 12-character alphanumeric booking code.
     *
     * @return string
     */
    private function generateUniqueBookingCode()
    {
        do {
            // Generate a random 12-character alphanumeric code
            $kode_booking = Str::upper(Str::random(12));
        } while (Penumpang::where('kode_booking', $kode_booking)->exists());
    
        return $kode_booking;
    }
    


    

    public function edit(Penumpang $penumpang)
    {
        $travels = Travel::where('tanggal_keberangkatan', '>', now())
                         ->where('sisa_kuota', '>', 0)
                         ->get();
        return view('penumpangs.edit', compact('penumpang', 'travels'));
    }

    public function update(Request $request, Penumpang $penumpang)
{
    // Validate the incoming request data
    $request->validate([
        'nama' => 'required|string',
        'jenis_kelamin' => 'required|in:L,P',
        'kota' => 'required|string',
        'usia' => 'required|integer',
        'tahun_lahir' => 'nullable|integer|between:1900,' . date('Y'), // Ensure valid year range
    ]);

    // Process the input data
    $data = $request->only(['nama', 'jenis_kelamin', 'kota', 'usia', 'tahun_lahir']);
    
    // Update the penumpang record
    $penumpang->update($data);

    return redirect()->route('penumpangs.index')->with('success', 'Penumpang berhasil diupdate.');
}


    public function destroy(Penumpang $penumpang)
    {
        $penumpang->delete();
        return redirect()->route('penumpangs.index')->with('success', 'Penumpang berhasil dihapus.');
    }
}
