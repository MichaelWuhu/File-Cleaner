<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Filename Cleaner</title>
</head>

<body>
    <h1>Smart Filename Cleaner</h1>

    <form method="POST" action="/clean">
        @csrf

        <textarea
            name="filenames"
            rows="12"
            cols="70"
            placeholder="Paste filenames here...">{{ old('filenames', $input ?? '') }}</textarea>

        <br><br>

        <strong>Options:</strong><br>

        <label>
            <input
                type="checkbox"
                name="lowercase"
                {{ old('lowercase', $lowercase ?? true) ? 'checked' : '' }}>
            Lowercase
        </label><br>

        <label>
            <input
                type="checkbox"
                name="hyphens"
                {{ old('hyphens', $hyphens ?? true) ? 'checked' : '' }}>
            Replace spaces/underscores with hyphens
        </label><br>

        <label>
            <input
                type="checkbox"
                name="dedupe"
                {{ old('dedupe', $dedupe ?? true) ? 'checked' : '' }}>
            Remove duplicate words
        </label><br>

        <label>
            <input
                type="checkbox"
                name="remove_copy"
                {{ old('remove_copy', $removeCopy ?? true) ? 'checked' : '' }}>
            Remove (1), (2), etc.
        </label><br><br>

        <button type="submit">Clean Filenames</button>
    </form>

    @if ($errors->any())
    <div style="color: red; margin-top: 20px;">
        @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    @isset($results)
    <h2>Results</h2>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Original</th>
                <th>Cleaned</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $result)
            <tr>
                <td>{{ $result['original'] }}</td>
                <td>{{ $result['cleaned'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endisset
</body>

</html>