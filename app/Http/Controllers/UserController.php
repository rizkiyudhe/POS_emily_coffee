<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{


    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function kickSession($id)
    {
        // Opsional: Pastikan yang mengakses ini adalah admin
        if (!auth()->user()->hasPermissionTo('manage users')) {
            abort(403);
        }

        // Menghapus semua sesi aktif milik user tersebut dari database
        DB::table('sessions')->where('user_id', $id)->delete();

        DB::table('activity_logs')->insert([
            'user_id' => $kickedUser->id,
            'action' => 'Kick Session',
            'description' => 'Sesi login ' . $kickedUser->name . ' telah di-reset secara paksa oleh Admin (' . auth()->user()->name . ').',
            'created_at' => now(),
        ]);

        return back()->with('success', 'Sesi perangkat berhasil direset. User tersebut sekarang bisa login kembali.');
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->assignRole($request->role);
        ActivityLog::create(['user_id' => auth()->id(), 'action' => 'create user', 'description' => "Tambah user {$user->name}", 'ip_address' => $request->ip()]);
        return redirect()->route('users.index')->with('success', 'User ditambahkan');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name'
        ]);
        $user->update(['name' => $request->name, 'email' => $request->email]);
        $user->syncRoles($request->role);
        ActivityLog::create(['user_id' => auth()->id(), 'action' => 'update user', 'description' => "Update user {$user->name}", 'ip_address' => $request->ip()]);
        return redirect()->route('users.index')->with('success', 'User diupdate');
    }

    public function destroy(User $user, Request $request)
    {
        $user->delete();
        ActivityLog::create(['user_id' => auth()->id(), 'action' => 'delete user', 'description' => "Hapus user {$user->name}", 'ip_address' => $request->ip()]);
        return redirect()->route('users.index')->with('success', 'User dihapus');
    }

    public function resetPassword(User $user, Request $request)
    {
        $user->update(['password' => bcrypt('password')]);
        ActivityLog::create(['user_id' => auth()->id(), 'action' => 'reset password', 'description' => "Reset password user {$user->name}", 'ip_address' => $request->ip()]);
        return back()->with('success', 'Password direset menjadi "password"');
    }
}
