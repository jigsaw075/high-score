<?php

namespace App\Http\Requests;

class ResetPasswordTokenRequest extends FormRequest
{
    /**
     * @return string
     */
    public function responseMessage(): string
    {
        return "Şifre sıfırlama işlemi başarısız!";
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'confirmed',
                'min:10',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#\?&]/'
            ],
            'token' => [
                'required',
                'exists:password_resets,token'
            ]
        ];
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'password' => "Parola",
            'token' => "Doğrulama kodu"
        ];
    }
}
