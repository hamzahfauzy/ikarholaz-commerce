<?php

namespace Database\Seeders;

use App\Models\CustomField;
use Illuminate\Database\Seeder;

class EventCustomFields extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //
        $class_target = 'App\\Models\\Event';
        CustomField::where('class_target',$class_target)->delete();
        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'nama',
            'field_type' => 'text',
        ]);

        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'tahun_lulus',
            'field_type' => 'text',
        ]);

        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'NRA',
            'field_type' => 'text',
        ]);
    }
}
