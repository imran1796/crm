<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->hasRole('Admin|system-admin');
    }

    public function rules()
    {
        $id = $this->route('id');
        return [
            'name' => 'required|string|max:100|unique:roles,name,'.$id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name'
        ];
    }
}
