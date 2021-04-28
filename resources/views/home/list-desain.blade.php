<div class="row">
    <div class="col-12">
        <label for="">Pilih Desain</label>
        <div class="variation-list">
            @foreach($desain_products as $product)
            <div class="property-card property-horizontal bg-white container-desain" style="cursor:pointer" onclick="pilihDesain(this)" data-id="{{$product->id}}">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="property-image" style="background: url('{{$product->thumbnail}}') center center / cover no-repeat;">
                        </div>
                    </div>
                    <!-- /col 4 -->
                    <div class="col-12 col-sm-9">
                        <div class="property-content">
                            <div class="listingInfo">
                                <div class="">
                                    <span class="text-success m-t-0">{{$product->price_formated}}</span>
                                </div>
                                <div class="">
                                    <h4><a href="{{route('shop.product-detail',$product->slug)}}" class="text-dark">{{$product->name}}</a></h4>
                                </div>
                            </div>
                            <div class="property-action">
                                <a href="#" target="new_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="280 square feet"><i class="mdi mdi-view-grid"></i><span>{{$product->stock_label}}</span></a>
                            </div>
                            <!-- end. Card actions -->
                        </div>
                    </div>
                    <!-- /col 8 -->
                </div>
                <!-- /inner row -->
            </div>
            @foreach($product->variants as $variant)
            <div class="property-card property-horizontal bg-white container-desain" style="cursor:pointer" onclick="pilihDesain(this)" data-id="{{$variant->id}}">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="property-image" style="background: url('{{$variant->thumbnail}}') center center / cover no-repeat;">
                        </div>
                    </div>
                    <!-- /col 4 -->
                    <div class="col-12 col-sm-9">
                        <div class="property-content">
                            <div class="listingInfo">
                                <div class="">
                                    <span class="text-success m-t-0">{{$variant->price_formated}}</span>
                                </div>
                                <div class="">
                                    <h4><a href="{{route('shop.product-detail',$product->slug)}}" class="text-dark">{{$variant->name}}</a></h4>
                                </div>
                            </div>
                            <div class="property-action">
                                <a href="#" target="new_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="280 square feet"><i class="mdi mdi-view-grid"></i><span>{{$variant->stock}}</span></a>
                            </div>
                            <!-- end. Card actions -->
                        </div>
                    </div>
                    <!-- /col 8 -->
                </div>
                <!-- /inner row -->
            </div>
            @endforeach
            @endforeach
            <!-- End property item -->
        </div>
    </div>
</div>