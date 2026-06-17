<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Football Social!</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background-color: #1f4788; color: white; padding: 10px 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; }
        .footer { text-align: center; font-size: 0.8em; color: #777; margin-top: 20px; }
        .button { display: inline-block; background-color: #ff6b6b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Welcome to Football Social!</h2>
        </div>
        <div class="content">
            <p>Hello {{ $user->name }},</p>
            <p>Thank you for joining Football Social, the ultimate platform for football fans!</p>
            <p>Connect with other fans, share your thoughts on matches, follow your favorite clubs, and much more.</p>
            <p>Get started by exploring your feed or joining a club:</p>
            <p style="text-align: center;"><a href="{{ url('/feed') }}" class="button">Go to your Feed</a></p>
            <p>We're excited to have you on board!</p>
            <p>Best regards,<br>The Football Social Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Football Social. All rights reserved.</p>
        </div>
    </div>
</body>
</html>