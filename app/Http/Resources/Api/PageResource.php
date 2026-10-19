<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'excerpt_ar' => $this->excerpt_ar,
            'content' => $this->content,
            'content_ar' => $this->content_ar,
            'hero_image' => $this->hero_image,
            'is_published' => (bool) $this->is_published,
            'published_at' => $this->published_at?->toIso8601String(),
            'sort_order' => $this->sort_order,
            'meta_title' => $this->meta_title,
            'meta_title_ar' => $this->meta_title_ar,
            'meta_description' => $this->meta_description,
            'meta_description_ar' => $this->meta_description_ar,
            'sections' => SectionResource::collection($this->whenLoaded('sections')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}