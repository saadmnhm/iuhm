<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;

class AdminActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject model
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Log an admin activity with enhanced tracking
     */
    public static function log($action, $description, $subjectType = null, $subjectId = null, $properties = [])
    {
        $agent = new Agent();
        $agent->setUserAgent(request()->userAgent());
        
        // Enhance properties with browser and device info
        $properties = array_merge($properties, [
            'browser' => $agent->browser() . ' ' . $agent->version($agent->browser()),
            'platform' => $agent->platform() . ' ' . $agent->version($agent->platform()),
            'device' => static::getDeviceType($agent),
        ]);

        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get device type from agent
     */
    protected static function getDeviceType($agent): string
    {
        if ($agent->isDesktop()) {
            return 'Desktop';
        } elseif ($agent->isMobile()) {
            return 'Mobile - ' . $agent->device();
        } elseif ($agent->isTablet()) {
            return 'Tablet - ' . $agent->device();
        } elseif ($agent->isRobot()) {
            return 'Robot - ' . $agent->robot();
        }
        return 'Unknown';
    }
}
