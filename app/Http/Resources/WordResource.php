<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WordResource extends JsonResource
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
        //    'id' => $this->id,
            'word' => $this->word,
      //    'translate' => $this->translate,
    //      'tags' => TagResource::collection($this->tags)
        ];
    }
}
