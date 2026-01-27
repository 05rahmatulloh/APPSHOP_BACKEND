<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * ======================
     * LOGIN
     * ======================
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        if (!Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) {
            return response()->json([
                'status'  => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        $user = Auth::user();

        // Hapus token lama (logout semua device)
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil',
            'user'    => [
                'id'    => $user->id,
            'name'  => $user->name,
                'email' => $user->email,
                'nim'   => $user->nim,
                'role'  => $user->role,
            ],
            'token' => $token,
        ], 200);
    }

    /**
     * ======================
     * REGISTER
     * ======================
     */
    public function register(Request $request)
    {

        // dd($request->all());
            $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:users,nim',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',

            'role' => 'in:admin,kurir,customer',

            'study_program' => 'required|string|max:255',

            // kampus hanya 1,2,3
            'kampus' => 'required|integer|in:1,2,3',

            'mabna' => 'required|string|max:255',
            'room_number' => 'required|string|max:50',

            // whatsapp hanya angka
            'whatsapp' => 'required|unique:users,whatsapp|digits_between:10,15',
            ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name'        => $request->name,
            'nim'         => $request->nim,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role?$request->role:'customer',
            'study_program' => $request->study_program,
            'kampus' => $request->kampus,
            'mabna'       => $request->mabna,
            'room_number' => $request->room_number,
            'whatsapp'    => $request->whatsapp,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Registrasi berhasil',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    /**
     * ======================
     * LOGOUT
     * ======================
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout dari semua device berhasil',
        ]);
    }

    /**
     * ======================
     * FORGOT PASSWORD
     * ======================
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token'      => $token,
                'created_at' => now(),
            ]
        );

        Mail::send(
            'emails.reset-password',
            ['token' => $token,'email' => $request->email],
            function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            }
        );

        return response()->json([
            'message' => 'Link reset password berhasil dikirim',
        ]);
    }

    /**
     * ======================
     * RESET PASSWORD
     * ======================
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'token'                 => 'required',
            'password'              => 'required|min:6|confirmed',
        ]);

        $check = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token,
            ])
            ->first();

        if (!$check) {
            return response()->json([
                'message' => 'Token tidak valid',
            ], 400);
        }

        DB::table('users')
            ->where('email', $request->email)
            ->update([
                'password' => Hash::make($request->password),
            ]);

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Logout semua device setelah reset password
        $user = User::where('email', $request->email)->first();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Password berhasil direset',
        ]);
    }
}
