<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('Event') }}
            {{ Form::text('event', ['REG'=>'REG','CEK NRA'=>'CEK NRA','ORDERTIKET'=>'ORDERTIKET','REGTIKET HUT4'=>'REGTIKET HUT4'], $model->text, ['class' => 'form-control' . ($errors->has('event') ? ' is-invalid' : ''), 'placeholder' => 'event']) }}
            {!! $errors->first('event', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        
        <div class="form-group">
            {{ Form::label('Content') }}
            {{ Form::text('content', $model->content, ['class' => 'form-control' . ($errors->has('content') ? ' is-invalid' : ''), 'placeholder' => 'content']) }}
            {!! $errors->first('content', '<p class="invalid-feedback">:message</p>') !!}
        </div>
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>