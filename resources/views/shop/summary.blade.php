<h4>{{__('Summary')}}</h4>
@if(cart()->count())
<table class="table table-striped">
    @foreach(cart()->all_lists()->get() as $cart)
    <tr>
        <td><a href="{{route('shop.cart-remove',$cart->id)}}"><i class="fa fa-times"></i></a></td>
        <td>
        <a href="{{route('shop.product-detail',$cart->parent?$cart->parent->parent->slug:$cart->slug)}}">{{$cart->parent?$cart->parent->parent->name.' - ':''}}{{$cart->name}}</a><br>
        <b>{{$cart->price_formated}} x {{cart()->get($cart->id)}}</b>
        @foreach(cart()->custom_fields($cart) as $cf)
        @for($i=0;$i<cart()->get($cart->id);$i++)
        <div class="form-group">
            <input type="{{$cf->field_type}}" name="cart_item[{{$cart->id}}][{{$cf->id}}][]" class="form-control nomorkartu" placeholder="{{$cf->field_key}}" required>
            <small></small>
        </div>
        @endfor
        @endforeach
        </td>
        <td>{{number_format(cart()->subtotal($cart->id))}}</td>
    </tr>
    @endforeach
    <tr>
        <td></td>
        <td>Kurir</td>
        <td>
            <select class="form-control" name="courier" id="courier" required="" onchange="getService(this.value, '#service')">
                <option value="">- Pilih -</option>
                <option value="jne">JNE</option>
                <option value="pos">POS</option>
                <option value="tiki">TIKI</option>
            </select>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>Servis</td>
        <td>
            <select class="form-control" name="service" id="service" required="" onchange="getPaymentChannel()">
                <option value="">- Pilih -</option>
            </select>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>Metode Pembayaran</td>
        <td>
            <select class="form-control" name="payment_method" id="payment_method" required="" onchange="recalculateSubtotal()">
                <option value="">- Pilih -</option>
            </select>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>Donasi</td>
        <td><input type="number" name="donasi" id="donasi" class="form-control" value="0" step=".1" onchange="recalculateSubtotal()"></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:right"><b>Total</b></td>
        <td><span id="total" data-total="{{cart()->subtotal()}}">{{number_format(cart()->subtotal())}}</span></td>
    </tr>
</table>
@else
<center>
<i>{{__('Your cart is empty!')}}</i>
<br>
<a href="{{route('shop.index')}}" class="btn btn-success">{{__('Back to Shop')}}</a>
</center>
@endif