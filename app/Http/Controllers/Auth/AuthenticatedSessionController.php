<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // 1. Cari user berdasarkan email/username yang diinput
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // 2. Cek apakah ada sesi aktif untuk user ini di database
            // Waktu last_activity menggunakan format UNIX Timestamp
            $hasActiveSession = DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('last_activity', '>=', time() - (config('session.lifetime') * 60))
                ->exists();

            // 3. Jika sesi masih aktif, tolak login
            if ($hasActiveSession) {
                return back()->withErrors([
                    'email' => 'Akun ini sedang login di perangkat lain. Harap logout terlebih dahulu.',
                ])->onlyInput('email');
            }
        }

        // 4. Jika aman (tidak ada sesi aktif), lanjutkan proses login bawaan Laravel
        $request->authenticate();
        $request->session()->regenerate();

        DB::table('activity_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'Login',
            'description' => auth()->user()->name . ' berhasil login ke sistem.',
            'created_at' => now(),
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (auth()->check()) {
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Logout',
                'description' => auth()->user()->name . ' keluar (logout) dari sistem.',
                'created_at' => now(),
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
