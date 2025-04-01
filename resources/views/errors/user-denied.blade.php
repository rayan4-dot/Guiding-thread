<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8fafc;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
        }

        .title {
            font-size: 48px;
            color: #e3342f;
            margin-bottom: 20px;
        }

        .message {
            font-size: 18px;
            margin-bottom: 30px;
        }

        a {
            text-decoration: none;
            background-color: #3490dc;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }

        a:hover {
            background-color: #2779bd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Access Denied</div>
        <div class="message">Dude , You're can't access to admin page</div>
        <a href="{{ route('user.home') }}">Go back !</a>
    </div>
</body>
</html>
