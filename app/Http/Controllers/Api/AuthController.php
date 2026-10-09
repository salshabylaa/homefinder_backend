<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function registerHomeadvisor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            // Tambahan field profil homeadvisor bisa ditambahkan di sini nanti sesuai detail form
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'homeadvisor' // Set role otomatis jadi homeadvisor
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Pendaftaran Homeadvisor berhasil',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $key = 'login_attempts:' . md5($request->ip() . '|' . $request->email);
        $cacheData = \Illuminate\Support\Facades\Cache::get($key, ['attempts' => 0, 'locked_until' => null]);

        if ($cacheData['locked_until'] && now()->timestamp < $cacheData['locked_until']) {
            $diff = ceil(($cacheData['locked_until'] - now()->timestamp) / 60);
            return response()->json([
                'message' => "Akun terkunci. Silakan coba lagi dalam {$diff} menit.", 
                'locked' => true
            ], 429);
        }

        if ($cacheData['attempts'] >= 3) {
            if (!$request->captcha_answer || $request->captcha_answer != $request->captcha_expected) {
                return response()->json([
                    'message' => 'Silakan selesaikan CAPTCHA dengan benar karena terdeteksi banyak aktivitas mencurigakan.', 
                    'requires_captcha' => true
                ], 422);
            }
        }

        $user = User::where('email', $request->email)->first();

        if ($user && $user->google_id) {
            return response()->json([
                'message' => 'Akun ini telah ditautkan secara eksklusif dengan Google. Silakan masuk menggunakan tombol Masuk dengan Google.'
            ], 403);
        }

        if (! $user || ! Hash::check($request->password, $user->password)) {
            $cacheData['attempts']++;
            
            if ($cacheData['attempts'] >= 10) {
                $cacheData['locked_until'] = now()->addMinutes(30)->timestamp;
            } elseif ($cacheData['attempts'] == 5) {
                $cacheData['locked_until'] = now()->addMinutes(5)->timestamp;
            }
            
            \Illuminate\Support\Facades\Cache::put($key, $cacheData, now()->addHours(1));

            return response()->json([
                'message' => 'Username atau password yang Anda masukkan salah.',
                'requires_captcha' => $cacheData['attempts'] >= 3
            ], 401);
        }

        \Illuminate\Support\Facades\Cache::forget($key);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password saat ini tidak cocok.'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password berhasil diubah.']);
    }

    public function forgotPassword(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Maaf, alamat email tersebut belum terdaftar di sistem kami.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first('email')
            ], 422);
        }

        $email = $request->email;

        // Generate a 64-char token
        $token = \Illuminate\Support\Str::random(64);
        
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        $resetLink = env('FRONTEND_URL', 'http://localhost:3000') . '/reset-password?token=' . $token . '&email=' . urlencode($email);

        try {
            \Illuminate\Support\Facades\Mail::raw("Silakan klik link berikut untuk mereset password Anda: $resetLink", function ($message) use ($email) {
                $message->to($email)->subject('Reset Password Akun Homefinder');
            });
            return response()->json(['message' => 'Tautan reset password telah dikirim ke email Anda.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Tautan gagal dikirim. Pastikan server email sudah dikonfigurasi. Info error: ' . $e->getMessage()], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $record = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return response()->json(['message' => 'Token reset password tidak valid atau sudah kadaluarsa.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password berhasil direset. Silakan login dengan password baru.']);
    }
}
