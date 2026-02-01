# Project Code Summary - IUHM Business Plan System

## 📋 Project Overview
A Laravel 11 + Livewire 3 project management system for managing business plan submissions from candidates with comprehensive admin controls.

---

## 🏗️ Architecture

### Technology Stack
- **Backend**: Laravel 11
- **Frontend**: Livewire 3, Alpine.js, Tailwind CSS
- **Database**: MySQL
- **PDF Generation**: DomPDF (barryvdh/laravel-dompdf)
- **Icons**: RemixIcon, FontAwesome

### Authentication System
- **Admin Authentication**: Traditional Laravel Auth (users table)
- **Candidate Authentication**: Custom guard (candidat table)
- **Guards**: `web` (admin), `candidat` (candidates)

---

## 📁 Project Structure

### Key Directories
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── AuthController.php (Admin login/logout)
│   │   │   └── ProjectExportController.php (PDF export)
│   │   └── FrontAuthController.php (Candidate auth)
│   └── Middleware/
│       ├── AdminMiddleware.php (Admin access control)
│       ├── CandidatMiddleware.php (Candidate access control)
│       └── SetLocale.php (Multilingual support)
├── Livewire/
│   ├── Admin/ (Admin components)
│   │   ├── Dashboard.php
│   │   ├── ProjectList.php
│   │   ├── ProjectDetail.php
│   │   ├── ActivityLogs.php
│   │   ├── UserManagement.php
│   │   └── RegistrationId.php
│   ├── Front/Dashboard/ (Candidate components)
│   │   ├── Dashboard.php
│   │   ├── Aside.php
│   │   ├── Navbar.php
│   │   └── Settings.php
│   ├── Concerns/ (Shared traits)
│   │   ├── ManagesTableRows.php
│   │   └── HasValidationRules.php
│   └── PublicFormWizard.php (Main form component)
└── Models/
    ├── User.php (Admin users)
    ├── Candidat.php (Candidates)
    ├── Project.php (Projects/Business Plans)
    ├── ProjectProduct.php
    ├── ProjectEmployee.php
    ├── ProjectPresentation.php
    ├── ProjectDelivery.php
    ├── ProjectEquipment.php
    ├── ProjectRawMaterial.php
    ├── ProjectFinancial.php
    └── AdminActivityLog.php (Activity tracking)

resources/
└── views/
    ├── layouts/
    │   ├── app.blade.php (Candidate layout)
    │   └── admin.blade.php (Admin layout)
    └── livewire/
        ├── admin/ (Admin views)
        └── front/
            └── steps/ (Form wizard steps)
                ├── step1.blade.php (Project Info)
                ├── step2.blade.php (Market Analysis + Products)
                ├── step3.blade.php (Marketing + Employees)
                ├── step4.blade.php (Location + Distribution)
                ├── step5.blade.php (Capacities + Investment)
                ├── step6.blade.php (Expenses + Results)
                ├── step7.blade.php (Equipment)
                ├── step8.blade.php (Raw Materials)
                └── public-form-wizard.blade.php (Main wrapper)
