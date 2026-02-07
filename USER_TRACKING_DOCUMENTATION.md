# User Tracking System Documentation

## Overview
The system now tracks IP address, browser, device, and login information for both admin users and candidates (candidats).

## Database Changes

### Tables Updated
1. **users table** - Admin users tracking
2. **candidat table** - Candidate users tracking

### New Columns Added (both tables)
- `last_ip_address` (varchar 45) - Last IP address used to login
- `last_user_agent` (text) - Full user agent string
- `last_browser` (varchar) - Browser name and version (e.g., "Chrome 120.0")
- `last_platform` (varchar) - Operating system and version (e.g., "Windows 10")
- `last_device` (varchar) - Device type (Desktop/Mobile/Tablet)
- `last_login_at` (timestamp) - Last successful login timestamp
- `login_count` (integer) - Total number of successful logins

## Model Updates

### User Model (`app/Models/User.php`)
- Added `TracksUserActivity` trait
- Added tracking fields to `$fillable` array
- Added `last_login_at` and `login_count` to casts

### Candidat Model (`app/Models/Candidat.php`)
- Added `TracksUserActivity` trait
- Added tracking fields to `$fillable` array
- Added `last_login_at` and `login_count` to casts

## New Trait: TracksUserActivity

Location: `app/Models/Traits/TracksUserActivity.php`

### Methods:
- `updateTrackingInfo()` - Updates all tracking information on login
- `getCurrentIp()` - Gets current user's IP address (static)
- `getCurrentBrowserInfo()` - Gets complete browser info array (static)

## Controller Updates

### Admin AuthController (`app/Http/Controllers/Admin/AuthController.php`)
- Added `$user->updateTrackingInfo()` call on successful login

### Front AuthController (`app/Http/Controllers/FrontAuthController.php`)
- Added `$candidat->updateTrackingInfo()` call on successful login
- Added tracking info update on new user registration

## AdminActivityLog Enhancement

Location: `app/Models/AdminActivityLog.php`

The `log()` method now automatically includes:
- IP address
- User agent
- Browser info (name + version)
- Platform info (OS + version)
- Device type

These are stored in the `properties` JSON field along with custom properties.

## Usage Examples

### Accessing Tracking Information

```php
// For admin user
$user = Auth::user();
echo "Last login: " . $user->last_login_at;
echo "IP: " . $user->last_ip_address;
echo "Browser: " . $user->last_browser;
echo "Platform: " . $user->last_platform;
echo "Device: " . $user->last_device;
echo "Total logins: " . $user->login_count;

// For candidat
$candidat = Auth::guard('candidat')->user();
echo "Last login: " . $candidat->last_login_at;
echo "IP: " . $candidat->last_ip_address;
// ... etc
```

### Getting Current Browser Info (Static Method)

```php
use App\Models\User;

$browserInfo = User::getCurrentBrowserInfo();
// Returns:
// [
//     'ip_address' => '192.168.1.1',
//     'user_agent' => 'Mozilla/5.0...',
//     'browser' => 'Chrome 120.0',
//     'platform' => 'Windows 10',
//     'device' => 'Desktop'
// ]
```

### Logging Admin Activity with Enhanced Tracking

```php
use App\Models\AdminActivityLog;

AdminActivityLog::log(
    'user.updated',
    'Updated user profile',
    User::class,
    $userId,
    ['field' => 'email', 'old_value' => 'old@email.com', 'new_value' => 'new@email.com']
);
// Automatically includes browser, platform, device info in properties
```

## Displaying Tracking Information in Views

### Example Blade Template

```blade
<div class="user-tracking-info">
    <h3>Login Information</h3>
    <table>
        <tr>
            <td>Last Login:</td>
            <td>{{ $user->last_login_at?->format('Y-m-d H:i:s') ?? 'Never' }}</td>
        </tr>
        <tr>
            <td>IP Address:</td>
            <td>{{ $user->last_ip_address ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Browser:</td>
            <td>{{ $user->last_browser ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Platform:</td>
            <td>{{ $user->last_platform ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Device:</td>
            <td>{{ $user->last_device ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Total Logins:</td>
            <td>{{ $user->login_count ?? 0 }}</td>
        </tr>
    </table>
</div>
```

## Security Considerations

1. **Privacy**: IP addresses and browser information are personal data. Ensure compliance with privacy regulations (GDPR, etc.)
2. **Storage**: This data is stored in plaintext. Consider encryption for sensitive environments
3. **Access Control**: Only authorized admin users should view tracking information
4. **Retention**: Consider implementing data retention policies to delete old tracking data

## Dependencies

- `jenssegers/agent` (^2.6) - For parsing user agent strings and detecting browsers, platforms, and devices

## Migration Files

1. `2026_02_07_133403_add_tracking_info_to_users_table.php`
2. `2026_02_07_133414_add_tracking_info_to_candidat_table.php`

## Rollback

To rollback the tracking system:

```bash
php artisan migrate:rollback --step=2
```

This will remove the tracking columns from both tables.
