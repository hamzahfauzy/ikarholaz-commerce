<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomField;

class EventProductCustomFields extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $class_target = 'App\\Models\\EventProduct';
        CustomField::where('class_target',$class_target)->delete();

        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'waktu',
            'field_type' => 'datetimeLocal',
        ]);
        
        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'venue',
            'field_type' => 'text',
        ]);
        
        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'alamat',
            'field_type' => 'textArea',
        ]);

        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'no_wa',
            'field_type' => 'text',
        ]);
    }
}
