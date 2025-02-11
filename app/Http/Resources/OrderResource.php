<?php

namespace App\Http\Resources;

use App\Enum\OrderStatusEnum;
use App\Enum\OrderStatusNumEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_type' => $this->order_type,
            'order_for' => $this->order_for,
            'note' => $this->note,

            'order_code' => $this->order_code,
            'status' => __('status.'.$this->status),  // Translate status here
            'status_num' => $this->getStatusNum($this->status), // Get status number
            'total_price' => $this->total_price,
            'order_details' => OrderDetailResource::collection($this->orderDetails),
            'order_categories' => OrderCategoryResource::collection($this->orderCategories),
        ];
    }

    private function getStatusNum($status): int
    {
        $statusMap = [
            OrderStatusEnum::COMPLETE => OrderStatusNumEnum::COMPLETE,
            OrderStatusEnum::PENDING => OrderStatusNumEnum::PENDING,
            OrderStatusEnum::NOT_COMPLETE => OrderStatusNumEnum::NOT_COMPLETE,
            OrderStatusEnum::CONFIRMED => OrderStatusNumEnum::CONFIRMED,
        ];

        return $statusMap[$status] ?? 0; // Return 0 if status not found
    }
}
