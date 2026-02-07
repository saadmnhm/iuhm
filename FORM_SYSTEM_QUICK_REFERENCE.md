# Quick Reference: Using the Refactored Form System

## For Developers

### Adding a New Form Type

1. **Create the Model** with the trait:
```php
use App\Models\Traits\HasFormSubmission;

class YourNewForm extends Model
{
    use HasFactory, SoftDeletes, HasFormSubmission;
    
    protected $fillable = [
        'candidat_id',
        'form_type', // Important!
        // ... other fields
    ];
}
```

2. **Add to FormSubmissionService**:
```php
protected function getFormModels(): array
{
    return [
        // ... existing forms
        'your_new_form' => YourNewForm::class,
    ];
}
```

3. **Update the trait's form type matching**:
```php
protected function getFormType(): string
{
    return match(class_basename($this)) {
        // ... existing
        'YourNewForm' => 'your_new_form',
        default => 'unknown',
    };
}
```

4. **Create migration** to add the table with `form_type` column.

---

## Usage Examples

### In Livewire Components

#### Get All Submissions for Current User
```php
use App\Services\FormSubmissionService;

public function mount()
{
    $formService = app(FormSubmissionService::class);
    $this->submissions = $formService->getCandidatSubmissions(Auth::id());
}
```

#### Get Specific Form Type
```php
$businessPlan = $formService->getCandidatSubmissionByType(
    $candidatId, 
    'business_plan'
);
```

#### Get Statistics
```php
$stats = $formService->getCandidatStats($candidatId);
// Returns: ['total', 'drafts', 'submitted', 'approved', 'in_review', 'rejected']
```

---

## Model Methods Available

All models using `HasFormSubmission` trait have these methods:

### Relationships
```php
$submission->candidat;  // Get the candidat
$submission->reviewer;  // Get the admin who reviewed
```

### Status Checks
```php
$submission->isDraft();      // Returns true if status is 'draft'
$submission->isSubmitted();  // Returns true if submitted/in_review/approved/rejected
$submission->canBeEdited();  // Returns true if status is 'draft'
```

### Display Attributes
```php
$submission->form_type_label;        // "Business Plan"
$submission->form_type_badge_color;  // "bg-blue-100 text-blue-800"
$submission->status_badge_color;     // "blue"
$submission->status_label;           // "Soumis"
```

---

## Routes

### Admin Routes
```php
// View any form submission
route('admin.form-submissions.view', ['type' => 'business_plan', 'id' => 1])

// Dashboard (shows all submissions)
route('admin.dashboard')
```

### User Routes
```php
// Dashboard (shows user's submissions)
route('form.dashboard')

// Individual forms
route('form.business_plan')
route('form.etude_marche')
route('form.evaluation_idee')
route('form.bmc')
route('form.bilan_competences')
```

---

## Blade View Usage

### Display Form Type Badge
```blade
<span class="px-2 py-1 rounded-full {{ $submission->form_type_badge_color }}">
    {{ $submission->form_type_label }}
</span>
```

### Display Status Badge
```blade
@if($submission->status === 'draft')
    <span class="badge badge-gray">Brouillon</span>
@elseif($submission->status === 'submitted')
    <span class="badge badge-blue">Soumis</span>
@elseif($submission->status === 'approved')
    <span class="badge badge-green">Approuvé</span>
@endif

<!-- Or use the status_label attribute -->
<span>{{ $submission->status_label }}</span>
```

### Check if User Can Edit
```blade
@if($submission->canBeEdited())
    <a href="..." class="btn">Continuer</a>
@else
    <a href="..." class="btn">Voir détails</a>
@endif
```

---

## Common Queries

### Get All Submitted Forms
```php
$submitted = FormSubmissionService::getAllSubmissions('submitted');
```

### Get Recent Submissions
```php
$recent = FormSubmissionService::getRecentSubmissions(10);
```

### Get Overall Statistics
```php
$stats = FormSubmissionService::getOverallStats();
// Returns stats including 'by_form_type' breakdown
```

---

## Form Type Constants

Use these string values when working with form types:
- `'business_plan'`
- `'etude_marche'`
- `'evaluation_idee'`
- `'bmc'`
- `'bilan_competence'`

Get all available types:
```php
use App\Models\Traits\HasFormSubmission;

$types = HasFormSubmission::formTypes();
// Returns associative array: ['business_plan' => 'Business Plan', ...]
```

---

## Database Structure

All form tables should have these fields:
```
- id
- candidat_id (foreign key)
- form_type (string, for identification)
- status (enum: draft, submitted, in_review, approved, rejected)
- current_step (integer, optional)
- submitted_at (timestamp, nullable)
- reviewed_by (foreign key to users, nullable)
- review_notes (text, nullable)
- reviewed_at (timestamp, nullable)
- created_at
- updated_at
- deleted_at (for soft deletes)
```

---

## Troubleshooting

### "Form not showing in dashboard"
✅ Check if `form_type` is set correctly in the database
✅ Ensure the model is included in `FormSubmissionService::getFormModels()`
✅ Verify `candidat_id` is set

### "Button still shows 'Commencer' after submission"
✅ Check the `status` field - should be 'submitted' not 'draft'
✅ Verify `form_type` matches the form identifier
✅ Clear cache: `php artisan cache:clear`

### "Admin dashboard not showing submission"
✅ Ensure record exists in the database
✅ Check `candidat` relationship is set
✅ Verify `form_type` field is populated

---

## Testing

### Test Form Submission
```php
// In Tinker or Test
$submission = BusinessPlan::create([
    'candidat_id' => 1,
    'form_type' => 'business_plan',
    'status' => 'submitted',
    'project_name' => 'Test Project',
    // ... other required fields
]);

// Verify it appears
$service = new FormSubmissionService();
$all = $service->getAllSubmissions();
dd($all->pluck('form_type', 'id'));
```

---

## Best Practices

1. **Always set form_type** when creating form submissions
2. **Use the service** instead of direct model queries for cross-form operations
3. **Leverage the trait methods** for consistent behavior
4. **Check canBeEdited()** before allowing modifications
5. **Use status_label and form_type_label** for display instead of raw values

---

## Performance Tips

- The service caches nothing by default - consider adding caching if needed
- Use eager loading: `->with('candidat')` when querying multiple submissions
- For large datasets, paginate: the service returns Collections, convert to Builder if needed

---

## Need Help?

Check these files:
- Trait: `app/Models/Traits/HasFormSubmission.php`
- Service: `app/Services/FormSubmissionService.php`
- Dashboard (User): `app/Livewire/Front/Dashboard/Dashboard.php`
- Dashboard (Admin): `app/Livewire/Admin/Dashboard.php`
- Full Summary: `FORM_REFACTORING_SUMMARY.md`
