<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Product::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'productName' => ['required'],
            'cost' => ['required', 'numeric', 'gt:0', fn($attribute, $value, $fail) => $value % 5 === 0 ?: $fail("Product cost must be a multiple of 5, $value received")],
            'amountAvailable' => ['required' , 'numeric', 'gt:0']
        ];
    }
}
