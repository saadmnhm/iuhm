<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'page_id' => $this->page_id,
            'key' => $this->key,
            'heading' => $this->heading,
            'heading_ar' => $this->heading_ar,
            'body' => $this->body,
            'body_ar' => $this->body_ar,
            'cta_label' => $this->cta_label,
            'cta_label_ar' => $this->cta_label_ar,
            'cta_url' => $this->cta_url,
            'image_path' => $this->image_path,
            'sort_order' => $this->sort_order,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}