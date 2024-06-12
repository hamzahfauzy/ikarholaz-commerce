@extends('layouts.app')

@section('content')
<style>
.dropdown-menu.select-tahun {         
  max-height: 250px;
  overflow-y: auto;
}
.property-card.property-horizontal .property-content .listingInfo, .property-card.property-horizontal .property-content {
    height:auto!important;
}
.property-card .property-image {
    height:100%;
    min-height:100px;
}
.property-card.property-horizontal .property-content .property-action {
    position: inherit;
}
.property-action {
    padding-left:0px!important;
    padding-right:0px!important;
}
.property-card.property-horizontal.bg-white.active {
    background-color:rgba(0,0,0,0.3)!important;
}

.btn-kta-primary{
    background:#0099FF;
    color:white;
    padding:12px;
}

.btn-kta-warning{
    background:#FE9900;
    color:white;
    padding:12px;
}

.btn-kta-pink{
    background:#FE33FF;
    color:white;
    padding:12px;
}

.btn-kta-danger{
    background:#FF2121;
    color:white;
    padding:12px;
}
</style>
<!-- START carousel-->
@include('home.modal-kta')
@include('home.modal-kta-regular')
@include('home.modal-kta-custom')
@include('home.modal-nra-cantik')
<div id="carouselExampleCaption" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner" role="listbox" style="height: calc(100vh - 113px);">
        <div style="width:100%;height:100%;position:absolute;background:rgba(0,0,0,0.5);z-index:1"></div>
        <div class="carousel-item active">
            <img src="{{asset('assets/images/kolasee.jpeg')}}" style="object-fit:cover;width:100%;object-position:center;height: calc(100vh - 113px);">
            <div class="carousel-caption" style="transform: translate(-50%, -50%);top: 50%;left: 50%;width:100%;bottom:unset;padding-bottom:0px;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#modal-order-regular" class="btn btn-block btn-kta-primary">ORDER KTA DESAIN TERSEDIA</a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#modal-order-custom" class="btn btn-block btn-kta-warning">ORDER KTA DESAIN SENDIRI</a>
                        </div>

                        <div class="col-md-3 mb-2">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#modal-nra-cantik" class="btn btn-block btn-kta-pink">KONVERSI KE NRA CANTIK</a>
                        </div>

                        <div class="col-md-3 mb-2">
                            <a href="{{route('shop.product-list',\App\Models\Category::find(getenv('DESAIN_KARTU_KATEGORI',1))->slug)}}" class="btn btn-block btn-kta-danger">REORDER KTA</a>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-12">
                            <h2 class="text-white">Konversi ke NRA Cantik</h2>
                        </div>
                        <div class="col-12 col-sm-9 mx-auto">
                            <form action="" onsubmit="return checkKartu()">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button class="btn waves-effect waves-light btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tahun Lulus</button>
                                        <ul class="dropdown-menu select-tahun" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                            @for($i=1974;$i<=2018;$i++)
                                            <li><a href="javascript:void(0)" class="dropdown-item" onclick="no_seri.value=({{$i}}).toString().slice(-2)">{{$i}}</a></li>
                                            @endfor
                                        </ul>
                                    </div>
                                    <input type="text" id="no_seri" name="no_seri" class="form-control" placeholder="No. Seri" readonly>
                                    <input type="text" id="no_kartu" name="no_kartu" maxlength="8" class="form-control" placeholder="Nomor Kartu. Ex : 01234567 (8 Digit)">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn waves-effect waves-light btn-primary">Cek</button>
                                    </span>
                                </div>
                            </form>
                            <p></p>
                            <p class="text-white">
                                Atau
                            </p>
                            <div>

                            </div>
                            <a href="javascript:void(0)" class="btn btn-success" data-toggle="modal" data-target="#modal-order-regular">Order Kartu (Bisa Kustom Desain)</a>
                            
                        </div>
                    </div> -->

                </div>
            </div>
        </div>
        <!-- <div class="carousel-item">
            <img src="{{asset('assets/images/kolasee.jpeg')}}" style="object-fit:cover;width:100%;object-position:center;height: calc(100vh - 113px);">
            <div class="carousel-caption d-none d-md-block" style="transform: translate(-50%, -50%);top: 50%;left: 50%;width:100%;bottom:unset;padding-bottom:0px;">
                <h4 class="text-white">Jelajahi seluruh Produk di GERAI IKARHOLAZ</h4>
                <p>
                    <a href="{{route('shop.index')}}" class="btn btn-success">Jelajahi Produk</a>
                </p>
            </div>
        </div> -->
    </div>
    <a class="carousel-control-prev" href="#carouselExampleCaption" role="button" data-slide="prev" style="z-index:1000">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleCaption" role="button" data-slide="next" style="z-index:1000">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<!-- END carousel-->

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row text-center">
                <div class="col-sm-12">
                    <h3 class="m-t-20">{{__('Latest Product')}}</h3>
                    <div class="border mx-auto d-block m-b-20"></div>
                </div>
            </div>
            <!-- end row -->

            <div class="row">
                @foreach($products as $product)
                @include('shop.product-list-template')
                @endforeach
            </div>
            <!-- end row -->
        </div> <!-- container -->

    </div> <!-- content -->

