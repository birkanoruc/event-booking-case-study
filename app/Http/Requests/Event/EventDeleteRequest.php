<?php

namespace App\Http\Requests\Event;

use App\Enums\UserRole;
use App\Traits\AuthorizationTrait;
use Illuminate\Foundation\Http\FormRequest;

class EventDeleteRequest extends FormRequest
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
        return [];
    }
}
