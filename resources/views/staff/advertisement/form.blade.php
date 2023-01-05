<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('Event') }}
            {{ Form::select('event', ['REG'=>'REG','CEK NRA'=>'CEK NRA','ORDERTIKET'=>'ORDERTIKET','REGTIKET HUT4'=>'REGTIKET HUT4','ORDER VOUCHER' => 'ORDER VOUCHER'], $model->event, ['class' => 'form-control' . ($errors->has('event') ? ' is-invalid' : '')]) }}
            {!! $errors->first('event', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        
        <div class="form-group">
            {{ Form::label('Content') }}
            {{ Form::textArea('contents', $model->contents, ['class' => 'form-control' . ($errors->has('contents') ? ' is-invalid' : ''), 'placeholder' => 'contents']) }}
            {!! $errors->first('contents', '<p class="invalid-feedback">:message</p>') !!}
        </div>
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>