@extends('layouts.app')

@section('content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row text-center">
                <div class="col-sm-12">
                    <h3 class="m-t-20">{{__('Cart')}}</h3>
                    <div class="border mx-auto d-block m-b-20"></div>
                </div>
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @endif
                        @if(cart()->count())
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            @foreach(cart()->all_lists()->get() as $cart)
                            <tr>
                                <td><a href="{{route('shop.cart-remove',$cart->id)}}"><i class="fa fa-times"></i></a></td>
                                <td>
                                    <img src="{{$cart->thumbnail}}" alt="" height="30px" style="object-fit:cover;">
                                </td>
                                <td>
                                <a href="{{route('shop.product-detail',$cart->parent?$cart->parent->parent->slug:$cart->slug)}}">{{$cart->parent?$cart->parent->parent->name.' - ':''}}{{$cart->name}}</a><br>
                                <b>{{$cart->price_formated}}</b>
                                </td>
                                <td>
                                    <form action="{{route('shop.cart-update',$cart->id)}}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$cart->id}}">
                                        <div class="form-inline">
                                            <input type="number" class="form-control" name="qty" value="{{cart()->get($cart->id)}}" @if($cart->categories->contains(config('reference.event_kategori'))) max="5" @endif>
                                            <button class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </td>
                                <td>{{number_format(cart()->subtotal($cart->id))}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" style="text-align:right"><b>Subtotal</b></td>
                                <td>{{number_format(cart()->subtotal())}}</td>
                            </tr>
                        </table>
                        <div class="float-right">
                            <a href="{{route('shop.checkout')}}" class="btn btn-success">Checkout</a>
                        </div>
                        @else
                        <center>
                        <i>{{__('Your cart is empty!')}}</i>
                        <br>
                        <a href="{{route('shop.index')}}" class="btn btn-success">{{__('Back to Shop')}}</a>
                        </center>
                        @endif
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container -->

    </div> <!-- content -->

</div>


<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->
@endsection