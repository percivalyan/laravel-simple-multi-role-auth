<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Session; // Assuming you have a Session model for logging
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Tampilkan semua user
    public function index()
    {
        $users = User::orderBy('name')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Form create user
    public function create()
    {
        $roles = ['admin', 'editor', 'user'];
        return view('admin.users.create', compact('roles'));
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,editor,user',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Log the user creation action
        Session::create([
            'id' => Str::uuid(),
            'user_id' => Auth::id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => 'create_user',
            'description' => 'User created: ' . $user->name,
            'payload' => json_encode(['email' => $request->email, 'role' => $request->role]),
            'last_activity' => Carbon::now()->timestamp,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    // Form edit user
    public function edit(User $user)
    {
        $roles = ['admin', 'editor', 'user'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'role' => 'required|in:admin,editor,user',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Log the user update action
        Session::create([
            'id' => Str::uuid(),
            'user_id' => Auth::id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => 'update_user',
            'description' => 'User updated: ' . $user->name,
            'payload' => json_encode(['email' => $request->email, 'role' => $request->role]),
            'last_activity' => Carbon::now()->timestamp,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate.');
    }

    // Hapus user
    public function destroy(User $user)
    {
        // Log the user deletion action
        Session::create([
            'id' => Str::uuid(),
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'action' => 'delete_user',
            'description' => 'User deleted: ' . $user->name,
            'payload' => json_encode(['email' => $user->email]),
            'last_activity' => Carbon::now()->timestamp,
        ]);

        // Menghapus user
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
