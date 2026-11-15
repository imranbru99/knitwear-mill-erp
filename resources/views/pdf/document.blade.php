<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: "Noto Sans Bengali", "Nirmala UI", sans-serif; color: #241c16; }
        h1 { font-weight: 500; }
        table { width: 100%; border-collapse: collapse; }
        td { border-bottom: 1px solid #d9cbb6; padding: .3rem 0; vertical-align: top; }
    </style>
</head>
<body>
    <p>নিটওয়্যার কারখানা · {{ $banglaDate }}</p>
    <h1>{{ $title }}</h1>
    <table>
        @foreach($record->getAttributes() as $key => $value)
            @continue(in_array($key, ['created_by', 'updated_by', 'deleted_at']))
            <tr><td>{{ $key }}</td><td>{{ is_scalar($value) || $value === null ? $value : json_encode($value) }}</td></tr>
        @endforeach
    </table>
</body>
</html>
