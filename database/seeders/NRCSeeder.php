<?php

namespace Database\Seeders;

use App\Models\NRC;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class NRCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the JSON file
        $jsonPath = database_path('data/nrc.json');

        // Load and decode the JSON data
        $nrcData = json_decode(File::get($jsonPath), true);

        // Seed the data
        foreach ($nrcData['data'] as $nrc) {
            NRC::create([
                'name_en' => $nrc['name_en'],
                'name_mm' => $nrc['name_mm'],
                'nrc_code' => $nrc['nrc_code'],
                'created_at' => $nrc['created_at'],
                'updated_at' => $nrc['updated_at'],
            ]);
        }
    }
}
