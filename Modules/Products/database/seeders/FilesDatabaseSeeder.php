<?php

namespace Modules\Products\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Products\Models\Files;

class FilesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Files::insert([
            ['name' => 'products_01.json.gz'],
            ['name' => 'products_02.json.gz'],
            ['name' => 'products_03.json.gz'],
            ['name' => 'products_04.json.gz'],
            ['name' => 'products_05.json.gz'],
            ['name' => 'products_06.json.gz'],
            ['name' => 'products_07.json.gz'],
            ['name' => 'products_08.json.gz'],
            ['name' => 'products_09.json.gz'],
        ]);
        // $this->call([]);
    }
}
