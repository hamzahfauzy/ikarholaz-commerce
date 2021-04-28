<?php

namespace Database\Seeders;

use App\Models\CustomField;
use Illuminate\Database\Seeder;

class CustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $class_target = 'App\\Models\\TransactionItem';
        CustomField::where('class_target',$class_target)->delete();
        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'nomor_kartu',
            'field_type' => 'text',
        ]);
    }
}
