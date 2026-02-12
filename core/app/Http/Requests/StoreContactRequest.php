<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Contact form is public
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|regex:/^(\+90|0)?[0-9]{10}$/',
            'subject' => 'required|string|min:3|max:200',
            'message' => 'required|string|min:10|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'İsim alanı zorunludur.',
            'name.min' => 'İsim en az 2 karakter olmalıdır.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'phone.regex' => 'Geçerli bir telefon numarası giriniz.',
            'subject.required' => 'Konu alanı zorunludur.',
            'subject.min' => 'Konu en az 3 karakter olmalıdır.',
            'message.required' => 'Mesaj alanı zorunludur.',
            'message.min' => 'Mesaj en az 10 karakter olmalıdır.',
            'message.max' => 'Mesaj en fazla 2000 karakter olabilir.',
        ];
    }

    protected function prepareForValidation()
    {
        // Sanitize inputs
        if ($this->name) {
            $this->merge(['name' => strip_tags($this->name)]);
        }
        if ($this->subject) {
            $this->merge(['subject' => strip_tags($this->subject)]);
        }
    }
}
