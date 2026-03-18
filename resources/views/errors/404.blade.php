<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Oups | IUHM</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at 20% 20%, #fff8e8 0%, #f3f8ff 45%, #eefcf5 100%);
            color: #1f2937;
            padding: 24px;
        }
        .card {
            width: min(680px, 100%);
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(17, 24, 39, 0.1);
            padding: 28px;
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #fef3c7;
            color: #92400e;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        h1 { font-size: 30px; margin: 14px 0 10px; }
        p { margin: 0; color: #6b7280; line-height: 1.7; }
        .mascot {
            font-size: 56px;
            margin: 18px 0;
            animation: wobble 2.5s ease-in-out infinite;
        }
        .actions {
            margin-top: 24px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }
        .btn {
            border: 0;
            text-decoration: none;
            border-radius: 10px;
            padding: 12px 18px;
            font-weight: 600;
            font-size: 14px;
        }
        .btn-primary {
            background: #2563eb;
            color: #fff;
        }
        .btn-muted {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }
        .tip {
            margin-top: 16px;
            font-size: 13px;
            color: #9ca3af;
        }
        @keyframes wobble {
            0%, 100% { transform: rotate(-2deg); }
            50% { transform: rotate(2deg); }
        }
    </style>
</head>
<body>
    <main class="card">
        <span class="badge">Erreur 404</span>
        <div class="mascot">(o_o)?</div>
        <h1>Cette page s'est cachee.</h1>
        <p>
            On a cherche partout, meme derriere le bouton "Retour", mais cette page reste introuvable.<br>
            Pas de panique: votre compte et vos donnees vont tres bien.
        </p>

        <div class="actions">
            <a class="btn btn-primary" href="{{ url('/') }}">Revenir a l'accueil</a>
            <a class="btn btn-muted" href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}">Page precedente</a>
        </div>

        <p class="tip">IUHM - {{ date('Y') }}</p>
    </main>
</body>
</html>
