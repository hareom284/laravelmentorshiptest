<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class TourApiRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'priceFrom' => 'integer',
            'priceTo' => 'integer',
            'dateFrom' => 'date',
            'dateTo' => 'date',
            'orderBy' => Rule::in(['price']),
            'sortBy' => Rule::in(['asc', 'desc'])
        ];
    }

    public function messages() : array
    {
        return [
            'orderBy' => "This 'orderBy' must be price only",
            'sortBy' => "This 'orderBy' must be price only 'asc' or 'desc' "
        ];
    }
}
