<?php

namespace App\Models;

class Cart
{
    private static $items;
    function __construct()
    {
        $this->init();
    }

    public static function init()
    {
        self::$items = session('cart',[]);
    }

    public static function update($product, $qty)
    {
        return self::add($product,$qty);
    }

    public static function add($product, $qty = false)
    {
        if(empty(self::$items)) self::init();
        if(isset(self::$items[$product]) && $qty == false)
            self::$items[$product]++;
        elseif(!isset(self::$items[$product]) && $qty == false)
            self::$items[$product] = 1;
        else    
            self::$items[$product] = $qty;
        session(['cart'=>self::$items]);
        return new static;
    }

    public static function pop($index)
    {
        if(empty(self::$items)) self::init();
        unset(self::$items[$index]);
        session(['cart'=>self::$items]);
        return new static;
    }

    public static function clear()
    {
        if(empty(self::$items)) self::init();
        self::$items = [];
        session(['cart'=>self::$items]);
        return new static;
    }

    public static function get($key = 0)
    {
        if(empty(self::$items)) self::init();
        if($key == 0)
            return self::$items;
        return self::$items[$key];
    }

    public static function custom_fields($product)
    {
        $categories = [];
        $categories = collect($product->categories)->map(function($cat){
            return $cat->id;
        });
        if($product->parent)
            $categories = collect($product->parent->parent->categories)->map(function($cat){
                return $cat->id;
            });
        if(in_array(getenv('DESAIN_KARTU_KATEGORI'),$categories->toArray()))
            return CustomField::where('class_target','App\\Models\\TransactionItem')->get();
        return [];
    }

    public static function count()
    {
        return self::all_lists()->count();
    }

    public static function all_lists($limit = false)
    {
        $ids = array_keys(self::get());
        $products = Product::whereIn('id',$ids);
        if($limit)
            $products->skip(0)->take($limit);
        return $products;
    }

    public static function get_weight()
    {
        $total_weight = 0;
        foreach(self::all_lists()->get() as $product)
            $total_weight += ($product->product_weight*self::get($product->id))*1000;

        return $total_weight;
    }

    public static function lists()
    {
        return self::all_lists(3)->get();
    }

    public static function subtotal($id = 0)
    {
        if($id)
        {
            $product = Product::findOrFail($id);
            return $product->price * self::get($id);
        }

        $subtotal = 0;
        foreach(self::all_lists()->get() as $product)
            $subtotal += $product->price * self::get($product->id);

        return $subtotal;
    }
}
