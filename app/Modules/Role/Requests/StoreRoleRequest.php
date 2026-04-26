<?php

namespace App\Modules\Role\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\RoleStatus;

class StoreRoleRequest extends FormRequest
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
                'unique:roles,name',
            ],

            'group_id' => [
                'required',
                'exists:groups,id',
            ],

               'status' => ['required', new Enum(RoleStatus::class)],
        ];
    }
}