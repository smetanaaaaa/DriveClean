<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveClean - Удаление метаданных из фотографий</title>
    <link rel="stylesheet" href="{{ asset('/assets/main/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- EXIF.js for reading metadata -->
    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
    <!-- piexifjs for selective metadata removal -->
    <script src="https://cdn.jsdelivr.net/npm/piexifjs@1.0.6/piexif.min.js"></script>
    <!-- JSZip for multiple files download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
</head>

<body>
    {{ $slot }}
</body>

</html>
