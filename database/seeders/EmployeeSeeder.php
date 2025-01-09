<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $divisions = Division::all();

        foreach ($divisions as $division) {
            Employee::create([
                'id' => (string) Str::uuid(),
                'name' => 'Employee ' . $division->name,
                'phone' => '08123456789',
                'division_id' => $division->id,  // Pastikan ID valid dari Division
                'position' => 'Staff ' . $division->name,
                'image' => null,
            ]);
        }
    }
}
