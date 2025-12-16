<?php

namespace Database\Seeders;

use App\Models\User;
use App\Model\StaffMember;
use App\Model\Student;
use App\Model\StudentParent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(100)->create();
         Student::factory(100)->create();
         StudentParent::factory(100)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
