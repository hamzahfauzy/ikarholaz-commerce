@extends('layouts.app')

@section('content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @endif
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="property-detail-wrapper">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="">
                            <ul class="bxslider property-slider">
                                <li><img src="{{$product->thumbnail}}" alt="slide-image" /></li>
                                @foreach($product->variants as $key => $variant)
                                <li><img src="{{$variant->thumbnail}}" alt="slide-image" /></li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- end slider -->

                    </div> <!-- end col -->

                    <div class="col-lg-7">

                        <div class="card-box">
                            <h3 style="margin-top:0px;">{{$product->name}}</h3>
                            <p class="text-muted text-overflow">
                                <i class="mdi mdi-tag m-r-5"></i>
                                @forelse($product->categories as $category)
                                <a href="{{route('shop.product-list',$category->slug)}}" class="badge badge-primary">{{$category->name}}</a>
                                @empty
                                <a href="{{route('shop.product-list','Uncategorized')}}">{{__('Uncategorized')}}</a>
                                @endforelse
                            </p>
                            <h3 style="margin-top:0px;" id="product_price">Rp. {{$product->price_formated}}</h3>
                            {{ $product->stock_label }}
                            <p class="m-t-20"></p>
                            <form action="{{route('shop.product-action')}}" method="POST">
                                @csrf
                                <input type="hidden" id="product_id" name="product_id" value="{{$product->id}}">
                                @if($product->variants)
                                <label for="">{{__('Pick a Variant')}} :</label>
                                <div class="variant-list">
                                    <div id="bx-pager">
                                        <a data-slide-index="0" href="javascript:void(0)" onclick="product_id.value={{$product->id}};product_price.innerHTML='Rp. {{$product->price_formated}}'"><img src="{{$product->thumbnail}}" alt="slide-image" height="40" /></a>
                                        @foreach($product->variants as $key => $variant)
                                        <a data-slide-index="{{++$key}}" href="javascript:void(0)" onclick="product_id.value={{$variant->id}};product_price.innerHTML='Rp. {{$product->price_formated}}'"><img src="{{$variant->thumbnail}}" alt="slide-image" height="40" /></a>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                <p></p>
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <input class="vertical-spin" type="text" value="1" name="qty">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        @if($product->is_available)
                                        <button class="btn btn-primary" name="action" value="checkout">{{__('Checkout')}}</button>
                                        <button class="btn btn-warning" name="action" value="add_to_cart">{{__('Add To Cart')}}</button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- end card-box -->

                    </div> <!-- end col -->
                </div>
                <p class="m-t-20"></p>
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h2>{{__('Description')}}</h2>
                            <hr>
                            {!!$product->description!!}
                        </div>
                    </div>
                </div> <!-- end row -->
            </div>
            <!-- end property-detail-wrapper -->
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="{{asset('plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />
<!-- Bx slider css -->
<link href="{{asset('plugins/bx-slider/jquery.bxslider.css')}}" rel="stylesheet" type="text/css" />
<style>
.bx-viewport,.bx-viewport li  {
    height:400px!important;
}
.bx-viewport img {
    width:100%;
    height:100%;
    object-fit:cover;
    object-position:center;
}
</style>
@endsection

@section('script')
<script src="{{asset('plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js')}}"></script>
<!-- Bx slider js -->
<script src="{{asset('plugins/bx-slider/jquery.bxslider.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $('.property-slider').bxSlider({
            pagerCustom: '#bx-pager',
            controls:false
        });

        $(".vertical-spin").TouchSpin({
            verticalbuttons: true,
            verticalupclass: 'glyphicon glyphicon-plus',
            verticaldownclass: 'glyphicon glyphicon-minus'
        })
    });
</script>
@endsection