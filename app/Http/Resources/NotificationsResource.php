<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;

class NotificationsResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
         return [
            'notifications'=>$this->collection->transform(function ($q){
                return [
                    'id' => $q->id,
                    'title'=>$q->data['title'] ?? null,
            'content' => isset($q->data['content']) ? strip_tags($q->data['content']) : null,
                    'created_at' => Carbon::parse($q->created_at)->format('Y-m-d H:i:s'),
                ];
            }),

            'paginate'=>[
                'total' => $this->total(),
                'last_page' => $this->lastPage(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'next_page_url'=>$this->nextPageUrl(),
                'prev_page_url'=>$this->previousPageUrl(),
                'current_page' => $this->currentPage(),
                'total_pages' =>  $this->collection->count() != 0 ? ceil($this->total() / $this->collection->count()):1
            ]
        ];
    }

    public function withResponse($request, $response)
    {
        $originalContent = $response->getOriginalContent();
        unset($originalContent['links'],$originalContent['meta']);
        $response->setData($originalContent);
    }
}
