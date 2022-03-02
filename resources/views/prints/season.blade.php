<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>

<body>
    <table>
        <tr>
            <td><div id="qrcodeseason"></div></td>
            <td>
                <table style="margin-left:10px;">
                    <tr>
                        <td>NIC</td>
                        <td>:</td>
                        <td>{{ $season->nic }}</td>
                    </tr>
                    <tr>
                        <td>CODE</td>
                        <td>:</td>
                        <td>{{ $season->authcode }}</td>
                    </tr>
                    <tr>
                        <td>VALID</td>
                        <td>:</td>
                        <td>{{ $season->from }} to {{ $season->to }}</td>
                    </tr>
                    <tr>
                        <td>ROUTE</td>
                        <td>:</td>
                        <td>{{ $season['location1data']->location }} to {{ $season['location2data']->location }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
