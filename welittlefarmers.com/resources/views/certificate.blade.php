<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'Great Vibes';
            src: url('./greatVibes_regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @page {
            margin: 0px;
        }

        body {
            margin: 0;
            background: url('assets/img/certificate_background.png') no-repeat center center;
            background-size: cover;
            height: 100vh;
            width: 100vw;
            color: #000;
            position: relative;
        }

        .content {
            position: absolute;
            top: 200px;
            left: 50px;
            right: 50px;
            text-align: center;
        }

        .content p {
            font-family: 'Great Vibes', cursive !important;
            font-size: 74pt !important;
            font-weight: 400 !important;
            color: #383630 !important;
            margin: 7rem 0 !important;
            text-align: center !important;
            margin-left: 1rem !important;
            font-weight: 400 !important;
            font-style: normal !important;
        }
    </style>
</head>

<body>
    <div class="content">
        <p>{{ ucfirst(strtok($user->name, ' ')) }}</p>
    </div>
</body>

</html>
