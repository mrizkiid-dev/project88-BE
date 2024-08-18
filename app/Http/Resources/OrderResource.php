<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'shopping_session_id' => $this->shopping_session_id,
            'name_receiver' => $this->name_receiver,
            'detail_address' => $this->detail_address,
            'province' => [
                'id' => $this->province_id,
                'name' => $this->province
            ],
            'city' => [
                'id' => $this->city_id,
                'name' => $this->city,
            ],
            'shipping' => [
                // 'provider' => $this->shipping_provider,
                'provider' => 'POST INDONESIA',
                'price' => $this->shipping_price
            ],
            'total_payment' => $this->total_payment,
            'midtrans' => [
                'id' => $this->midtrans_id,
                'token' => $this->midtrans_token,
                // 'status' => $this->midtrans_status
            ],
            'status_order' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at
        ];
    }
}
