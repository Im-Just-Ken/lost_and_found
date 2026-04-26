<?php

namespace App\Modules\Permission\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\PermissionStatus;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:permissions,name',
            ],

            'group_id' => [
                'required',
                'exists:groups,id',
            ],

               'status' => ['required', new Enum(PermissionStatus::class)],
        ];
    }
}