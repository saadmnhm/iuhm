# Admin Submissions System - Usage Guide

## ✅ What's New

### 1. Submissions List Page
**URL:** `/admin/submissions`

Shows all candidats with their submission counts:
- Total submissions per candidat
- Quick counts by form type (color-coded badges)
- Search by name/email
- Filter by address
- Click "Voir" to view detailed submissions

### 2. Candidat Submissions Overview
**URL:** `/admin/candidat/{id}/submissions`

Beautiful card-based view showing:
- Candidat profile and contact info
- Summary statistics (5 form types)
- Individual cards for each form type:
  - ✅ **Submitted** - Shows details, status, submission date, with "Voir les détails" button
  - ❌ **Not Submitted** - Shows "Pas encore soumis" message with disabled button
- Color-coded by form type (blue/green/purple/yellow/pink)

### 3. Form Details View
**URL:** `/admin/form-submissions/{type}/{id}`

Generic view for all form types with:
- Candidat information card
- Submission status timeline
- Form-specific details in cards
- Clickthrough to detailed views

---

## 🎯 Admin Workflow

### Step 1: Access Submissions List
```
Navigate to: Admin Dashboard → Submissions
or directly: /admin/submissions
```

### Step 2: Find a Candidat
- Use search bar to find by name/email
- Filter by address if needed
- See submission counts at a glance

### Step 3: View Candidat's Forms
- Click "Voir" button next to candidat
- See all 5 form types in card layout
- Green checkmark ✓ = submitted
- Red X ✗ = not submitted

### Step 4: View Form Details
- Click "Voir les détails" on any submitted form
- Business Plans redirect to full project view
- Other forms show generic view with all data

---

## 📊 Form Types & Colors

| Form Type | Color | Icon |
|-----------|-------|------|
| Business Plan | Blue | 📊 |
| Étude de Marché | Green | 🔍 |
| Évaluation d'Idée | Purple | 💡 |
| Business Model Canvas | Yellow | 📐 |
| Bilan de Compétences | Pink | ⭐ |

---

## 🔗 Routes Reference

```php
// List all candidats with submission counts
Route: admin.submissions
URL: /admin/submissions

// View specific candidat's forms
Route: admin.candidat.submissions  
URL: /admin/candidat/{id}/submissions

// View form details
Route: admin.form-submissions.view
URL: /admin/form-submissions/{type}/{id}

// Business Plan detail (redirected)
Route: admin.projects.show
URL: /admin/projects/{id}
```

---

## 🧪 Testing Command

### Generate Test Business Plan

```bash
# Create test data for first candidat
php artisan test:business-plan

# Create for specific candidat
php artisan test:business-plan 5
```

**Note:** The command creates a complete business plan but may have issues with related tables due to database schema. The main business plan record is created successfully.

**What it creates:**
- ✅ Complete Business Plan with all fields filled
- ✅ Project details, market analysis, timeline
- ✅ Investment program and financial data
- ⚠️ Related tables (products, employees, equipment) - may need manual adjustment

---

## 💡 Tips for Admins

1. **Quick Search**: Type candidat name in submissions list
2. **Visual Indicators**: Look for colored badges to see what's submitted
3. **Status Colors**:
   - Gray = Draft
   - Blue = Submitted
   - Yellow = In Review
   - Green = Approved
   - Red = Rejected

4. **Not Submitted Forms**: These appear as grayed-out cards with "Non disponible" button

---

## 🎨 Card UI Features

### Submitted Cards
- Form icon with color gradient
- Status badge (top-right)
- Key details (ID, project name, dates)
- Active "Voir les détails" button
- Review notes (if any) in yellow footer

### Not Submitted Cards
- Grayed-out icon
- "Pas encore soumis" message
- File-add icon
- Disabled button

---

## 🔄 Integration with Existing System

- ✅ Uses same `FormSubmissionService` as dashboard
- ✅ Same authentication and authorization
- ✅ Connects with existing admin layout
- ✅ Links to existing BusinessPlan detail view
- ✅ Uses Livewire for reactivity

---

## 📝 Future Enhancements

Possible improvements:
1. Bulk status updates
2. Export candidat submissions to PDF
3. Filter by submission status
4. Date range filters
5. Detailed views for non-BP forms
6. Comments/notes on each form
7. Email notifications to candidats

---

## 🐛 Troubleshooting

### "No candidats found"
- Ensure candidats exist in database
- Check search filters are not too restrictive

### "Submission not showing"
- Verify `form_type` field is set correctly
- Check `candidat_id` relationship
- Run: `php artisan migrate` to ensure schema is up-to-date

### Card not displaying correctly
- Clear browser cache
- Check Tailwind CSS is compiled
- Verify Remix Icon CSS is loaded

---

## 📚 Related Documentation

- [FORM_REFACTORING_SUMMARY.md](FORM_REFACTORING_SUMMARY.md) - Complete refactoring details
- [FORM_SYSTEM_QUICK_REFERENCE.md](FORM_SYSTEM_QUICK_REFERENCE.md) - Developer guide
- Admin Dashboard - Main dashboard with recent submissions

---

**Last Updated:** February 6, 2026
**Version:** 2.0
