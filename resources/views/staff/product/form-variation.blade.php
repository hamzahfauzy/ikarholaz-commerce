<style>
.property-card.property-horizontal .property-content .listingInfo, .property-card.property-horizontal .property-content {
    height:auto!important;
}
.property-card .property-image {
    height:100%;
    min-height:100px;
}
.property-card.property-horizontal .property-content .property-action {
    position: inherit;
}
.property-action {
    padding-left:0px!important;
    padding-right:0px!important;
}
</style>
<div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0">{{__('Add New Variant')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="form-variant" action="{{route('staff.product-variants.store')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="parent_id" value="{{$product->id}}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-3" class="control-label">Name</label>
                            <input type="text" class="form-control" id="field-3" placeholder="Name" name="name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="field-4" class="control-label">Price</label>
                            <input type="text" name="price" class="form-control" id="field-4" placeholder="Price">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="field-5" class="control-label">Stock</label>
                            <input type="number" name="stock" class="form-control" id="field-5" placeholder="Stock">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="file" name="image" class="filestyle image-variant" data-input="false"  data-iconname="fas fa-cloud-upload-alt">
                        <img src="" alt="" id="preview-variant" width="100%">
                        <button type="button" class="btn btn-danger btn-block d-none btn-delete" onclick="deleteVariantThumbnail()"><i class="fa fa-times"></i> {{__('Remove')}}</button>
                    </div>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info waves-effect waves-light" onclick="document.getElementById('form-variant').submit()">Save changes</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->

<div class="box box-info padding-1">
    <div class="box-body">
        <button class="btn btn-primary" data-toggle="modal" data-target="#con-close-modal" type="button">{{__('Add New')}}</button>
        <p></p><br>
        <div class="variation-list">
            @foreach($product->variants as $variant)
            <div class="property-card property-horizontal bg-white">
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
                                    <h4><a href="{{ route('staff.products.edit',$variant->id) }}" class="text-dark">{{$variant->name}}</a></h4>
                                </div>
                            </div>
                            <div class="property-action">
                                <a href="#" target="new_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="280 square feet"><i class="mdi mdi-view-grid"></i><span>{{$variant->stock}}</span></a>
                                <div class="float-right">
                                    <a href="{{ route('staff.products.edit',$variant->id) }}" class="btn btn-light"><i class="fas fa-edit"></i><span>{{__('Edit')}}</span></a>
                                    <a href="javascript:void(0)" onclick="if(confirm('{{__('Are you sure to delete this item ?')}}')){ document.getElementById('delete-variant-{{$variant->id}}').submit() }" class="btn btn-light"><i class="far fa-trash-alt"></i><span>{{__('Delete')}}</span></a>
                                    <form method="post" id="delete-variant-{{$variant->id}}" action="{{ route('staff.products.destroy',$variant->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    </form>
                                </div>
                            </div>
                            <!-- end. Card actions -->
                        </div>
                    </div>
                    <!-- /col 8 -->
                </div>
                <!-- /inner row -->
            </div>
            @endforeach
            <!-- End property item -->
        </div>
    </div>
</div>