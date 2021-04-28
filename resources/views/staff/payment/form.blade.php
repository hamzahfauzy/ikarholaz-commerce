<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('transaction_id') }}
            {{ Form::text('transaction_id', $payment->transaction_id, ['class' => 'form-control' . ($errors->has('transaction_id') ? ' is-invalid' : ''), 'placeholder' => 'Transaction Id']) }}
            {!! $errors->first('transaction_id', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('total') }}
            {{ Form::text('total', $payment->total, ['class' => 'form-control' . ($errors->has('total') ? ' is-invalid' : ''), 'placeholder' => 'Total']) }}
            {!! $errors->first('total', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('admin_fee') }}
            {{ Form::text('admin_fee', $payment->admin_fee, ['class' => 'form-control' . ($errors->has('admin_fee') ? ' is-invalid' : ''), 'placeholder' => 'Admin Fee']) }}
            {!! $errors->first('admin_fee', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('checkout_url') }}
            {{ Form::text('checkout_url', $payment->checkout_url, ['class' => 'form-control' . ($errors->has('checkout_url') ? ' is-invalid' : ''), 'placeholder' => 'Checkout Url']) }}
            {!! $errors->first('checkout_url', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('payment_type') }}
            {{ Form::text('payment_type', $payment->payment_type, ['class' => 'form-control' . ($errors->has('payment_type') ? ' is-invalid' : ''), 'placeholder' => 'Payment Type']) }}
            {!! $errors->first('payment_type', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('merchant_ref') }}
            {{ Form::text('merchant_ref', $payment->merchant_ref, ['class' => 'form-control' . ($errors->has('merchant_ref') ? ' is-invalid' : ''), 'placeholder' => 'Merchant Ref']) }}
            {!! $errors->first('merchant_ref', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('status') }}
            {{ Form::text('status', $payment->status, ['class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : ''), 'placeholder' => 'Status']) }}
            {!! $errors->first('status', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('payment_reference') }}
            {{ Form::text('payment_reference', $payment->payment_reference, ['class' => 'form-control' . ($errors->has('payment_reference') ? ' is-invalid' : ''), 'placeholder' => 'Payment Reference']) }}
            {!! $errors->first('payment_reference', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('payment_code') }}
            {{ Form::text('payment_code', $payment->payment_code, ['class' => 'form-control' . ($errors->has('payment_code') ? ' is-invalid' : ''), 'placeholder' => 'Payment Code']) }}
            {!! $errors->first('payment_code', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('expired_time') }}
            {{ Form::text('expired_time', $payment->expired_time, ['class' => 'form-control' . ($errors->has('expired_time') ? ' is-invalid' : ''), 'placeholder' => 'Expired Time']) }}
            {!! $errors->first('expired_time', '<p class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>