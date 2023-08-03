<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

/**
 * Class PlaylistCreateRequest
 * @package App\Http\Requests
 */
class PlaylistCreateRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(Request $request): array
    {
        return [
            'name' => 'required|string|min:1|max:64',
            'typeId' => 'required|exists:playlist_types,id',
            'selectedFolder'=> 'nullable|numeric',
            'selectedList'=> 'nullable|numeric',
            'filterItems.*.field'=> 'nullable|exists:filter_types,name',
            'filterItems.*.value'=> 'nullable|string|max:64'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(): array
    {
        return [
        ];
    }
}
