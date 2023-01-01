<div class="box box-info padding-1">
    <div class="box-body">
        <div class="form-group">
            {{ Form::label('Nomor') }}
            {{ Form::text('nomor', $model->nomor, ['class' => 'form-control' . ($errors->has('nomor') ? ' is-invalid' : ''), 'placeholder' => 'nomor']) }}
            {!! $errors->first('nomor', '<p class="invalid-feedback">:message</p>') !!}
        </div>
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>