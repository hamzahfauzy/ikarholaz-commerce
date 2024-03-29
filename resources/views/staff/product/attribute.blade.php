<div class="card card-default">
    <div class="card-header">
        <span class="card-title">{{__('Status')}}</span>
    </div>
    <div class="card-body">
        <div class="box box-info padding-1">
            <div class="box-body">
                <div class="form-group">
                    {{ Form::select('status', ['Draft' => 'Draft','Publish' => 'Publish'], $product->status, ['class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : ''), 'data-placeholder' => __('Status')]) }}
                    {!! $errors->first('status', '<p class="invalid-feedback">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>
</div>

@if(!$product->id || !$product->parent)
<div class="card card-default">
    <div class="card-header">
        <span class="card-title">{{__('Category')}}</span>
    </div>
    <div class="card-body">
        <div class="box box-info padding-1">
            <div class="box-body">
                <div class="form-group">
                    {{ Form::select('category_id[]', $categories, $product->categories()->pluck('category_id'), ['class' => 'form-control select2 select2-multiple' . ($errors->has('category_id') ? ' is-invalid' : ''), 'data-placeholder' => __('Category'), 'multiple' => 'true']) }}
                    {!! $errors->first('category_id', '<p class="invalid-feedback">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($product->categories->contains(config('reference.event_kategori')))
<div class="card card-default">
    <div class="card-header">
        <span class="card-title">{{__('Custom Fields')}}</span>
    </div>
    <div class="card-body">
        <div class="box box-info padding-1">
            <div class="box-body">
                <input type="hidden" name="custom_field_target" value="App\Models\EventProduct">
                @php($customFields = App\Models\CustomField::where('class_target','App\Models\EventProduct')->get())
                @foreach($customFields as $customField)
                <div class="form-group">
                    <label for="">{{ucwords(__($customField->field_key))}}</label>
                    {{ Form::{$customField->field_type}("custom_fields[$customField->field_key]", $customField->get_value($product->id)?$customField->get_value($product->id)->field_value:'', ['class' => 'form-control select2 select2-multiple' . ($errors->has('name') ? ' is-invalid' : '')]) }}
                    {!! $errors->first('name', '<p class="invalid-feedback">:message</p>') !!}
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

@if($product->categories->contains(config('reference.voucher_kategori')))
<div class="card card-default">
    <div class="card-header">
        <span class="card-title">{{__('Custom Fields')}}</span>
    </div>
    <div class="card-body">
        <div class="box box-info padding-1">
            <div class="box-body">
                <input type="hidden" name="custom_field_target" value="App\Models\VoucherProduct">
                @php($customFields = App\Models\CustomField::where('class_target','App\Models\VoucherProduct')->get())
                @foreach($customFields as $customField)
                <div class="form-group">
                    <label for="">{{ucwords(__($customField->field_key))}}</label>
                    @if($customField->field_type == 'select')
                    {{ Form::select("custom_fields[$customField->field_key]", App\Models\Merchant::get()->pluck('merchant_name','merchant_name'), $customField->get_value($product->id)?$customField->get_value($product->id)->field_value:'', ['class' => 'form-control select2' . ($errors->has('name') ? ' is-invalid' : '')]) }}
                    @else
                    {{ Form::{$customField->field_type}("custom_fields[$customField->field_key]", $customField->get_value($product->id)?$customField->get_value($product->id)->field_value:'', ['class' => 'form-control ' . ($errors->has('name') ? ' is-invalid' : '')]) }}
                    @endif
                    {!! $errors->first('name', '<p class="invalid-feedback">:message</p>') !!}
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<div class="card card-default">
    <div class="card-header">
        <span class="card-title">{{__('Thumbnail')}}</span>
    </div>
    <div class="card-body">
        <input type="hidden" name="hidden_image">
        <img src="{{$product->thumb?Storage::url($product->thumb->file_url):''}}" alt="" id="preview" width="100%">
        <button type="button" class="btn btn-danger btn-block d-none btn-delete" onclick="deleteThumbnail()"><i class="fa fa-times"></i> {{__('Remove')}}</button>
        @if($product->thumb)
        <button type="button" class="btn btn-danger btn-block btn-delete-existing" onclick="deleteExistingThumbnail()"><i class="fa fa-times"></i> {{__('Remove')}}</button>
        @endif
        <button type="button" class="btn btn-info btn-lg btn-block" data-toggle="modal" data-target="#myModal">Pilih Gambar</button>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
  
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pilih Gambar</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Upload Gambar</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="loadAllProductImages()" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Pilih Gambar</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <input type="file" name="image" class="filestyle image" data-input="false"  data-iconname="fas fa-cloud-upload-alt">
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Loading...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
  
    </div>
</div>