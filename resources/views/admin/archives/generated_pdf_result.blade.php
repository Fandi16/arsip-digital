<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generated PDF</title>
    <style>
        body { margin: 0; padding: 0; }
        .page { page-break-after: always; }
        img { width: 100%; height: auto; }
    </style>
</head>
<body>
    @foreach ($dataImages as $img)
        <div class="page">
            <img src="{{ $img }}" alt="Image">
        </div>
    @endforeach
</body>
</html>
