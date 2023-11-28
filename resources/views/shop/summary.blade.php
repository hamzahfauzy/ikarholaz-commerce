<h4>{{__('Summary')}}</h4>
@if(cart()->count())
<table class="table table-striped">
    @foreach(cart()->all_lists()->get() as $cart)
    <tr>
        <td><a href="{{route('shop.cart-remove',$cart->id)}}"><i class="fa fa-times"></i></a></td>
        <td>
            <a href="{{route('shop.product-detail',$cart->parent?$cart->parent->parent->slug:$cart->slug)}}">{{$cart->parent?$cart->parent->parent->name.' - ':''}}{{$cart->name}}</a><br>
            <b>{{$cart->price_formated}} x {{cart()->get($cart->id)}}</b><br>
        </td>
        <td>{{number_format(cart()->subtotal($cart->id))}}</td>
    </tr>
    @if(!($cart->categories->contains(config('reference.voucher_kategori'))))
    <tr>
        <td colspan="3"><input type="text" name="notes[]" id="" class="form-control" placeholder="Catatan"></td>
    </tr>
    @endif
    @for($i=0;$i<cart()->get($cart->id);$i++)
    @if(count(cart()->custom_fields($cart)))
    <tr>
        <td>{{($i+1)}}</td>
        <td colspan="2">
        @foreach(cart()->custom_fields($cart) as $cf)
            <div class="form-group">
                @if($cf->field_key == 'NRA')
                <label for="">{{ucwords(__($cf->field_key))}}</label>
                <input type="{{$cf->field_type}}" name="cart_item[{{$cart->id}}][{{$cf->id}}][]" class="form-control {{$cf->field_key}}" placeholder="{{ucwords($cf->field_key)}}">
                @elseif($cf->field_key == 'tahun_lulus')
                <label for="">{{ucwords(__($cf->field_key))}}</label>
                <select name="cart_item[{{$cart->id}}][{{$cf->id}}][]" id="" class="form-control {{$cf->field_key}}" required>
                    <option value="">Pilih Tahun</option>
                    @for($j=1974;$j<=2021;$j++)
                    @if($j == 1978)
                    @continue
                    @endif
                    <option value="{{$j}}">{{$j}}</option>
                    @endfor
                    <option value="0">Bukan Alumni</option>
                </select>
                @else
                @php($label=$cf->field_key)
                @if(($cart->parent && $cart->parent->parent->categories->contains(config('reference.event_kategori')) && $cf->field_key == 'nama') || ($cart->categories->contains(config('reference.event_kategori')) && $cf->field_key == 'nama'))
                @php($label="Nama Peserta")
                @endif
                <label for="">{{$label}}</label>
                <input type="{{$cf->field_type}}" name="cart_item[{{$cart->id}}][{{$cf->id}}][]" class="form-control {{$cf->field_key}}" placeholder="{{ucwords($label)}}" required>
                @endif
                <small></small>
            </div>
        @endforeach
        </td>
    </tr>
    @endif
    @endfor
    @endforeach
    @if(! (($cart->parent && $cart->parent->parent->categories->contains(config('reference.event_kategori'))) || $cart->categories->contains(config('reference.event_kategori')) || $cart->categories->contains(config('reference.voucher_kategori')) ))
    <tr>
        <td></td>
        <td>Kurir</td>
        <td>
            <select class="form-control" name="courier" id="courier" required="" onchange="getService(this.value, '#service')">
                <option value="">- Pilih -</option>
                <option value="jne">JNE</option>
                <option value="pos">POS</option>
                <option value="tiki">TIKI</option>
                <option value="pickup">Ambil Sendiri</option>
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
    @endif
    @if(cart()->subtotal() > 0)
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
    @else
    <input type="hidden" name="payment_method" value="cash">
    @endif
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