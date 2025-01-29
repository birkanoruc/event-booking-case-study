<?php

namespace App\Http\Requests\Event;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\AuthorizationTrait;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Event;

class EventStoreOrUpdateRequest extends FormRequest
{
    use AuthorizationTrait; // Trait'i dahil ediyoruz

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->authorizeRole(UserRole::ADMIN);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:now',
            'end_date' => 'required|date|after:start_date',
            'venue_id' => 'required|exists:venues,id',
        ];
    }

    /**
     * Custom validation after rules are applied.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateEventConflict($validator);
        });
    }

    /**
     * Çakışan etkinlikleri kontrol eder ve hata mesajı ekler.
     */
    protected function validateEventConflict($validator): void
    {
        $conflictingEvent = Event::where('venue_id', $this->venue_id)
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                    ->orWhereBetween('end_date', [$this->start_date, $this->end_date]);
            });


        if ($this->isMethod('put') || $this->isMethod('patch')) { // Eğer güncelleme yapılıyorsa, mevcut etkinliği göz ardı et

            $conflictingEvent = $conflictingEvent->where('id', '!=', $this->id); // Güncellenen etkinliği hariç tut
        }

        if ($conflictingEvent->exists()) {
            $validator->errors()->add('start_date', 'Etkinlik zaman diliminde çakışma var. Lütfen farklı bir tarih aralığı seçin.');
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Etkinlik adı gereklidir.',
            'name.string' => 'Etkinlik adı metin formatında olmalıdır.',
            'name.max' => 'Etkinlik adı en fazla 255 karakter olabilir.',
            'venue_id.required' => 'Mekan ID’si gereklidir.',
            'venue_id.exists' => 'Belirtilen mekan bulunamadı.',
            'start_date.required' => 'Başlangıç tarihi gereklidir.',
            'start_date.date' => 'Başlangıç tarihi geçerli bir tarih formatında olmalıdır.',
            'start_date.after_or_equal' => 'Başlangıç tarihi bugünden önce olamaz.',
            'end_date.required' => 'Bitiş tarihi gereklidir.',
            'end_date.date' => 'Bitiş tarihi geçerli bir tarih formatında olmalıdır.',
            'end_date.after' => 'Bitiş tarihi, başlangıç tarihinden sonra olmalıdır.',
        ];
    }
}
