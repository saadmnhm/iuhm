<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Acces refuse | IUHM</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(140deg, #f8fafc 0%, #eef6ff 45%, #fff8f1 100%);
            padding: 24px;
            color: #1f2937;
        }
        .card {
            width: min(680px, 100%);
            background: #fff;
            border-radius: 22px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 18px 48px rgba(15, 23, 42, 0.12);
            padding: 28px;
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #fee2e2;
            color: #991b1b;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        h1 { margin: 14px 0 10px; font-size: 30px; }
        p { margin: 0; color: #6b7280; line-height: 1.7; }
        .emoji { font-size: 54px; margin: 16px 0; }
        .actions { margin-top: 24px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; }
        .btn { text-decoration: none; border-radius: 10px; padding: 12px 18px; font-weight: 600; font-size: 14px; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-muted { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <main class="card">
        <span class="badge">Erreur 403</span>
        <div class="emoji">nope</div>
        <h1>Zone reservee</h1>
        <p>
            Cette action est protegee et vous n'avez pas les droits necessaires pour y acceder.<br>
            Si vous pensez qu'il s'agit d'une erreur, contactez un administrateur.
        </p>

        <div class="actions">
            <a class="btn btn-primary" href="{{ url('/') }}">Accueil</a>
            <a class="btn btn-muted" href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}">Retour</a>
        </div>
    </main>
</body>
</html>
