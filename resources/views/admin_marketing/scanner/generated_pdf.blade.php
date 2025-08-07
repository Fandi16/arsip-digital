<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .page {
            page-break-after: always;
            text-align: center;
        }
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    @foreach($images as $image)
        <div class="page">
            <img src="{{ $image }}">
        </div>
    @endforeach
</body>
</html>
