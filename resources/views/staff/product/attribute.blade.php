@if(!$product->id || !$product->parent)
<div class="card card-default">
    <div class="card-header">
        <span class="card-title">{{__('Category')}}</span>
    </div>
    <div class="card-body">
        <div class="box box-info padding-1">
            <div class="box-body">
                <div class="form-group">
                    {{ Form::select('category_id[]', $categories, $product->categories()->pluck('category_id'), ['class' => 'form-control select2 select2-multiple' . ($errors->has('name') ? ' is-invalid' : ''), 'data-placeholder' => __('Category'), 'multiple' => 'true']) }}
                    {!! $errors->first('name', '<p class="invalid-feedback">:message</p>') !!}
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
                    <label for="">{{ucwords($customField->field_key)}}</label>
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
                    {{ Form::{$customField->field_type}("custom_fields[$customField->field_key]", $customField->get_value($product->id)?$customField->get_value($product->id)->field_value:'', ['class' => 'form-control select2 select2-multiple' . ($errors->has('name') ? ' is-invalid' : '')]) }}
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
        <input type="file" name="image" class="filestyle image" data-input="false"  data-iconname="fas fa-cloud-upload-alt">
        <img src="{{$product->thumb?Storage::url($product->thumb->file_url):''}}" alt="" id="preview" width="100%">
        <button type="button" class="btn btn-danger btn-block d-none btn-delete" onclick="deleteThumbnail()"><i class="fa fa-times"></i> {{__('Remove')}}</button>
        @if($product->thumb)
        <button type="button" class="btn btn-danger btn-block btn-delete-existing" onclick="deleteExistingThumbnail()"><i class="fa fa-times"></i> {{__('Remove')}}</button>
        @endif
    </div>
</div>