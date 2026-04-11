<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f4f6f9;
        }

        .card {
            background: #fff;
            padding: 40px;
            width: 420px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border-top: 4px solid #2f80ed;
        }

        h2 {
            text-align: center;
            color: #2f80ed;
        }

        p {
            text-align: center;
            font-size: 13px;
            color: #666;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            background: #2f80ed;
            color: white;
            font-weight: 600;
        }

        button:hover {
            background: #1f6fd6;
        }

        .error {
            text-align: center;
            color: red;
        }
    </style>
</head>

<body>

<div class="card">

    <h2>Reset Password</h2>
    <p>Enter your new password</p>

    @if(session('fail'))
        <div class="error">{{ session('fail') }}</div>
    @endif

    <form method="POST" action="{{ route('forgot.password.update') }}">
        @csrf

        <input type="password" name="password" placeholder="New Password" required>

        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

        <button type="submit">Save Password</button>
    </form>

</div>

</body>
</html>