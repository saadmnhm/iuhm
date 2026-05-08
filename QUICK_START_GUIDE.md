# Quick Start Guide - Content Management System

## ⚡ Get Started in 5 Minutes

### Step 1: Run Migrations (1 minute)
```bash
php artisan migrate
```

This creates 3 new database tables:
- `news` - For news articles
- `deliverables` - For files/resources
- `newsletters` - For email newsletters

### Step 2: Clear Cache (30 seconds)
```bash
php artisan cache:clear
php artisan route:cache
```

### Step 3: Start Using Admin Panel (Instant)

Visit these URLs in your admin panel:

| Module | URL | Features |
|--------|-----|----------|
| **News Management** | `/admin/news` | Create, edit, publish news articles |
| **Deliverables** | `/admin/deliverables` | Manage files and resources |
| **Newsletters** | `/admin/newsletters` | Create and track emails |

---

## 🎯 Features at a Glance

### Each Module Includes:

✅ **Full CRUD Operations** - Create, Read, Update, Delete  
✅ **Bilingual Support** - English & Arabic fields  
✅ **Rich Modals** - Beautiful forms matching design images  
✅ **Search & Filter** - Find content quickly  
✅ **Status Management** - Draft/Published/Completed states  
✅ **Statistics Dashboard** - View totals and metrics  
✅ **File Management** - Upload and track files  
✅ **Activity Logging** - All changes recorded  

---

## 🚀 API Usage

### Base URL
```
https://your-domain/api/v1
```

### Quick Examples

**Get Latest News:**
```bash
curl https://your-domain/api/v1/news/latest?limit=5
```

**Search Deliverables:**
```bash
curl https://your-domain/api/v1/deliverables/search?q=report
```

**Get Newsletter Stats:**
```bash
curl https://your-domain/api/v1/newsletters/stats
```

### Use in Your Code

**JavaScript:**
```javascript
fetch('https://your-domain/api/v1/news/latest?limit=5')
  .then(res => res.json())
  .then(data => console.log(data.data));
```

**React:**
```jsx
const [news, setNews] = useState([]);

useEffect(() => {
  fetch('https://your-domain/api/v1/news/latest?limit=5')
    .then(res => res.json())
    .then(data => setNews(data.data));
}, []);
```

**PHP:**
```php
$json = file_get_contents('https://your-domain/api/v1/news/latest?limit=5');
$data = json_decode($json, true);
```

---

## 📋 Available API Endpoints

### News
- `GET /api/v1/news` - List all
- `GET /api/v1/news/latest` - Get latest
- `GET /api/v1/news/{id}` - Get by ID
- `GET /api/v1/news/search?q=query` - Search
- `GET /api/v1/news/category/{cat}` - By category

### Deliverables
- `GET /api/v1/deliverables` - List all
- `GET /api/v1/deliverables/popular` - Most popular
- `GET /api/v1/deliverables/status/{status}` - By status
- `GET /api/v1/deliverables/search?q=query` - Search
- `GET /api/v1/deliverables/category/{cat}` - By category

### Newsletters
- `GET /api/v1/newsletters` - List all
- `GET /api/v1/newsletters/latest` - Latest
- `GET /api/v1/newsletters/sent` - Sent only
- `GET /api/v1/newsletters/stats` - Statistics
- `GET /api/v1/newsletters/search?q=query` - Search

### Blog Posts (Enhanced)
- `GET /api/v1/blog` - List all
- `GET /api/v1/blog/trending` - Trending posts
- `GET /api/v1/blog/search?q=query` - Search
- `GET /api/v1/blog/category/{cat}` - By category

---

## 🔗 Full Documentation Files

Located in your project root:

1. **API_DOCUMENTATION.md** - Complete API reference
2. **SETUP_GUIDE.md** - Detailed setup instructions
3. **INTEGRATION_EXAMPLES.md** - Code examples for all frameworks

---

## 📁 What Was Created

### Models
```
app/Models/News.php
app/Models/Deliverable.php
app/Models/Newsletter.php
```

### Components
```
app/Livewire/Admin/Blog/NewsManagement.php
app/Livewire/Admin/Blog/DeliverableManagement.php
app/Livewire/Admin/Blog/NewsletterManagement.php
```

### API Controllers
```
app/Http/Controllers/Api/NewsApiController.php
app/Http/Controllers/Api/DeliverableApiController.php
app/Http/Controllers/Api/NewsletterApiController.php
app/Http/Controllers/Api/BlogPostApiController.php
```

### Views
```
resources/views/livewire/admin/blog/news-management.blade.php
resources/views/livewire/admin/blog/deliverable-management.blade.php
resources/views/livewire/admin/blog/newsletter-management.blade.php
```

### Migrations
```
database/migrations/2026_05_07_000001_create_news_table.php
database/migrations/2026_05_07_000002_create_deliverables_table.php
database/migrations/2026_05_07_000003_create_newsletters_table.php
```

### Routes
```
routes/api.php (Updated with new endpoints)
routes/web.php (Updated with new admin routes)
```

---

## ✨ Design Features

All admin panels match your provided Figma design:

- 📊 **Statistics Cards** - Total, Published, Status counts
- 🔍 **Smart Search** - Real-time filtering
- 📋 **Data Tables** - Organized content display
- 🎨 **Modal Forms** - Clean create/edit dialogs
- 🌍 **Bilingual** - Full English & Arabic support
- 📱 **Responsive** - Works on all devices
- ⚡ **Fast** - Optimized performance

---

## 🔐 Security

✓ Admin authentication required  
✓ Module permission checks (`blog`)  
✓ File upload validation  
✓ SQL injection prevention  
✓ XSS protection  
✓ Soft deletes for data recovery  
✓ Activity logging  

---

## 🐛 Troubleshooting

### Routes not working
```bash
php artisan route:clear
php artisan route:cache
```

### Database errors
```bash
php artisan migrate:fresh --seed
```

### Cache issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Component not loading
```bash
php artisan optimize:clear
php artisan livewire:publish
```

---

## 📞 Next Steps

1. ✅ Run migrations: `php artisan migrate`
2. ✅ Clear cache: `php artisan cache:clear`
3. ✅ Access admin: Visit `/admin/news`, `/admin/deliverables`, `/admin/newsletters`
4. ✅ Test API: Use the example code from **INTEGRATION_EXAMPLES.md**
5. ✅ Integrate: Add to your external projects using the provided code snippets

---

## 📚 Documentation Structure

```
Project Root/
├── API_DOCUMENTATION.md          ← API Reference
├── SETUP_GUIDE.md                ← Detailed Setup
├── INTEGRATION_EXAMPLES.md        ← Code Examples
├── QUICK_START_GUIDE.md          ← This file
└── app/
    ├── Models/
    ├── Livewire/Admin/Blog/
    ├── Http/Controllers/Api/
    └── ...
```

---

## 🎉 You're All Set!

The Content Management System is ready to use. Start creating content in your admin panel and leverage the API in your external projects.

For detailed information, refer to the documentation files listed above.

**Happy Content Managing!** 🚀
