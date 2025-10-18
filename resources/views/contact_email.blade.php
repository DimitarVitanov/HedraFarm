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
                        <td style="padding: 20px 30px;">
                            <h2 style="font-size: 18px; color: #333; text-align: left; margin-bottom: 10px;">Инфо Порака</h2>
                            <table width="100%" cellpadding="10" cellspacing="0" border="1" style="border-collapse: collapse; font-size: 14px; color: #444;">
                                <thead style="background-color: #f9f9f9;">
                                    <tr>
                                        <th align="left">Име</th>
                                        <th align="center">Емаил</th>
                                        <th align="right">Прашање</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                            <td align="center">{{ $data['name'] }}</td>
                                            <td align="right">{{ $data['email'] }} </td>
                                            <td align="right">{{ $data['message'] }} </td>
                                        </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
