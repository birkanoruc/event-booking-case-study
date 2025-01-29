<?php

namespace App\Http\Requests\Event;

use App\Enums\EventStatus;
use Illuminate\Foundation\Http\FormRequest;

class EventFilterRequest extends FormRequest
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
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'venue_id' => 'nullable|integer|exists:venues,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_by' => 'nullable|string|in:name,start_date,end_date,description',
            'sort_order' => 'nullable|string|in:asc,desc',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.before_or_equal' => 'Başlangıç tarihi, bitiş tarihinden önce olamaz.',
            'end_date.after_or_equal' => 'Bitiş tarihi, başlangıç tarihinden sonra olmalıdır.',
            'venue_id.integer' => 'Geçerli bir mekan ID\'si girilmelidir.',
            'venue_id.exists' => 'Girilen mekan ID\'si geçersiz.',
            'name.string' => 'Ad alanı geçerli bir metin olmalıdır.',
            'name.max' => 'Ad alanı 255 karakteri aşamaz.',
            'description.string' => 'Açıklama alanı geçerli bir metin olmalıdır.',
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
