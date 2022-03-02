<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>QR Code Preview</title>
</head>

<body>
    <center>
        <div id="qrcodeview"></div>
        <br>
        <a href="/">Visit Your Dashboard</a>
    </center>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        new QRCode(document.getElementById("qrcodeview"), {
            text: {{ $code }},
            label:'jQueryScript.Net',
            width: 500,
            height: 500,
        });
    </script>
</body>

</html>
