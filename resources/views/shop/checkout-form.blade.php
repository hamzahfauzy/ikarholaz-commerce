<h4>{{__('Customer Identity')}}</h4>
<div class="form-group">
    <label for="">{{__('First Name')}}</label>
    <input type="text" class="form-control" name="first_name" required="" value="{{old('first_name')}}">
</div>
<div class="form-group">
    <label for="">{{__('Last Name')}}</label>
    <input type="text" class="form-control" name="last_name" required="" value="{{old('last_name')}}">
</div>
<div class="form-group">
    <label for="">{{__('Email')}}</label>
    <input type="email" class="form-control" name="email" required="" value="{{old('email')}}">
</div>
<?php
$is_event = false;
foreach(cart()->all_lists()->get() as $cart)
{
    $is_event = ( ($cart->parent && $cart->parent->parent->categories->contains(config('reference.event_kategori'))) || $cart->categories->contains(config('reference.event_kategori')) || $cart->categories->contains(config('reference.voucher_kategori')) );
    if($is_event) break;
}
?>
@if(!$is_event)
<div class="form-group">
    <label for="">{{__('Province')}}</label>
    <select name="province_id" class="form-control" onchange="getDistrict(this.value,'#dest_id')" required="" value="{{old('province_id')}}">
        <option value="">- Pilih Provinsi -</option>
        @foreach($provinces as $province)
        <option value="{{$province->province_id}}">{{$province->province}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="">{{__('District')}}</label>
    <select name="dest_id" id="dest_id" class="form-control" required="" onchange="resetCalculation()">
        <option value="">- Pilih Provinsi terlebih dahulu -</option>
    </select>
</div>
<div class="form-group">
    <label for="">{{__('Address')}}</label>
    <textarea name="address" class="form-control" cols="30" rows="10" required="">{{old('address')}}</textarea>
</div>
<div class="form-group">
    <label for="">{{__('Postal Code')}}</label>
    <input type="text" class="form-control" name="postal_code" required="" value="{{old('postal_code')}}">
</div>
@endif
<div class="form-group">
    <label for="">{{__('Whatsapp Number').' Ex. 08123xxxx'}}</label>
    <input type="text" class="form-control" name="phone_number" required="" value="{{old('phone_number')}}">
</div>