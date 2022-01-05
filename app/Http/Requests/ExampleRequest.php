<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class ExampleRequest extends FormRequest
{
    /**
     * @return string
     */
    public function responseMessage(): string
    {
        return "Validation error!";
    }

    /**
     * Auth::check to check login status
     * Add true if login status is not important
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return [];
    }
}
