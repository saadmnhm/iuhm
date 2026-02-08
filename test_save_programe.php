<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ProgrameList;
use App\Models\Address;

echo "Testing ProgrameList save...\n\n";

// Check addresses exist
$addresses = Address::all();
echo "Addresses found: " . $addresses->count() . "\n";
foreach ($addresses as $addr) {
    echo "  - ID: {$addr->id}, {$addr->address_line1}, {$addr->city}\n";
}
echo "\n";

// Try to create a project
try {
    $data = [
        'project_name' => 'Test Project',
        'description' => 'This is a test description',
        'slug' => 'test-project',
        'icon' => 'ri-file-list-3-line',
        'color' => '#2f5496',
        'bg_color' => '#ffffff',
        'min_age' => 18,
        'max_age' => 35,
        'allowed_address_id' => json_encode([]),
        'form_attached_id' => null,
        'sort_order' => 0,
        'is_active' => true,
    ];
    
    echo "Attempting to create project with data:\n";
    print_r($data);
    echo "\n";
    
    $project = ProgrameList::create($data);
    
    echo "✓ Project created successfully!\n";
    echo "  ID: {$project->id}\n";
    echo "  Name: {$project->project_name}\n";
    echo "  Slug: {$project->slug}\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . "\n";
    echo "  Line: " . $e->getLine() . "\n";
}
