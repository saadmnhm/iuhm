# Form Detail Pages & Export Guide

## Overview
This document describes the detailed view pages and PDF export functionality for all form types.

## Form Detail Pages

### 1. Business Plan Detail
- **Route**: `admin.projects.show`
- **Component**: `App\Livewire\Admin\ProjectDetail`
- **View**: `resources/views/livewire/admin/formulaire/project-detail.blade.php`
- **Export Route**: `admin.projects.export.pdf`

### 2. Étude de Marché Detail
- **Route**: `admin.etude-marche.show`
- **Component**: `App\Livewire\Admin\EtudeMarcheDetail`
- **View**: `resources/views/livewire/admin/etude-marche-detail.blade.php`
- **Export Route**: `admin.etude-marche.export-pdf`

### 3. Évaluation d'Idée Detail
- **Route**: `admin.evaluation-idee.show`
- **Component**: `App\Livewire\Admin\EvaluationIdeeDetail`
- **View**: `resources/views/livewire/admin/evaluation-idee-detail.blade.php`
- **Export Route**: `admin.evaluation-idee.export-pdf`

### 4. BMC Detail
- **Route**: `admin.bmc.show`
- **Component**: `App\Livewire\Admin\BmcDetail`
- **View**: `resources/views/livewire/admin/bmc-detail.blade.php`
- **Export Route**: `admin.bmc.export-pdf`

### 5. Bilan de Compétences Detail
- **Route**: `admin.bilan-competence.show`
- **Component**: `App\Livewire\Admin\BilanCompetenceDetail`
- **View**: `resources/views/livewire/admin/bilan-competence-detail.blade.php`
- **Export Route**: `admin.bilan-competence.export-pdf`

## Features

### Detail Page Features
Each detail page includes:
- **Header Section**:
  - Form title
  - Form ID
  - Creation date
  - Form type badge (color-coded)
  - Status badge
  - Export PDF button
  - Back button

- **Candidat Information**:
  - Profile image (or initial avatar)
  - Full name
  - Email address

- **Form Content**:
  - Organized in sections
  - All form fields displayed in grid layout
  - Color-coded icons for each section
  - Responsive design (2-column on large screens)

- **Status Actions**:
  - Approve button (green)
  - Reject button (red)
  - In Review button (yellow)
  - Only shows buttons for status changes (not current status)

### PDF Export Features
- Professional PDF layout with branded colors
- Header with form title, ID, date, and status
- Candidat information section
- All form data organized by sections
- Page break management to avoid content splitting
- Automatic filename: `{form-type}-{id}-{date}.pdf`

## Color Coding

- **Business Plan**: Blue (`#3b82f6`)
- **Étude de Marché**: Green (`#10b981`)
- **Évaluation d'Idée**: Purple (`#9333ea`)
- **BMC**: Indigo (`#6366f1`)
- **Bilan de Compétences**: Yellow (`#eab308`)

## Usage Flow

1. Admin navigates to candidat submissions: `/admin/candidat/{id}/submissions`
2. Clicks on a specific form card
3. Views detailed form information
4. Can:
   - Export to PDF
   - Change status (Approve/Reject/In Review)
   - Navigate back to candidat submissions

## Export Controller

**Controller**: `App\Http\Controllers\FormExportController`

Methods:
- `exportEtudeMarche($id)` - Exports Étude de Marché
- `exportEvaluationIdee($id)` - Exports Évaluation d'Idée
- `exportBmc($id)` - Exports BMC
- `exportBilanCompetence($id)` - Exports Bilan de Compétences

All methods:
1. Load form with candidat relationship
2. Generate PDF from view template
3. Return downloadable PDF with timestamped filename

## Routes Summary

```php
// Detail Routes
Route::get('/admin/etude-marche/{id}', EtudeMarcheDetail::class)->name('admin.etude-marche.show');
Route::get('/admin/evaluation-idee/{id}', EvaluationIdeeDetail::class)->name('admin.evaluation-idee.show');
Route::get('/admin/bmc/{id}', BmcDetail::class)->name('admin.bmc.show');
Route::get('/admin/bilan-competence/{id}', BilanCompetenceDetail::class)->name('admin.bilan-competence.show');

// Export Routes
Route::get('/admin/etude-marche/{id}/export-pdf', [FormExportController::class, 'exportEtudeMarche']);
Route::get('/admin/evaluation-idee/{id}/export-pdf', [FormExportController::class, 'exportEvaluationIdee']);
Route::get('/admin/bmc/{id}/export-pdf', [FormExportController::class, 'exportBmc']);
Route::get('/admin/bilan-competence/{id}/export-pdf', [FormExportController::class, 'exportBilanCompetence']);
```

## Files Created

### Livewire Components
- `app/Livewire/Admin/EtudeMarcheDetail.php`
- `app/Livewire/Admin/EvaluationIdeeDetail.php`
- `app/Livewire/Admin/BmcDetail.php`
- `app/Livewire/Admin/BilanCompetenceDetail.php`

### Views
- `resources/views/livewire/admin/etude-marche-detail.blade.php`
- `resources/views/livewire/admin/evaluation-idee-detail.blade.php`
- `resources/views/livewire/admin/bmc-detail.blade.php`
- `resources/views/livewire/admin/bilan-competence-detail.blade.php`

### PDF Export Views
- `resources/views/livewire/admin/exports/etude-marche-pdf.blade.php`
- `resources/views/livewire/admin/exports/evaluation-idee-pdf.blade.php`
- `resources/views/livewire/admin/exports/bmc-pdf.blade.php`
- `resources/views/livewire/admin/exports/bilan-competence-pdf.blade.php`

### Controllers
- `app/Http/Controllers/FormExportController.php`

## Status Flow

1. **Draft** (gray) - Initial state
2. **Submitted** (blue) - Candidat submitted the form
3. **In Review** (yellow) - Admin is reviewing
4. **Approved** (green) - Form accepted
5. **Rejected** (red) - Form rejected

## Dependencies

- **Laravel 11**: Framework
- **Livewire 3**: Reactive components
- **DomPDF**: PDF generation (barryvdh/laravel-dompdf)
- **Tailwind CSS**: Styling
- **Remix Icons**: Icons

## Testing

To test the system:
1. Navigate to `/admin/candidat/{candidat_id}/submissions`
2. Click on any form card
3. Verify detail page displays correctly
4. Test PDF export button
5. Test status change buttons
6. Verify back button navigation

## Notes

- All routes are protected by `auth:admin` middleware
- PDF templates use DejaVu Sans font (supports UTF-8)
- Views are responsive (mobile-first design)
- Success messages shown after status updates
- Candidat relationship is eager-loaded for performance
