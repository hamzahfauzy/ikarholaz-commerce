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
                    <h3 class="m-t-20">{{__('Merchant Section')}}</h3>
                    <div class="border mx-auto d-block m-b-20"></div>
                    <a class="btn btn-danger" href="{{route('merchant.logout')}}">Keluar Merchant</a>
                </div>
            </div>

            <div class="row m-t-20">
                <div class="col-12 col-md-8 m-auto">
                    <div id="reader" width="600px"></div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container -->

    </div> <!-- content -->

</div>


<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->
@endsection

@section('script')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script type="text/javascript">

let html5QrcodeScanner = new Html5QrcodeScanner(
  "reader",
  { fps: 10, qrbox: {width: 500, height: 500} },
  /* verbose= */ false);
html5QrcodeScanner.render(onScanSuccess, onScanFailure);

async function onScanSuccess(decodedText, decodedResult) {
    // handle the scanned code as you like, for example:
    // console.log(`Code matched = ${decodedText}`, decodedResult);
    $('#html5-qrcode-button-camera-stop').click()

    var formData = new FormData;
    formData.append('_token','{{ csrf_token() }}')
    formData.append('voucher_code',decodedText)
    var request = await fetch('{{route('merchant.voucher-detail')}}',{
        method: 'POST',
        body: formData
    })

    var response = null

    if(request.ok)
    {
        response = await request.json()
        if(response.status == 'success')
        {
            if(confirm('Voucher valid. Apakah anda mau menukarkan voucher ini ?'))
            {
                request = await fetch('{{route('merchant.claim-voucher')}}',{
                    method: 'POST',
                    body: formData
                })

                if(request.ok)
                {
                    response = await request.json()
                    alert(response.message)
                    return
                }
            }
            return
        }
        else
        {
            alert(response.message)
            return
        }
    }

    response = await request.json()
    alert(response.message)
    
}
  
function onScanFailure(error) {
    // handle scan failure, usually better to ignore and keep scanning.
    // for example:
    console.warn(`Code scan error = ${error}`);
}
</script>
@endsection