```

---

## 🗄️ Database Schema

### Core Tables

#### users (Admins)
- id, name, email, password, role (admin/super_admin), created_at, updated_at

#### candidat (Candidates)
- id, login, password, nom, prenom, age, gender, email, phone, address, date_naissance
- profile_image, cv_path, is_active, created_at, updated_at, deleted_at

#### projects (Business Plans)
Main project information including:
- **Basic**: id, candidat_id, project_name, description, registration, legal_structure
- **Market Analysis**: public_cible, concurrent, volume_produits_locaux, volume_demande
- **Marketing**: méthodes_marketing, adaptation_methodes, differenciation_marketing
- **Timeline**: plan_affaires, obtention_financement, ouverture_proces, etc.
- **Location**: lieu_projet, adaptation_lieu, benefices_from_projet
- **Financials**: couts_creation, preparation_entreprise, achat_machines, etc.
- **Status Management**: 
  - status (draft/submitted/in_review/approved/rejected)
  - current_step (1-8)
  - submitted_at, reviewed_by, review_notes, reviewed_at
- **Timestamps**: created_at, updated_at, deleted_at

#### Related Tables
- **project_products**: Products/services offered
- **project_employees**: Employee structure
- **project_presentations**: Presentation methods
- **project_deliveries**: Delivery methods
- **project_equipment**: Equipment needed
- **project_raw_materials**: Raw materials sources
- **project_financials**: 3-year financial projections (revenues, expenses, results)

#### admin_activity_logs
- id, user_id, action, subject_type, subject_id
- description, properties (JSON), ip_address, user_agent
- created_at, updated_at

---

## 🔐 Authentication & Authorization

### Admin Routes (`/admin/*`)
- **Middleware**: `admin`
- **Guard**: `web`
- **Access Levels**: admin, super_admin

### Candidate Routes (`/form/*`)
- **Middleware**: `candidat`
- **Guard**: `candidat`
- **Profile Check**: Handled in Aside component (shows modal if incomplete)

---

## 🎯 Key Features

### 1. Multi-Step Form Wizard (8 Steps)
- **Step 1**: Project Information & Structure
- **Step 2**: Market Analysis & Product Description
- **Step 3**: Marketing Strategy & Employee Structure
- **Step 4**: Location & Distribution Methods
- **Step 5**: Capacities & Investment Program
- **Step 6**: Financial Projections (Expenses & Results)
- **Step 7**: Equipment Requirements
- **Step 8**: Raw Materials & Suppliers

### 2. Draft System
- **Auto-save**: Saves draft when navigating between steps
- **Manual save**: "Save Draft" button available
- **Resume**: Automatically loads existing draft on return
- **Data preserved**: All form fields and dynamic tables saved

### 3. Submission Control
- **One submission per candidate**: Cannot submit multiple projects
- **Read-only after submission**: Form becomes view-only
- **Status tracking**: submitted_at timestamp recorded
- **Visual indicators**: Banners showing submission status

### 4. Project Status Workflow
```
draft → submitted → in_review → approved/rejected
```
- **draft**: Candidate working on project
- **submitted**: Candidate has submitted for review
- **in_review**: Admin actively reviewing
- **approved**: Project approved by admin
- **rejected**: Project rejected by admin

### 5. Admin Activity Logging
- **Actions tracked**:
  - Project status changes
  - Registration number additions
  - User management actions
- **Data logged**:
  - Who performed action (user_id)
  - What was done (action, description)
  - When it happened (timestamp)
  - Where from (IP address, user agent)
  - Additional details (properties JSON)

### 6. Profile Completion System
- **Required fields**: phone, address
- **Modal notification**: Shows on sidebar when incomplete
- **User control**: Can dismiss modal, no forced redirect
- **Settings access**: Modal hidden on settings page

### 7. PDF Export
- **Route**: `/admin/projects/{id}/export-pdf`
- **Format**: Comprehensive business plan document
- **Includes**: All project data, financials, and candidate info

---

## 🔧 Key Components

### PublicFormWizard.php (Main Form Component)
**Properties:**
- `$step`: Current step (1-8)
- `$projectId`: ID of draft project
- `$isReadOnly`: Read-only mode flag
- `$existingProject`: Loaded project model
- Dynamic table rows for products, employees, presentations, deliveries, equipment, materials

**Key Methods:**
- `mount()`: Loads existing project or checks for drafts
- `next()`: Navigate to next step, auto-saves draft
- `back()`: Navigate to previous step
- `saveAsDraft()`: Manual save draft function
- `submit()`: Final submission, locks form
- `loadExistingProject($id, $readOnly)`: Load project data
- `saveDraftTables($project)`: Save all related tables

### ProjectDetail.php (Admin View)
**Properties:**
- `$project`: Project being viewed
- `$showStatusModal`: Status change modal visibility
- `$newStatus`: Selected new status
- `$reviewNotes`: Admin review comments

**Key Methods:**
- `loadProject()`: Load project with all relationships
- `openStatusModal()`: Show status change modal
- `updateStatus()`: Change project status, log activity
- `saveRegistration()`: Add registration number, log activity

### ActivityLogs.php (Admin Logging)
**Properties:**
- `$search`: Search filter
- `$actionFilter`: Action type filter
- `$dateFrom`, `$dateTo`: Date range filters

**Displays:**
- User who performed action
- Action type and description
- IP address and timestamp
- Paginated results with filters

---

## 🌐 Routes

### Public Routes
```php
GET  /                          → Welcome page
GET  /user/login                → Candidate login
POST /user/login                → Process candidate login
GET  /user/register             → Candidate registration
POST /user/register             → Process registration
```

### Candidate Routes (Auth Required)
```php
GET  /form/dashboard            → Candidate dashboard
GET  /form/business-plan        → Business plan form wizard
GET  /form/settings             → Profile settings
POST /form/logout               → Logout
```

### Admin Routes (Auth Required)
```php
GET  /admin/login               → Admin login page
POST /admin/login               → Process admin login
GET  /admin/dashboard           → Admin dashboard
GET  /admin/projects            → Project list with filters
GET  /admin/projects/{id}       → Project detail view
GET  /admin/projects/{id}/export-pdf → Export project as PDF
GET  /admin/users               → User management
GET  /admin/users/create        → Create new user
GET  /admin/users/{id}          → View user
GET  /admin/users/{id}/edit     → Edit user
GET  /admin/activity-logs       → View activity logs
POST /admin/logout              → Logout
```

---

## 🎨 UI Components

### Status Badge Component
```blade
<x-status-badge :status="$project->status" />
```
**Colors:**
- draft: Gray
- submitted: Blue
- in_review: Yellow
- approved: Green
- rejected: Red

### Read-Only Form Inputs
All form inputs check `$isReadOnly` property:
```blade
<input wire:model="field" @if($isReadOnly) readonly @endif>
<textarea wire:model="field" @if($isReadOnly) readonly @endif></textarea>
<select wire:model="field" @if($isReadOnly) disabled @endif></select>
```

### Dynamic Table Rows
Hidden in read-only mode:
```blade
@if(!$isReadOnly)
<button wire:click="addTableRow">Add Row</button>
@endif
```

---

## 🔄 Data Flow

### Candidate Submission Flow
1. Candidate logs in
2. System checks for incomplete profile → shows modal if needed
3. Candidate clicks "Business Plan" → navigates to form wizard
4. System checks for existing project:
   - If submitted project exists → load in read-only mode
   - If draft exists → load draft for editing
   - If none → start new project
5. Candidate fills form step by step
6. Auto-save on navigation between steps
7. Manual save available via "Save Draft" button
8. On step 8 → "Submit" button available
9. After submission:
   - Status → 'submitted'
   - submitted_at → timestamp
   - Form → read-only mode
10. Candidate can view submission but not edit

### Admin Review Flow
1. Admin logs in to dashboard
2. Navigates to Projects list
3. Can filter by status (draft/submitted/in_review/approved/rejected)
4. Clicks on project to view details
5. Reviews all project information
6. Clicks "Change Status" button
7. Selects new status and adds review notes
8. Action is logged in activity_logs table
9. Candidate can see status and notes in their dashboard

---

## 📊 Statistics & Analytics

### Admin Dashboard Stats
- Total projects
- Projects by status (draft, submitted, in_review, approved, rejected)
- Candidates by gender (male, female)
- Recent activities

### Project List Stats
- Real-time counters
- Filter by status
- Search functionality (name, email, registration)

---

## 🌍 Multilingual Support

### Supported Languages
- French (fr) - Default
- English (en)
- Arabic (ar)

### Language Files
Located in `resources/lang/{locale}/messages.php`

### Switch Language
Route: `/lang/{locale}`
Stores in session and applies throughout app

---

## 🔒 Security Features

1. **CSRF Protection**: All forms protected
2. **Authentication Guards**: Separate for admin and candidates
3. **Middleware**: Route protection
4. **Password Hashing**: bcrypt
5. **SQL Injection Prevention**: Eloquent ORM
6. **XSS Protection**: Blade escaping
7. **Activity Logging**: Full audit trail
8. **IP Tracking**: All admin actions logged with IP
9. **Soft Deletes**: Data preservation
10. **Read-Only Mode**: Prevents tampering after submission

---

## 📈 Performance Optimizations

1. **Eager Loading**: Relationships loaded efficiently
2. **Pagination**: Lists paginated (15-20 items)
3. **Indexed Columns**: status, email, dates
4. **Livewire Lazy Loading**: Components loaded on demand
5. **Asset Bundling**: Vite for CSS/JS optimization

---

## 🐛 Error Handling

### Validation
- Step-by-step validation in form wizard
- Custom validation rules per step
- Error messages displayed inline
- French/English/Arabic translations

### Logging
- Laravel log files in `storage/logs/`
- Database query logging available
- Activity logs for admin actions

---

## 📝 Code Quality

### Standards
- PSR-12 coding standards
- Blade templating best practices
- Livewire lifecycle hooks properly used
- Component isolation and reusability

### Traits Used
- `ManagesTableRows`: Dynamic table row management
- `HasValidationRules`: Centralized validation
- `WithFileUploads`: File upload handling
- `WithPagination`: Pagination support
- `SoftDeletes`: Soft delete functionality

---

## 🚀 Deployment Checklist

1. Run migrations: `php artisan migrate`
2. Clear caches: `php artisan optimize:clear`
3. Generate app key: `php artisan key:generate`
4. Set environment variables (.env)
5. Configure database connection
6. Set up storage symlink: `php artisan storage:link`
7. Configure mail settings for notifications
8. Set up queue worker if using jobs
9. Configure PDF generation settings
10. Test all auth flows
11. Test form submission flows
12. Verify activity logging works

---

## 📚 Common Tasks

### Add New Admin
```php
User::create([
    'name' => 'Admin Name',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin', // or 'super_admin'
]);
```

### Log Admin Action
```php
AdminActivityLog::log(
    'action_name',
    'Description of action',
    Project::class,
    $project->id,
    ['key' => 'value']
);
```

### Change Project Status
```php
$project->update([
    'status' => 'approved',
    'reviewed_by' => auth()->id(),
    'reviewed_at' => now(),
    'review_notes' => 'Approved with recommendations',
]);
```

### Export Project to PDF
Navigate to: `/admin/projects/{id}/export-pdf`

---

## 🔮 Future Enhancements (Suggestions)

1. **Email Notifications**
   - Notify candidates when status changes
   - Notify admins of new submissions

2. **Document Upload**
   - Allow candidates to attach supporting documents
   - CV, business documents, financial statements

3. **Comments System**
   - Allow back-and-forth communication
   - Admin can request clarifications

4. **Dashboard Analytics**
   - Charts and graphs for statistics
   - Approval rate trends
   - Time to approval metrics

5. **Export Options**
   - Word document export
   - Excel financial data export

6. **Advanced Search**
   - Full-text search
   - Filter by multiple criteria
   - Saved search filters

7. **Bulk Operations**
   - Bulk status changes
   - Bulk export
   - Bulk notifications

---

## 📞 Support & Maintenance

### Key Files to Monitor
- `storage/logs/laravel.log` - Application errors
- `database/migrations/` - Schema changes
- `.env` - Configuration
- `routes/web.php` - Route definitions

### Regular Tasks
- [ ] Monitor activity logs for unusual patterns
- [ ] Backup database regularly
- [ ] Review and archive old projects
- [ ] Update dependencies: `composer update`
- [ ] Clear old cache files
- [ ] Monitor storage space for uploads

---

## ✅ Testing Recommendations

### Manual Testing Checklist
- [ ] Candidate registration and login
- [ ] Profile completion flow
- [ ] Form wizard navigation
- [ ] Draft save and load
- [ ] Form submission
- [ ] Read-only mode after submission
- [ ] Admin login
- [ ] Project list filters
- [ ] Status change functionality
- [ ] Activity logging
- [ ] PDF export
- [ ] Multilingual switching

### Test Users
Create test accounts for each role:
- Super Admin
- Regular Admin
- Candidate with complete profile
- Candidate with incomplete profile

---

## 📖 Documentation Files

- `IMPLEMENTATION_SUMMARY.md` - Technical implementation details
- `QUICK_REFERENCE.md` - Developer quick reference
- `PROJECT_SUMMARY.md` - This comprehensive overview

---

**Last Updated**: February 1, 2026
**Version**: 1.0
**Laravel Version**: 11.x
**Livewire Version**: 3.x
