<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0;">

    <table width="100%" cellspacing="0" cellpadding="0" bgcolor="#f4f4f4">
        <tr>
            <td align="center" valign="top" style="padding: 50px 0;">
                <table width="600" cellspacing="0" cellpadding="20" bgcolor="#ffffff" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 5px;">
                    <tr>
                        <td align="center" valign="top">
                            <h1 style="color: #000000;">Tech Crafted</h1>
                            <h3 style="color: #007BFF;">Password Reset</h3>
                            <p>Hello there!</p>
                            <p>We received a request to reset your password. If you didn't make this request, you can ignore this email.</p>
                            <p>If you did request a password reset, please click the button below:</p>
                            <a href="{{ route('password.update.get', $token) }}" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: #fff; text-decoration: none; border-radius: 3px;">Reset Password</a>
                            <p>If the button above doesn't work, you can also copy and paste the following link into your browser:</p>
                            <p>{{ route('password.update.get', $token) }}</p>
                            <p>Thank you!</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>
