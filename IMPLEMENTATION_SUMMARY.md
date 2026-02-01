# Project Updates Summary

## Features Implemented

### 1. **Save as Draft Functionality**
- Candidates can now save their project form as a draft and return to complete it later
- Auto-save occurs when moving between steps
- Manual save button can be added to the view with `wire:click="saveAsDraft"`
- Draft projects are automatically loaded when the candidate returns

### 2. **Profile Completion Check (Global Middleware)**
- Created `CheckCandidatProfileCompletion` middleware
- Prevents access to any routes (except settings) until profile is complete
- Checks for `phone` and `address` fields
- Automatically redirects incomplete profiles to settings page
- Applied to all candidat routes except settings

### 3. **Form Submission Control**
- Candidates can only submit ONE form
- Once submitted, the form becomes read-only
- Cannot edit or resubmit after initial submission
- Form displays in view-only mode for submitted projects
- Status automatically changes to 'submitted' with timestamp

### 4. **Project Status System**
Available statuses:
- **draft**: Work in progress
- **submitted**: Candidate has submitted the form
- **in_review**: Admin is reviewing the project
- **approved**: Project approved by admin
- **rejected**: Project rejected by admin

### 5. **Admin Activity Logging**
- All admin actions are logged in `admin_activity_logs` table
- Logs include:
  - User who performed the action
  - Action type
  - Description
  - Subject (model type and ID)
  - Additional properties (old/new values)
  - IP address and user agent
  - Timestamp
- New route: `/admin/activity-logs` to view all logs

### 6. **Admin Project Management**
Enhanced `ProjectDetail` component:
- View project status
- Change project status with review notes
- Track who reviewed the project and when
- All status changes are logged

Enhanced `ProjectList` component:
- Filter projects by status
- View statistics by status (draft, submitted, in_review, approved, rejected)
- Search by project name, registration, or candidate info

## Database Changes

### New Migrations Created:
1. `2026_02_01_000001_update_projects_table_add_status_fields.php`
   - Updates status enum to include: draft, submitted, in_review, approved, rejected
   - Adds `submitted_at` timestamp
   - Adds `reviewed_by` foreign key to users table
   - Adds `review_notes` text field
   - Adds `reviewed_at` timestamp

2. `2026_02_01_000002_create_admin_activity_logs_table.php`
   - Creates activity logging table for admin actions

### New Model:
- `AdminActivityLog` - Handles activity logging with helper method `::log()`

## Files Modified:

1. **app/Livewire/PublicFormWizard.php**
   - Added `$projectId`, `$existingProject`, `$isReadOnly` properties
   - Added `mount()` logic to check for existing projects
   - Added `saveAsDraft()` method
   - Added `saveDraftTables()` method
   - Added `loadExistingProject()` method
   - Updated `submit()` to prevent re-submission and set submitted_at
   - Auto-save on next/previous step navigation

2. **app/Livewire/Front/Dashboard/Dashboard.php**
   - Removed local `checkProfileCompletion()` (now handled by middleware)
   - Added `$project` property to display candidate's project status

3. **app/Livewire/Admin/ProjectDetail.php**
   - Added status update functionality
   - Added activity logging for registration and status changes
   - Added `updateStatus()` method
   - Added modal support for status changes

4. **app/Livewire/Admin/ProjectList.php**
   - Added `$statusFilter` property
   - Enhanced statistics to include all statuses
   - Updated query to support status filtering
   - Fixed relationships (candidat instead of user)

5. **app/Models/Project.php**
   - Added new fillable fields: submitted_at, reviewed_by, review_notes, reviewed_at
   - Added casts for datetime fields
   - Added `reviewer()` relationship
   - Added `activityLogs()` morphMany relationship
   - Added helper methods: `isDraft()`, `isSubmitted()`, `canBeEdited()`

6. **app/Http/Middleware/CheckCandidatProfileCompletion.php**
   - New middleware for profile completion check

7. **bootstrap/app.php**
   - Registered 'profile.complete' middleware alias

8. **routes/web.php**
   - Added activity logs route for admin
   - Applied 'profile.complete' middleware to candidat routes
   - Excluded settings route from profile.complete middleware

## New Components Created:

1. **app/Livewire/Admin/ActivityLogs.php**
   - Lists all admin activity logs
   - Filters by action, date range, and search
   - Pagination support

2. **resources/views/livewire/admin/activity-logs.blade.php**
   - View for activity logs with filters and table

## Installation Steps:

1. **Run migrations:**
```bash
php artisan migrate
```

2. **Clear cache:**
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

3. **Verify the following in your view files:**
   - Add status badge display where needed using: `<x-status-badge :status="$project->status" />`
   - Add `@if($isReadOnly) disabled @endif` or `readonly` attribute to all form inputs in step1.blade.php through step8.blade.php
   - Example: `<input type="text" wire:model="project_name" @if($isReadOnly) readonly @endif>`
   - For select elements: `<select wire:model="status" @if($isReadOnly) disabled @endif>`
   - For textareas: `<textarea wire:model="description" @if($isReadOnly) readonly @endif></textarea>`

4. **UI Updates Made:**
   - Added status banners in form wizard (read-only mode, draft mode)
   - Added "Save Draft" button in navigation
   - Added flash messages for success/error
   - Disabled navigation buttons in read-only mode

## Usage Instructions:

### For Candidates:
1. Complete profile (phone and address required)
2. Start filling project form
3. Form auto-saves when moving between steps
4. Can manually save as draft and return later
5. Submit form when complete (can only submit once)
6. After submission, form is view-only

### For Admins:
1. View all projects at `/admin/projects`
2. Filter by status using dropdown
3. Click on project to view details
4. Change project status in project detail page
5. Add review notes when changing status
6. View all activity logs at `/admin/activity-logs`
7. Filter logs by action type, date range, or search

## API/Methods Available:

### PublicFormWizard Component:
- `saveAsDraft()` - Save current progress as draft
- `loadExistingProject($id, $readOnly)` - Load existing project
- `submit()` - Final submission (locks the form)

### AdminActivityLog Model:
- `AdminActivityLog::log($action, $description, $subjectType, $subjectId, $properties)` - Log admin action

### Project Model Methods:
- `$project->isDraft()` - Check if project is draft
- `$project->isSubmitted()` - Check if project is submitted
- `$project->canBeEdited()` - Check if project can be edited

## Security Features:
- Profile completion middleware prevents access without complete profile
- Read-only mode after submission prevents tampering
- Only one project submission per candidate
- All admin actions are logged with IP and user agent
- Status changes tracked with reviewer and timestamp

## Testing:
1. Test draft save/load functionality
2. Test profile completion redirect
3. Test form submission lock
4. Test admin status changes
5. Test activity logging
6. Test filters in project list and activity logs
