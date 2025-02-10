<div id="print-area">
    @if ($order->order_type == 'high_need')
        {{-- Display categories first --}}

        @foreach ($order->orderCategories as $category)
            <h4 style="text-align: center; background-color: #ddd; padding: 10px;">
                {{ $category->category->name }}
            </h4>

            {{-- Get order details under this category --}}
            @php
                $orderDetails = $order->orderDetails;
            @endphp

            @if ($orderDetails->isNotEmpty())
                <table class="table table-hover table-bordered">
                    <thead style="background-color: darkgray">
                        <tr>
                            <th>@lang('site.name')</th>
                            <th>@lang('site.quantity')</th>
                            <th>@lang('site.price')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderDetails as $detail)
                            <tr>
                                <td>{{ $detail->product->name }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ number_format($detail->quantity * $detail->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align: center;">@lang('site.no_products_in_category')</p>
            @endif
        @endforeach
    @else
        {{-- If order type is 'custom', show mosque-wise grouping --}}
        @php
            $mosques = $order->orderDetails->groupBy('mosque_id');
        @endphp

        @foreach ($mosques as $mosque_id => $orderDetails)
            <h4 style="text-align: center; background-color: #ddd; padding: 10px;">
                {{ $orderDetails->first()->mosque->name ?? __('site.unknown_mosque') }}
            </h4>

            <table class="table table-hover table-bordered">
                <thead style="background-color: darkgray">
                    <tr>
                        <th>@lang('site.name')</th>
                        <th>@lang('site.quantity')</th>
                        <th>@lang('site.price')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderDetails as $detail)
                        <tr>
                            <td>{{ $detail->product->name }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ number_format($detail->quantity * $detail->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endif

    <h3>@lang('site.total') <span>{{ $order->formatted_total_price }}</span></h3>
</div>
