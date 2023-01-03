<?php

namespace Database\Seeders;

use App\Models\CustomField;
use Illuminate\Database\Seeder;

class VoucherProductCustomFields extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $class_target = 'App\\Models\\VoucherProduct';
        CustomField::where('class_target',$class_target)->delete();

        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'nama_merchant',
            'field_type' => 'text',
        ]);
        
        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'kode_voucher',
            'field_type' => 'text',
        ]);
        
        CustomField::create([
            'class_target' => $class_target,
            'field_key' => 'tanggal_berakhir',
            'field_type' => 'datetimeLocal',
        ]);
    }
}
