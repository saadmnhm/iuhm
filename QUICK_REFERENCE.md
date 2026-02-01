# Quick Reference Guide

## For Developers

### Adding Read-Only Attribute to Form Inputs

In all step files (step1.blade.php through step8.blade.php), add the readonly/disabled attribute:

```blade
<!-- Text inputs -->
<input type="text" wire:model="project_name" @if($isReadOnly) readonly @endif>

<!-- Textareas -->
<textarea wire:model="description" @if($isReadOnly) readonly @endif></textarea>

<!-- Select dropdowns -->
<select wire:model="legal_structure" @if($isReadOnly) disabled @endif>
    <option value="">Select...</option>
</select>

<!-- Number inputs -->
<input type="number" wire:model="couts_creation" @if($isReadOnly) readonly @endif>

<!-- Date inputs -->
<input type="date" wire:model="plan_affaires" @if($isReadOnly) readonly @endif>
```

### Displaying Project Status

```blade
<!-- In any view with access to $project -->
<x-status-badge :status="$project->status" />
```

### Logging Admin Actions

```php
use App\Models\AdminActivityLog;

// Log an action
AdminActivityLog::log(
    'action_name',              // e.g., 'project_approved'
    'Description of action',    // Human-readable description
    Project::class,             // Model class (optional)
    $project->id,              // Model ID (optional)
    [                          // Additional properties (optional)
        'old_value' => 'draft',
        'new_value' => 'approved',
    ]
);
```

### Checking Project Status in Code

```php
// In controllers or Livewire components
if ($project->isDraft()) {
    // Project is still a draft
}

if ($project->isSubmitted()) {
    // Project has been submitted
}

if ($project->canBeEdited()) {
    // Project can be edited (only drafts)
}
```

## Common Tasks

### View All Projects with Filters
Navigate to: `/admin/projects`
- Use search to find by name, registration, or candidate
- Use status filter dropdown to filter by status
- View statistics at the top

### Change Project Status
1. Go to `/admin/projects`
2. Click on a project
3. Click "Change Status" button
4. Select new status
5. Add review notes (optional)
6. Save

### View Activity Logs
Navigate to: `/admin/activity-logs`
- Search by description, action, or user
- Filter by action type
- Filter by date range

### Candidate Flow
1. Register/Login
2. Complete profile (phone + address required)
3. Start filling project form
4. Form auto-saves on step navigation
5. Can manually save draft anytime
6. Submit form when complete
7. View submission status on dashboard

## API Endpoints

### Admin Routes
- `GET /admin/projects` - List all projects
- `GET /admin/projects/{id}` - View project details
- `GET /admin/activity-logs` - View activity logs

### Candidate Routes
- `GET /form/dashboard` - Candidate dashboard
- `GET /form/business-plan` - Project form wizard
- `GET /form/settings` - Profile settings

## Database Tables

### projects
Key fields:
- `status`: draft, submitted, in_review, approved, rejected
- `current_step`: Last completed step (1-8)
- `submitted_at`: When project was submitted
- `reviewed_by`: Admin user ID who reviewed
- `review_notes`: Admin's review comments
- `reviewed_at`: When review was done

### admin_activity_logs
Key fields:
- `user_id`: Admin who performed action
- `action`: Action type (string)
- `description`: Human-readable description
- `subject_type`: Model class name
- `subject_id`: Model ID
- `properties`: JSON with additional data
- `ip_address`: IP address of admin
- `created_at`: Timestamp

## Testing Checklist

- [ ] Candidate can register and login
- [ ] Profile completion check redirects to settings
- [ ] Form wizard loads correctly
- [ ] Draft save works (manual and auto)
- [ ] Draft loads when returning
- [ ] Form submission works
- [ ] Form becomes read-only after submission
- [ ] Cannot submit second project
- [ ] Admin can view all projects
- [ ] Admin can filter by status
- [ ] Admin can change project status
- [ ] Status changes are logged
- [ ] Activity logs display correctly
- [ ] Activity log filters work

## Troubleshooting

### "Cannot access dashboard" after login
- Check if phone and address are filled in candidate profile
- Go to `/form/settings` to complete profile

### "Cannot save draft"
- Check browser console for errors
- Verify database connection
- Check `storage/logs/laravel.log` for errors

### Form shows old data
- Clear browser cache
- Run `php artisan view:clear`
- Run `php artisan cache:clear`

### Migration errors
- Check if migrations already ran: `php artisan migrate:status`
- Rollback if needed: `php artisan migrate:rollback`
- Re-run: `php artisan migrate`
