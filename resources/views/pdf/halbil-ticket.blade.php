<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket</title>
</head>
<body>
<style>
#bg{
    margin-top: 0px;
    margin-left: 0px;
    padding-top:80px;
    font-size:14px;
    page-break-after: always;
}
</style>
    @foreach($participants as $index => $p)
    <div id="bg">
        <img src="{{$bg}}" style='position:absolute;top:0px;z-index:-1;width:500px;height:650px;object-fit:contain;' />
        <div style="width:100%;text-align:center">
            <b>e-TICKET</b><br>
            <img src="{{$qrcode[$index]}}" style="width:125px;height:125px;margin:0px;">
        </div>
        <div  style="width:100%;text-align:center;margin-bottom:5px;">NAMA : <b>{{$p['name']}}</b></div>
        <div  style="width:100%;text-align:center;margin-bottom:5px;">ANGKATAN : <b>{{$p['graduation_year']}}</b></div>
        <div  style="width:100%;text-align:center;margin-bottom:5px;">NAMA ACARA : <b>HALAL BI HALAL 2024</b></div>
        <div  style="width:100%;text-align:center;margin-bottom:5px;">TANGGAL DAN WAKTU ACARA : <b>KAMIS 9 MEI 2024 PKL 10.00</b></div>
        <div  style="width:100%;text-align:center;margin-bottom:20px;">NO. SEAT : <b>C{{str_pad(($lastTicket+$index+1), 3, "0", STR_PAD_LEFT )}}</b></div>
    </div>
    @endforeach
</body>
</html>