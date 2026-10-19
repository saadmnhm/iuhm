<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'short_description' => $this->short_description,
            'short_description_ar' => $this->short_description_ar,
            'slug' => $this->slug,
            'featured_image' => $this->featured_image,
            'gallery' => $this->gallery ?? [],
            'start_date' => $this->start_date?->toIso8601String(),
            'end_date' => $this->end_date?->toIso8601String(),
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'status_label_ar' => $this->getStatusLabelAr(),
            'location' => $this->location,
            'partner' => $this->partner,
            'tags' => $this->tags ?? [],
            'external_link' => $this->external_link,
            'sort_order' => $this->sort_order,
            'is_published' => (bool) $this->is_published,
            'is_featured' => (bool) $this->is_featured,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}