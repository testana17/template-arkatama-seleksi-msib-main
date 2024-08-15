<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel;
use Carbon\Carbon;

class TravelController extends Controller
{
    public function index()
    {
        $travels = Travel::all()->map(function($travel) {
            $travel->tanggal_keberangkatan = Carbon::parse($travel->tanggal_keberangkatan)->format('d M Y');
            return $travel;
        });
    
        return view('travels.index', compact('travels'));
    
    }

    public function create()
    {
        return view('travels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255', 
            'tanggal_keberangkatan' => 'required|date',
            'kuota' => 'required|integer',
        ]);

        Travel::create([
            'nama' => $request->nama,
            'tanggal_keberangkatan' => $request->tanggal_keberangkatan,
            'kuota' => $request->kuota,
            'sisa_kuota' => $request->kuota,
        ]);

        return redirect()->route('travels.index')->with('success', 'Travel berhasil ditambahkan.');
    }

    public function edit(Travel $travel)
    {
        // Tidak perlu mencari Travel lagi jika menggunakan route-model binding
        $travel->tanggal_keberangkatan = $travel->tanggal_keberangkatan ?: now()->format('Y-m-d');
    
        return view('travels.edit', compact('travel'));
    }
    public function update(Request $request, Travel $travel)
    {
        $request->validate([
           'nama' => 'required|string|max:255',
            'tanggal_keberangkatan' => 'required|date',
            'kuota' => 'required|integer',
        ]);

        $travel->update($request->all());

        return redirect()->route('travels.index')->with('success', 'Travel berhasil diupdate.');
    }

    public function destroy(Travel $travel)
    {
        $travel->delete();
        return redirect()->route('travels.index')->with('success', 'Travel berhasil dihapus.');
    }
}
