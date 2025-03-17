<?php

namespace App\Http\Controllers;

use App\Models\LetterClassification;
use Illuminate\Http\Request;

class LetterClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $letterClassifications = LetterClassification::orderBy('code')->paginate(10);
        return view('letter-classifications.index', compact('letterClassifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('letter-classifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:letter_classifications',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        LetterClassification::create($validated);

        return redirect()
            ->route('letter-classifications.index')
            ->with('success', 'Klasifikasi surat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LetterClassification $letterClassification)
    {
        return view('letter-classifications.show', compact('letterClassification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LetterClassification $letterClassification)
    {
        return view('letter-classifications.edit', compact('letterClassification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LetterClassification $letterClassification)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:letter_classifications,code,' . $letterClassification->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $letterClassification->update($validated);

        return redirect()
            ->route('letter-classifications.index')
            ->with('success', 'Klasifikasi surat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LetterClassification $letterClassification)
    {
        if ($letterClassification->incomingLetters()->exists() || $letterClassification->outgoingLetters()->exists()) {
            return redirect()
                ->route('letter-classifications.index')
                ->with('error', 'Klasifikasi surat tidak dapat dihapus karena masih digunakan oleh surat masuk atau surat keluar.');
        }

        $letterClassification->delete();

        return redirect()
            ->route('letter-classifications.index')
            ->with('success', 'Klasifikasi surat berhasil dihapus.');
    }
}
