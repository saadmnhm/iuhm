<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'addresses';
    
    protected $fillable = [
        'address_line1',
        'city',
        'state',
        'postal_code',
    ];


    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address_line1,
            $this->city,
            $this->state,
            $this->postal_code,
        ])->filter()->implode(', ');
    }
}
