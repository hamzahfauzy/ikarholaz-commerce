<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Staff::truncate();
        Staff::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => 'password'
        ]);
    }
}
