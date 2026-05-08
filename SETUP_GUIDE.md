# Content Management System - Setup & Installation Guide

## Overview
This document provides complete instructions for setting up and using the new Content Management System with integrated Article, News, Deliverables, and Newsletter management modules.

## What Was Created

### 1. **Database Models**
- `App\Models\News` - For news/actualités management
- `App\Models\Deliverable` - For deliverables/livrables management  
- `App\Models\Newsletter` - For newsletter/infolettre management

### 2. **Livewire Components**
- `App\Livewire\Admin\Blog\NewsManagement` - News management interface
- `App\Livewire\Admin\Blog\DeliverableManagement` - Deliverables management interface
- `App\Livewire\Admin\Blog\NewsletterManagement` - Newsletter management interface

### 3. **API Controllers**
- `App\Http\Controllers\Api\NewsApiController` - REST API for news
- `App\Http\Controllers\Api\DeliverableApiController` - REST API for deliverables
- `App\Http\Controllers\Api\NewsletterApiController` - REST API for newsletters
- `App\Http\Controllers\Api\BlogPostApiController` - Enhanced REST API for blog posts

### 4. **Database Migrations**
```
database/migrations/2026_05_07_000001_create_news_table.php
database/migrations/2026_05_07_000002_create_deliverables_table.php
database/migrations/2026_05_07_000003_create_newsletters_table.php
```

### 5. **API Routes**
```
routes/api.php - Complete REST API endpoints
```

### 6. **Blade Views**
```
resources/views/livewire/admin/blog/news-management.blade.php
resources/views/livewire/admin/blog/deliverable-management.blade.php
resources/views/livewire/admin/blog/newsletter-management.blade.php
```

---

## Installation Steps

### Step 1: Run Database Migrations
Execute the migrations to create the necessary database tables:

```bash
php artisan migrate
```

This will create three new tables:
- `news` - For storing news articles
- `deliverables` - For storing deliverables
- `newsletters` - For storing newsletters

### Step 2: Clear Cache
Clear Laravel cache to register new routes and components:

```bash
php artisan cache:clear
php artisan route:cache --force
```

### Step 3: Verify Installation
Check that all routes are registered:

```bash
php artisan route:list | grep api/v1
```

---

## How to Use

### Admin Dashboard Access

#### News Management
- **URL:** `https://your-domain/admin/news`
- **Route Name:** `admin.news.index`
- **Module Permission:** `blog`

#### Deliverables Management
- **URL:** `https://your-domain/admin/deliverables`
- **Route Name:** `admin.deliverables.index`
- **Module Permission:** `blog`

#### Newsletter Management
- **URL:** `https://your-domain/admin/newsletters`
- **Route Name:** `admin.newsletters.index`
- **Module Permission:** `blog`

### Features in Each Module

#### News Module
- ✓ Create/Edit/Delete news articles
- ✓ Bilingual support (English & Arabic)
- ✓ Category and tags management
- ✓ Featured image upload
- ✓ Publish/Draft status
- ✓ View count tracking
- ✓ Search and filter functionality

#### Deliverables Module
- ✓ Create/Edit/Delete deliverables
- ✓ Bilingual support (English & Arabic)
- ✓ File upload and download tracking
- ✓ Status management (pending, completed, overdue)
- ✓ Due date tracking
- ✓ Category organization
- ✓ Download count statistics

#### Newsletter Module
- ✓ Create/Edit/Delete newsletters
- ✓ Edition number management
- ✓ Featured image for email
- ✓ Send tracking with timestamps
- ✓ Recipient count tracking
- ✓ Bilingual content support
- ✓ Publication status management

---

## API Endpoints

### Base URL
```
https://your-domain/api/v1
```

### Available Endpoints

#### Blog Posts
```
GET  /blog                      - List all blog posts
GET  /blog/{id}                 - Get blog post by ID
GET  /blog/slug/{slug}          - Get blog post by slug
GET  /blog/trending             - Get trending posts
GET  /blog/search?q=query       - Search blog posts
GET  /blog/category/{category}  - Get posts by category
```

#### News
```
GET  /news                      - List all news
GET  /news/{id}                 - Get news by ID
GET  /news/slug/{slug}          - Get news by slug
GET  /news/latest?limit=10      - Get latest news
GET  /news/search?q=query       - Search news
GET  /news/category/{category}  - Get news by category
```

#### Deliverables
```
GET  /deliverables                      - List all deliverables
GET  /deliverables/{id}                 - Get deliverable by ID
GET  /deliverables/slug/{slug}          - Get deliverable by slug
GET  /deliverables/popular?limit=10     - Get popular deliverables
GET  /deliverables/search?q=query       - Search deliverables
GET  /deliverables/category/{category}  - Get by category
GET  /deliverables/status/{status}      - Get by status (pending, completed, overdue)
```

#### Newsletters
```
GET  /newsletters                   - List all newsletters
GET  /newsletters/{id}              - Get newsletter by ID
GET  /newsletters/slug/{slug}       - Get newsletter by slug
GET  /newsletters/issue/{number}    - Get newsletter by issue number
GET  /newsletters/latest?limit=5    - Get latest newsletters
GET  /newsletters/sent              - Get sent newsletters only
GET  /newsletters/search?q=query    - Search newsletters
GET  /newsletters/stats             - Get newsletter statistics
```

---

## Using the APIs in External Projects

### Example 1: Fetch Latest News in React
```javascript
import { useEffect, useState } from 'react';

export function LatestNews() {
  const [news, setNews] = useState([]);
  
  useEffect(() => {
    fetch('https://your-domain/api/v1/news/latest?limit=5')
      .then(res => res.json())
      .then(data => setNews(data.data));
  }, []);
  
  return (
    <div>
      {news.map(item => (
        <article key={item.id}>
          <h2>{item.title}</h2>
          <p>{item.excerpt}</p>
          <img src={item.image} alt={item.title} />
        </article>
      ))}
    </div>
  );
}
```

