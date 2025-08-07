<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .page-break {
            page-break-after: always;
        }
        img {
            display: block;
            width: 100%;  /* Full lebar halaman */
            height: auto; /* Proporsional */
        }
    </style>
</head>
<body>
    @foreach ($images as $index => $img)
        <img src="{{ $img }}">
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
