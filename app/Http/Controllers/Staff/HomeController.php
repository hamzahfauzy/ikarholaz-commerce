<?php

namespace App\Http\Controllers\Staff;

use App\Models\Alumni;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //

    function index()
    {
        $alumnis = Alumni::get()->count();
        $alumnis_approved = Alumni::where('approval_status','approved')->get()->count();
        $alumnis_pending = Alumni::where('approval_status','pending')->get()->count();
        $alumnis_denied = Alumni::where('approval_status','denied')->get()->count();
        $alumnis_died = Alumni::where('approval_status','died')->get()->count();
        $alumnis_angkatan = Alumni::select(DB::raw('graduation_year as x, count(*) as y'))->groupBy('graduation_year')->get();

        $ticket = Category::where('slug','tiket')->first();
        $ticketIds = $ticket->products->pluck('id');
       
        $events = TransactionItem::join('products', 'product_id', '=', 'products.id')
        ->select(DB::raw('products.name as name, product_id,count(*) as count, sum(total) as total, sum(amount) as amount'))
        ->whereIn('product_id',$ticketIds)
        ->groupBy('product_id')
        ->groupBy('products.name')
        ->get();

        $transactions = TransactionItem::join('products', 'product_id', '=', 'products.id')
        ->select(DB::raw('products.name as name, product_id,count(*) as count, sum(total) as total, sum(amount) as amount'))
        ->whereNotIn('product_id',$ticketIds)
        ->groupBy('product_id')
        ->groupBy('products.name')
        ->get();

        return view('staff.dashboard',compact('alumnis','alumnis_approved','alumnis_pending','alumnis_denied','alumnis_died','alumnis_angkatan','transactions','events'));
    }
}
