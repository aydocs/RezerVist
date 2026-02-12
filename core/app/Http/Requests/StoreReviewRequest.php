<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Reservation;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        if (!$user) return false;

        // 1. Check if user is blocked
        if ($user->is_review_blocked) {
            return false;
        }

        // 2. User must have an approved reservation at this business that has already started (past or current)
        // This ensures they actually visited/used the service.
        $businessId = $this->route('id');
        $userId = $user->id;

        $hasEligibleReservation = Reservation::where('business_id', $businessId)
            ->where('user_id', $userId)
            ->whereIn('status', ['approved', 'completed'])
            ->where('start_time', '<=', now())
            ->exists();

        return $hasEligibleReservation;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'rating.required' => 'Puan vermek zorunludur.',
            'rating.min' => 'En az 1 puan verebilirsiniz.',
            'rating.max' => 'En fazla 5 puan verebilirsiniz.',
            'comment.required' => 'Yorum yazmak zorunludur.',
            'comment.min' => 'Yorum en az 10 karakter olmalıdır.',
            'comment.max' => 'Yorum en fazla 1000 karakter olabilir.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check for duplicate review
            if (!$validator->errors()->has('rating')) {
                $this->checkDuplicateReview($validator);
            }
        });
    }

    /**
     * Check if user already reviewed this business.
     */
    protected function checkDuplicateReview($validator)
    {
        $businessId = $this->route('id');
        $userId = auth()->id();

        $existingReview = \App\Models\Review::where('business_id', $businessId)
            ->where('user_id', $userId)
            ->exists();

        if ($existingReview) {
            $validator->errors()->add(
                'comment',
                'Bu işletme için daha önce yorum yaptınız.'
            );
        }
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        throw new \Illuminate\Auth\Access\AuthorizationException(
            'Bu işletmede tamamlanmış rezervasyonunuz bulunmadığı için yorum yapamazsınız.'
        );
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'rating' => 'puan',
            'comment' => 'yorum',
        ];
    }
}
