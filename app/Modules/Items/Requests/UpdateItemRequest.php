<?php

namespace App\Modules\Items\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'contact_number' => ['nullable', 'string'],
            'location_text' => ['nullable', 'string'],
            'date_lost' => ['nullable', 'date'],
            'type' => ['required', 'in:lost,found'],
            'new_images.*' => ['image', 'max:5120'], // 5MB
            'removed_images' => ['array'],
            'removed_images.*' => ['integer', 'exists:item_images,id'],
            // PRIMARY
            'primary_type' => ['required', 'in:existing,new'],
            'primary_index' => ['required', 'integer'],
        ];
    }
}