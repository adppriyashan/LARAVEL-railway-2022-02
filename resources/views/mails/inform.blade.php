<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Information By Train Master</title>
</head>

<body>
    <h4>Train Schedule Changed</h4>
    <p>Train schedule ({{ $alldata[0]->turn }}) has been changed. {{ $alldata[1] == 1 ? 'departure' : 'arrival' }}
        location rescheduled to
        {{ $alldata[0]->slot }}</p>
</body>

</html>
