<?php

namespace Database\Seeders;

use App\Models\CustomField;
use Illuminate\Database\Seeder;

class ProductCustomFields extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $class_target = 'App\\Models\\Product';
        CustomField::where('class_target',$class_target)->delete();
        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'nama_lengkap',
            'field_type' => 'text',
        ]);

        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'nama_tercetak_di_kartu',
            'field_type' => 'text',
        ]);

        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'nomor_kartu',
            'field_type' => 'text',
        ]);

        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'tahun_lulus',
            'field_type' => 'text',
        ]);

        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'pemesanan',
            'field_type' => 'text',
        ]);
    }
}
