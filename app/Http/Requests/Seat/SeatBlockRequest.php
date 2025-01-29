<?php

namespace App\Http\Requests\Seat;

use App\Exceptions\AuthenticationException;
use App\Traits\SeatConflictTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class SeatBlockRequest extends FormRequest
{
    use SeatConflictTrait;

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
            'event_id' => 'required|exists:events,id',
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

    /**
     * Validation işlemleri sonrasında çakışma kontrolü yapar.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $conflict = $this->checkSeatConflict($this->event_id, $this->seat_id); // Koltuk çakışmasını kontrol et

            if ($conflict) { // çakışma sorgulamak için gerekli verilerde hata bulunursa
                foreach ($conflict as $key => $errorMessage) { // hata mesajlarını döngü ile ekle
                    // Zaten aynı hata varsa, tekrar eklememek için
                    if (!$validator->errors()->has($key)) {
                        $validator->errors()->add($key, $errorMessage);
                    }
                }
            }
        });
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
