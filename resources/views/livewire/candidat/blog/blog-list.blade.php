@php
    $isArabic = str_starts_with(app()->getLocale(), 'ar');
    $tr = fn (string $fr, string $ar) => $isArabic ? $ar : $fr;
@endphp

<div @if($isArabic) dir="rtl" @endif class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-5xl mx-auto">
        
        {{-- Toolbar --}}
        <div class="flex flex-col sm:flex-row gap-4 mb-8">
            <div class="flex-1">
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg" @if($isArabic) style="left: auto; right: 12px;" @endif></i>
                    <input type="text" wire:model.live="search" 
                           class="w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-gray-200 text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                           style="font-size: 0.875rem; height: 44px;"
                           placeholder="{{ $tr('Rechercher un article...', 'ابحث عن مقال...') }}"
                           @if($isArabic) style="padding-left: 1rem; padding-right: 2.5rem;" @endif>
                </div>
            </div>
            <div class="w-full sm:w-auto">
                <select wire:model.live="category" 
                        class="w-full px-4 py-3 rounded-xl bg-white border border-gray-200 text-gray-900 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all appearance-none bg-no-repeat bg-right pr-10"
                        style="font-size: 0.875rem; height: 44px; background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z'/%3E%3C/svg%3E\"); background-position: right 0.75rem center; background-size: 1.25rem;">
                    <option value="all">{{ $tr('Toutes les catégories', 'كل الفئات') }}</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Posts Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($posts as $post)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                @if($post->image)
                <img src="{{ Str::startsWith($post->image, ['http','https','/storage']) ? $post->image : asset($post->image) }}" class="w-full h-48 object-cover" alt="{{ $post->title }}">
                @else
                <div class="bg-gray-100 w-full h-48 flex items-center justify-center">
                    <i class="ri-article-line text-5xl text-gray-300"></i>
                </div>
                @endif
                <div class="p-5 flex flex-col">
                    @if($post->category)
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 mb-3 align-self-start">{{ $post->category }}</span>
                    @endif
                    <h5 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2">{{ $post->title }}</h5>
                    @if($post->excerpt)
                    <p class="text-gray-500 text-sm leading-relaxed grow">{{ Str::limit($post->excerpt, 120) }}</p>
                    @endif
                    <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-100">
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="ri-calendar-line"></i>{{ $post->published_at->format('d/m/Y') }}
                        </span>
                        <a href="{{ route('user.blog.show', $post->slug) }}" 
                           class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-sm font-medium border border-green-600 text-green-600 hover:bg-green-50 transition-colors">
                            {{ $tr('Lire plus', 'اقرأ المزيد') }} <i class="ri-arrow-right-line"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <i class="ri-article-line text-7xl text-gray-300"></i>
                <h5 class="text-gray-500 mt-4 text-lg">{{ $tr('Aucun article disponible', 'لا توجد مقالات متاحة') }}</h5>
                <p class="text-gray-400 mt-1">{{ $tr('Revenez plus tard pour découvrir nos actualités.', 'عد لاحقًا لاكتشاف آخر الأخبار.') }}</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
</div>