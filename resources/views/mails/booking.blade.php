<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Booking : {{ $data->id }}</title>
</head>

<body>
    <p>Thank you for the reservation. Please find your seat position codes (QR Codes) for authentication process in the
        station.</p>
    <br>
    <p>Turn No : {{ $data->turn }}</p>
    <table>
        <thead>
            <tr>
                <th>Seat No</th>
                <th>URL</th>
            </tr>
            @foreach ($data['seatsdata'] as $seat)
                <tr>
                    <td>No {{ $seat->seat }}</td>
                    <td><a href="{{ route('pass.view', $seat->id) }}">Download Pass</a></td>
                </tr>
            @endforeach
        </thead>
    </table>
</body>

</html>
