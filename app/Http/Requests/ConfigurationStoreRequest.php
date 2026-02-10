<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigurationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * This is where we can modify the incoming request data
     * before it's validated.
     */
    protected function prepareForValidation()
    {
        // Convert 'key' to lowercase and replace spaces or hyphens with underscores
        $this->merge([
            'key' => strtolower(trim($this->key)),
        ]);

        // Replace any spaces or hyphens with underscores
        $this->merge([
            'key' => preg_replace('/[\s\-]+/', '_', $this->key),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'key' => 'required|string|max:255|unique:configurations,key',
            'value' => 'required|string|max:1000',
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
