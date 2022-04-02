<?php

namespace App\Http\Resources;

use App\Models\Branch;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
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
            'id'=> $this->id,
            'name'=> $this->name,
            'branch_name' => $this->branch->name,
            'branch_id' => $this->branch->id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'coaches' => CoachResource::collection($this->coaches)
        ];
    }
}
