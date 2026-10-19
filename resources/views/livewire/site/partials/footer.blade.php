<footer class="mt-16 border-t border-slate-200 bg-[#dde3ec] text-[#36538a]">
    <div class="iu-container py-14">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <h3 class="text-xl font-bold text-[#173466]">{{ __('ui.brand_name') }}</h3>
                <p class="mt-4 max-w-xs text-sm leading-7 text-[#4e6a99]">{{ __('ui.footer_tagline') }}</p>
            </div>

            <div>
                <h4 class="text-xs font-bold uppercase tracking-[0.16em] text-[#173466]">{{ __('ui.navigation') }}</h4>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="{{ route('history.index') }}" class="transition hover:text-[#112b56]">{{ __('ui.history') }}</a></li>
                    <li><a href="{{ route('actualite.index') }}" class="transition hover:text-[#112b56]">{{ __('ui.nav_news') }}</a></li>
                    <li><a href="{{ route('services.index') }}" class="transition hover:text-[#112b56]">{{ __('ui.nav_services') }}</a></li>
                    <li><a href="{{ route('home') }}#trust" class="transition hover:text-[#112b56]">{{ __('ui.partners') }}</a></li>
                    <li><a href="{{ route('actualite.index') }}" class="transition hover:text-[#112b56]">{{ __('ui.nav_articles') }}</a></li>
                    <li><a href="{{ route('home') }}#contact" class="transition hover:text-[#112b56]">{{ __('ui.nav_deliverables') }}</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-bold uppercase tracking-[0.16em] text-[#173466]">{{ __('ui.informations') }}</h4>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="#" class="transition hover:text-[#112b56]">{{ __('ui.privacy_policy') }}</a></li>
                    <li><a href="#" class="transition hover:text-[#112b56]">{{ __('ui.terms_of_service') }}</a></li>
                    <li><a href="#" class="transition hover:text-[#112b56]">{{ __('ui.press_kit') }}</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-bold uppercase tracking-[0.16em] text-[#173466]">{{ __('ui.engagement') }}</h4>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="#" class="transition hover:text-[#112b56]">{{ __('ui.volunteer') }}</a></li>
                    <li><a href="#" class="transition hover:text-[#112b56]">{{ __('ui.join_us') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="border-t border-slate-300/70 py-6 text-center text-sm text-[#5673a6]">
        &copy; {{ now()->year }} {{ __('ui.brand_name') }}. {{ __('ui.all_rights_reserved') }}
    </div>
</footer>
