<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify your email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            background: #f6f5f7;
            font-family: sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,.15);
            max-width: 460px;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
        }
        .icon { font-size: 3rem; color: #648454; margin-bottom: 1rem; }
        h1 { font-size: 1.4rem; font-weight: 700; margin-bottom: .5rem; }
        p  { color: #555; font-size: 14px; }
        .btn-resend {
            background: #648454;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 10px 36px;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            width: 100%;
        }
        .btn-resend:hover { opacity: .88; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon"><i class="ri-mail-check-line"></i></div>
        <h1>Verify your email address</h1>
        <p class="mb-4">
            Please verify your email by clicking the link sent to
            <strong>{{ Auth::user()->email }}</strong>.
        </p>

        @if (session('message'))
            <div class="alert alert-success mb-3">{{ session('message') }}</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-resend">Resend verification email</button>
        </form>

        <a href="{{ route('admin.login') }}" class="d-block mt-3 text-muted" style="font-size:13px">
            Back to login
        </a>
    </div>
</body>
</html>
