<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionStoreRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->hasRole('Admin|system-admin');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:150|unique:permissions,name'
        ];
    }
}
