<?php

namespace App\Http\Requests\Seat;

use Illuminate\Foundation\Http\FormRequest;

class SeatFilterRequest extends FormRequest
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
            'section' => 'nullable|string|max:255',
            'row' => 'nullable|string|max:255',
            'number' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'sort_by' => 'nullable|string|in:section,row,number,price',
            'sort_order' => 'nullable|string|in:asc,desc',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'venue_id.exists' => 'Seçilen mekan geçerli değil.',
            'venue_id.integer' => 'Mekan ID\'si geçerli bir sayı olmalıdır.',
            'section.string' => 'Bölüm adı geçerli bir metin olmalıdır.',
            'section.max' => 'Bölüm adı en fazla 255 karakter olabilir.',
            'row.string' => 'Sıra adı geçerli bir metin olmalıdır.',
            'row.max' => 'Sıra adı en fazla 255 karakter olabilir.',
            'number.integer' => 'Koltuk numarası geçerli bir sayı olmalıdır.',
            'number.min' => 'Koltuk numarası 1 veya daha büyük bir değer olmalıdır.',
            'price.numeric' => 'Fiyat geçerli bir sayı olmalıdır.',
            'price.min' => 'Fiyat sıfırdan küçük olamaz.',
            'sort_by.string' => 'Sıralama kriteri geçerli bir metin olmalıdır.',
            'sort_by.in' => 'Geçersiz sıralama kriteri.',
            'sort_order.string' => 'Sıralama düzeni geçerli bir metin olmalıdır.',
            'sort_order.in' => 'Geçersiz sıralama düzeni. Geçerli değerler: asc, desc.',
            'page.integer' => 'Sayfa numarası geçerli bir sayı olmalıdır.',
            'page.min' => 'Sayfa numarası 1 veya daha büyük bir değer olmalıdır.',
            'per_page.integer' => 'Sayfa başına sonuç sayısı geçerli bir sayı olmalıdır.',
            'per_page.min' => 'Sayfa başına sonuç sayısı 1 veya daha büyük bir değer olmalıdır.',
            'per_page.max' => 'Sayfa başına sonuç sayısı 100\'den fazla olamaz.',
        ];
    }
}
