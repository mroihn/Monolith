<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilmResource extends JsonResource
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
            'director' => $this-> director,
            'duration' => $this->duration,
            'price' => $this->price,
            'release_year' => $this->release_year,
            'title' => $this->title,
            'genre' => explode(',', $this->genre),
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'cover_image_url' => $this-> cover_image_url,
        ];
    }
}
