<div class="col-12 col-md-3 col-sm-6">
    <div class="property-card bg-white">
        <div class="property-image" style="background: url('{{$product->thumbnail}}') center center / cover no-repeat;height:180px;">
            @if($product->is_new)
            <span class="property-label badge badge-success">{{__('New Product')}}</span>
            @endif
        </div>

        <div class="property-content">
            <div class="listingInfo">
                <div class="">
                    <h5 class="text-success m-t-0">Rp. {{$product->price_formated}}</h5>
                </div>
                <div class="">
                    <h4 class="text-overflow"><a href="{{route('shop.product-detail',$product->slug)}}" class="text-dark">{{$product->name}}</a></h4>
                    <p class="text-muted text-overflow">
                        <i class="mdi mdi-tag m-r-5"></i>
                        @forelse($product->categories as $category)
                        <a href="{{route('shop.product-list',$category->slug)}}" class="badge badge-primary">{{$category->name}}</a>
                        @empty
                        <a href="{{route('shop.product-list','Uncategorized')}}">{{__('Uncategorized')}}</a>
                        @endforelse
                    </p>
                    <div class="m-t-20 text-center">
                        <a href="{{route('shop.product-detail',$product->slug)}}" class="btn btn-primary waves-effect waves-light">{{__('View Detail')}}</a>
                        <a href="{{route('shop.add_to_cart',$product->slug)}}" class="btn btn-success waves-effect waves-light">{{__('Add To Cart')}}</a>
                    </div>

                </div>
            </div>
            <!-- end. Card actions -->
        </div>
        <!-- /inner row -->
    </div>
    <!-- End property item -->
</div>
<!-- end col -->