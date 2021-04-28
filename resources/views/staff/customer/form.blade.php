<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('user_id') }}
            {{ Form::text('user_id', $customer->user_id, ['class' => 'form-control' . ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => 'User Id']) }}
            {!! $errors->first('user_id', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('first_name') }}
            {{ Form::text('first_name', $customer->first_name, ['class' => 'form-control' . ($errors->has('first_name') ? ' is-invalid' : ''), 'placeholder' => 'First Name']) }}
            {!! $errors->first('first_name', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('last_name') }}
            {{ Form::text('last_name', $customer->last_name, ['class' => 'form-control' . ($errors->has('last_name') ? ' is-invalid' : ''), 'placeholder' => 'Last Name']) }}
            {!! $errors->first('last_name', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('email') }}
            {{ Form::text('email', $customer->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Email']) }}
            {!! $errors->first('email', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('province_id') }}
            {{ Form::text('province_id', $customer->province_id, ['class' => 'form-control' . ($errors->has('province_id') ? ' is-invalid' : ''), 'placeholder' => 'Province Id']) }}
            {!! $errors->first('province_id', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('district_id') }}
            {{ Form::text('district_id', $customer->district_id, ['class' => 'form-control' . ($errors->has('district_id') ? ' is-invalid' : ''), 'placeholder' => 'District Id']) }}
            {!! $errors->first('district_id', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('subdistrict_id') }}
            {{ Form::text('subdistrict_id', $customer->subdistrict_id, ['class' => 'form-control' . ($errors->has('subdistrict_id') ? ' is-invalid' : ''), 'placeholder' => 'Subdistrict Id']) }}
            {!! $errors->first('subdistrict_id', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('address') }}
            {{ Form::text('address', $customer->address, ['class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : ''), 'placeholder' => 'Address']) }}
            {!! $errors->first('address', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('postal_code') }}
            {{ Form::text('postal_code', $customer->postal_code, ['class' => 'form-control' . ($errors->has('postal_code') ? ' is-invalid' : ''), 'placeholder' => 'Postal Code']) }}
            {!! $errors->first('postal_code', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('phone_number') }}
            {{ Form::text('phone_number', $customer->phone_number, ['class' => 'form-control' . ($errors->has('phone_number') ? ' is-invalid' : ''), 'placeholder' => 'Phone Number']) }}
            {!! $errors->first('phone_number', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('province_name') }}
            {{ Form::text('province_name', $customer->province_name, ['class' => 'form-control' . ($errors->has('province_name') ? ' is-invalid' : ''), 'placeholder' => 'Province Name']) }}
            {!! $errors->first('province_name', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('district_name') }}
            {{ Form::text('district_name', $customer->district_name, ['class' => 'form-control' . ($errors->has('district_name') ? ' is-invalid' : ''), 'placeholder' => 'District Name']) }}
            {!! $errors->first('district_name', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('subdistrict_name') }}
            {{ Form::text('subdistrict_name', $customer->subdistrict_name, ['class' => 'form-control' . ($errors->has('subdistrict_name') ? ' is-invalid' : ''), 'placeholder' => 'Subdistrict Name']) }}
            {!! $errors->first('subdistrict_name', '<p class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>