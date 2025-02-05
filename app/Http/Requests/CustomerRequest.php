<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;
use Illuminate\Contracts\Validation\Validator;

class CustomerRequest extends FormRequest
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

    private function createRules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:m_users',
            'password' => 'required|string|min:6',
            'address' => 'nullable|string',
            'photo' => 'nullable|file|image',
            'phone' => 'nullable|numeric',
        ];
    }

    private function updateRules(): array
    {
        return [
            'name' => 'string|max:100',
            'address' => 'string',
            'photo' => 'file|image',
            'phone' => 'numeric',
        ];
    }

    protected function base64FileKeys(): array
    {
        return [
            'photo' => 'foto-customer.jpg',
        ];
    }
}
