<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = ['Mobile Apps', 'QA', 'Full Stack', 'Backend', 'Frontend', 'UI/UX Designer'];

        foreach ($divisions as $division) {
            Division::create([
                'id' => (string) Str::uuid(), // Generate UUID untuk kolom 'id'
                'name' => $division,
            ]);
        }
    }
}
