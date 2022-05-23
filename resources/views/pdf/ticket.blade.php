<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket</title>
</head>
<body>
<style>
#bg{
    margin-top: 0px;
    margin-left: 0px;
    padding-top:80px;
    font-size:18px;
}
</style>
    <div id="bg">
        <img src="{{$bg}}" style='position:absolute;top:0px;z-index:-1;width:500px;height:650px;object-fit:contain;' />
        <div style="width:100%;text-align:center">
            <b>e-TICKET</b><br>
            <img src="{{$qrcode}}" style="width:125px;height:125px;margin:0px;">
        </div>
        <div  style="width:100%;text-align:center;margin-bottom:20px;">KODE BOOKING : <b>{{$transaction->id}}</b></div>
        <div  style="width:100%;text-align:center;margin-bottom:15px">{{$product->name}}</div>

        <div  style="width:100%;text-align:center">{{$cf['venue']}}</div>
        <div  style="width:100%;text-align:center;margin-bottom:15px;">{{date('d-m-Y H:i',strtotime(str_replace('T','',$cf['waktu']).':00'))}}</div>
        <div  style="width:100%;text-align:center"><strong>DAFTAR TAMU / PESERTA</strong></div>
        <div  style="width:100%;text-align:center;margin-bottom:15px">{!!nl2br($part)!!}</div>
        
        <div  style="width:100%;text-align:center"><strong>INFO PEMESANAN</strong></div>
        <div  style="width:100%;text-align:center">Pemesan : {{ $customer->full_name}}</div>
        <div  style="width:100%;text-align:center">Total Biaya : {{$transaction->total_formated}}</div>
        <div  style="width:100%;text-align:center">Pembayaran via {{$payment->payment_type}}</div>
        <div  style="width:100%;text-align:center">Waktu Bayar : {{$payment->updated_at->format('d-m-Y H:i')}}</div>
    </div>
</body>
</html>