<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Privacy Policy</title>

    <link rel="stylesheet" href="{{ asset('dashboard_files/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/skin-blue.min.css') }}">

    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/font-awesome-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/AdminLTE-rtl.min.css') }}">
        <link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/bootstrap-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/rtl.css') }}">
    @else
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/AdminLTE.min.css') }}">
    @endif

    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            font-family: 'Roboto', sans-serif !important;
        }
        .privacy-box {
            max-width: 800px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .privacy-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .privacy-box p {
            font-size: 16px;
            line-height: 1.6;
        }
    </style>
</head>
<body>

<div class="privacy-box">
    <div class="text-center">
        <img src="{{ asset('dashboard_files/img/c66277aa5008d4a424a69c334f1f2d37.png') }}" alt="Logo" style="max-width: 150px;">
    </div>
    <h2>{{__('site.Privacy Policy')}}</h2>
    {!! $settings[0]->translations->first()->privacy_policy !!}

</div>

</body>
</html>
