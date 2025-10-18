<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Ви благодариме за претплатата!</title>
</head>

<body style="margin:0;padding:0;background:#f5f7fa;font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f5f7fa">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;margin:20px auto;border-radius:8px;overflow:hidden;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background:#4f46e5;padding:30px 20px;">
                            <h1 style="color:#ffffff;font-size:24px;font-weight:600;margin:0;">
                                🎉 Ви благодариме за претплатата!
                            </h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:40px 30px;color:#444444;font-size:16px;line-height:1.6;">
                            <p style="margin:0 0 20px;">
                                Здраво,
                            </p>
                            <p style="margin:0 0 20px;">
                                Ви благодариме што се претплативте на нашиот билтен! Возбудени сме што ве имаме во
                                нашата заедница.
                            </p>
                            <p style="margin:0 0 25px;">
                                Скоро ќе започнете да добивате ажурирања со ексклузивни вести, најнови функции, совети и
                                специјални понуди - директно во вашата пошта.
                            </p>

                            <!-- Call to Action -->
                            <table border="0" cellpadding="0" cellspacing="0" style="margin:25px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/') }}"
                                            style="background:#4f46e5;color:#ffffff;padding:12px 25px;border-radius:6px;text-decoration:none;font-weight:600;display:inline-block;">
                                            Посетете ја нашата веб-страница
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:25px 0 0;">
                                Доколку сакате да се одјавите од нашиот билтен, кликнете
                                <a href="{{ route('newsletter.unsubscribe', ['email' => $data['email']]) }}"
                                    style="color:#4f46e5;">овде</a>.
                            </p>

                            <p style="margin:25px 0 0;">
                                Ви благодариме<br>
                                <strong>{{ config('app.name') }}</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background:#f1f1f1;padding:20px;font-size:13px;color:#777;">
                            <p style="margin:0 0 5px;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                            <p style="margin:0;">
                                Го добивте овој е-маил бидејќи се претплативте на нашиот билтен.
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>

</html>
