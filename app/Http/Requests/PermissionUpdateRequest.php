<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->hasRole('Admin|system-admin');
    }

    public function rules()
    {
        $id = $this->route('id');
        return [
            'name' => 'required|string|max:150|unique:permissions,name,'.$id
        ];
    }
}
