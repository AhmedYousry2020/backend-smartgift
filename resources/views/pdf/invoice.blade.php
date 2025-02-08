<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: 'Amiri', sans-serif; /* Use the Arabic font */
            direction: rtl; /* Right-to-left for Arabic text */
            text-align: right; /* Right align text */
            font-size: 12px;
        }

        .invoice-header {
            text-align: center;
        }

        .invoice-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .invoice-header p {
            font-size: 18px;
        }

        .invoice-details {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .invoice-details th, .invoice-details td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .invoice-details th {
            background-color: #f2f2f2;
        }

        .invoice-total {
            text-align: right;
            margin-top: 20px;
            font-size: 20px;
        }

        .qrcode {
            margin-top: 20px;
            text-align: center;
        }

        .qrcode img {
            width: 150px;
            height: 150px;
        }
        .logo {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 100px; /* Adjust size of logo */
        }
    </style>
</head>
<body>
<!-- Add your logo image here -->
<img src="{{ asset('dashboard_files/img/c66277aa5008d4a424a69c334f1f2d37.png') }}" alt="Logo" class="logo">
<div class="invoice-header">
    <h1>فاتورة {{ $invoice->order_code }}</h1>
    <p>تاريخ الفاتورة: {{ $invoice->created_at->format('d/m/Y') }}</p>
    <p>العميل: {{ $invoice->user->first_name }} {{ $invoice->user->last_name }}</p>
    <p>رقم التلفون: {{ $invoice->user->phone }} </p>

</div>


<table class="invoice-details">
    <thead>
        <tr>
            <th>المنتج</th>
            <th>الكمية</th>
            <th>السعر</th>
            <th>الإجمالي</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoice->orderDetails as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }} {{$invoice->currency}}</td>
                <td>{{ number_format($item->quantity * $item->price, 2) }} {{$invoice->currency}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4">
                <strong>الإجمالي: </strong>{{ number_format($invoice->total_price, 2) }} {{$invoice->currency}}
            </td>
        </tr>

    </tbody>
</table>

<div class="invoice-total">
    <strong>المجموع الكلي: </strong> {{ $invoice->total_with_arabic}}

</div>



</body>
</html>
