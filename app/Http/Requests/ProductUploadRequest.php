<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductUploadRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "payload" => ['required'],
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            // "payload.sku" => ['required', 'string'],
            // "payload.name" => ['required', 'string'],
            // "payload.description" => ['required', 'string'],
            // "payload.category.id" => ['required', 'integer'],
            // "payload.product_image" => ['required', 'array', 'max:5'],
            // "payload.product_image.*.image_url" => ['required', 'string'],
            // "payload.price" => ['required', 'integer'],
            // "payload.discount" => ['required', 'integer'],
            // "payload.qty" => ['required', 'integer'],
            // "payload.weight" => ['required', 'integer']
            
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->getMessageBag()
        ]));
    }

}
