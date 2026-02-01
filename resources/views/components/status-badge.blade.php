@props(['status'])

@php
    $colors = [
        'draft' => 'bg-gray-100 text-gray-800',
        'submitted' => 'bg-blue-100 text-blue-800',
        'in_review' => 'bg-yellow-100 text-yellow-800',
        'approved' => 'bg-green-100 text-green-800',
        'rejected' => 'bg-red-100 text-red-800',
    ];
    
    $labels = [
        'draft' => 'Draft',
        'submitted' => 'Submitted',
        'in_review' => 'In Review',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];
    
    $color = $colors[$status] ?? 'bg-gray-100 text-gray-800';
    $label = $labels[$status] ?? ucfirst($status);
@endphp

<span {{ $attributes->merge(['class' => "px-2 py-1 text-xs font-semibold rounded-full {$color}"]) }}>
    {{ $label }}
</span>
