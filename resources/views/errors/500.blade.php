<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Petit bug | IUHM</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(circle at 80% 10%, #fef3c7 0%, #f8fafc 40%, #ecfeff 100%);
            color: #111827;
            padding: 24px;
        }
        .card {
            width: min(720px, 100%);
            background: #fff;
            border-radius: 24px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 20px 55px rgba(17, 24, 39, 0.12);
            padding: 30px;
            text-align: center;
        }
        .chip {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1e40af;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .face { font-size: 58px; margin: 18px 0; }
        h1 { margin: 12px 0 10px; font-size: 31px; }
        p { color: #6b7280; line-height: 1.75; margin: 0; }
        .buttons { margin-top: 24px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; }
        .btn { text-decoration: none; border-radius: 10px; padding: 12px 18px; font-weight: 600; font-size: 14px; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-muted { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <main class="card">
        <span class="chip">Erreur 500</span>
        <div class="face">x_x</div>
        <h1>Le serveur a fait une petite pause cafe.</h1>
        <p>
            Une erreur technique est survenue, mais rien n'est perdu.<br>
            Rechargez la page dans quelques secondes. Si le probleme continue, notre equipe pourra verifier rapidement.
        </p>

        <div class="buttons">
            <a class="btn btn-primary" href="{{ url()->current() }}">Reessayer</a>
            <a class="btn btn-muted" href="{{ url('/') }}">Accueil</a>
        </div>
    </main>
</body>
</html>
