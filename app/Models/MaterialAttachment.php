<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialAttachment extends Model
{
    protected $table = 'material_attachments';

    protected $fillable = [
        'material_id', 'file_path', 'file_name', 'file_type',
        'mime_type', 'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
