@php
    $invoiceNumber = str_pad($order->id, 6, '0', STR_PAD_LEFT);
    $statusLabels = ['pending' => 'Во обработка', 'processing' => 'Во обработка', 'completed' => 'Одобрена', 'cancelled' => 'Откажана'];
    $paymentLabels = ['cash' => 'Готовина при достава', 'card' => 'Платежна картичка'];
    $money = fn($value) => number_format((float) $value, 2, ',', '.') . ' ден.';
@endphp
<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Фактура #{{ $invoiceNumber }}</title>
    <style>
        @page { margin: 0; }
        * { margin: 0; padding: 0; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            color: #47564f;
            line-height: 1.5;
        }
        table { border-collapse: collapse; width: 100%; }
        td, th { vertical-align: top; }

        .topbar { height: 8px; background: #0b8f7f; }
        .topbar-accent { height: 8px; width: 30%; background: #a4b334; }
        .page { padding: 34px 46px 120px; }

        /* header */
        .logo { width: 190px; height: auto; }
        .doc-label { color: #7b8a84; font-size: 9px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; }
        .doc-title { margin-top: 2px; color: #10231f; font-size: 26px; font-weight: bold; letter-spacing: -0.5px; }
        .doc-number { margin-top: 2px; color: #0b8f7f; font-size: 13px; font-weight: bold; }
        .status {
            display: inline-block;
            margin-top: 8px;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .status-pending, .status-processing { background: #fdf3dc; color: #9a6200; }
        .status-completed { background: #e6f5f2; color: #08766a; }
        .status-cancelled { background: #fdecec; color: #cd2b31; }

        /* meta strip */
        .meta { margin-top: 26px; border: 1px solid #e4ebe7; border-radius: 8px; }
        .meta td { width: 33.33%; padding: 11px 14px; border-right: 1px solid #e4ebe7; }
        .meta td.last { border-right: 0; }
        .label { color: #7b8a84; font-size: 8.5px; font-weight: bold; letter-spacing: 1.2px; text-transform: uppercase; }
        .meta-value { margin-top: 2px; color: #10231f; font-size: 11.5px; font-weight: bold; }

        /* parties */
        .parties { margin-top: 22px; }
        .party { width: 49%; padding: 14px 16px; border-radius: 8px; background: #f6f9f7; }
        .party-gap { width: 2%; }
        .party-name { margin-top: 6px; color: #10231f; font-size: 12px; font-weight: bold; }
        .party-text { margin-top: 3px; line-height: 1.65; }
        .party-text strong { color: #10231f; }

        /* items */
        .items { margin-top: 26px; }
        .items th {
            padding: 9px 10px;
            border-bottom: 1.5px solid #10231f;
            color: #10231f;
            font-size: 8.5px;
            font-weight: bold;
            letter-spacing: 1px;
            text-align: left;
            text-transform: uppercase;
        }
        .items td { padding: 10px; border-bottom: 1px solid #e4ebe7; }
        .items .num { width: 6%; color: #9aa7a1; }
        .items .product { color: #10231f; font-weight: bold; }
        .items .qty { width: 12%; text-align: center; }
        .items .price { width: 18%; text-align: right; white-space: nowrap; }
        .items .total { width: 18%; color: #10231f; font-weight: bold; text-align: right; white-space: nowrap; }

        /* totals */
        .summary { margin-top: 18px; }
        .note { padding: 12px 14px; border-left: 3px solid #a4b334; background: #f6f9f7; }
        .note-text { margin-top: 3px; }
        .totals td { padding: 5px 10px; }
        .totals .key { color: #7b8a84; }
        .totals .val { color: #10231f; font-weight: bold; text-align: right; white-space: nowrap; }
        .totals .discount { color: #cd2b31; }
        .grand { background: #0c2a26; }
        .grand td { padding: 11px 12px; border: 0; color: #ffffff; }
        .grand .key { color: #b9d3cc; font-size: 10px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
        .grand .val { color: #ffffff; font-size: 15px; font-weight: bold; text-align: right; white-space: nowrap; }

        /* footer */
        .footer { position: fixed; left: 46px; right: 46px; bottom: 30px; padding-top: 14px; border-top: 1px solid #e4ebe7; color: #7b8a84; font-size: 9px; }
        .footer strong { color: #10231f; }
        .thanks { color: #0b8f7f; font-size: 11px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="topbar"><div class="topbar-accent"></div></div>

    <div class="footer">
        <table>
            <tr>
                <td>
                    <div class="thanks">Ви благодариме за вашата нарачка!</div>
                    <div>{{ $company->name ?? 'Хедера Фарм+' }} · hederafarmplus.mk</div>
                </td>
                <td style="text-align: right;">
                    @if($company && $company->phone)<div><strong>Тел:</strong> {{ $company->phone }}</div>@endif
                    @if($company && $company->email)<div>{{ $company->email }}</div>@endif
                </td>
            </tr>
        </table>
    </div>

    <div class="page">
        {{-- Header --}}
        <table>
            <tr>
                <td style="vertical-align: middle;">
                    <img src="{{ $logoBase64 }}" class="logo" alt="Хедера Фарм+">
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <div class="doc-label">Документ</div>
                    <div class="doc-title">ФАКТУРА</div>
                    <div class="doc-number">#{{ $invoiceNumber }}</div>
                    <div class="status status-{{ $order->status }}">{{ $statusLabels[$order->status] ?? $order->status }}</div>
                </td>
            </tr>
        </table>

        {{-- Meta --}}
        <table class="meta">
            <tr>
                <td>
                    <div class="label">Број на фактура</div>
                    <div class="meta-value">{{ $invoiceNumber }}</div>
                </td>
                <td>
                    <div class="label">Датум на нарачка</div>
                    <div class="meta-value">{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y H:i') }}</div>
                </td>
                <td class="last">
                    <div class="label">Начин на плаќање</div>
                    <div class="meta-value">{{ $paymentLabels[$order->payment_method] ?? $order->payment_method }}</div>
                </td>
            </tr>
        </table>

        {{-- Customer & Seller --}}
        <table class="parties">
            <tr>
                <td class="party">
                    <div class="label">Купувач</div>
                    <div class="party-name">{{ $order->first_name }} {{ $order->last_name }}</div>
                    <div class="party-text">
                        {{ $order->address }}<br>
                        {{ $order->postal_code }} {{ $order->city }}@if($order->municipality), {{ $order->municipality }}@endif<br>
                        {{ $order->country }}<br>
                        <strong>Тел:</strong> {{ $order->phone }}<br>
                        <strong>Е-пошта:</strong> {{ $order->email }}
                    </div>
                </td>
                <td class="party-gap"></td>
                <td class="party">
                    <div class="label">Продавач</div>
                    <div class="party-name">{{ $company->name ?? 'Хедера Фарм+' }}</div>
                    <div class="party-text">
                        @if($company && $company->address){{ $company->address }}<br>@endif
                        hederafarmplus.mk<br>
                        @if($company && $company->phone)<strong>Тел:</strong> {{ $company->phone }}<br>@endif
                        @if($company && $company->email)<strong>Е-пошта:</strong> {{ $company->email }}<br>@endif
                        @if($company && $company->worktime)<strong>Работно време:</strong> {{ $company->worktime }}@endif
                    </div>
                </td>
            </tr>
        </table>

        {{-- Items --}}
        <table class="items">
            <thead>
                <tr>
                    <th class="num">#</th>
                    <th>Производ</th>
                    <th class="qty">Количина</th>
                    <th class="price">Цена</th>
                    <th class="price">Вкупно</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td class="num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="product">{{ $item->title }}</td>
                    <td class="qty">{{ $item->quantity }}</td>
                    <td class="price">{{ $money($item->price) }}</td>
                    <td class="total">{{ $money($item->price * $item->quantity) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Note & Totals --}}
        <table class="summary">
            <tr>
                <td style="width: 52%; padding-right: 24px;">
                    @if($order->additional_description)
                    <div class="note">
                        <div class="label">Забелешка од купувачот</div>
                        <div class="note-text">{{ $order->additional_description }}</div>
                    </div>
                    @endif
                </td>
                <td style="width: 48%;">
                    <table class="totals">
                        <tr>
                            <td class="key">Меѓузбир</td>
                            <td class="val">{{ $money($order->subtotal) }}</td>
                        </tr>
                        @if($order->discount > 0)
                        <tr>
                            <td class="key">Попуст</td>
                            <td class="val discount">-{{ $money($order->discount) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="key">Достава</td>
                            <td class="val">{{ $order->delivery_price > 0 ? $money($order->delivery_price) : 'Бесплатна' }}</td>
                        </tr>
                    </table>
                    <table class="grand" style="margin-top: 8px;">
                        <tr>
                            <td class="key" style="vertical-align: middle;">Вкупно за плаќање</td>
                            <td class="val">{{ $money($order->total_price) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
