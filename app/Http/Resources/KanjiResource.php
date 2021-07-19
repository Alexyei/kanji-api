<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KanjiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
           // 'req' => $request,
            'id' => $this->id,
            'word' => $this->word,
            'translate' => explode(", ",$this->translate)[0],
            'tags' => TagResource::collection($this->tags)
        ];
    }
}