### Example 2: Search Deliverables in Vue.js
```vue
<template>
  <div>
    <input v-model="searchQuery" @input="searchDeliverables" placeholder="Search...">
    <div v-for="item in results" :key="item.id">
      <h3>{{ item.title }}</h3>
      <p>Status: {{ item.status }}</p>
      <a :href="item.file_url">Download</a>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return { searchQuery: '', results: [] };
  },
  methods: {
    async searchDeliverables() {
      if (this.searchQuery.length < 2) return;
      const res = await fetch(`https://your-domain/api/v1/deliverables/search?q=${this.searchQuery}`);
      const data = await res.json();
      this.results = data.data;
    }
  }
};
</script>
```

### Example 3: Fetch Content in PHP
```php
<?php
$newsUrl = 'https://your-domain/api/v1/news/latest?limit=10';
$response = file_get_contents($newsUrl);
$data = json_decode($response, true);

foreach ($data['data'] as $article) {
    echo "<h2>" . htmlspecialchars($article['title']) . "</h2>";
    echo "<p>" . htmlspecialchars($article['excerpt']) . "</p>";
}
?>
```

---

## Database Schema

### News Table
```sql
CREATE TABLE news (
    id BIGINT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    title_ar VARCHAR(255) NULLABLE,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT NULLABLE,
    excerpt_ar TEXT NULLABLE,
    content LONGTEXT NOT NULL,
    content_ar LONGTEXT NULLABLE,
    image VARCHAR(255) NULLABLE,
    category VARCHAR(100) NULLABLE,
    tags JSON NULLABLE,
    is_published BOOLEAN DEFAULT 0,
    published_at TIMESTAMP NULLABLE,
    author_id BIGINT NOT NULL,
    views_count INT DEFAULT 0,
    deleted_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Deliverables Table
```sql
CREATE TABLE deliverables (
    id BIGINT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    title_ar VARCHAR(255) NULLABLE,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NULLABLE,
    description_ar TEXT NULLABLE,
    file_url VARCHAR(255) NULLABLE,
    file_type VARCHAR(50) NULLABLE,
    category VARCHAR(100) NULLABLE,
    status ENUM('pending', 'completed', 'overdue') DEFAULT 'pending',
    due_date TIMESTAMP NULLABLE,
    is_published BOOLEAN DEFAULT 0,
    published_at TIMESTAMP NULLABLE,
    author_id BIGINT NOT NULL,
    downloads_count INT DEFAULT 0,
    deleted_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Newsletters Table
```sql
CREATE TABLE newsletters (
    id BIGINT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    title_ar VARCHAR(255) NULLABLE,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT NOT NULL,
    content_ar LONGTEXT NULLABLE,
    featured_image VARCHAR(255) NULLABLE,
    issue_number INT NULLABLE UNIQUE,
    is_published BOOLEAN DEFAULT 0,
    published_at TIMESTAMP NULLABLE,
    sent_at TIMESTAMP NULLABLE,
    author_id BIGINT NOT NULL,
    recipients_count INT DEFAULT 0,
    deleted_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## File Structure

```
app/
├── Models/
│   ├── News.php (NEW)
│   ├── Deliverable.php (NEW)
│   └── Newsletter.php (NEW)
├── Livewire/Admin/Blog/
│   ├── NewsManagement.php (NEW)
│   ├── DeliverableManagement.php (NEW)
│   └── NewsletterManagement.php (NEW)
└── Http/Controllers/Api/
    ├── BlogPostApiController.php (NEW)
    ├── NewsApiController.php (NEW)
    ├── DeliverableApiController.php (NEW)
    └── NewsletterApiController.php (NEW)

database/migrations/
├── 2026_05_07_000001_create_news_table.php (NEW)
├── 2026_05_07_000002_create_deliverables_table.php (NEW)
└── 2026_05_07_000003_create_newsletters_table.php (NEW)

resources/views/livewire/admin/blog/
├── news-management.blade.php (NEW)
├── deliverable-management.blade.php (NEW)
└── newsletter-management.blade.php (NEW)

routes/
└── api.php (UPDATED)
```

---

## Security Notes

✓ All models use soft deletes for data preservation
✓ Authorization via middleware: `module:blog` permission
✓ API endpoints are read-only (GET only)
✓ File uploads validated for type and size
✓ User authentication required for admin panel
✓ Activity logging included via `AdminActivityLog`

---

## Troubleshooting

### Routes Not Found
```bash
php artisan route:clear
php artisan route:cache
```

### Migrations Failed
```bash
php artisan migrate:fresh --seed
```

### Cache Issues
```bash
php artisan cache:clear
php artisan config:clear
```

### View Not Found
```bash
php artisan view:clear
php artisan cache:clear
```

---

## Support & Customization

For custom modifications or feature requests, contact the development team.

### Customization Options

1. **Modify upload storage:** Edit storage path in controllers
2. **Add more bilingual support:** Add language fields in models
3. **Extend API responses:** Modify API controller methods
4. **Custom styling:** Edit Blade view Tailwind classes
5. **Add more statuses:** Extend enum in migration

---

## Version Information

- **Created:** May 7, 2026
- **Laravel Version:** 11.x
- **Livewire Version:** 3.x
- **Framework:** Tailwind CSS

---

## Next Steps

1. Run migrations: `php artisan migrate`
2. Clear cache: `php artisan cache:clear`
3. Access admin panel: `/admin/news`, `/admin/deliverables`, `/admin/newsletters`
4. Test API endpoints via postman or browser
5. Integrate with your external projects using provided API examples
