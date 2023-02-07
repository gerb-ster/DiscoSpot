
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
    You are now connected to Discogs & Spotify!
</p>
<p>
    <a href="{{ route('app.playlist') }}">Click here to test the Spotify API</a>
</p>
</body>
</html>
