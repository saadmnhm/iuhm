# 📁 Media Management Module

## Overview

The Media Management module is a **separate system** from the candidat/user system. It's designed to manage media files for use across external websites and projects via REST API.

---

## 🚀 Quick Start

### 1. Run Migration

```bash
php artisan migrate
```

This creates the `media` table with all necessary fields.

### 2. Clear Cache

```bash
php artisan cache:clear
php artisan route:cache
```

### 3. Access Admin Panel

Navigate to: **`/admin/media`**

---

## 📊 Features

### Admin Interface (`/admin/media`)
- ✅ Upload media files
- ✅ Manage file metadata (titles, descriptions in English & Arabic)
- ✅ Organize by categories
- ✅ Add tags for easy filtering
- ✅ Toggle public/private visibility
- ✅ View usage statistics
- ✅ Search and filter media
- ✅ Activity logging

### Supported File Types
- Images: JPG, JPEG, PNG, GIF
- Documents: PDF, DOC, DOCX, XLS, XLSX
- Archives: ZIP
- Video: MP4, WEBM
- **Max Size: 50 MB**

---

## 🔌 REST API Endpoints

### Base URL
```
/api/v1/media
```

### Endpoints

#### 1. List All Public Media
```
GET /api/v1/media
```

**Query Parameters:**
- `per_page` - Items per page (default: 15)
- `category` - Filter by category
- `type` - Filter by MIME type (e.g., `image`, `pdf`)
- `search` - Search query

**Example:**
```
GET /api/v1/media?category=logos&per_page=10
```

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "title": "Logo",
      "title_ar": "الشعار",
      "description": "Company logo",
      "category": "logos",
      "tags": ["brand", "2024"],
      "file_type": "png",
      "file_size": 245125,
      "usage_count": 42,
      "created_at": "2026-05-07T10:30:00Z"
    }
  ],
  "pagination": {
    "total": 50,
    "per_page": 15,
    "current_page": 1,
    "last_page": 4
  }
}
```

---

#### 2. Get Media by ID
```
GET /api/v1/media/{id}
```

Retrieves a specific media file and increments its usage count.

**Example:**
```
GET /api/v1/media/1
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "title": "Logo",
    "title_ar": "الشعار",
    "description": "Company logo",
    "description_ar": "شعار الشركة",
    "category": "logos",
    "tags": ["brand", "2024"],
    "file_url": "http://domain/uploads/media/2026/05/logo.png",
    "file_size": "239.38 KB",
    "file_type": "png",
    "usage_count": 43,
    "created_at": "2026-05-07T10:30:00Z"
  }
}
```

---

#### 3. Search Media
```
GET /api/v1/media/search?q={query}
```

Search in titles, descriptions, and tags.

**Query Parameters:**
- `q` - Search query (required)
- `per_page` - Items per page (default: 15)

**Example:**
```
GET /api/v1/media/search?q=logo&per_page=10
```

---

#### 4. Get by Category
```
GET /api/v1/media/category/{category}
```

Get all media files in a specific category.

**Example:**
```
GET /api/v1/media/category/logos
```

---

#### 5. Get by File Type
```
GET /api/v1/media/type/{type}
```

Filter by MIME type (image, pdf, video, etc.).

**Example:**
```
GET /api/v1/media/type/image
```

---

#### 6. Get Latest Media
```
GET /api/v1/media/latest?limit={limit}
```

Get the most recently uploaded media.

**Query Parameters:**
- `limit` - Number of items (default: 10)

**Example:**
```
GET /api/v1/media/latest?limit=5
```

---

#### 7. Get Most Used Media
```
GET /api/v1/media/most-used?limit={limit}
```

Get media files with highest usage count.

**Example:**
```
GET /api/v1/media/most-used?limit=10
```

---

#### 8. Get All Categories
```
GET /api/v1/media/categories
```

Get list of all available categories.

**Response:**
```json
{
  "status": "success",
  "data": ["logos", "banners", "documents", "images"]
}
```

---

#### 9. Get Statistics
```
GET /api/v1/media/stats
```

Get overall media statistics.

**Response:**
```json
{
  "status": "success",
  "data": {
    "total": 150,
    "public": 145,
    "private": 5,
    "by_category": {
      "logos": 25,
      "banners": 30,
      "documents": 40,
      "images": 50
    },
    "most_used": {
      "1": "Main Logo",
      "5": "Header Banner",
      "12": "Footer Image"
    }
  }
}
```

---

#### 10. Download Media File
```
GET /api/v1/media/download/{id}
```

Download a media file (increments usage count).

**Example:**
```
GET /api/v1/media/download/1
```

---

## 💻 Integration Examples

### JavaScript/React
```javascript
// Fetch latest media
async function getLatestMedia() {
  const response = await fetch('https://domain/api/v1/media/latest?limit=5');
  const data = await response.json();
  console.log(data.data);
}

