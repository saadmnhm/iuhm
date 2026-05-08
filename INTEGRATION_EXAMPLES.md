# API Integration Examples for External Projects

This document contains ready-to-use code snippets for integrating with the IUHM Content Management API from various frameworks and platforms.

## Table of Contents
1. [JavaScript/Vanilla](#javascriptvanilla)
2. [React](#react)
3. [Vue.js](#vuejs)
4. [Angular](#angular)
5. [Next.js](#nextjs)
6. [PHP](#php)
7. [WordPress](#wordpress)
8. [Python](#python)
9. [C# / .NET](#c--net)

---

## JavaScript/Vanilla

### Fetch Latest News
```javascript
// Fetch and display latest news
async function displayLatestNews(containerId, limit = 5) {
  try {
    const response = await fetch(`https://iuhm.example.com/api/v1/news/latest?limit=${limit}`);
    const { status, data } = await response.json();
    
    if (status !== 'success') throw new Error('API Error');
    
    const container = document.getElementById(containerId);
    container.innerHTML = data.map(news => `
      <article class="news-item" data-id="${news.id}">
        <h3>${news.title}</h3>
        <p class="excerpt">${news.excerpt}</p>
        ${news.image ? `<img src="${news.image}" alt="${news.title}" />` : ''}
        <p class="date">${new Date(news.published_at).toLocaleDateString()}</p>
      </article>
    `).join('');
  } catch (error) {
    console.error('Error loading news:', error);
  }
}

// Usage
displayLatestNews('news-container', 10);
```

### Search Deliverables
```javascript
async function searchDeliverables(query) {
  const response = await fetch(`https://iuhm.example.com/api/v1/deliverables/search?q=${encodeURIComponent(query)}`);
  const { data } = await response.json();
  
  return data.map(item => ({
    title: item.title,
    description: item.description,
    fileUrl: item.file_url,
    downloadCount: item.downloads_count,
    status: item.status
  }));
}

// Usage
searchDeliverables('annual report').then(results => {
  console.log('Found:', results.length, 'deliverables');
});
```

### Real-time Newsletter Subscriber Count
```javascript
async function getNewsletterStats() {
  const response = await fetch('https://iuhm.example.com/api/v1/newsletters/stats');
  const { data } = await response.json();
  
  return {
    totalNewsletters: data.total,
    published: data.published,
    sent: data.sent,
    totalRecipients: data.total_recipients
  };
}

// Update dashboard
async function updateDashboard() {
  const stats = await getNewsletterStats();
  document.getElementById('subscriber-count').textContent = stats.totalRecipients;
}
```

---

## React

### News Feed Component
```jsx
import React, { useEffect, useState } from 'react';

export function NewsFeed({ limit = 10 }) {
  const [news, setNews] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchNews = async () => {
      try {
        const response = await fetch(`https://iuhm.example.com/api/v1/news/latest?limit=${limit}`);
        const data = await response.json();
        setNews(data.data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchNews();
  }, [limit]);

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div className="news-feed">
      {news.map(article => (
        <article key={article.id} className="news-card">
          <h2>{article.title}</h2>
          <p>{article.excerpt}</p>
          <img src={article.image} alt={article.title} />
          <small>By {article.author.name} on {new Date(article.published_at).toLocaleDateString()}</small>
          <p className="views">👁️ {article.views_count} views</p>
        </article>
      ))}
    </div>
  );
}
```

### Deliverables Search Component
```jsx
import React, { useState } from 'react';
import useDebounce from './useDebounce';

export function DeliverableSearch() {
  const [query, setQuery] = useState('');
  const [results, setResults] = useState([]);
  const debouncedQuery = useDebounce(query, 300);

  React.useEffect(() => {
    if (!debouncedQuery) {
      setResults([]);
      return;
    }

    fetch(`https://iuhm.example.com/api/v1/deliverables/search?q=${encodeURIComponent(debouncedQuery)}`)
      .then(res => res.json())
      .then(data => setResults(data.data))
      .catch(err => console.error(err));
  }, [debouncedQuery]);

  return (
    <div>
      <input 
        type="text"
        placeholder="Search deliverables..."
        value={query}
        onChange={(e) => setQuery(e.target.value)}
      />
      <ul>
        {results.map(item => (
          <li key={item.id}>
            <h3>{item.title}</h3>
            <p>{item.description}</p>
            <a href={item.file_url}>Download ({item.file_type})</a>
            <span className={`status status-${item.status}`}>{item.status}</span>
          </li>
        ))}
      </ul>
    </div>
  );
}
```

### Newsletter Subscription Stats
```jsx
import React, { useEffect, useState } from 'react';

export function NewsletterStats() {
  const [stats, setStats] = useState(null);

  useEffect(() => {
    fetch('https://iuhm.example.com/api/v1/newsletters/stats')
      .then(res => res.json())
      .then(data => setStats(data.data));
  }, []);

  if (!stats) return <div>Loading stats...</div>;

  return (
    <div className="stats-grid">
      <div className="stat-card">
        <h3>Total Newsletters</h3>
        <p>{stats.total}</p>
      </div>
      <div className="stat-card">
        <h3>Subscribers</h3>
        <p>{stats.total_recipients.toLocaleString()}</p>
      </div>
      <div className="stat-card">
        <h3>Sent</h3>
        <p>{stats.sent}</p>
      </div>
    </div>
  );
}
```

---

## Vue.js

### News Component
```vue
<template>
  <div class="news-container">
    <h2>Latest News</h2>
    <div v-if="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else class="news-list">
      <article v-for="item in news" :key="item.id" class="news-item">
        <h3>{{ item.title }}</h3>
        <img v-if="item.image" :src="item.image" :alt="item.title" />
        <p>{{ item.excerpt }}</p>
        <footer>
          <span>By {{ item.author.name }}</span>
          <span>{{ formatDate(item.published_at) }}</span>
          <span>👁️ {{ item.views_count }}</span>
        </footer>
      </article>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      news: [],
      loading: true,
      error: null
    };
  },
  async mounted() {
    try {
      const response = await fetch('https://iuhm.example.com/api/v1/news/latest?limit=10');
      const data = await response.json();
      this.news = data.data;
    } catch (err) {
      this.error = err.message;
    } finally {
      this.loading = false;
    }
  },
  methods: {
    formatDate(dateStr) {
      return new Date(dateStr).toLocaleDateString();
    }
  }
};
</script>
```

### Deliverable Browser
```vue
<template>
  <div class="deliverable-browser">
    <div class="filters">
      <input v-model="search" placeholder="Search..." @input="updateSearch">
      <select v-model="selectedCategory">
        <option value="">All Categories</option>
        <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
      </select>
    </div>

    <div class="deliverable-grid">
      <div v-for="item in deliverables" :key="item.id" class="deliverable-card">
        <h3>{{ item.title }}</h3>
        <p>{{ item.description }}</p>
        <div class="meta">
          <span class="category">{{ item.category }}</span>
          <span :class="`status status-${item.status}`">{{ item.status }}</span>
        </div>
        <a :href="item.file_url" class="download-btn">
          Download ({{ item.file_type }}) • {{ item.downloads_count }} downloads
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      search: '',
      selectedCategory: '',
      deliverables: [],
      categories: []
    };
  },
  watch: {
    search: 'updateSearch',
    selectedCategory: 'updateSearch'
  },
  async mounted() {
    await this.updateSearch();
  },
  methods: {
    async updateSearch() {
      const url = new URL('https://iuhm.example.com/api/v1/deliverables');
      if (this.search) {
        url.pathname = '/api/v1/deliverables/search';
        url.searchParams.set('q', this.search);
      }
      if (this.selectedCategory) {
        url.pathname = `/api/v1/deliverables/category/${this.selectedCategory}`;
      }

      const response = await fetch(url);
      const data = await response.json();
      this.deliverables = data.data;
    }
  }
};
</script>
```

---

## Angular

### News Service
```typescript
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

interface NewsItem {
  id: number;
  title: string;
  excerpt: string;
  content: string;
  image: string;
  published_at: string;
  views_count: number;
  author: { name: string; email: string };
}

@Injectable({
  providedIn: 'root'
})
export class NewsService {
  private apiUrl = 'https://iuhm.example.com/api/v1';

  constructor(private http: HttpClient) {}

  getLatestNews(limit: number = 10): Observable<{ data: NewsItem[] }> {
    return this.http.get<{ data: NewsItem[] }>(
      `${this.apiUrl}/news/latest?limit=${limit}`
    );
  }

  searchNews(query: string): Observable<{ data: NewsItem[] }> {
    return this.http.get<{ data: NewsItem[] }>(
      `${this.apiUrl}/news/search?q=${encodeURIComponent(query)}`
    );
  }

  getNewsByCategory(category: string): Observable<{ data: NewsItem[] }> {
    return this.http.get<{ data: NewsItem[] }>(
      `${this.apiUrl}/news/category/${category}`
    );
  }
}
```

### News Component
```typescript
import { Component, OnInit } from '@angular/core';
import { NewsService } from './news.service';

@Component({
  selector: 'app-news',
  template: `
    <div class="news-container">
      <h2>Latest News</h2>
      <div *ngIf="loading" class="loading">Loading...</div>
      <article *ngFor="let item of news" class="news-item">
        <h3>{{ item.title }}</h3>
        <img *ngIf="item.image" [src]="item.image" [alt]="item.title" />
        <p>{{ item.excerpt }}</p>
        <small>{{ item.published_at | date }} • 👁️ {{ item.views_count }}</small>
      </article>
    </div>
  `
})
export class NewsComponent implements OnInit {
  news: any[] = [];
  loading = true;

  constructor(private newsService: NewsService) {}

  ngOnInit() {
    this.newsService.getLatestNews(10).subscribe(
      response => {
        this.news = response.data;
        this.loading = false;
      }
    );
  }
}
```

---

## Next.js

### API Route Helper
```typescript
// lib/iuhm-api.ts
const API_BASE = process.env.NEXT_PUBLIC_IUHM_API_URL || 'https://iuhm.example.com/api/v1';

export async function fetchNews(limit: number = 10) {
  const res = await fetch(`${API_BASE}/news/latest?limit=${limit}`);
  return res.json();
}

export async function searchDeliverables(query: string) {
  const res = await fetch(`${API_BASE}/deliverables/search?q=${encodeURIComponent(query)}`);
  return res.json();
}

export async function getNewsletterStats() {
  const res = await fetch(`${API_BASE}/newsletters/stats`);
  return res.json();
}
```

### News Page Component
```tsx
// pages/news.tsx
import { GetStaticProps } from 'next';
import { fetchNews } from '../lib/iuhm-api';

export default function NewsPage({ news }) {
  return (
    <main>
      <h1>Latest News</h1>
      {news.map(article => (
        <article key={article.id}>
          <h2>{article.title}</h2>
          <img src={article.image} alt={article.title} />
          <p>{article.excerpt}</p>
          <time>{new Date(article.published_at).toLocaleDateString()}</time>
        </article>
      ))}
    </main>
  );
}

export const getStaticProps: GetStaticProps = async () => {
  const { data } = await fetchNews(10);
  
  return {
    props: { news: data },
    revalidate: 3600 // ISR - revalidate every hour
  };
};
```

---

## PHP

### Simple Integration
```php
<?php
class IUHMApi {
    private $baseUrl = 'https://iuhm.example.com/api/v1';
    
    public function getLatestNews($limit = 10) {
        $url = $this->baseUrl . '/news/latest?limit=' . $limit;
        $json = file_get_contents($url);
        return json_decode($json, true);
    }
    
    public function searchDeliverables($query) {
        $url = $this->baseUrl . '/deliverables/search?q=' . urlencode($query);
        $json = file_get_contents($url);
        return json_decode($json, true);
    }
    
    public function getNewsletterStats() {
        $url = $this->baseUrl . '/newsletters/stats';
        $json = file_get_contents($url);
        return json_decode($json, true)['data'];
    }
}

// Usage
$api = new IUHMApi();
$news = $api->getLatestNews(5);

foreach ($news['data'] as $article) {
    echo "<h2>" . htmlspecialchars($article['title']) . "</h2>";
    echo "<p>" . htmlspecialchars($article['excerpt']) . "</p>";
}
?>
```

### Laravel Integration
```php
<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class IUHMService {
    private $baseUrl = 'https://iuhm.example.com/api/v1';
    
    public function getLatestNews($limit = 10) {
        return Http::get("{$this->baseUrl}/news/latest", [
            'limit' => $limit
        ])->json()['data'];
    }
    
    public function getDeliverables($category = null) {
        $url = $category 
            ? "{$this->baseUrl}/deliverables/category/{$category}"
            : "{$this->baseUrl}/deliverables";
            
        return Http::get($url)->json()['data'];
    }
}

// In controller
class PageController {
    public function home(IUHMService $iuhm) {
        return view('home', [
            'news' => $iuhm->getLatestNews(5),
            'deliverables' => $iuhm->getDeliverables()
        ]);
    }
}
```

---

## WordPress

### Plugin Helper
```php
<?php
/**
 * Plugin Name: IUHM Content Integration
 * Description: Integrate IUHM content into WordPress
 */

class IUHM_Integration {
    private $api_url = 'https://iuhm.example.com/api/v1';
    
    public function shortcode_news($atts) {
        $atts = shortcode_atts([
            'limit' => 5,
            'category' => ''
        ], $atts);
        
        $url = $this->api_url . '/news/latest?limit=' . $atts['limit'];
        $response = wp_remote_get($url);
        $news = json_decode(wp_remote_retrieve_body($response), true);
        
        ob_start();
        ?>
        <div class="iuhm-news-feed">
            <?php foreach ($news['data'] as $item): ?>
                <article class="iuhm-news-item">
                    <h3><?php echo esc_html($item['title']); ?></h3>
                    <?php if ($item['image']): ?>
                        <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title']); ?>" />
                    <?php endif; ?>
                    <p><?php echo esc_html($item['excerpt']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function init() {
        add_shortcode('iuhm_news', [$this, 'shortcode_news']);
    }
}

$iuhm = new IUHM_Integration();
add_action('init', [$iuhm, 'init']);
?>
```

### Use in WordPress Template
```php
<!-- In your theme template -->
<?php echo do_shortcode('[iuhm_news limit="10"]'); ?>
```

---

## Python

### Requests Library
```python
import requests
from datetime import datetime

class IUHMClient:
    def __init__(self, base_url='https://iuhm.example.com/api/v1'):
        self.base_url = base_url
        self.session = requests.Session()
    
    def get_latest_news(self, limit=10):
        response = self.session.get(
            f'{self.base_url}/news/latest',
            params={'limit': limit}
        )
        return response.json()['data']
    
    def search_deliverables(self, query):
        response = self.session.get(
            f'{self.base_url}/deliverables/search',
            params={'q': query}
        )
        return response.json()['data']
    
    def get_newsletter_stats(self):
        response = self.session.get(f'{self.base_url}/newsletters/stats')
        return response.json()['data']

# Usage
client = IUHMClient()
news = client.get_latest_news(5)

for article in news:
    print(f"Title: {article['title']}")
    print(f"Views: {article['views_count']}")
    print(f"Published: {article['published_at']}")
    print("---")
```

### Flask Integration
```python
from flask import Flask, render_template
import requests

app = Flask(__name__)

class IUHM:
    BASE_URL = 'https://iuhm.example.com/api/v1'
    
    @staticmethod
    def fetch(endpoint):
        response = requests.get(f'{IUHM.BASE_URL}/{endpoint}')
        return response.json()

@app.route('/')
def home():
    news_data = IUHM.fetch('news/latest?limit=5')
    return render_template('home.html', news=news_data['data'])

if __name__ == '__main__':
    app.run(debug=True)
```

---

## C# / .NET

### HTTP Client Wrapper
```csharp
using System.Net.Http;
using System.Threading.Tasks;
using Newtonsoft.Json;

public class IUHMApiClient {
    private readonly HttpClient _httpClient;
    private const string BaseUrl = "https://iuhm.example.com/api/v1";
    
    public IUHMApiClient() {
        _httpClient = new HttpClient();
    }
    
    public async Task<NewsResponse> GetLatestNewsAsync(int limit = 10) {
        var response = await _httpClient.GetAsync($"{BaseUrl}/news/latest?limit={limit}");
        var content = await response.Content.ReadAsStringAsync();
        return JsonConvert.DeserializeObject<NewsResponse>(content);
    }
    
    public async Task<DeliverablesResponse> SearchDeliverablesAsync(string query) {
        var encodedQuery = Uri.EscapeDataString(query);
        var response = await _httpClient.GetAsync($"{BaseUrl}/deliverables/search?q={encodedQuery}");
        var content = await response.Content.ReadAsStringAsync();
        return JsonConvert.DeserializeObject<DeliverablesResponse>(content);
    }
}

// Models
public class NewsResponse {
    public string Status { get; set; }
    public NewsItem[] Data { get; set; }
}

public class NewsItem {
    public int Id { get; set; }
    public string Title { get; set; }
    public string Excerpt { get; set; }
    public string Content { get; set; }
    public string Image { get; set; }
    public DateTime PublishedAt { get; set; }
    public int ViewsCount { get; set; }
}

// Usage
var client = new IUHMApiClient();
var newsResponse = await client.GetLatestNewsAsync(5);

foreach (var news in newsResponse.Data) {
    Console.WriteLine($"{news.Title} - {news.ViewsCount} views");
}
```

---

## Error Handling (All Frameworks)

```javascript
// Standard error handling pattern
async function safeApiCall(url) {
  try {
    const response = await fetch(url);
    
    if (!response.ok) {
      throw new Error(`HTTP Error: ${response.status}`);
    }
    
    const data = await response.json();
    
    if (data.status !== 'success') {
      throw new Error(data.message || 'Unknown error');
    }
    
    return data.data;
  } catch (error) {
    console.error('API Error:', error);
    return null;
  }
}
```

---

## Caching Best Practices

```javascript
// Simple client-side cache
const apiCache = new Map();
const CACHE_TTL = 5 * 60 * 1000; // 5 minutes

async function cachedFetch(url) {
  const cached = apiCache.get(url);
  if (cached && Date.now() - cached.timestamp < CACHE_TTL) {
    return cached.data;
  }
  
  const response = await fetch(url);
  const data = await response.json();
  
  apiCache.set(url, { data, timestamp: Date.now() });
  return data;
}
```

---

For more examples or specific framework integrations, contact the development team.
