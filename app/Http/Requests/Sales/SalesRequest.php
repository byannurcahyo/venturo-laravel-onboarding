<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class SalesRequest extends FormRequest
{
    public $validator;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
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
    private function createRules(): array
    {
        return [
            'm_customer_id' => 'required|uuid|exists:m_customers,id',
            'date' => 'nullable|date',
            'product_detail' => 'required|array|min:1',
            'product_detail.*.m_product_id' => 'required|uuid|exists:m_product,id',
            'product_detail.*.m_product_detail_id' => 'nullable|uuid|exists:m_product_detail,id',
            'product_detail.*.total_item' => 'required|integer|min:1',
            'product_detail.*.price' => 'required|numeric|min:0',
        ];
    }
    private function updateRules(): array
    {
        return [
            'm_customer_id' => 'required|uuid|exists:m_customers,id',
            'date' => 'nullable|date',
            'product_detail' => 'required|array|min:1',
            'product_detail.*.m_product_id' => 'required|uuid|exists:m_product,id',
            'product_detail.*.m_product_detail_id' => 'nullable|uuid|exists:m_product_detail,id',
            'product_detail.*.total_item' => 'required|integer|min:1',
            'product_detail.*.price' => 'required|numeric|min:0'
        ];
    }
}
