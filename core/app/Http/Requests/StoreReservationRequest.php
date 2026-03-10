<?php

namespace App\Http\Requests;

use App\Models\Business;
use App\Models\BusinessHour;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check() || auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_id' => 'required|exists:businesses,id',
            'location_id' => 'nullable|exists:locations,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required|date_format:H:i',
            'guest_count' => 'nullable|integer|min:1|max:50',
            'services' => 'nullable|array',
            'services.*.id' => 'required|exists:menus,id',
            'services.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:wallet,card',
            'special_requests' => 'nullable|string|max:500',
            'is_recurring' => 'nullable|boolean',
            'recurrence_pattern' => 'required_if:is_recurring,true|in:daily,weekly,monthly',
            'recurrence_end_date' => 'nullable|date|after:reservation_date',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'business_id.required' => 'İşletme seçimi zorunludur.',
            'business_id.exists' => 'Seçilen işletme bulunamadı.',
            'reservation_date.required' => 'Rezervasyon tarihi zorunludur.',
            'reservation_date.after_or_equal' => 'Rezervasyon tarihi bugünden önce olamaz.',
            'reservation_time.required' => 'Rezervasyon saati zorunludur.',
            'reservation_time.date_format' => 'Saat formatı hatalı (HH:MM).',
            'guest_count.min' => 'En az 1 misafir olmalıdır.',
            'guest_count.max' => 'Maksimum 50 misafir kabul edilir.',
            'services.array' => 'Hizmet seçimi hatalı.',
            'services.*.id.exists' => 'Seçilen hizmet bulunamadı.',
            'services.*.quantity.min' => 'Hizmet miktarı en az 1 olmalıdır.',
            'payment_method.required' => 'Ödeme yöntemi seçimi zorunludur.',
            'payment_method.in' => 'Geçersiz ödeme yöntemi.',
            'special_requests.max' => 'Özel istek en fazla 500 karakter olabilir.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if business is open on selected date/time
            if (! $validator->errors()->has('business_id') &&
                ! $validator->errors()->has('reservation_date') &&
                ! $validator->errors()->has('reservation_time')) {

                $this->validateBusinessHours($validator);
            }
        });
    }

    /**
     * Validate if business is open on selected date and time.
     */
    protected function validateBusinessHours($validator)
    {
        $businessId = $this->input('business_id');
        $date = Carbon::parse($this->input('reservation_date'));
        $time = $this->input('reservation_time');

        $dayOfWeek = $date->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

        // 1. Check for special date override first
        $businessHour = BusinessHour::where('business_id', $businessId)
            ->where('special_date', $date->format('Y-m-d'))
            ->first();

        // 2. Fallback to weekly schedule if no special date is set
        if (! $businessHour) {
            $businessHour = BusinessHour::where('business_id', $businessId)
                ->where('day_of_week', $dayOfWeek)
                ->whereNull('special_date')
                ->first();
        }

        // 3. Check if business exists for this day and is not closed
        if (! $businessHour || $businessHour->is_closed) {
            $validator->errors()->add(
                'reservation_date',
                'İşletme seçtiğiniz günde kapalıdır.'
            );

            return;
        }

        // 4. Check if requested time is within the OPEN/CLOSE range of RESERVATION settings
        $business = Business::find($businessId);
        $reservationSettings = $business->getAvailableTimeSlots()[0] ?? ['start' => '10:00', 'end' => '23:00'];
        
        $openTimeStr = $reservationSettings['start'] ?? '10:00';
        $closeTimeStr = $reservationSettings['end'] ?? '23:00';

        $requestedTime = Carbon::createFromFormat('H:i', $time);
        $openTime = Carbon::createFromFormat('H:i', $openTimeStr);
        $closeTime = Carbon::createFromFormat('H:i', $closeTimeStr);

        // Handle cross-midnight closing
        if ($closeTime->lessThan($openTime)) {
            $isValid = ($requestedTime->greaterThanOrEqualTo($openTime) || $requestedTime->lessThan($closeTime));
        } else {
            $isValid = ($requestedTime->greaterThanOrEqualTo($openTime) && $requestedTime->lessThan($closeTime));
        }

        if (! $isValid) {
            $validator->errors()->add(
                'reservation_time',
                "Seçilen saat işletmenin rezervasyon saatleri dışındadır. Rezervasyon saatleri: {$openTimeStr} - {$closeTimeStr}"
            );
        }
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'business_id' => 'işletme',
            'reservation_date' => 'rezervasyon tarihi',
            'reservation_time' => 'rezervasyon saati',
            'guest_count' => 'misafir sayısı',
            'special_requests' => 'özel istekler',
        ];
    }
}