</div>


<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->
@endsection
@section('css')
<!-- Sweet Alert -->
<link href="{{asset('plugins/sweet-alert2/sweetalert2.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('script')
<!-- Sweet-Alert  -->
<script src="{{asset('plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
<script>
function checkKartu()
{
    event.preventDefault()
    if(!no_seri.value)
    {
        swal(    
            {
                title: 'Maaf!',
                text: 'Tahun lulus harus dipilih',
                type: 'error',
                confirmButtonColor: '#4fa7f3'
            }
        )
        return
    }
    var nomor_kartu = parseInt(no_kartu.value)
    var digit = `${nomor_kartu}`.length
    var no_request = nomor_kartu
    if(nomor_kartu < 10)
        nomor_kartu = '0000000'+nomor_kartu

    else if(nomor_kartu < 100)
        nomor_kartu = '000000'+nomor_kartu
    
    else if(nomor_kartu < 1000)
        nomor_kartu = '00000'+nomor_kartu

    else if(nomor_kartu < 10000)
        nomor_kartu = '0000'+nomor_kartu
    
    else if(nomor_kartu < 100000)
        nomor_kartu = '000'+nomor_kartu

    else if(nomor_kartu < 1000000)
        nomor_kartu = '00'+nomor_kartu

    else if(nomor_kartu < 10000000)
        nomor_kartu = '0'+nomor_kartu

    no_kartu.value = nomor_kartu
    var nomorkartu = no_seri.value +'.'+ nomor_kartu
    fetch('/api/cek-kartu/'+nomor_kartu)
    .then(res => {
        if (!res.ok) {
            throw Error(res.statusText);
        }
        return res.json()
    })
    .then(res => {
        swal({
                title: 'Selamat!',
                text: 'Nomor yang anda minta tersedia! Klik OK untk lanjut ke tahap berikutnya',
                type: 'success',
                showCancelButton: true,
                confirmButtonColor: '#4fa7f3',
                cancelButtonColor: '#d57171',
                confirmButtonText: 'Ok'
            }).then(function (result) {
                if(result.value)
                {
                    // to order kta page
                    fetch('/api/get-price/'+digit)
                    .then(res => res.text())
                    .then(res => {
                        $('#modal-nra-cantik').modal('hide'); 
                        $('#modal-order-kta').modal('show'); 
                        // $('[name=product_id]').val(found.id)
                        $('[name=digit]').val(digit)
                        $('[name=no_request]').val(no_request)
                        $('#no_kartu_fix').val(nomorkartu)
                        $('#harga_fix').val(res)
                    })
                }
            })
        
    })
    .catch(function(error) {
        swal(    
            {
                title: 'Maaf!',
                text: 'Nomor yang anda minta tidak tersedia! Silahkan pilih nomor yang lain',
                type: 'error',
                confirmButtonColor: '#4fa7f3'
            }
        )
        // var nra_cantiks = <?=$nra_cantiks?>;
        // var found = nra_cantiks.find(nra=>nra.name == nomorkartu);
        // if(found){
        //     swal({
        //         title: 'Selamat!',
        //         text: 'Nomor yang anda minta tersedia! Klik OK untk lanjut ke tahap berikutnya',
        //         type: 'success',
        //         showCancelButton: true,
        //         confirmButtonColor: '#4fa7f3',
        //         cancelButtonColor: '#d57171',
        //         confirmButtonText: 'Ok'
        //     }).then(function (result) {
        //         if(result.value)
        //         {
        //             // to order kta page
        //             fetch('/api/get-price/'+digit)
        //             .then(res => res.text())
        //             .then(res => {
        //                 $('#modal-nra-cantik').modal('hide'); 
        //                 $('#modal-order-kta').modal('show'); 
        //                 $('[name=product_id]').val(found.id)
        //                 $('[name=digit]').val(digit)
        //                 $('[name=no_request]').val(no_request)
        //                 $('#no_kartu_fix').val(nomorkartu)
        //                 $('#harga_fix').val(found.base_price)
        //             })
        //         }
        //     })

        // }else{
        //     swal(    
        //     {
        //         title: 'Maaf!',
        //         text: 'Nomor yang anda minta tidak tersedia! Silahkan pilih nomor yang lain',
        //         type: 'error',
        //         confirmButtonColor: '#4fa7f3'
        //     }
        // )
        // }

        console.log(error);
    })
}

function orderRegular()
{
    // fetch('/api/get-nomor-regular/'+tahun_lulus.value)
    // .then(res => {
    //     if (!res.ok) {
    //         throw Error(res.statusText);
    //     }
    //     return res.text()
    // })
    // .then(res => {
    //     no_kartu_regular.value = res
    // })
    // .catch(function(error) {
    //     console.log(error);
    // })
}

function pilihDesain(el)
{
    $('.container-desain:not([data-id='+el.dataset.id+'])').removeClass('active')
    $(el).toggleClass('active')
    $('input[name=desain_id]').val(el.dataset.id)
}
</script>
@endsection
