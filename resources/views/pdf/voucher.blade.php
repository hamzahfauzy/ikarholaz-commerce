<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Voucher</title>
</head>
<style>
@page { margin: 0px; }
body { margin: 0px; }
</style>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td width="282">
            <img src="{{$bg}}" width="100%" height="100%" style="object-fit:cover">
        </td>
        <td>
            <div style="width:100%;text-align:center">
                <b>e-Voucher</b><br>
                #{{$transaction->id}} | {{$cf['nama_merchant']}}<br>
                Rp. {{$product->price_formated}}<br>
                <img src="{{$barcode}}" width="100" height="100">
            </div>
        </td>
    </tr>
</table>
</body>
</html>