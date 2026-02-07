<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;

trait TracksUserActivity
{
    /**
     * Update tracking information on login
     */
    public function updateTrackingInfo()
    {
        $agent = new Agent();
        $agent->setUserAgent(Request::header('User-Agent'));

        $this->update([
            'last_ip_address' => Request::ip(),
            'last_user_agent' => Request::header('User-Agent'),
            'last_browser' => $agent->browser() . ' ' . $agent->version($agent->browser()),
            'last_platform' => $agent->platform() . ' ' . $agent->version($agent->platform()),
            'last_device' => $this->getDeviceType($agent),
            'last_login_at' => now(),
            'login_count' => $this->login_count + 1,
        ]);
    }

    /**
     * Get device type
     */
    protected function getDeviceType($agent): string
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

    /**
     * Get current IP address
     */
    public static function getCurrentIp(): string
    {
        return Request::ip();
    }

    /**
     * Get current browser info
     */
    public static function getCurrentBrowserInfo(): array
    {
        $agent = new Agent();
        $agent->setUserAgent(Request::header('User-Agent'));

        return [
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
            'browser' => $agent->browser() . ' ' . $agent->version($agent->browser()),
            'platform' => $agent->platform() . ' ' . $agent->version($agent->platform()),
            'device' => self::getDeviceTypeStatic($agent),
        ];
    }

    /**
     * Static method to get device type
     */
    protected static function getDeviceTypeStatic($agent): string
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
