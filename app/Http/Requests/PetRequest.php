<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'category_id' => 'nullable|integer',
            'category_name' => 'nullable|string',
            'photo_urls' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'sometimes|string',
        ];
    }
}
