@extends('layouts.site')

@section('title', __('ui.services_page_title').' | '.config('app.name'))

@section('content')
<div class="bg-white">

    <!-- Hero Section -->
    <!-- Added a very soft radial gradient to match the header lighting in the design -->
    <section class="relative overflow-hidden bg-white pt-20 pb-20 sm:pt-28 sm:pb-32" style="background: radial-gradient(circle at 100% 0%, #f0fdf4 0%, transparent 40%), radial-gradient(circle at 0% 100%, #f1f5f9 0%, transparent 40%);">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
            <h1 class="max-w-4xl text-[3.5rem] leading-[1.1] font-bold tracking-tight text-[#0f172a] sm:text-6xl lg:text-[4.5rem]">
                Au service de notre<br>
                <span class="text-[#10752d]">communauté</span> urbaine.
            </h1>
            <p class="mt-8 max-w-2xl text-lg text-slate-600 leading-relaxed font-medium">
                Nous déployons des solutions concrètes pour améliorer la qualité de vie des résidents, en favorisant l'inclusion, l'éducation et le bien-être social à travers des programmes dédiés.
            </p>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="bg-[#FAFAFC] py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative inline-block mb-12">
                <h2 class="text-3xl font-bold tracking-tight text-[#0f172a] sm:text-4xl">Nos Services</h2>
                <!-- Green underline exact match -->
                <div class="absolute -bottom-3 left-0 h-1.5 w-12 bg-[#10752d] rounded-full"></div>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4 items-stretch">
                <!-- Education -->
                <div class="bg-white rounded-2xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] flex flex-col items-start transition-transform hover:-translate-y-1">
                    <div class="flex h-[3.25rem] w-[3.25rem] items-center justify-center rounded-xl bg-[#eef2ff] text-[#1e3a8a] mb-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                    </div>
                    <h3 class="text-[1.15rem] font-bold text-[#0f172a] mb-3">Éducation</h3>
                    <p class="text-sm font-medium text-slate-500 leading-relaxed">
                        Soutien scolaire, ateliers numériques et formation continue pour tous les âges au sein du quartier.
                    </p>
                </div>

                <!-- Santé -->
                <div class="bg-white rounded-2xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] flex flex-col items-start transition-transform hover:-translate-y-1">
                    <div class="flex h-[3.25rem] w-[3.25rem] items-center justify-center rounded-xl bg-[#eef2ff] text-[#1e3a8a] mb-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v4m-2-2h4" />
                        </svg>
                    </div>
                    <h3 class="text-[1.15rem] font-bold text-[#0f172a] mb-3">Santé</h3>
                    <p class="text-sm font-medium text-slate-500 leading-relaxed">
                        Accès aux soins de proximité, prévention et accompagnement psychologique pour les plus vulnérables.
                    </p>
                </div>

                <!-- Sécurité Alimentaire -->
                <div class="bg-white rounded-2xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] flex flex-col items-start transition-transform hover:-translate-y-1">
                    <div class="flex h-[3.25rem] w-[3.25rem] items-center justify-center rounded-xl bg-[#eef2ff] text-[#1e3a8a] mb-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="12" r="8" stroke-width="2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v4m0 0a2 2 0 100 4 2 2 0 000-4z" />
                        </svg>
                    </div>
                    <h3 class="text-[1.15rem] font-bold text-[#0f172a] mb-3">Sécurité Alimentaire</h3>
                    <p class="text-sm font-medium text-slate-500 leading-relaxed">
                        Distribution de paniers solidaires et ateliers de cuisine saine pour lutter contre la précarité.
                    </p>
                </div>

                <!-- Intégration Sociale -->
                <div class="bg-white rounded-2xl p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] flex flex-col items-start transition-transform hover:-translate-y-1">
                    <div class="flex h-[3.25rem] w-[3.25rem] items-center justify-center rounded-xl bg-[#eef2ff] text-[#1e3a8a] mb-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-[1.15rem] font-bold text-[#0f172a] mb-3">Intégration Sociale</h3>
                    <p class="text-sm font-medium text-slate-500 leading-relaxed">
                        Accompagnement administratif et médiation culturelle pour favoriser le vivre-ensemble.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Appointment Form -->
    <section class="bg-white py-20 sm:py-28 text-[#0f172a]">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-[1fr_1.2fr] gap-12 lg:gap-20 items-stretch">
                <!-- Left Content -->
                <div class="flex flex-col justify-start max-w-md pt-4">
                    <h2 class="text-[2.5rem] font-bold tracking-tight leading-[1.1] mb-6">Prendre un Rendez-vous</h2>
                    <p class="text-base text-slate-600 font-medium leading-relaxed mb-12">
                        Remplissez ce formulaire pour être contacté par l'un de nos conseillers. Nous nous engageons à vous répondre sous 48 heures ouvrées.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#f0fdf4]">
                                <svg class="h-5 w-5 text-[#10752d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <span class="font-bold text-[0.95rem]">+33 (0) 1 23 45 67 89</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#f0fdf4]">
                                <svg class="h-5 w-5 text-[#10752d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="font-bold text-[0.95rem]">12 Avenue du Progrès, Paris</span>
                        </div>
                    </div>
                </div>

                <!-- Right Form Box -->
                @livewire('contact.contact-form')
            </div>
        </div>
    </section>

    <!-- Bottom CTAs -->
    <section class="bg-white py-20 pb-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid mg:grid-cols-1 lg:grid-cols-[1.6fr_1fr] gap-6 lg:gap-8 items-stretch">
                
                <!-- Restez informés Card -->
                <div class="relative overflow-hidden rounded-[2rem] bg-[#0c132b] text-white p-10 md:p-14 min-h-[300px] flex flex-col justify-center">
                    <!-- Background image with blue multiply blending -->
                    <div class="absolute inset-0 z-0">
                        <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=1000&q=80" alt="Community" class="w-full h-full object-cover opacity-60 mix-blend-overlay">
                        <div class="absolute inset-0 bg-[#0c132b]/80"></div>
                    </div>
                    
                    <div class="relative z-10 max-w-xl">
                        <h3 class="text-[2rem] leading-tight font-bold mb-3">Restez informés</h3>
                        <p class="text-[1.05rem] text-white/80 font-medium leading-relaxed mb-8 max-w-md">
                            Recevez mensuellement nos actualités et l'impact de nos actions dans votre boîte mail.
                        </p>
                        @livewire('newsletter.subscribe-form')
                    </div>
                </div>

                <!-- Devenir Bénévole Card -->
                <div class="rounded-[2rem] bg-[#8efb8e] p-10 md:p-14 flex flex-col justify-center">
                    <div class="text-[#0e5c21] mb-5">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9l-2 2m0 0l-2-2m2 2V3" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#0c132b] mb-3">Devenir Bénévole</h3>
                    <p class="text-sm text-[#0c132b]/70 font-medium leading-relaxed">
                        Rejoignez notre équipe de terrain pour avoir un impact direct sur le quotidien des urbains.
                    </p>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection
