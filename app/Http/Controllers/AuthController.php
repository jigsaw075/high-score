<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckResetPasswordTokenRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ResetPasswordTokenRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!$token = auth()->attempt($request->validated())) {
            return $this->respondError("No matching account found!");
        }

        return $this->respondOk("Login successful!", [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->respondOk("OK", User::create($request->validated()));
    }

    /**
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $user = User::select('id', 'email')
            ->where('email', $request->input("email"))
            ->first();

        if (!$user) {
            return $this->respondNotFound("Eşleşen bir hesap bulunamadı!");
        }

        $token = Str::random(32);

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'user_id' => $user->id,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('email.forgot-password', ['token' => $token, 'user' => $user], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Parolamı Unuttum');
        });

        return $this->respondOk("Parola sıfırlama isteğiniz başarıyla gönderilmiştir, lütfen gelen kutunuzu kontrol edin");
    }

    /**
     * @param ResetPasswordTokenRequest $request
     * @return JsonResponse
     */
    public function resetPasswordToken(ResetPasswordTokenRequest $request): JsonResponse
    {
        $token = DB::table('password_resets')
            ->where('token', $request->input('token'))
            ->first();

        if ($token->created_at < Carbon::now()->subMinutes(15)) {
            return $this->respondError("Tokenin süresi dolmuştur");
        }

        User::find($token->user_id)->update([
            'password' => Hash::make($request->input('password'))
        ]);

        DB::table('password_resets')->delete($token->id);

        return $this->respondOk("Parolanız başarıyla sıfırlanmıştır.");
    }

    /*
    * @param CheckResetPasswordTokenRequest $request
    * @return JsonResponse
    */
    public function checkResetPasswordToken(CheckResetPasswordTokenRequest $request): JsonResponse
    {
        $token = DB::table('password_resets')
            ->where([
                ['token', '=', $request->input('token')],
                ['created_at', '>', Carbon::now()->subMinutes(15)]
            ])
            ->first();

        if (!$token) {
            return $this->respondNotFound("Token'in süresi dolmuş veya hatalıdır");
        }

        return $this->respondOk('Token', $token);
    }

}
