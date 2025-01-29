<?php

namespace App\Http\Requests\Seat;

use App\Exceptions\AuthenticationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SeatReleaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'seat_id' => 'required|exists:seats,id',
            'event_id' => 'required|exists:events,id|',
        ];
    }

    /**
     * Ek doğrulama: Kullanıcı kimliği alınıyor ve request'e ekleniyor.
     */
    protected function prepareForValidation(): void
    {
        $user = Auth::user();

        if (!$user) {
            throw new AuthenticationException("Kullanıcı kimliği bulunamadı.", 401);
        }

        $this->merge([
            'user_id' => $user->id,
        ]);
    }

    public function messages(): array
    {
        return [
            'seat_id.required' => 'Koltuk kimliği gereklidir.',
            'seat_id.exists' => 'Belirtilen koltuk bulunamadı.',
            'event_id.required' => 'Etkinlik kimliği gereklidir.',
            'event_id.exists' => 'Belirtilen etkinlik bulunamadı.',
        ];
    }
}
