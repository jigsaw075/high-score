<?php

namespace App\Http\Requests;

class ResetPasswordRequest extends FormRequest
{
    /**
     * @return string
     */
    public function responseMessage(): string
    {
        return "Şifre sıfırlama başarısız!";
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
            'email' => [
                'required',
                'email'
            ]
        ];
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [
            'email' => "Mail Bilgisi"
        ];
    }
}
