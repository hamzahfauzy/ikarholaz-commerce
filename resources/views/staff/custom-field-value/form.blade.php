<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('custom_field_id') }}
            {{ Form::text('custom_field_id', $customFieldValue->custom_field_id, ['class' => 'form-control' . ($errors->has('custom_field_id') ? ' is-invalid' : ''), 'placeholder' => 'Custom Field Id']) }}
            {!! $errors->first('custom_field_id', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('pk_id') }}
            {{ Form::text('pk_id', $customFieldValue->pk_id, ['class' => 'form-control' . ($errors->has('pk_id') ? ' is-invalid' : ''), 'placeholder' => 'Pk Id']) }}
            {!! $errors->first('pk_id', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('field_value') }}
            {{ Form::text('field_value', $customFieldValue->field_value, ['class' => 'form-control' . ($errors->has('field_value') ? ' is-invalid' : ''), 'placeholder' => 'Field Value']) }}
            {!! $errors->first('field_value', '<p class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>