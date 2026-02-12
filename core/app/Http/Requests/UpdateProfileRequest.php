<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'name' => 'required|string|min:2|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\+90|0)?[0-9]{10}$/',
                Rule::unique('users')->ignore($userId),
            ],
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'İsim zorunludur.',
            'name.min' => 'İsim en az 2 karakter olmalıdır.',
            'name.max' => 'İsim en fazla 255 karakter olabilir.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi başka bir kullanıcı tarafından kullanılıyor.',
            'phone.regex' => 'Geçerli bir telefon numarası giriniz (örn: 05XXXXXXXXX).',
            'phone.unique' => 'Bu telefon numarası başka bir kullanıcı tarafından kullanılıyor.',
            'address.max' => 'Adres en fazla 500 karakter olabilir.',
            'city.max' => 'Şehir en fazla 100 karakter olabilir.',
            'bio.max' => 'Biyografi en fazla 1000 karakter olabilir.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Normalize phone number
        if ($this->phone) {
            $phone = preg_replace('/[^0-9]/', '', $this->phone);

            // Remove leading 0 if exists
            if (substr($phone, 0, 1) === '0') {
                $phone = substr($phone, 1);
            }

            // Remove +90 country code if exists
            if (substr($phone, 0, 2) === '90') {
                $phone = substr($phone, 2);
            }

            $this->merge([
                'phone' => $phone,
            ]);
        }
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'isim',
            'email' => 'e-posta',
            'phone' => 'telefon',
            'address' => 'adres',
            'city' => 'şehir',
            'bio' => 'biyografi',
        ];
    }
}
