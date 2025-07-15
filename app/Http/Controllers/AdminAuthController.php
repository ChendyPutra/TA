<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::guard('admin')->attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
}

    public function logout(Request $request)
{
    Auth::guard('admin')->logout(); // pastikan pakai guard admin kalau pakai multi-auth

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/admin/login'); // redirect ke halaman login admin
}
public function index()
    {
        $admins = Admin::with('bidang')
                    ->where('role', 'memberadmin')
                    ->get();

        return view('admin.manage.index', compact('admins'));
    }

    public function create()
    {
        $bidangs = Bidang::all();
        return view('admin.manage.create', compact('bidangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:admins,email',
            'password'  => 'required|string|min:6|confirmed',
            'bidang_id' => 'required|exists:bidangs,id',
        ]);

        Admin::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'memberadmin',
            'bidang_id' => $request->bidang_id,
        ]);

        return redirect()->route('admin.manage.index')->with('success', 'Admin berhasil ditambahkan');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        $bidangs = Bidang::all();

        return view('admin.manage.edit', compact('admin', 'bidangs'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:admins,email,' . $admin->id,
            'bidang_id' => 'required|exists:bidangs,id',
            'password'  => 'nullable|string|min:6|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->bidang_id = $request->bidang_id;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.manage.index')->with('success', 'Admin berhasil diupdate');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin.manage.index')->with('success', 'Admin berhasil dihapus');
    }

}
