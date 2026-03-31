<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Impression' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" integrity="sha512-XcIsjKMcuVe0Ucj/xgIXQnytNwBttJbNjltBV18IOnru2lDPe9KRRyvCXw6Y5H415vbBLRm8+q6fmLUU7DfO6Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />    <style>
        :root {
            --paper-width: 210mm;
            --paper-height: 297mm;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 24px;
            background: #e9ecef;
            font-family: "Manrope", sans-serif;
        }

        .print-wrap {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: calc(100vh - 48px);
        }

        .paper {
            width: var(--paper-width);
            height: var(--paper-height);
            background: #fff;
            position: relative;
            margin: 0 auto;
        }
        .a4-page{
            width: 210mm;
            height: 297mm;
            background: #fff;
            position: relative;
        }
        .paper-content {
            padding: 0;
        }

        .print-cta {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 1000;
            border: 0;
            border-radius: 999px;
            background: #2563eb;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(37, 99, 235, .35);
            width: 55px;
            height: 55px;
            font-size: 32px;
        }
        .print-cta:hover {
            background: #1d4ed8;
        }
        .footer-logos {
            position: absolute;
            left: 20mm;
            right: 20mm;
            bottom: 3mm;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 10px;
            display: flex;
            justify-content: center;
            gap: 20px;
            align-items: center;
        }
        .footer-logos img {
            max-height: 50px;
            max-width: 100px;
            object-fit: contain;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .paper {
                margin: 0 auto !important;
            }

            .print-wrap {
                min-height: auto;
                display: block;
            }
            .print-cta {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <button class="print-cta" onclick="window.print()">
   <i class="ri-printer-line"></i>
    </button>

    <div class="print-wrap">
        <main class="paper">

                    {!! $printHtml !!}

        </main>
    </div>
</body>
</html>