// Get media by category
async function getLogos() {
  const response = await fetch('https://domain/api/v1/media/category/logos');
  const data = await response.json();
  return data.data;
}

// Search media
async function searchMedia(query) {
  const response = await fetch(`https://domain/api/v1/media/search?q=${query}`);
  const data = await response.json();
  return data.data;
}
```

### React Component
```jsx
import React, { useEffect, useState } from 'react';

function MediaGallery() {
  const [media, setMedia] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('https://domain/api/v1/media/category/logos')
      .then(res => res.json())
      .then(data => {
        setMedia(data.data);
        setLoading(false);
      });
  }, []);

  if (loading) return <div>Loading...</div>;

  return (
    <div className="gallery">
      {media.map(item => (
        <div key={item.id} className="media-item">
          <h3>{item.title}</h3>
          <img src={item.file_url} alt={item.title} />
          <p>{item.description}</p>
          <p>Category: {item.category}</p>
          <p>Used {item.usage_count} times</p>
        </div>
      ))}
    </div>
  );
}

export default MediaGallery;
```

### Vue.js
```vue
<template>
  <div class="media-gallery">
    <div v-for="item in media" :key="item.id" class="media-card">
      <img :src="item.file_url" :alt="item.title" />
      <h3>{{ item.title }}</h3>
      <p>{{ item.description }}</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      media: []
    };
  },
  mounted() {
    fetch('https://domain/api/v1/media/category/logos')
      .then(res => res.json())
      .then(data => {
        this.media = data.data;
      });
  }
};
</script>
```

### PHP/Laravel
```php
// Fetch media
$response = Http::get('https://domain/api/v1/media/category/logos');
$media = $response->json()['data'];

foreach ($media as $item) {
    echo "Title: " . $item['title'];
    echo "File: " . $item['file_url'];
}

// Download media
$response = Http::get('https://domain/api/v1/media/download/1');
$response->throw();
```

### Python
```python
import requests

# Get media by category
response = requests.get('https://domain/api/v1/media/category/logos')
media = response.json()['data']

for item in media:
    print(f"Title: {item['title']}")
    print(f"File: {item['file_url']}")

# Search media
response = requests.get('https://domain/api/v1/media/search', 
                       params={'q': 'logo'})
results = response.json()['data']
```

---

## 🔐 Permissions

The Media Management module uses the permission system: `module:media`

Add this to admin user roles to grant access to the media management interface.

---

## 📂 Directory Structure

Files are uploaded to: `uploads/media/{YEAR}/{MONTH}/{filename}`

Example:
```
uploads/
└── media/
    └── 2026/
        └── 05/
            └── 507895d6ea69d_1714857600.png
```

---

## 🛠️ Database Schema

### Media Table

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| title | string | English title |
| title_ar | string | Arabic title |
| description | text | English description |
| description_ar | text | Arabic description |
| file_name | string | Original file name |
| file_path | string | Storage path |
| file_size | integer | File size in bytes |
| file_type | string | File extension (jpg, pdf, etc.) |
| mime_type | string | MIME type (image/jpeg, etc.) |
| category | string | Categorization |
| tags | json | Array of tags |
| is_public | boolean | Public/private visibility |
| uploaded_by | bigint | User ID who uploaded |
| usage_count | integer | Times accessed via API |
| created_at | timestamp | Upload timestamp |
| updated_at | timestamp | Last update timestamp |
| deleted_at | timestamp | Soft delete timestamp |

---

## 📋 Activity Logging

All media operations are logged in `admin_activity_logs`:
- File uploads
- Metadata updates
- File deletions
- Visibility changes

Access logs via: `/admin/activity-logs`

---

## 🔧 Configuration

The module is configured in:
- `app/Livewire/Admin/Media/MediaManagement.php` - Component logic
- `app/Http/Controllers/Api/MediaApiController.php` - API endpoints
- `app/Models/Media.php` - Database model
- `database/migrations/2026_05_07_000004_create_media_table.php` - Database schema

---

## ⚠️ Notes

- Only **public media** is accessible via the API
- Media files are automatically deleted from storage when the record is deleted
- Usage count is incremented each time a file is accessed via API
- Soft deletes are enabled - deleted media can be recovered from database backups
- File uploads require proper permissions (`module:media`)

---

## 🚀 Next Steps

1. **Start uploading media** via `/admin/media`
2. **Organize** media by categories
3. **Use the API** in your external projects
4. **Monitor statistics** to track usage
5. **Archive old media** by changing visibility to private

---

## 📚 Related Modules

- [News Management](/media/documentation/CMS_API_DOCUMENTATION.md)
- [Deliverables Management](/media/documentation/CMS_API_DOCUMENTATION.md)
- [Newsletter Management](/media/documentation/CMS_API_DOCUMENTATION.md)

---

**Last Updated:** May 7, 2026
