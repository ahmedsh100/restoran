<?php

namespace App\Http\Requests;

use App\Models\Food\Food;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Quantity cannot exceed 99.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $food = Food::find($this->route('id'));

            if (! $food) {
                throw ValidationException::withMessages([
                    'food' => 'The selected food item does not exist.',
                ]);
            }

            if (! $food->is_available) {
                throw ValidationException::withMessages([
                    'food' => 'This item is currently unavailable.',
                ]);
            }
        });
    }
}
