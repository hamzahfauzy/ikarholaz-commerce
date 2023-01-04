<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WaBlast;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Models\TransactionItem;

class MerchantController extends Controller
{

    public function index()
    {
        //
        if(session('merchant')) return redirect()->route('merchant.scan');

        $otp_status = false;
        if(session('otp'))
        {
            $otp = session('otp');
            $now = strtotime('now');
            if($now > $otp['expired'])
            {
                session()->forget('otp');
                session()->flush();
            }
            else
            {
                $otp_status = true;
            }
        }
        return view('merchant.index',compact('otp_status'));
    }

    public function sendOtp(Request $request)
    {
        // merchant wa validation
        $phone = $request->phone;
        $validation = Merchant::where('phone','LIKE','%'.$phone.'%');
        if(!$validation->exists())
        {
            return redirect()->back()->withErrors(['phone' => 'No WA tidak ditemukan pada merchant apapun']);
        }

        $merchant = $validation->first();
        $otp      = mt_rand(111111, 999999);
        $expired  = strtotime('now + 2 minute');
        session(['otp' => ['pwd' => $otp, 'expired' => $expired, 'merchant' => $merchant]]);

        // send otp
        WaBlast::fonnteSend($phone, "Kode OTP untuk Merchant ".$merchant->name." adalah ".$otp);

        return redirect()->route('merchant.index');
    }

    public function verifyOtp(Request $request)
    {
        $otp = session('otp');
        if($otp['pwd'] == $request->otp)
        {
            session()->forget('otp');
            session()->flush();

            session(['merchant' => $otp['merchant']]);

            return redirect()->route('merchant.scan');
        }
        else
        {
            return redirect()->back()->withErrors(['otp' => 'OTP tidak valid']);
        }
    }

    public function scan()
    {
        if(!session('merchant')) return redirect()->back();
        return view('merchant.scan');
    }

    public function voucherDetail(Request $request)
    {
        if(!session('merchant')) return redirect()->back();
        if($this->checkVoucher($request->voucher_code))
        {
            return response()->json([
                'status' => 'success',
                'message' => 'Voucher valid'
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'Voucher tidak valid'
        ], 400);
    }

    public function claimVoucher(Request $request)
    {
        if(!session('merchant')) return redirect()->back();
        if($this->checkVoucher($request->voucher_code))
        {
            $voucher = TransactionItem::where('notes',$request->voucher_code)->first();
            $voucher->update(['notes' => '']);

            return response()->json([
                'status' => 'success',
                'message' => 'Voucher claimed'
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'Voucher tidak valid'
        ], 400);
    }

    public function checkVoucher($voucher_code)
    {
        $voucher = TransactionItem::where('notes',$voucher_code);
        if($voucher->exists())
        {
            // check merchant
            $merchant = session('merchant');
            $voucher = $voucher->first();
            $product = Product::where('id',$voucher->product_id)->first();

            
            $custom_fields = \App\Models\CustomField::where('class_target','App\Models\VoucherProduct')->get();
            $cf = [];
            $cf_product_id = isset($product->parent) ? $product->parent->parent->id : $product->id;
            foreach($custom_fields as $key => $value)
            {
                $cf[$value->field_key] = $value->get_value($cf_product_id)->field_value;
            }

            $cf_merchant = explode(' | ',$cf['nama_merchant']);
            $is_merchant_valid = $cf_merchant[0] == $merchant->code;
            $is_expired = strtotime('now') > strtotime($cf['tanggal_berakhir']);
            return $is_merchant_valid && !$is_expired;
        }
        return false;
    }

    public function logout()
    {
        session()->forget('merchant');
        session()->flush();
        return redirect()->route('merchant.index');
    }

    
}
