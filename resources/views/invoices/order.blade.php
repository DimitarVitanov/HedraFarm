<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .invoice-container {
            padding: 30px 40px;
        }
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #6b8e23;
            padding-bottom: 20px;
        }
        .header-left {
            display: table-cell;
            width: 50%;
            vertical-align: middle;
        }
        .header-right {
            display: table-cell;
            width: 50%;
            vertical-align: middle;
            text-align: right;
        }
        .logo {
            max-width: 200px;
            height: auto;
        }
        .company-info {
            margin-top: 8px;
            font-size: 11px;
            color: #555;
            line-height: 1.6;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #6b8e23;
        }
        .invoice-number {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .details-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .details-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .details-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }
        .details-title {
            font-size: 13px;
            font-weight: bold;
            color: #6b8e23;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .details-text {
            font-size: 12px;
            color: #555;
            line-height: 1.7;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table thead th {
            background-color: #6b8e23;
            color: #fff;
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .items-table thead th:last-child,
        .items-table thead th:nth-child(3),
        .items-table thead th:nth-child(4) {
            text-align: right;
        }
        .items-table tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 12px;
        }
        .items-table tbody td:last-child,
        .items-table tbody td:nth-child(3),
        .items-table tbody td:nth-child(4) {
            text-align: right;
        }
        .items-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .totals-section {
            width: 100%;
            display: table;
        }
        .totals-spacer {
            display: table-cell;
            width: 55%;
        }
        .totals-table-wrapper {
            display: table-cell;
            width: 45%;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table tr td {
            padding: 6px 12px;
            font-size: 12px;
        }
        .totals-table tr td:first-child {
            text-align: left;
            color: #666;
        }
        .totals-table tr td:last-child {
            text-align: right;
            font-weight: bold;
        }
        .totals-table tr.total-row {
            border-top: 2px solid #6b8e23;
        }
        .totals-table tr.total-row td {
            padding-top: 10px;
            font-size: 16px;
            color: #6b8e23;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #999;
            font-size: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .payment-info {
            margin-top: 20px;
            padding: 12px 15px;
            background-color: #f8f9fa;
            border-left: 3px solid #6b8e23;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        {{-- Header --}}
        <div class="header">
            <div class="header-left">
                <img src="{{ $logoBase64 }}" class="logo" alt="Хедера Фарм+">
                @if($company)
                <div class="company-info">
                    <strong>{{ $company->name }}</strong><br>
                    @if($company->address){{ $company->address }}<br>@endif
                    @if($company->phone)<strong>Тел:</strong> {{ $company->phone }}<br>@endif
                    @if($company->email)<strong>Е-пошта:</strong> {{ $company->email }}<br>@endif
                    @if($company->worktime)<strong>Работно време:</strong> {{ $company->worktime }}@endif
                </div>
                @endif
            </div>
            <div class="header-right">
                <div class="invoice-title">ФАКТУРА</div>
                <div class="invoice-number">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="invoice-number">{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y H:i') }}</div>
            </div>
        </div>

        {{-- Customer & Company Details --}}
        <div class="details-section">
            <div class="details-left">
                <div class="details-title">Купувач</div>
                <div class="details-text">
                    <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                    {{ $order->address }}<br>
                    {{ $order->postal_code }} {{ $order->city }}<br>
                    @if($order->municipality){{ $order->municipality }}<br>@endif
                    {{ $order->country }}<br>
                    <br>
                    <strong>Тел:</strong> {{ $order->phone }}<br>
                    <strong>Е-пошта:</strong> {{ $order->email }}
                </div>
            </div>
            <div class="details-right">
                <div class="details-title">Продавач</div>
                <div class="details-text">
                    <strong>Хедера Фарм+</strong><br>
                    hederafarmplus.mk
                </div>
            </div>
        </div>

        {{-- Items Table --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 50%;">Производ</th>
                    <th style="width: 15%;">Количина</th>
                    <th style="width: 15%;">Цена</th>
                    <th style="width: 15%;">Вкупно</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }} ден.</td>
                    <td>{{ number_format($item->price * $item->quantity, 2) }} ден.</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="totals-section">
            <div class="totals-spacer"></div>
            <div class="totals-table-wrapper">
                <table class="totals-table">
                    <tr>
                        <td>Меѓузбир:</td>
                        <td>{{ number_format($order->subtotal, 2) }} ден.</td>
                    </tr>
                    @if($order->discount > 0)
                    <tr>
                        <td>Попуст:</td>
                        <td>-{{ number_format($order->discount, 2) }} ден.</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Достава:</td>
                        <td>{{ number_format($order->delivery_price, 2) }} ден.</td>
                    </tr>
                    <tr class="total-row">
                        <td>Вкупно:</td>
                        <td>{{ number_format($order->total_price, 2) }} ден.</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Payment Info --}}
        <div class="payment-info">
            <strong>Начин на плаќање:</strong> {{ $order->payment_method }}
        </div>

        {{-- Footer --}}
        <div class="footer">
            Хедера Фарм+ | hederafarmplus.mk<br>
            Ви благодариме за вашата нарачка!
        </div>
    </div>
</body>
</html>
