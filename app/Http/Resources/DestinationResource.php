<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestinationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lodging' => $this->lodging,
            'things_to_do' => $this->things_to_do,
             // 'order' => $this->order, // Si utilisé
        ];
    }
}
