<?php

namespace Tests\Unit;

use App\Models\Candidat;
use Tests\TestCase;

class CandidatProfileCompletionTest extends TestCase
{
    /**
     * Build an unsaved candidat with a complete profile.
     */
    private function candidat(array $overrides = []): Candidat
    {
        return new Candidat(array_merge([
            'nom' => 'Doe',
            'prenom' => 'Jane',
            'email' => 'jane@example.com',
            'cin' => 'AB123456',
            'date_naissance' => '2000-01-01',
            'phone' => '0612345678',
            'gender' => 'femme',
            'niveau_etude' => 'Licence',
            'specialite' => 'Informatique',
            'selected_region' => 'Casablanca-Settat',
            'selected_city' => 'Casablanca',
            'selected_prefecture' => 'Casablanca',
            'address_detail' => '12 rue',
        ], $overrides));
    }

    public function test_profile_is_complete_when_all_required_fields_are_filled(): void
    {
        $candidat = $this->candidat();

        $this->assertTrue($candidat->isProfileComplete());
        $this->assertSame([], $candidat->missingProfileFields());
    }

    public function test_profile_is_incomplete_when_a_required_field_is_missing(): void
    {
        $candidat = $this->candidat(['phone' => null]);

        $this->assertFalse($candidat->isProfileComplete());
        $this->assertContains('phone', $candidat->missingProfileFields());
    }

    public function test_missing_fields_lists_every_empty_required_field(): void
    {
        $candidat = $this->candidat([
            'phone' => null,
            'address_detail' => '',
        ]);

        $this->assertEqualsCanonicalizing(
            ['phone', 'address_detail'],
            $candidat->missingProfileFields()
        );
    }
}
