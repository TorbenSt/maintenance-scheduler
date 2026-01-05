<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Termin ablehnen</title>
</head>
<body>
    <h1>Termin passt nicht</h1>

    <form method="POST"
          action="{{ route('public.proposals.reject', ['token' => $proposal->token] + request()->query()) }}">
        @csrf

        <label for="comment">Kommentar / Wunschzeit</label><br>
        <textarea id="comment" name="comment" rows="4" cols="40">{{ old('comment') }}</textarea><br><br>

        <button type="submit">Senden</button>
    </form>
</body>
</html>
