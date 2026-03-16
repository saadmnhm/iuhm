<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Impression' }}</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" integrity="sha512-XcIsjKMcuVe0Ucj/xgIXQnytNwBttJbNjltBV18IOnru2lDPe9KRRyvCXw6Y5H415vbBLRm8+q6fmLUU7DfO6Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />    <style>
        :root {
            --paper-width: 210mm;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 24px;
            background: #e9ecef;
            font-family: Arial, Helvetica, sans-serif;
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
            box-shadow: 0 8px 24px rgba(0,0,0,.18);
            position: relative;
            overflow: hidden;
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
        .page {
        background: var(--cream);
        width: 760px;
        padding: 50px 60px 60px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.18), 0 2px 8px rgba(0,0,0,0.08);
        position: relative;
        border-top: 4px solid var(--rust);
        }

        /* Decorative corner marks */
        .page::before, .page::after {
        content: '';
        position: absolute;
        width: 18px; height: 18px;
        border-color: var(--gold);
        border-style: solid;
        }
        .page::before { top: 12px; left: 12px; border-width: 2px 0 0 2px; }
        .page::after  { bottom: 12px; right: 12px; border-width: 0 2px 2px 0; }

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
                <div class="page">

                    {!! $printHtml !!}

                </div>
            </section>
        </main>
    </div>
</body>
</html>
