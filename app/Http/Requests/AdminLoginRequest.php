<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        // sanitize
        $this->merge([
            'email' => is_string($this->email) ? strtolower(trim($this->email)) : $this->email,
            'password' => is_string($this->password) ? trim($this->password) : $this->password,
        ]);
    }

    public function rules(): array
    {
        return [
            'email'    => ['bail','required','email','max:255'],
            'password' => ['bail','required','string','min:8','max:100'],
        ];
    }
}
