
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DiscoSpot</title>
</head>
<body>
<p>
    Hello {{ \Illuminate\Support\Facades\Auth::user()->name }},<br />
    <a href="{{ route('auth.spotify.connect') }}">Click here to connect to spotify</a>
</p>
</body>
</html>
