<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Banned</title>
</head>

<body style="font-family: 'Arial', sans-serif;">

    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f8f8f8; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #e44d26;">Account Banned</h2>
        <p> Hello,</p>
        <b>{{ $name }}</b>
        <p>We regret to inform you that your account has been banned due to a violation of our platform rules.</p>
        <p>Your comment does not comply with company standards.</p>

        <div style="background-color: #ffe6e6; padding: 10px; border-radius: 8px; margin-top: 10px;">
            <p> {{ $comment->text }}</p>
            <p> Realizado em: {{ $comment->commented_at }}</p>
        </div>

        <p>If you believe this ban is in error or would like to appeal, please contact our support team at tech.crafted.pt@gmail.com.</p>

        <p>Thank you for your understanding.</p>

        <p>Best regards,<br> Tech Crafted </p>
    </div>

</body>

</html>
