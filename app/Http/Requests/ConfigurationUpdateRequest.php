<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigurationUpdateRequest extends FormRequest
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
        $id = $this->route('configuration');
        return [
            'key' => 'required|string|max:255|unique:configurations,key,' . $id,
            'value' => 'nullable|max:1000',
        ];
    }
}
