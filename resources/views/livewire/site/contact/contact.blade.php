@extends('layouts.site')

@section('title', __('ui.contact_page_title').' | '.config('app.name'))

@section('content')
    <section id="contact" class="bg-[#FBF8FD] py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#2d4b86]">{{ __('ui.contact_page_label') ?? 'Contact' }}</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-[#16254d] sm:text-4xl">{{ __('ui.contact_page_title') }}</h2>
                <p class="mt-4 mx-auto max-w-xl text-sm leading-7 text-slate-600">{{ __('ui.contact_page_description') }}</p>
            </div>

            <div class="grid gap-8 lg:grid-cols-5">
                {{-- Contact Info + Map --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="rounded-[1.75rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/40">
                        <div class="space-y-4 text-sm text-slate-600">
                            <div class="flex gap-3">
                                <span class="mt-0.5 inline-flex size-9 items-center justify-center rounded-xl bg-[#EEF2FF] text-[#2d4b86] flex-shrink-0">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M12 21s7-5.4 7-11a7 7 0 1 0-14 0c0 5.6 7 11 7 11Z" stroke="currentColor" stroke-width="1.6" />
                                        <circle cx="12" cy="10" r="2.3" stroke="currentColor" stroke-width="1.6" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-semibold text-[#16254d]">{{ __('ui.contact_address_label') }}</p>
                                    <p class="mt-0.5 text-slate-500">{{ __('ui.contact_address') }}</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="mt-0.5 inline-flex size-9 items-center justify-center rounded-xl bg-[#EEF2FF] text-[#2d4b86] flex-shrink-0">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M5 4h4l2 6-3 2c1.2 2.4 3.1 4.3 5.5 5.5l2-3 6 2v4c0 1.1-.9 2-2 2C11.6 22 2 12.4 2 6c0-1.1.9-2 2-2h1Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-semibold text-[#16254d]">{{ __('ui.contact_phone_label') }}</p>
                                    <p class="mt-0.5 text-slate-500">{{ __('ui.contact_phone') }}</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="mt-0.5 inline-flex size-9 items-center justify-center rounded-xl bg-[#EEF2FF] text-[#2d4b86] flex-shrink-0">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M4 6h16v12H4z" stroke="currentColor" stroke-width="1.6" />
                                        <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-semibold text-[#16254d]">{{ __('ui.contact_email_label') }}</p>
                                    <p class="mt-0.5 text-slate-500">{{ __('ui.contact_email') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-[1.75rem] shadow-xl shadow-slate-200/40" style="height:280px">
                        <iframe
                            src="{{ $mapEmbed }}"
                            class="h-full w-full"
                            style="border:0"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Casablanca map"
                        ></iframe>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div class="lg:col-span-3">
                    <div class="rounded-[1.75rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/40 sm:p-8">
                        <h3 class="text-xl font-bold text-[#16254d] mb-6">{{ __('ui.contact_form_title') }}</h3>
                        @livewire('contact.contact-form')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection