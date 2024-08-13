<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductUpdateRequest extends FormRequest
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
            // "sku" => ['string'],
            // "name" => ['string'],
            // "description" => ['string'],
            // "category.id" => ['integer'],
            // "product_image" => ['array', 'max:5'],
            // "product_image.*.image_url" => ['string'],
            // "price" => ['integer'],
            // "discount" => ['integer'],
            // "qty" => ['integer'],
            // "weight" => ['integer']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->getMessageBag()
        ]));
    }

}
