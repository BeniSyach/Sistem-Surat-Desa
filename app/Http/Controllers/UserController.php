<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['role', 'village', 'department'])
            ->orderBy('name')
            ->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $villages = Village::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        return view('users.create', compact('roles', 'villages', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'village_id' => 'required|exists:villages,id',
            'department_id' => 'nullable|exists:departments,id',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load([
            'role', 
            'village', 
            'department',
            'processedOutgoingLetters'
        ]);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $villages = Village::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        return view('users.edit', compact('user', 'roles', 'villages', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'village_id' => 'required|exists:villages,id',
            'department_id' => 'nullable|exists:departments,id',
            'is_active' => 'boolean',
        ]);

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->outgoingLetters()->exists() || 
            $user->processedOutgoingLetters()->exists()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Pengguna tidak dapat dihapus karena memiliki surat terkait.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Activate the specified user.
     */
    public function activate(User $user)
    {
        $user->update(['is_active' => true]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil diaktifkan.');
    }

    /**
     * Deactivate the specified user.
     */
    public function deactivate(User $user)
    {
        if ($user->role->name === 'Admin') {
            return redirect()
                ->route('users.index')
                ->with('error', 'Admin tidak dapat dinonaktifkan.');
        }

        $user->update(['is_active' => false]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil dinonaktifkan.');
    }

    /**
     * Show signature form.
     */
    public function showSignature(User $user)
    {
        
        return view('users.signature', compact('user'));
    }

    /**
     * Upload signature file.
     */
    public function uploadSignature(Request $request, User $user)
    {
        $this->authorize('manageSignature', $user);
        
        $validated = $request->validate([
            'signature' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        if ($request->hasFile('signature')) {
            $path = $request->file('signature')->store('signatures', 'public');
            $user->updateSignature($path);

            return redirect()
                ->route('users.signature', $user)
                ->with('success', 'Tanda tangan berhasil diunggah.');
        }

        return redirect()
            ->back()
            ->with('error', 'Gagal mengunggah tanda tangan.');
    }

    /**
     * Save drawn signature.
     */
    public function saveDrawnSignature(Request $request, User $user)
    {
        $this->authorize('manageSignature', $user);
        
        $validated = $request->validate([
            'signature' => 'required|string'
        ]);

        try {
            // Remove header from base64 string
            $image = explode(',', $validated['signature'])[1];
            $image = base64_decode($image);

            // Generate unique filename
            $filename = 'signatures/' . uniqid() . '.png';
            
            // Save file
            Storage::disk('public')->put($filename, $image);
            
            // Update user signature
            $user->updateSignature($filename);

            return redirect()
                ->route('users.signature', $user)
                ->with('success', 'Tanda tangan berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menyimpan tanda tangan.');
        }
    }
}
