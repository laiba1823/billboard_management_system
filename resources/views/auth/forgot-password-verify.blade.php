<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            font-family: 'Poppins', sans-serif;
            margin:0;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;

            background: url('{{ asset("images/bannerimg/banner-img.jpg") }}') no-repeat center center fixed;
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

        .msg{ color:green; font-size:13px; margin-bottom:10px; }
        .error{ color:red; font-size:13px; margin-bottom:10px; }

    </style>
</head>

<body>

<div class="overlay"></div>

<div class="card">

    <h2>Verify OTP</h2>
    <p>Enter the 6-digit OTP sent to your email</p>

    @if(session('success'))
        <div class="msg">{{ session('success') }}</div>
    @endif

    @if(session('fail'))
        <div class="error">{{ session('fail') }}</div>
    @endif
<form method="POST" action="{{ route('forgot.password.verify.submit') }}">
    @csrf

    <input type="text" name="otp" placeholder="Enter OTP" required>

    <button type="submit">Verify OTP</button>
</form>

</div>

</body>
</html>