# Content Management API Documentation

## Base URL
```
https://your-domain/api/v1
```

## Authentication
All API endpoints are public (no authentication required for GET requests).

---

## Blog Posts API

### 1. List All Blog Posts
```
GET /blog
```

**Query Parameters:**
- `per_page` (optional, default: 15) - Items per page
- `category` (optional) - Filter by category
- `search` (optional) - Search in title, excerpt, content

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "title": "Article Title",
      "title_ar": "عنوان المقالة",
      "slug": "article-title",
      "excerpt": "Short excerpt",
      "content": "Full content...",
      "image": "uploads/blog/image.jpg",
      "category": "Technology",
      "tags": ["tag1", "tag2"],
      "is_published": true,
      "published_at": "2026-05-07T10:00:00Z",
      "views_count": 150,
      "author": {
        "id": 1,
        "name": "Author Name",
        "email": "author@example.com"
      }
    }
  ],
  "pagination": {
    "total": 45,
    "per_page": 15,
    "current_page": 1,
    "last_page": 3
  }
}
```

### 2. Get Single Blog Post by ID
```
GET /blog/{id}
```

### 3. Get Blog Post by Slug
```
GET /blog/slug/{slug}
```

### 4. Search Blog Posts
```
GET /blog/search?q=query
```

**Query Parameters:**
- `q` (required) - Search query

### 5. Get Posts by Category
```
GET /blog/category/{category}?per_page=15
```

### 6. Get Trending Posts
```
GET /blog/trending?limit=10
```

---

## News (Actualités) API

### 1. List All News
```
GET /news
```

**Query Parameters:**
- `per_page` (optional, default: 15)
- `category` (optional)
- `search` (optional)

### 2. Get Single News by ID
```
GET /news/{id}
```

### 3. Get News by Slug
```
GET /news/slug/{slug}
```

### 4. Search News
```
GET /news/search?q=query
```

### 5. Get News by Category
```
GET /news/category/{category}?per_page=15
```

### 6. Get Latest News
```
GET /news/latest?limit=10
```

---

## Deliverables API

### 1. List All Deliverables
```
GET /deliverables
```

**Query Parameters:**
- `per_page` (optional, default: 15)
- `category` (optional)
- `status` (optional) - pending, completed, overdue
- `search` (optional)

### 2. Get Single Deliverable by ID
```
GET /deliverables/{id}
```

### 3. Get Deliverable by Slug
```
GET /deliverables/slug/{slug}
```

### 4. Search Deliverables
```
GET /deliverables/search?q=query
```

### 5. Get Deliverables by Category
```
GET /deliverables/category/{category}?per_page=15
```

### 6. Get Deliverables by Status
```
GET /deliverables/status/{status}?per_page=15
```

**Status Values:** pending, completed, overdue

### 7. Get Most Popular Deliverables
```
GET /deliverables/popular?limit=10
```

---

## Newsletters API

### 1. List All Newsletters
```
GET /newsletters
```

**Query Parameters:**
- `per_page` (optional, default: 15)

### 2. Get Single Newsletter by ID
```
GET /newsletters/{id}
```

### 3. Get Newsletter by Slug
```
GET /newsletters/slug/{slug}
```

### 4. Get Newsletter by Issue Number
```
GET /newsletters/issue/{issue}
```

### 5. Search Newsletters
```
GET /newsletters/search?q=query
```

### 6. Get Latest Newsletters
```
GET /newsletters/latest?limit=5
```

### 7. Get Sent Newsletters Only
```
GET /newsletters/sent?per_page=15
```

### 8. Get Newsletter Statistics
```
GET /newsletters/stats
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "total": 50,
    "published": 45,
    "sent": 40,
    "draft": 5,
    "total_recipients": 5000
  }
}
```

---

## Example Usage in External Projects

### JavaScript/React
```javascript
// Fetch latest news
async function getLatestNews() {
  try {
    const response = await fetch('https://your-domain/api/v1/news/latest?limit=5');
    const data = await response.json();
    
    if (data.status === 'success') {
      console.log('Latest news:', data.data);
    }
  } catch (error) {
    console.error('Error fetching news:', error);
  }
}

// Search articles
async function searchArticles(query) {
  const response = await fetch(`https://your-domain/api/v1/blog/search?q=${query}`);
  const data = await response.json();
  return data.data;
}

// Get deliverables by category
async function getDeliverables(category) {
  const response = await fetch(`https://your-domain/api/v1/deliverables/category/${category}`);
  const data = await response.json();
  return data.data;
}
```

### Vue.js
```vue
<template>
  <div>
    <h2>Latest News</h2>
    <div v-for="news in latestNews" :key="news.id">
      <h3>{{ news.title }}</h3>
      <p>{{ news.excerpt }}</p>
      <img :src="news.image" :alt="news.title" />
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      latestNews: []
    };
  },
  async mounted() {
    const response = await fetch('https://your-domain/api/v1/news/latest?limit=5');
    const data = await response.json();
    this.latestNews = data.data;
  }
};
</script>
```

### PHP/Laravel
```php
use Illuminate\Support\Facades\Http;

// Get latest blog posts
$response = Http::get('https://your-domain/api/v1/blog/latest', [
    'limit' => 10
]);

$posts = $response->json()['data'];

// Search deliverables
$response = Http::get('https://your-domain/api/v1/deliverables/search', [
    'q' => 'project report'
]);

$deliverables = $response->json()['data'];
```

### Python
```python
import requests

# Get all published news
response = requests.get('https://your-domain/api/v1/news')
news_data = response.json()

# Fetch newsletters stats
response = requests.get('https://your-domain/api/v1/newsletters/stats')
stats = response.json()['data']

print(f"Total newsletters: {stats['total']}")
print(f"Published: {stats['published']}")
print(f"Sent: {stats['sent']}")
```

---

## Error Handling

All API responses follow this format:

**Success Response:**
```json
{
  "status": "success",
  "data": { ... }
}
```

**Error Response:**
```json
{
  "status": "error",
  "message": "Resource not found",
  "code": 404
}
```

---

## Response Fields

### Blog Post / News Object
- `id` - Unique identifier
- `title` - English title
- `title_ar` - Arabic title (optional)
- `slug` - URL-friendly slug
- `excerpt` - Short description
- `excerpt_ar` - Arabic excerpt (optional)
- `content` - Full content
- `content_ar` - Arabic content (optional)
- `image` - Image URL
- `category` - Category name
- `tags` - Array of tags
- `is_published` - Publication status
- `published_at` - Publication date
- `views_count` - Number of views
- `author` - Author information

### Deliverable Object
- `id` - Unique identifier
- `title` - English title
- `slug` - URL-friendly slug
- `description` - Description
- `file_url` - URL to download file
- `file_type` - File extension (pdf, doc, etc.)
- `category` - Category name
- `status` - pending, completed, overdue
- `due_date` - Deadline
- `is_published` - Publication status
- `downloads_count` - Number of downloads
- `author` - Author information

### Newsletter Object
- `id` - Unique identifier
- `title` - Email title
- `slug` - URL-friendly slug
- `content` - Email content
- `featured_image` - Header image
- `issue_number` - Edition number
- `is_published` - Publication status
- `published_at` - Publication date
- `sent_at` - Send date
- `recipients_count` - Number of recipients
- `author` - Author information

---

## Rate Limiting

Currently, there is no rate limiting applied. However, it's recommended to cache responses when possible.

---

## CORS

The API supports CORS requests from any origin. You can safely call these endpoints from any frontend application.

---

## Support

For issues or questions about the API, contact the development team.
