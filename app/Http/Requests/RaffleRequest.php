<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RaffleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title'=>'required|min:10',
            'description'=>'required|min:20',
            'value_point'=>'required',
            'number_points'=>'required',
            'format'=>'required'
        ];
    }
}
