<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $data['subject'] }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f5f5f5; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding: 40px 30px 20px 30px; text-align: center;">
                            <h1 style="margin: 0; font-size: 24px; color: #333;">{{ $data['title'] }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px 30px; text-align: center;">
                            <p style="font-size: 16px; color: #666; line-height: 1.5; margin: 0;">
                                {{ $data['message'] }}
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 30px;">
                            <h2 style="font-size: 18px; color: #333; text-align: left; margin-bottom: 10px;">Преглед на нарачка</h2>
                            <table width="100%" cellpadding="10" cellspacing="0" border="1" style="border-collapse: collapse; font-size: 14px; color: #444;">
                                <thead style="background-color: #f9f9f9;">
                                    <tr>
                                        <th align="left">Производ</th>
                                        <th align="center">Количина</th>
                                        <th align="right">Цена</th>
                                        <th align="right">Вкупно</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $grandTotal = 0; @endphp
                                    @foreach($data['order']->items as $item)
                                        @php
                                            $lineTotal = $item->price * $item->quantity;
                                            $grandTotal += $lineTotal;
                                        @endphp
                                        <tr>
                                            <td>{{ $item->title }}</td>
                                            <td align="center">{{ $item->quantity }}</td>
                                            <td align="right">{{ number_format($item->price, 2) }} ден</td>
                                            <td align="right">{{ number_format($lineTotal, 2) }} ден</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" align="right"><strong>Вкупно:</strong></td>
                                        <td align="right"><strong>{{ number_format($grandTotal, 2) }} ден</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>

                    @if (!empty($data['button_url']) && !empty($data['button_text']))
                    <tr>
                        <td style="padding: 30px 30px 40px 30px; text-align: center;">
                            <a href="{{ $data['button_url'] }}" style="background-color: #4CAF50; color: white; padding: 14px 24px; text-decoration: none; border-radius: 5px; font-size: 16px; display: inline-block;">
                                {{ $data['button_text'] }}
                            </a>
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <td style="background-color: #f0f0f0; padding: 20px; text-align: center;">
                            <p style="font-size: 12px; color: #999; margin: 0;">&copy; {{ date('Y') }} Hedera Farm Plus. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
