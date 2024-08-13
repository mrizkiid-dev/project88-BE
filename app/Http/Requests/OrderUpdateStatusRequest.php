<?php

namespace App\Http\Requests;

use App\Enums\StatusOrderEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class OrderUpdateStatusRequest extends FormRequest
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
            'status_order' => 'required',
            'status_order.status' => [
                'required', new Enum(StatusOrderEnum::class)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'status_order.status.Illuminate\Validation\Rules\Enum' => 'Status should be "pending, paid, send, need_confirmation, cancel" and case-sensitive.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->getMessageBag()
            ],400)
        );
    }

    // nested array errors
    // protected function failedValidation(Validator $validator)
    // {
    //     $errors = $validator->errors()->toArray();
    //     $formattedErrors = [];

    //     foreach ($errors as $key => $messages) {
    //         $keys = explode('.', $key);
    //         $temp = &$formattedErrors;

    //         foreach ($keys as $innerKey) {
    //             if (!isset($temp[$innerKey])) {
    //                 $temp[$innerKey] = [];
    //             }
    //             $temp = &$temp[$innerKey];
    //         }

    //         $temp = $messages;
    //     }

    //     throw new HttpResponseException(response()->json([
    //         'message' => 'Validation failed',
    //         'errors' => $errors,
    //     ], 422));
    // }

}
