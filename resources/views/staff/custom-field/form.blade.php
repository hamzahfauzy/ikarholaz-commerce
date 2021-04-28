<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('field_key') }}
            {{ Form::text('field_key', $customField->field_key, ['class' => 'form-control' . ($errors->has('field_key') ? ' is-invalid' : ''), 'placeholder' => 'Field Key']) }}
            {!! $errors->first('field_key', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('field_type') }}
            {{ Form::text('field_type', $customField->field_type, ['class' => 'form-control' . ($errors->has('field_type') ? ' is-invalid' : ''), 'placeholder' => 'Field Type']) }}
            {!! $errors->first('field_type', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('class_target') }}
            {{ Form::text('class_target', $customField->class_target, ['class' => 'form-control' . ($errors->has('class_target') ? ' is-invalid' : ''), 'placeholder' => 'Class Target']) }}
            {!! $errors->first('class_target', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('query_condition') }}
            {{ Form::text('query_condition', $customField->query_condition, ['class' => 'form-control' . ($errors->has('query_condition') ? ' is-invalid' : ''), 'placeholder' => 'Query Condition']) }}
            {!! $errors->first('query_condition', '<p class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>