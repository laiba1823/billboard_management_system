<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            font-family: 'Poppins', sans-serif;
            margin:0;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;

            background: url('{{ asset("images/bg/billboard-bg.jpg") }}') no-repeat center center fixed;
            background-size: cover;
        }

        .overlay{
            position:absolute;
            inset:0;
            background: rgba(0,0,0,0.65);
        }

        .card{
            position: relative;
            background: rgba(255,255,255,0.95);
            padding: 35px;
            width: 420px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
            z-index: 2;
            text-align:center;
        }

        h2{
            margin-bottom: 15px;
            font-size: 24px;
            color:#222;
        }

        p{
            font-size: 13px;
            color:#666;
            margin-bottom: 20px;
        }

        input{
            width:100%;
            padding:12px;
            border-radius:8px;
            border:1px solid #ddd;
            margin-top:10px;
            font-family: 'Poppins', sans-serif;
        }

        button{
            width:100%;
            padding:12px;
            margin-top:15px;
            border:none;
            border-radius:8px;
            background:#1f3c88;
            color:white;
            font-weight:600;
            cursor:pointer;
        }

        button:hover{
            background:#162c66;
        }

        .msg{
            font-size:13px;
            margin-bottom:10px;
            color:green;
        }

        .error{
            font-size:13px;
            margin-bottom:10px;
            color:red;
        }
    </style>
</head>

<body>

<div class="overlay"></div>

<div class="card">

    <h2>Forgot Password</h2>
    <p>Enter your email and we will send you an OTP</p>

    @if(session('success'))
        <div class="msg">{{ session('success') }}</div>
    @endif

    @if(session('fail'))
        <div class="error">{{ session('fail') }}</div>
    @endif

    <form method="POST" action="{{ route('forgot.password.send') }}">
        @csrf

        <input type="email" name="email" placeholder="Enter your email" required>

        <button type="submit">Send OTP</button>
    </form>

</div>

</body>
</html>