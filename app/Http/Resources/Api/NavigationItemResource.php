<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NavigationItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'label_ar' => $this->label_ar,
            'type' => $this->type,
            'page_id' => $this->page_id,
            'url' => $this->url,
            'parent_id' => $this->parent_id,
            'sort_order' => $this->sort_order,
            'is_active' => (bool) $this->is_active,
            'is_visible' => (bool) $this->is_visible,
            'icon' => $this->icon,
            'icon_color' => $this->icon_color,
            'settings' => $this->settings,
            'children' => self::collection($this->whenLoaded('children')),
            'page' => new PageResource($this->whenLoaded('page')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}