<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('kode merchant') }}
            {{ Form::text('code', $model->code, ['class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : '')]) }}
            {!! $errors->first('code', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        
        <div class="form-group">
            {{ Form::label('name') }}
            {{ Form::text('name', $model->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : '')]) }}
            {!! $errors->first('name', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        
        <div class="form-group">
            {{ Form::label('No. WA (Jika nomor banyak, pisahkan dengan koma)') }}
            {{ Form::textArea('phone', $model->phone, ['class' => 'form-control' . ($errors->has('phone') ? ' is-invalid' : ''), 'placeholder' => 'phone']) }}
            {!! $errors->first('phone', '<p class="invalid-feedback">:message</p>') !!}
        </div>
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>