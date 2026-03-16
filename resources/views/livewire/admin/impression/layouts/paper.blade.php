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
            width: 100%;
            max-width: var(--paper-width);
            min-height: calc(297mm - 20mm);
            background: #fff;
            border-radius: 6px;
            position: relative;
            overflow: hidden;
            padding: 20px 20px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.18), 0 2px 8px rgba(0,0,0,0.08);
            border-top: 4px solid var(--rust);
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
            /* padding: 10px 16px; */
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

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .print-wrap {
                min-height: auto;
                display: block;
            }

            .paper {
                max-width: none;
                width: auto;
                min-height: auto;
                border-radius: 0;
                box-shadow: none;
                overflow: visible;
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
            <section class="paper-content">

                    {!! $printHtml !!}

            </section>
        </main>
    </div>
</body>
</html>
