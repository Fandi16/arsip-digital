<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
         $validated = $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required',
            'role'     => 'required|in:admin,admin_marketing,marketing',
            'wilayah'  => 'nullable|array',
        ]);

        if (in_array($request->role, ['admin_marketing', 'marketing']) && (!$request->wilayah || count($request->wilayah) == 0)) {
            return back()->withErrors(['wilayah' => 'Wilayah wajib diisi.'])->withInput();
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'wilayah' => $validated['wilayah'] ? json_encode($validated['wilayah']) : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $user->wilayah = $user->wilayah ? json_decode($user->wilayah, true) : [];
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
         $validated = $request->validate([
            'name'    => 'required',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'role'    => 'required|in:admin,admin_marketing,marketing',
            'wilayah' => 'nullable|array',
        ]);

        if (in_array($request->role, ['admin_marketing', 'marketing']) && (!$request->wilayah || count($request->wilayah) == 0)) {
            return back()->withErrors(['wilayah' => 'Wilayah wajib diisi.'])->withInput();
        }

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'role'    => $request->role,
            'wilayah' => $validated['wilayah'] ? json_encode($validated['wilayah']) : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
