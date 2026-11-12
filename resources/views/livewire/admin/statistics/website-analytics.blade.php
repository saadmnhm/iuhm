<div class="space-y-6">

    {{-- ── Header ─────────────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-[#0f2441] to-[#15304a] text-white p-7 rounded-xl shadow-lg flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
        <div>
            <h2 class="text-2xl font-bold flex items-center gap-2">
                <i class="ri-bar-chart-box-line"></i> Statistiques du site
            </h2>
            <p class="text-sm text-white/70 mt-1">
                Articles, actualités, projets et consultations du site public.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="bg-white/10 rounded-lg px-4 py-2 text-sm">
                <span class="text-white/60">Publications sur la période</span>
                <span class="font-bold ml-2">{{ number_format($periodTotal) }}</span>
                @if($growth !== 0)
                    <span class="ml-2 text-xs font-bold {{ $growth > 0 ? 'text-emerald-300' : 'text-rose-300' }}">
                        {{ $growth > 0 ? '↑' : '↓' }} {{ abs($growth) }} %
                    </span>
                @endif
            </div>

            <div class="flex items-center gap-1 bg-white/10 rounded-lg p-1">
                @foreach([6, 12, 24] as $option)
                    <button wire:click="$set('months', {{ $option }})"
                            class="px-3 py-1.5 rounded-md text-xs font-bold transition {{ $months === $option ? 'bg-white text-[#0f2441]' : 'text-white/70 hover:text-white' }}">
                        {{ $option }} mois
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── KPI cards ───────────────────────────────────────────────────── --}}
    @php
        $totalViews = $kpi['articles']['views'] + $kpi['actualites']['views'] + $kpi['projets']['views'];
        $cards = [
            ['label' => 'Articles', 'value' => number_format($kpi['articles']['total']), 'sub' => $kpi['articles']['published'].' publiés · '.number_format($kpi['articles']['views']).' vues', 'icon' => 'ri-article-line', 'color' => 'bg-blue-50 text-blue-600'],
            ['label' => 'Actualités', 'value' => number_format($kpi['actualites']['total']), 'sub' => $kpi['actualites']['published'].' publiées · '.number_format($kpi['actualites']['views']).' vues', 'icon' => 'ri-newspaper-line', 'color' => 'bg-emerald-50 text-emerald-600'],
            ['label' => 'Projets', 'value' => number_format($kpi['projets']['total']), 'sub' => $kpi['projets']['published'].' publiés · '.number_format($kpi['projets']['views']).' vues', 'icon' => 'ri-trello-line', 'color' => 'bg-violet-50 text-violet-600'],
            ['label' => 'Pages', 'value' => number_format($kpi['pages']['total']), 'sub' => $kpi['pages']['published'].' publiées', 'icon' => 'ri-pages-line', 'color' => 'bg-amber-50 text-amber-600'],
            ['label' => 'Livrables', 'value' => number_format($kpi['livrables']['total']), 'sub' => $kpi['livrables']['published'].' publiés · '.number_format($kpi['livrables']['downloads']).' téléchargements', 'icon' => 'ri-file-download-line', 'color' => 'bg-cyan-50 text-cyan-600'],
            ['label' => 'Newsletter', 'value' => number_format($kpi['newsletter']['total']), 'sub' => $kpi['newsletter']['active'].' abonnés actifs', 'icon' => 'ri-mail-send-line', 'color' => 'bg-pink-50 text-pink-600'],
            ['label' => 'Messages contact', 'value' => number_format($kpi['contacts']['total']), 'sub' => $kpi['contacts']['unread'].' non lus', 'icon' => 'ri-message-3-line', 'color' => 'bg-orange-50 text-orange-600'],
            ['label' => 'Vues totales', 'value' => number_format($totalViews), 'sub' => 'articles + actualités + projets', 'icon' => 'ri-eye-line', 'color' => 'bg-indigo-50 text-indigo-600'],
        ];
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($cards as $card)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide truncate">{{ $card['label'] }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $card['value'] }}</p>
                        <p class="text-[11px] text-gray-400 mt-1 truncate">{{ $card['sub'] }}</p>
                    </div>
                    <div class="w-10 h-10 shrink-0 rounded-lg {{ $card['color'] }} flex items-center justify-center">
                        <i class="{{ $card['icon'] }} text-lg"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ── Charts ──────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Publications per month --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <div>
                    <h3 class="text-base font-bold text-[#04103A]">Publications par mois</h3>
                    <p class="text-xs text-gray-400">Articles, actualités et projets sur {{ $months }} mois</p>
                </div>
                <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-gray-500">
                    <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#214f95]"></span>Articles</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#23803B]"></span>Actualités</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#8b5cf6]"></span>Projets</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <div class="flex items-end gap-2 sm:gap-3 min-w-max h-48">
                    @foreach($monthLabels as $index => $label)
                        @php
                            $values = [$articleSeries[$index], $newsSeries[$index], $projectSeries[$index]];
                            $colors = ['#214f95', '#23803B', '#8b5cf6'];
                            $total = array_sum($values);
                        @endphp
                        <div class="flex flex-col items-center gap-2 w-8 sm:w-10">
                            <div class="flex items-end gap-1 h-40">
                                @foreach($values as $seriesIndex => $value)
                                    <div class="w-2 sm:w-2.5 rounded-t-sm {{ $total > 0 ? '' : 'opacity-20' }}"
                                         style="background-color: {{ $colors[$seriesIndex] }}; height: {{ $value > 0 ? max(6, round($value / $monthlyMax * 100)) : 2 }}%;"
                                         title="{{ $label }} · {{ ['Articles', 'Actualités', 'Projets'][$seriesIndex] }} : {{ $value }}">
                                    </div>
                                @endforeach
                            </div>
                            <span class="text-[10px] font-semibold text-gray-400 {{ $months > 12 && $loop->iteration % 2 !== 0 ? 'invisible' : '' }}">
                                {{ $label }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Categories --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-base font-bold text-[#04103A]">Répartition par catégorie</h3>
            <p class="text-xs text-gray-400 mb-5">Articles et actualités publiés</p>

            <div class="space-y-4">
                @forelse($categoryLabels as $index => $label)
                    @php $value = $categoryValues[$index]; @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="font-semibold text-gray-700 truncate">{{ $label }}</span>
                            <span class="text-gray-400 font-bold ml-2">{{ $value }}</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-[#214f95] to-[#82E682] transition-all"
                                 style="width: {{ round($value / $categoryMax * 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-6">Aucune catégorie pour le moment.</p>
                @endforelse
            </div>
        </div>

        {{-- Project statuses + content distribution --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-base font-bold text-[#04103A]">Statuts des projets</h3>
                <p class="text-xs text-gray-400 mb-4">{{ number_format($statusTotal) }} projet(s)</p>

                <div class="space-y-3">
                    @foreach($statusRows as $status)
                        <div class="flex items-center gap-3">
                            <span class="w-32 shrink-0 text-xs font-semibold text-gray-600 truncate">{{ $status['label'] }}</span>
                            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $status['bar'] }}" style="width: {{ $status['percent'] }}%"></div>
                            </div>
                            <span class="w-14 shrink-0 text-right text-xs font-bold text-gray-500">{{ $status['count'] }} · {{ $status['percent'] }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>

            @php
                $segments = [];
                $cursor = 0;
                foreach ($distribution as $segment) {
                    $percent = $distributionTotal > 0 ? round($segment['value'] / $distributionTotal * 100) : 0;
                    $segments[] = $segment['color'].' '.$cursor.'% '.($cursor + $percent).'%';
                    $cursor += $percent;
                }
                $gradient = 'conic-gradient('.implode(', ', $segments).')';
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-base font-bold text-[#04103A]">Répartition du contenu publié</h3>
                <p class="text-xs text-gray-400 mb-4">Répartition entre articles, actualités et projets</p>

                <div class="flex items-center gap-5">
                    <div class="relative w-28 h-28 shrink-0 rounded-full" style="{{ $distributionTotal > 0 ? 'background: '.$gradient : 'background: conic-gradient(#e5e7eb 0% 100%)' }}">
                        <div class="absolute inset-4 bg-white rounded-full flex flex-col items-center justify-center">
                            <span class="text-xl font-bold text-[#04103A]">{{ number_format($distributionTotal) }}</span>
                            <span class="text-[10px] text-gray-400 uppercase font-semibold">contenus</span>
                        </div>
                    </div>

                    <ul class="space-y-2 text-sm">
                        @foreach($distribution as $segment)
                            <li class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $segment['color'] }}"></span>
                                <span class="text-gray-600">{{ $segment['label'] }}</span>
                                <span class="font-bold text-gray-900 ml-auto pl-4">{{ number_format($segment['value']) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Top content --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-[#04103A]">Contenus les plus consultés</h3>
                    <p class="text-xs text-gray-400">Classés par nombre de vues</p>
                </div>
                <span class="text-xs font-semibold text-gray-400">{{ $topContent->count() }} résultat(s)</span>
            </div>

            <div class="overflow-x-auto -mx-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wider text-gray-400 border-b border-gray-100">
                            <th class="px-5 py-3 font-semibold w-12">#</th>
                            <th class="px-5 py-3 font-semibold">Contenu</th>
                            <th class="px-5 py-3 font-semibold">Type</th>
                            <th class="px-5 py-3 font-semibold">Date</th>
                            <th class="px-5 py-3 font-semibold text-right">Vues</th>
                            <th class="px-5 py-3 font-semibold text-right">Lien</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topContent as $index => $item)
                            <tr class="border-b border-gray-50 hover:bg-gray-50/60 transition">
                                <td class="px-5 py-3">
                                    <span class="inline-flex w-6 h-6 items-center justify-center rounded-full text-xs font-bold {{ $index < 3 ? 'bg-[#DCFCE7] text-[#066E1B]' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 font-semibold text-gray-800 max-w-md">
                                    <span class="line-clamp-1">{{ $item['title'] }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full border px-2.5 py-0.5 text-[11px] font-bold {{ $item['badge'] }}">
                                        {{ $item['type'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $item['date']?->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-3 text-right font-bold text-gray-900">{{ number_format($item['views']) }}</td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ $item['url'] }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center gap-1 text-xs font-bold text-[#066E1B] hover:underline">
                                        Voir <i class="ri-external-link-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">
                                    Aucun contenu publié pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent publications --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-[#04103A]">Dernières publications</h3>
                    <p class="text-xs text-gray-400">8 contenus les plus récents</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @forelse($recentContent as $item)
                    <a href="{{ $item['url'] }}" target="_blank" rel="noopener"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:border-[#82E682] hover:bg-[#F7FBF7] transition group">
                        <div class="w-8 h-8 shrink-0 rounded-lg bg-[#F5F3F7] flex items-center justify-center text-gray-500 group-hover:bg-[#DCFCE7] group-hover:text-[#066E1B] transition">
                            <i class="ri-article-line text-sm"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-800 line-clamp-1">{{ $item['title'] }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">
                                <span class="font-semibold text-[#066E1B]">{{ $item['type'] }}</span>
                                · {{ $item['date']?->translatedFormat('d M Y') }}
                            </p>
                        </div>
                        <i class="ri-arrow-right-s-line text-gray-300 group-hover:text-[#066E1B] shrink-0"></i>
                    </a>
                @empty
                    <p class="col-span-full text-sm text-gray-400 text-center py-6">Aucun contenu récent.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
