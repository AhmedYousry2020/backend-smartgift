<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: 'Amiri', sans-serif;
            direction: rtl;
            text-align: right;
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

        .logo {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 100px;
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
    <p>نوع الطلب: {{ __('site.'.$invoice->order_for) }} </p>

</div>

<div id="print-area">
    @if ($invoice->order_type == 'high_need')
        {{-- High Need Orders - Display by Categories --}}
        @foreach ($invoice->orderCategories as $category)
            <h2 style="text-align: center; background-color: #ddd; padding: 10px;">
                {{ $category->category->translate(app()->getLocale())->name }}
            </h2>

            @php
                $orderDetails = $invoice->orderDetails;
            @endphp

            @if ($orderDetails->isNotEmpty())
                <table class="invoice-details">
                    <thead>
                        <tr>
                            <th>@lang('site.name')</th>
                            <th>@lang('site.quantity')</th>
                            <th>@lang('site.price')</th>
                            <th>@lang('site.total')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderDetails as $detail)
                            <tr>
                                <td>{{ $detail->product->name }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ number_format($detail->price, 2) }} {{$invoice->currency}}</td>
                                <td>{{ number_format($detail->quantity * $detail->price, 2) }} {{$invoice->currency}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align: center;">@lang('site.no_products_in_category')</p>
            @endif
        @endforeach
    @else
        {{-- Custom Orders - Display by Mosque --}}
        @php
            $mosques = $invoice->orderDetails->groupBy('mosque_id');
        @endphp

        @foreach ($mosques as $mosque_id => $orderDetails)
            <h2 style="text-align: center; background-color: #ddd; padding: 10px;">
                {{ $orderDetails->first()->mosque->name ?? __('site.unknown_mosque') }}
            </h2>

            <table class="invoice-details">
                <thead>
                    <tr>
                        <th>@lang('site.name')</th>
                        <th>@lang('site.quantity')</th>
                        <th>@lang('site.price')</th>
                        <th>@lang('site.total')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderDetails as $detail)
                        <tr>
                            <td>{{ $detail->product->name }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ number_format($detail->price, 2) }} {{$invoice->currency}}</td>
                            <td>{{ number_format($detail->quantity * $detail->price, 2) }} {{$invoice->currency}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endif
</div>

<div class="invoice-total">
    <strong>المجموع الكلي: </strong> {{ $invoice->total_with_arabic }}
</div>

</body>
</html>
