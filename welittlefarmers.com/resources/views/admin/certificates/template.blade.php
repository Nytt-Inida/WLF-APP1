<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif
        }
    </style>
</head>

<body>
    <h1>Certificate of Completion</h1>
    <p>{{ $certificate->user->name }} — {{ $certificate->course->title }}</p>
    <p>Certificate #: {{ $certificate->certificate_number }}</p>
    <p>Issued: {{ $certificate->issued_at->format('M d, Y') }}</p>
</body>

</html>
