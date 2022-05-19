<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('Nama') }}
            {{ Form::text('name', $event->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'nama']) }}
            {!! $errors->first('name', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Kategori') }}
            {{ Form::select('category',['General'=>'General','Pengurus'=>'Pengurus','Angkatan'=>'Angkatan','Lintas Angkatan'=>'Lintas Angkatan'], $event->category, ['class' => 'form-control' . ($errors->has('category') ? ' is-invalid' : ''), 'placeholder' => 'Pilih Kategori']) }}
            {!! $errors->first('category', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('deskripsi') }}
            {{ Form::textarea('description', $event->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'description']) }}
            {!! $errors->first('description', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('waktu_mulai') }}
            {{ Form::input('datetime-local','start_time', $event->start_time, ['class' => 'form-control' . ($errors->has('start_time') ? ' is-invalid' : ''), 'placeholder' => 'waktu_mulai']) }}
            {!! $errors->first('start_time', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('waktu_selesai') }}
            {{ Form::input('datetime-local','end_time', $event->end_time, ['class' => 'form-control' . ($errors->has('end_time') ? ' is-invalid' : ''), 'placeholder' => 'waktu_selesai']) }}
            {!! $errors->first('end_time', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('tempat') }}
            {{ Form::text('place', $event->place, ['class' => 'form-control' . ($errors->has('place') ? ' is-invalid' : ''), 'placeholder' => 'tempat']) }}
            {!! $errors->first('place', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('lokasi') }}
            {{ Form::text('location', $event->location, ['class' => 'form-control' . ($errors->has('location') ? ' is-invalid' : ''), 'placeholder' => 'lokasi']) }}
            {!! $errors->first('location', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('PIC') }}
            {{ Form::text('PIC', $event->PIC, ['class' => 'form-control' . ($errors->has('PIC') ? ' is-invalid' : ''), 'placeholder' => 'PIC']) }}
            {!! $errors->first('PIC', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('tag') }}
            {{ Form::text('tag', $event->tag, ['class' => 'form-control' . ($errors->has('tag') ? ' is-invalid' : ''), 'placeholder' => 'tag']) }}
            {!! $errors->first('tag', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('image') }}
            {{ Form::input('file','image', $event->image, ['class' => 'form-control' . ($errors->has('image') ? ' is-invalid' : ''), 'placeholder' => 'image']) }}
            {!! $errors->first('image', '<p class="invalid-feedback">:message</p>') !!}
        </div>
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>