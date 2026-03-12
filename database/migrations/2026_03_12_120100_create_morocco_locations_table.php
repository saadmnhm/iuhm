<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('morocco_locations', function (Blueprint $table) {
            $table->id();
            $table->string('region', 150);
            $table->string('city', 150);
            $table->string('prefecture', 150);
            $table->timestamps();

            $table->unique(['region', 'city', 'prefecture'], 'morocco_locations_unique');
            $table->index(['region', 'city']);
        });

        $rows = [
            ['region' => 'Casablanca-Settat', 'city' => 'Casablanca', 'prefecture' => 'Aïn Sebaâ-Hay Mohammadi'],
            ['region' => 'Casablanca-Settat', 'city' => 'Casablanca', 'prefecture' => 'Aïn Chock'],
            ['region' => 'Casablanca-Settat', 'city' => 'Casablanca', 'prefecture' => 'Anfa'],
            ['region' => 'Casablanca-Settat', 'city' => 'Casablanca', 'prefecture' => 'Ben M’Sick'],
            ['region' => 'Casablanca-Settat', 'city' => 'Casablanca', 'prefecture' => 'Hay Hassani'],
            ['region' => 'Casablanca-Settat', 'city' => 'Casablanca', 'prefecture' => 'Moulay Rachid'],
            ['region' => 'Casablanca-Settat', 'city' => 'Casablanca', 'prefecture' => 'Sidi Bernoussi'],
            ['region' => 'Casablanca-Settat', 'city' => 'Casablanca', 'prefecture' => 'Al Fida-Mers Sultan'],
            ['region' => 'Casablanca-Settat', 'city' => 'Mohammedia', 'prefecture' => 'Mohammedia'],
            ['region' => 'Casablanca-Settat', 'city' => 'Settat', 'prefecture' => 'Settat'],
            ['region' => 'Casablanca-Settat', 'city' => 'El Jadida', 'prefecture' => 'El Jadida'],
            ['region' => 'Casablanca-Settat', 'city' => 'Berrechid', 'prefecture' => 'Berrechid'],
            ['region' => 'Casablanca-Settat', 'city' => 'Benslimane', 'prefecture' => 'Benslimane'],
            ['region' => 'Casablanca-Settat', 'city' => 'Médiouna', 'prefecture' => 'Médiouna'],
            ['region' => 'Casablanca-Settat', 'city' => 'Sidi Bennour', 'prefecture' => 'Sidi Bennour'],
            ['region' => 'Casablanca-Settat', 'city' => 'Nouaceur', 'prefecture' => 'Nouaceur'],

            ['region' => 'Rabat-Salé-Kénitra', 'city' => 'Rabat', 'prefecture' => 'Rabat'],
            ['region' => 'Rabat-Salé-Kénitra', 'city' => 'Salé', 'prefecture' => 'Salé'],
            ['region' => 'Rabat-Salé-Kénitra', 'city' => 'Skhirate-Témara', 'prefecture' => 'Skhirate-Témara'],
            ['region' => 'Rabat-Salé-Kénitra', 'city' => 'Kénitra', 'prefecture' => 'Kénitra'],
            ['region' => 'Rabat-Salé-Kénitra', 'city' => 'Khémisset', 'prefecture' => 'Khémisset'],
            ['region' => 'Rabat-Salé-Kénitra', 'city' => 'Sidi Kacem', 'prefecture' => 'Sidi Kacem'],
            ['region' => 'Rabat-Salé-Kénitra', 'city' => 'Sidi Slimane', 'prefecture' => 'Sidi Slimane'],

            ['region' => 'Marrakech-Safi', 'city' => 'Marrakech', 'prefecture' => 'Marrakech'],
            ['region' => 'Marrakech-Safi', 'city' => 'Safi', 'prefecture' => 'Safi'],
            ['region' => 'Marrakech-Safi', 'city' => 'Essaouira', 'prefecture' => 'Essaouira'],
            ['region' => 'Marrakech-Safi', 'city' => 'El Kelaâ des Sraghna', 'prefecture' => 'El Kelaâ des Sraghna'],
            ['region' => 'Marrakech-Safi', 'city' => 'Rehamna', 'prefecture' => 'Rehamna'],
            ['region' => 'Marrakech-Safi', 'city' => 'Chichaoua', 'prefecture' => 'Chichaoua'],
            ['region' => 'Marrakech-Safi', 'city' => 'Al Haouz', 'prefecture' => 'Al Haouz'],
            ['region' => 'Marrakech-Safi', 'city' => 'Youssoufia', 'prefecture' => 'Youssoufia'],

            ['region' => 'Fès-Meknès', 'city' => 'Fès', 'prefecture' => 'Fès'],
            ['region' => 'Fès-Meknès', 'city' => 'Meknès', 'prefecture' => 'Meknès'],
            ['region' => 'Fès-Meknès', 'city' => 'Taza', 'prefecture' => 'Taza'],
            ['region' => 'Fès-Meknès', 'city' => 'Sefrou', 'prefecture' => 'Sefrou'],
            ['region' => 'Fès-Meknès', 'city' => 'Ifrane', 'prefecture' => 'Ifrane'],
            ['region' => 'Fès-Meknès', 'city' => 'El Hajeb', 'prefecture' => 'El Hajeb'],
            ['region' => 'Fès-Meknès', 'city' => 'Boulemane', 'prefecture' => 'Boulemane'],
            ['region' => 'Fès-Meknès', 'city' => 'Taounate', 'prefecture' => 'Taounate'],
            ['region' => 'Fès-Meknès', 'city' => 'Moulay Yacoub', 'prefecture' => 'Moulay Yacoub'],

            ['region' => 'Tanger-Tétouan-Al Hoceïma', 'city' => 'Tanger', 'prefecture' => 'Tanger-Assilah'],
            ['region' => 'Tanger-Tétouan-Al Hoceïma', 'city' => 'Tétouan', 'prefecture' => 'Tétouan'],
            ['region' => 'Tanger-Tétouan-Al Hoceïma', 'city' => 'Al Hoceïma', 'prefecture' => 'Al Hoceïma'],
            ['region' => 'Tanger-Tétouan-Al Hoceïma', 'city' => 'Larache', 'prefecture' => 'Larache'],
            ['region' => 'Tanger-Tétouan-Al Hoceïma', 'city' => 'Ksar El Kebir', 'prefecture' => 'Larache'],
            ['region' => 'Tanger-Tétouan-Al Hoceïma', 'city' => 'Chefchaouen', 'prefecture' => 'Chefchaouen'],
            ['region' => 'Tanger-Tétouan-Al Hoceïma', 'city' => 'Ouezzane', 'prefecture' => 'Ouezzane'],
            ['region' => 'Tanger-Tétouan-Al Hoceïma', 'city' => 'Fahs-Anjra', 'prefecture' => 'Fahs-Anjra'],
            ['region' => 'Tanger-Tétouan-Al Hoceïma', 'city' => 'M’diq-Fnideq', 'prefecture' => 'M’diq-Fnideq'],

            ['region' => 'Souss-Massa', 'city' => 'Agadir', 'prefecture' => 'Agadir Ida-Outanane'],
            ['region' => 'Souss-Massa', 'city' => 'Inezgane', 'prefecture' => 'Inezgane-Aït Melloul'],
            ['region' => 'Souss-Massa', 'city' => 'Chtouka Aït Baha', 'prefecture' => 'Chtouka Aït Baha'],
            ['region' => 'Souss-Massa', 'city' => 'Taroudant', 'prefecture' => 'Taroudant'],
            ['region' => 'Souss-Massa', 'city' => 'Tiznit', 'prefecture' => 'Tiznit'],
            ['region' => 'Souss-Massa', 'city' => 'Tata', 'prefecture' => 'Tata'],

            ['region' => 'Oriental', 'city' => 'Oujda', 'prefecture' => 'Oujda-Angad'],
            ['region' => 'Oriental', 'city' => 'Nador', 'prefecture' => 'Nador'],
            ['region' => 'Oriental', 'city' => 'Berkane', 'prefecture' => 'Berkane'],
            ['region' => 'Oriental', 'city' => 'Taourirt', 'prefecture' => 'Taourirt'],
            ['region' => 'Oriental', 'city' => 'Jerada', 'prefecture' => 'Jerada'],
            ['region' => 'Oriental', 'city' => 'Guercif', 'prefecture' => 'Guercif'],
            ['region' => 'Oriental', 'city' => 'Driouch', 'prefecture' => 'Driouch'],
            ['region' => 'Oriental', 'city' => 'Figuig', 'prefecture' => 'Figuig'],

            ['region' => 'Béni Mellal-Khénifra', 'city' => 'Béni Mellal', 'prefecture' => 'Béni Mellal'],
            ['region' => 'Béni Mellal-Khénifra', 'city' => 'Khouribga', 'prefecture' => 'Khouribga'],
            ['region' => 'Béni Mellal-Khénifra', 'city' => 'Fquih Ben Salah', 'prefecture' => 'Fquih Ben Salah'],
            ['region' => 'Béni Mellal-Khénifra', 'city' => 'Azilal', 'prefecture' => 'Azilal'],
            ['region' => 'Béni Mellal-Khénifra', 'city' => 'Khénifra', 'prefecture' => 'Khénifra'],

            ['region' => 'Drâa-Tafilalet', 'city' => 'Errachidia', 'prefecture' => 'Errachidia'],
            ['region' => 'Drâa-Tafilalet', 'city' => 'Ouarzazate', 'prefecture' => 'Ouarzazate'],
            ['region' => 'Drâa-Tafilalet', 'city' => 'Midelt', 'prefecture' => 'Midelt'],
            ['region' => 'Drâa-Tafilalet', 'city' => 'Tinghir', 'prefecture' => 'Tinghir'],
            ['region' => 'Drâa-Tafilalet', 'city' => 'Zagora', 'prefecture' => 'Zagora'],

            ['region' => 'Guelmim-Oued Noun', 'city' => 'Guelmim', 'prefecture' => 'Guelmim'],
            ['region' => 'Guelmim-Oued Noun', 'city' => 'Tan-Tan', 'prefecture' => 'Tan-Tan'],
            ['region' => 'Guelmim-Oued Noun', 'city' => 'Sidi Ifni', 'prefecture' => 'Sidi Ifni'],
            ['region' => 'Guelmim-Oued Noun', 'city' => 'Assa-Zag', 'prefecture' => 'Assa-Zag'],

            ['region' => 'Laâyoune-Sakia El Hamra', 'city' => 'Laâyoune', 'prefecture' => 'Laâyoune'],
            ['region' => 'Laâyoune-Sakia El Hamra', 'city' => 'Boujdour', 'prefecture' => 'Boujdour'],
            ['region' => 'Laâyoune-Sakia El Hamra', 'city' => 'Tarfaya', 'prefecture' => 'Tarfaya'],
            ['region' => 'Laâyoune-Sakia El Hamra', 'city' => 'Es-Semara', 'prefecture' => 'Es-Semara'],

            ['region' => 'Dakhla-Oued Ed-Dahab', 'city' => 'Dakhla', 'prefecture' => 'Oued Ed-Dahab'],
            ['region' => 'Dakhla-Oued Ed-Dahab', 'city' => 'Aousserd', 'prefecture' => 'Aousserd'],
        ];

        $now = now();
        $payload = array_map(static function (array $row) use ($now) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
            return $row;
        }, $rows);

        DB::table('morocco_locations')->insert($payload);
    }

    public function down(): void
    {
        Schema::dropIfExists('morocco_locations');
    }
};
