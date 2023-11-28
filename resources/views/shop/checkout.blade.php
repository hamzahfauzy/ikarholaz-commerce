@extends('layouts.app')

@section('content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row text-center">
                <div class="col-sm-12">
                    <h3 class="m-t-20">{{__('Checkout')}}</h3>
                    <div class="border mx-auto d-block m-b-20"></div>
                </div>
            </div>
            <!-- end row -->

            <form action="{{route('shop.place-order')}}" method="post" onsubmit="return placeOrder()" id="checkout-form">
            @csrf
            <div class="row">
                <div class="col-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif
                </div>
                <div class="col-12 col-md-6">
                    <div class="card-box">
                        @include('shop.checkout-form')
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card-box">
                        @include('shop.summary')
                        <div class="clearfix"></div>
                    </div>
                    <button class="btn btn-success btn-block btn-order">Kirim</button>
                </div>
            </div>
            </form>
            <!-- end row -->
        </div> <!-- container -->

    </div> <!-- content -->

</div>


<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->
@endsection

@section('script')
<script>
var all_district = []
var all_services = []
var all_payment_methods = []
async function getDistrict(province_id, target_element)
{
    document.querySelector(target_element).innerHTML = "<option value=''>Loading...</option>"
    var request = await fetch('/api/get-district/'+province_id)
    var response = await request.json()
    all_district = response
    document.querySelector(target_element).innerHTML = "<option value=''>- Pilih Kabupaten / Kota -</option>"
    response.forEach(val => {
        var option = document.createElement("option");
        option.text = val.city_name;
        option.value = val.city_id;
        document.querySelector(target_element).appendChild(option);
    })
}

async function getService(courier, target_element)
{
    var district = document.querySelector('#dest_id').value
    if(district == '')
    {
        alert('Pilih Kabupaten / Kota terlebih dahulu')
        return
    }
    document.querySelector(target_element).innerHTML = "<option value=''>Loading...</option>"
    var request = await fetch('/api/get-service/'+courier+'?dest='+district+'&weight={{cart()->get_weight()}}')
    var response = await request.json()
    all_services = response
    document.querySelector(target_element).innerHTML = "<option value=''>- Pilih Servis -</option>"
    response.forEach((val,index) => {
        var option = document.createElement("option");
        option.text = val.service+' ('+val.cost[0].value+' - '+val.cost[0].etd+')';
        option.value = index;
        document.querySelector(target_element).appendChild(option);
    })
}

async function getPaymentChannel()
{
    var payment_metod = document.querySelector('#payment_method')
    payment_method.innerHTML = "<option value=''>Loading...</option>"
    var request = await fetch('/api/get-payment-channel')
    var response = await request.json()
    all_payment_methods = response
    payment_method.innerHTML = "<option value=''>- Pilih Metode Pembayaran -</option>"
    payment_method.innerHTML += "<option value='cash'>Cash</option>"
    response.data.forEach(val => {
        if(val.active)
        {
            var option = document.createElement("option");
            option.text = val.name + " (" + val.total_fee.flat + ")"
            option.value = val.code
            payment_method.appendChild(option);
        }
    })
}

function resetCalculation()
{
    var donasi = document.querySelector("#donasi").value
    donasi = parseInt(donasi)
    document.querySelector('#courier').value = ''
    document.querySelector('#payment_method').innerHTML = "<option value=''>- Pilih -</option>"
    document.querySelector("#service").innerHTML = "<option value=''>- Pilih -</option>"
    var subtotal = document.querySelector('#total').dataset.total
    subtotal = parseInt(subtotal)+donasi
    document.querySelector('#total').innerHTML = new Intl.NumberFormat().format(subtotal) 
}

function recalculateSubtotal()
{
    var donasi = document.querySelector("#donasi").value
    donasi = parseInt(donasi)
    var payment_method = document.querySelector("#payment_method").value
    var subtotal = document.querySelector('#total').dataset.total
    var shipping_service = document.querySelector('#service') ? document.querySelector('#service').value : 0
    var shipping_rates = shipping_service ? all_services[shipping_service].cost[0].value : 0
    var payment_channel = all_payment_methods.data.find(e => e.code == payment_method)
    var payment_fee = payment_channel.total_fee.flat
    var total = parseInt(subtotal)+parseInt(shipping_rates)+parseInt(payment_fee)+donasi
    document.querySelector('#total').innerHTML = new Intl.NumberFormat().format(total)   
}

async function placeOrder()
{
    event.preventDefault();
    document.querySelector(".btn-order").disabled="disabled"
    document.querySelector(".btn-order").innerHTML="Mohon Menunggu..."
    // check nomor kartu
    if(document.querySelectorAll('.nomorkartu'))
    {
        var nomorkartu = document.querySelectorAll('.nomorkartu')
        console.log(nomorkartu)
        for(var i=0;i<nomorkartu.length;i++)
        {
            var check = await fetch('/api/get-kartu/'+nomorkartu[i].value)
            console.log(check)
            if(!check.ok)
            {
                alert('nomor kartu harus valid')
                return false
            }
        }
    }
    $('#checkout-form').submit();
    return true
}


$('.nomor_kartu').change(e => {
    var nomorkartu = $(e.target).val()
    if(nomorkartu == "")
    {
        $(e.target).next().html('Nomor kartu tidak boleh kosong')
        return
    }
    fetch('/api/get-kartu/'+nomorkartu)
    .then(res => {
        if (!res.ok) {
            throw Error(res.statusText);
        }
        return res.json()
    })
    .then(res => {
        $(e.target).next().html('Kartu ditemukan atas nama : '+res.name)
    })
    .catch(function(error) {
        $(e.target).next().html('Kartu tidak ditemukan')
        console.log(error);
    })
})

@if(
    (cart()->all_lists()->first()->parent && cart()->all_lists()->first()->parent->parent->categories->contains(config('reference.event_kategori')))
     || 
     cart()->all_lists()->first()->categories->contains(config('reference.event_kategori'))
     || 
     cart()->all_lists()->first()->categories->contains(config('reference.voucher_kategori'))
     )
getPaymentChannel()
@endif
</script>
@endsection