<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="margin: 0; padding: 0; background-color: #6C7A89;">
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #6C7A89;">
    <tr>
        <td style="text-align: center;padding: 50px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 600px; margin: 0 auto;">
                <tr>
                    <td style="background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.16);">
                        <h2 style="margin-bottom: 20px;">Redefinição de Senha</h2>
                        <p>
                            Вы получаете это письмо, потому что мы получаем запрос на переопределение пароля вашей учетной записи.Токен это ниже:                            
                            <br>
                            <br>
                            <br>
                            <code style="padding: 5px; background-color: #9ca3af46;">{{ $token }}</code>
                            <br>
                            <br>
                            <a href="{{ $resetLink }}" style="background-color: #007BFF; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Смена пароля</a>
                        </p>
                        <p>
                            Если вы не запросили переосмысление пароля, игнорируйте это письмо.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
