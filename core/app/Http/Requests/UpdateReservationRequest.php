<?php

namespace App\Http\Requests;

use App\Models\BusinessHour;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $reservation = Reservation::find($this->route('id'));

        return $reservation && $reservation->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required|date_format:H:i',
            'guest_count' => 'nullable|integer|min:1|max:50',
            'note' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'reservation_date.required' => 'Rezervasyon tarihi zorunludur.',
            'reservation_date.after_or_equal' => 'Rezervasyon tarihi bugünden önce olamaz.',
            'reservation_time.required' => 'Rezervasyon saati zorunludur.',
            'reservation_time.date_format' => 'Saat formatı hatalı (HH:MM).',
            'guest_count.min' => 'En az 1 misafir olmalıdır.',
            'guest_count.max' => 'Maksimum 50 misafir kabul edilir.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (! $validator->errors()->hasAny(['reservation_date', 'reservation_time'])) {
                $this->validateBusinessHours($validator);
            }
        });
    }

    /**
     * Validate if business is open on selected date and time.
     */
    protected function validateBusinessHours($validator)
    {
        $reservation = Reservation::find($this->route('id'));
        $businessId = $reservation->business_id;

        $date = Carbon::parse($this->input('reservation_date'));
        $time = $this->input('reservation_time');

        $dayOfWeek = strtolower($date->format('l'));

        $businessHour = BusinessHour::where('business_id', $businessId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (! $businessHour || ! $businessHour->is_open) {
            $validator->errors()->add('reservation_date', 'İşletme seçtiğiniz günde kapalıdır.');

            return;
        }

        $requestedTime = Carbon::parse($time);
        $openTime = Carbon::parse($businessHour->open_time);
        $closeTime = Carbon::parse($businessHour->close_time);

        if ($requestedTime->lt($openTime) || $requestedTime->gte($closeTime)) {
            $validator->errors()->add(
                'reservation_time',
                "İşletme bu saatte kapalıdır. Çalışma saatleri: {$businessHour->open_time} - {$businessHour->close_time}"
            );
        }
    }
}
