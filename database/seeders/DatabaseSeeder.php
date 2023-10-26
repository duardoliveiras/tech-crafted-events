<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $path = base_path('database/file.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);
        $this->command->info('Database tech crafted seeded!');
    }
}
