<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class ReservationFilterRequest extends FormRequest
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
            'sort_by' => 'nullable|string|in:name,start_date,end_date,description',
            'sort_order' => 'nullable|string|in:asc,desc',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'event_id' => 'nullable|integer|exists:events,id',
            'total_amount' => 'nullable|numeric|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.integer' => 'Geçerli bir etkinlik ID\'si girilmelidir.',
            'event_id.exists' => 'Girilen etkinlik ID\'si geçersiz.',
            'total_amount.numeric' => 'Toplam tutar sayısal bir değer olmalıdır.',
            'total_amount.min' => 'Toplam tutar negatif olamaz.',
            'sort_by.in' => 'Geçersiz sıralama alanı seçimi.',
            'sort_order.in' => 'Sıralama türü yalnızca "asc" veya "desc" olabilir.',
            'page.integer' => 'Sayfa numarası geçerli bir tamsayı olmalıdır.',
            'page.min' => 'Sayfa numarası 1\'den küçük olamaz.',
            'per_page.integer' => 'Her sayfada gösterilecek öğe sayısı geçerli bir tamsayı olmalıdır.',
            'per_page.min' => 'Her sayfada gösterilecek öğe sayısı en az 1 olmalıdır.',
            'per_page.max' => 'Her sayfada gösterilecek öğe sayısı en fazla 100 olabilir.',
        ];
    }
}
