<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;

class MoroccoAddressSeeder extends Seeder
{
    public function run(): void
    {
        $addresses = [
            // Casablanca districts
            ['address_line1' => 'Hay Mohammadi', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20000'],
            ['address_line1' => 'Ain Sbaa', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20580'],
            ['address_line1' => 'Roches Noires', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20290'],
            ['address_line1' => 'Sidi Bernoussi', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20600'],
            ['address_line1' => 'Ben M\'Sick', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20400'],
            ['address_line1' => 'Moulay Rachid', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20450'],
            ['address_line1' => 'Sbata', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20250'],
            ['address_line1' => 'Al Fida', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20340'],
            ['address_line1' => 'Mers Sultan', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20100'],
            ['address_line1' => 'Derb Sultan', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20350'],
            ['address_line1' => 'Sidi Moumen', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20400'],
            ['address_line1' => 'Bernoussi', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20600'],
            ['address_line1' => 'Maarif', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20100'],
            ['address_line1' => 'Anfa', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20050'],
            ['address_line1' => 'Oulfa', 'city' => 'Casablanca', 'state' => 'Casablanca-Settat', 'postal_code' => '20290'],

            // Other major cities
            ['address_line1' => 'Rabat', 'city' => 'Rabat', 'state' => 'Rabat-Salé-Kénitra', 'postal_code' => '10000'],
            ['address_line1' => 'Salé', 'city' => 'Salé', 'state' => 'Rabat-Salé-Kénitra', 'postal_code' => '11000'],
            ['address_line1' => 'Marrakech', 'city' => 'Marrakech', 'state' => 'Marrakech-Safi', 'postal_code' => '40000'],
            ['address_line1' => 'Fès', 'city' => 'Fès', 'state' => 'Fès-Meknès', 'postal_code' => '30000'],
            ['address_line1' => 'Tanger', 'city' => 'Tanger', 'state' => 'Tanger-Tétouan-Al Hoceïma', 'postal_code' => '90000'],
            ['address_line1' => 'Meknès', 'city' => 'Meknès', 'state' => 'Fès-Meknès', 'postal_code' => '50000'],
            ['address_line1' => 'Oujda', 'city' => 'Oujda', 'state' => 'Oriental', 'postal_code' => '60000'],
            ['address_line1' => 'Kénitra', 'city' => 'Kénitra', 'state' => 'Rabat-Salé-Kénitra', 'postal_code' => '14000'],
            ['address_line1' => 'Agadir', 'city' => 'Agadir', 'state' => 'Souss-Massa', 'postal_code' => '80000'],
            ['address_line1' => 'Tétouan', 'city' => 'Tétouan', 'state' => 'Tanger-Tétouan-Al Hoceïma', 'postal_code' => '93000'],
            ['address_line1' => 'Mohammedia', 'city' => 'Mohammedia', 'state' => 'Casablanca-Settat', 'postal_code' => '28800'],
            ['address_line1' => 'El Jadida', 'city' => 'El Jadida', 'state' => 'Casablanca-Settat', 'postal_code' => '24000'],
            ['address_line1' => 'Beni Mellal', 'city' => 'Beni Mellal', 'state' => 'Béni Mellal-Khénifra', 'postal_code' => '23000'],
            ['address_line1' => 'Nador', 'city' => 'Nador', 'state' => 'Oriental', 'postal_code' => '62000'],
            ['address_line1' => 'Settat', 'city' => 'Settat', 'state' => 'Casablanca-Settat', 'postal_code' => '26000'],
        ];

        foreach ($addresses as $address) {
            Address::firstOrCreate(
                ['address_line1' => $address['address_line1'], 'city' => $address['city']],
                $address
            );
        }
    }
}
