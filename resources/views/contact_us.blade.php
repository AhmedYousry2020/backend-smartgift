<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contact Us | اتصل بنا</title>

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
        .contact-box {
            max-width: 800px;
            margin: 100px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .contact-box h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        .contact-box p {
            font-size: 18px;
            line-height: 1.6;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
        .form-group {
            text-align: left;
        }
        .contact-form {
            max-width: 600px;
            margin: 20px auto;
            text-align: left;
        }
        .btn-primary {
            background-color: #4a90e2;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
        }
        label {
            display: block;
            font-weight: bold;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};

        }
        .alert-success {
    background-color: #28a745;
    color: white;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
}

    </style>
</head>
<body>

<div class="contact-box">
    <img src="{{ asset('dashboard_files/img/c66277aa5008d4a424a69c334f1f2d37.png') }}" alt="الشعار" class="logo">
    <h1>{{__('site.Contact Us')}}</h1>
    <p>{{__('site.Please fill out the form below and we will get back to you soon.')}}</p>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    <form action="{{ route('contact.submit') }}" method="post" class="contact-form" style="direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}; text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="name">{{__('site.Name')}}</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">{{__('site.Email')}} </label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="message">{{__('site.Message')}} </label>
            <textarea name="message" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">{{__('site.Send')}}</button>
    </form>
</div>

</body>
</html>
