<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Village;
use Illuminate\Support\Facades\Storage;

class VillageController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $villages = Village::all();
        return view('villages.index', compact('villages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('villages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'village_head' => 'nullable|string|max:255',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('village-logos', 'public');
            $data['logo'] = $logoPath;
        }

        Village::create($data);

        return redirect()->route('villages.index')
            ->with('success', 'Data Instansi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Village $village)
    {
        return view('villages.show', compact('village'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Village $village)
    {
        return view('villages.edit', compact('village'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Village $village)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'village_head' => 'nullable|string|max:255',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($village->logo) {
                Storage::disk('public')->delete($village->logo);
            }

            $logo = $request->file('logo');
            $logoPath = $logo->store('village-logos', 'public');
            $data['logo'] = $logoPath;
        }

        $village->update($data);

        return redirect()->route('villages.index')
            ->with('success', 'Data Instansi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Village $village)
    {
        // Delete logo if exists
        if ($village->logo) {
            Storage::disk('public')->delete($village->logo);
        }

        $village->delete();

        return redirect()->route('villages.index')
            ->with('success', 'Data Instansi berhasil dihapus.');
    }
}
