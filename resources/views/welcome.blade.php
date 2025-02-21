<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome | Coming Soon</title>

    <link rel="stylesheet" href="{{ asset('dashboard_files/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/skin-blue.min.css') }}">

    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            text-align: center;
            font-family: 'Roboto', sans-serif !important;
        }
        .welcome-box {
            max-width: 800px;
            margin: 100px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .welcome-box h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        .welcome-box p {
            font-size: 18px;
            line-height: 1.6;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="welcome-box">
    <img src="{{ asset('dashboard_files/img/c66277aa5008d4a424a69c334f1f2d37.png') }}" alt="Logo" class="logo">
    <h1>{{__('site.Welcome to Our Website')}}</h1>
    <p>{{__('site.We are working hard to bring you something amazing. Stay tuned!')}}</p>
    <h2>{{__('site.Coming Soon')}}</h2>
</div>

</body>
</html>
