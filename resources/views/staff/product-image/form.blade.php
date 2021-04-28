<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('product_id') }}
            {{ Form::text('product_id', $productImage->product_id, ['class' => 'form-control' . ($errors->has('product_id') ? ' is-invalid' : ''), 'placeholder' => 'Product Id']) }}
            {!! $errors->first('product_id', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('file_url') }}
            {{ Form::text('file_url', $productImage->file_url, ['class' => 'form-control' . ($errors->has('file_url') ? ' is-invalid' : ''), 'placeholder' => 'File Url']) }}
            {!! $errors->first('file_url', '<div class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>