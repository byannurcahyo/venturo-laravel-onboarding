<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;
use Illuminate\Contracts\Validation\Validator;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function authorize(): bool
    {
        return true;
    }

    use ConvertsBase64ToFiles;

    public $validator;
    /**
    * Setting custom attribute pesan error yang ditampilkan
    *
    * @return array
    */

    public function attributes()
    {
        return [
            'password' => 'Kolom Password',
        ];
    }

    /**
    * Tampilkan pesan error ketika validasi gagal
    *
    * @return void
    */
    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return $this->createRules();
        }
        return $this->updateRules();
    }

    private function createRules(): array {
        return [
            'name' => 'required|string|max:100',
            'photo' => 'nullable|file|image',
            'email' => 'required|email|unique:m_users',
            'password' => 'required|string|min:6',
            'phone_number' => 'numeric',
            'm_user_roles_id' => 'required'
        ];
    }

    private function updateRules(): array {
        return [
            'name' => 'nullable|string|max:100',
            'photo' => 'nullable|file|image',
            'email' => 'nullable|email|unique:m_users,email,' . $this->id,
            'password' => 'nullable|string|min:6',
            'phone_number' => 'numeric',
            'm_user_roles_id' => 'nullable|exists:m_user_roles,id'
        ];
    }

    /**
    * inisialisasi key "photo" dengan value base64 sebagai "FILE"
    *
    * @return array
    */
    protected function base64FileKeys(): array
    {
        return [
            'photo' => 'foto-user.jpg'
        ];
    }
}
