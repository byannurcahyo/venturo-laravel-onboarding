<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;


class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    use ConvertsBase64ToFiles;

    public $validator;
    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return $this->createRules();
        }
        return $this->updateRules();
    }

    protected function base64FileKeys(): array
    {
        return [
            'photo' => 'foto-product.jpg',
        ];
    }

    private function createRules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'price' => 'required|numeric',
            'photo' => 'nullable|file|image',
            'description' => 'required|string',
            'is_available' => 'required|numeric|max:1',
            'm_product_category_id' => 'required|exists:m_product_categories,id',
            'detail' => 'required|array|min:1',
            'detail.*.type' => 'required',
            'detail.*.description' => 'required',
            'detail.*.price' => 'required|numeric',
        ];
    }

    private function updateRules(): array
    {
        return [
            'name' => 'nullable|string|max:150',
            'price' => 'nullable|numeric',
            'photo' => 'nullable|file|image',
            'is_available' => 'nullable|numeric|max:1',
            'm_product_category_id' => 'nullable|exists:m_product_categories,id',
            'detail' => 'nullable|array|min:1',
            'detail.*.type' => 'nullable',
            'detail.*.description' => 'nullable',
            'detail.*.price' => 'nullable|numeric',
        ];
    }
}
