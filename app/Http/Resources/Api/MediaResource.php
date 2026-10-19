<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'category' => $this->category,
            'tags' => $this->tags,
            'file_path' => $this->file_path,
            'file_url' => $this->file_path ? asset($this->file_path) : null,
            'file_name' => $this->file_name,
            'file_size' => $this->file_size,
            'file_size_formatted' => $this->file_size_formatted,
            'file_type' => $this->file_type,
            'usage_count' => $this->usage_count,
            'is_public' => (bool) $this->is_public,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}