<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class LoginController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.signin');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // 1. Validasi input
    $credentials = $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    // 2. Coba login (menggunakan username, bukan email)
    if (Auth::attempt($credentials, $request->filled('remember'))) {
        
        // Jika sukses, buat ulang session agar aman
        $request->session()->regenerate();

        // Lempar ke halaman dashboard
        return redirect()->intended('/dashboard');
    }

    // 3. Jika gagal, balikkan ke login dengan pesan error
    return back()->withErrors([
        'message' => 'Username atau password salah.',
    ])->withInput($request->only('username'));
}



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/sign-in');
    }

    public function username()
{
    return 'username';
}

public function login(Request $request)
{
    $credentials = $request->validate([
        'username' => ['required'],
        'password' => ['required'],
    ]);

    // Logika tambahan: Hanya izinkan username BMThasanah
    if ($request->username !== 'BMThasanah') {
        return back()->withErrors(['message' => 'Akses ditolak. Username tidak terdaftar.']);
    }

    if (Auth::attempt(['username' => 'BMThasanah', 'password' => $request->password])) {
        $request->session()->regenerate();
        return redirect()->intended('/'); // Masuk ke dashboard
    }

    return back()->withErrors(['message' => 'Password salah.']);
}

}

