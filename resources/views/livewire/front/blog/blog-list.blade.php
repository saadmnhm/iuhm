@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif>
    {{-- Toolbar --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="ri-search-line"></i></span>
                <input type="text" wire:model.live="search" class="form-control" placeholder="{{ $tr('Rechercher un article...', 'ابحث عن مقال...') }}">
            </div>
        </div>
        <div class="col-md-4">
            <select wire:model.live="category" class="form-select">
                <option value="all">{{ $tr('Toutes les catégories', 'كل الفئات') }}</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Posts Grid --}}
    <div class="row g-4">
        @forelse($posts as $post)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                @if($post->image)
                <img src="{{ Str::startsWith($post->image, ['http','https','/storage']) ? $post->image : asset($post->image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 180px; object-fit: cover;">
                @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                    <i class="ri-article-line" style="font-size: 3rem; color: #ccc;"></i>
                </div>
                @endif
                <div class="card-body d-flex flex-column">
                    @if($post->category)
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-2 align-self-start">{{ $post->category }}</span>
                    @endif
                    <h5 class="card-title fw-bold">{{ $post->title }}</h5>
                    @if($post->excerpt)
                    <p class="card-text text-muted small grow">{{ Str::limit($post->excerpt, 120) }}</p>
                    @endif
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <small class="text-muted">
                            <i class="ri-calendar-line me-1"></i>{{ $post->published_at->format('d/m/Y') }}
                        </small>
                        <a href="{{ route('user.blog.show', $post->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                            {{ $tr('Lire plus', 'اقرأ المزيد') }} <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="ri-article-line" style="font-size: 4rem; color: #ddd;"></i>
                <h5 class="text-muted mt-3">{{ $tr('Aucun article disponible', 'لا توجد مقالات متاحة') }}</h5>
                <p class="text-muted">{{ $tr('Revenez plus tard pour découvrir nos actualités.', 'عد لاحقًا لاكتشاف آخر الأخبار.') }}</p>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $posts->links() }}</div>
</div>
