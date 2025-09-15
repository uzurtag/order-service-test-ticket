<?php

namespace App\Order\Presentation\Request;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OrdersLatestRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'limit' => ['sometimes','integer','min:1','max:100']
        ];
    }

    public function limit(): int
    {
        return (int) ($this->input('limit', 10));
    }
}
