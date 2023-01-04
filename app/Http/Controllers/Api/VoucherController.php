<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\CustomFieldValue;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
    //
    public function index()
    {
        $end_date_field = \App\Models\CustomField::where('class_target','App\Models\VoucherProduct')
                            ->where('field_key','tanggal_berakhir')
                            ->first();

        $products = CustomFieldValue::where('custom_field_id',$end_date_field->id)->whereRaw("STR_TO_DATE(field_value, '%Y-%m-%dT%TZ') > now()")->join('products','products.id','=','custom_field_values.pk_id')->get();



        return response()->json([
            'status' => 'success',
            'message' => 'voucher send',
            'data' => $products
        ]);
         
    }
}
