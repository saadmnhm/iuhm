<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - Maintenance | IUHM</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(145deg, #eef2ff 0%, #f0fdf4 55%, #fff7ed 100%);
            padding: 24px;
            color: #111827;
        }
        .card {
            width: min(700px, 100%);
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            box-shadow: 0 20px 55px rgba(17, 24, 39, 0.12);
            padding: 30px;
            text-align: center;
        }
        .tag {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #dcfce7;
            color: #166534;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .icon { font-size: 58px; margin: 18px 0; }
        h1 { margin: 12px 0 10px; font-size: 30px; }
        p { margin: 0; color: #6b7280; line-height: 1.75; }
        .btn {
            margin-top: 24px;
            display: inline-block;
            text-decoration: none;
            background: #2563eb;
            color: #fff;
            border-radius: 10px;
            padding: 12px 18px;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <main class="card">
        <span class="tag">Erreur 503</span>
        <div class="icon">tools</div>
        <h1>Maintenance en cours</h1>
        <p>
            Nous appliquons quelques ajustements pour ameliorer votre experience.<br>
            Merci pour votre patience, le service revient tres bientot.
        </p>
        <a class="btn" href="{{ url('/') }}">Retourner a l'accueil</a>
    </main>
</body>
</html>
