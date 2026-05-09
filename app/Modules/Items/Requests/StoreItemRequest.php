<?php

namespace App\Modules\Items\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:lost,found'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'contact_number' => ['nullable', 'string'],
            'date_lost' => ['nullable', 'date'],
            'location_text' => ['required', 'string'],
       
        ];
    }
}   