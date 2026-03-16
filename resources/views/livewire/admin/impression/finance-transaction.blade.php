<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction {{ $transaction->reference }}</title>
    <style>
        @page { margin: 15mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
        .header { border-bottom: 3px solid #4f46e5; padding-bottom: 12px; margin-bottom: 20px; }
        .header table { width: 100%; }
        .title { font-size: 20px; font-weight: bold; color: #4f46e5; }
        .subtitle { font-size: 11px; color: #666; }
        .badge { display: inline-block; padding: 3px 12px; border-radius: 12px; font-size: 10px; font-weight: bold; }
        .badge-revenue { background: #d1fae5; color: #065f46; }
        .badge-depense { background: #fee2e2; color: #991b1b; }
        .info-box { background: #f8f9ff; border: 1px solid #c7d2fe; border-radius: 6px; padding: 12px; margin-bottom: 16px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 4px 8px; font-size: 11px; }
        .info-label { color: #6366f1; font-weight: bold; width: 35%; }
        .amount { font-size: 28px; font-weight: bold; text-align: center; padding: 20px; margin: 16px 0; border-radius: 8px; }
        .amount-revenue { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .amount-depense { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .section-title { font-size: 13px; font-weight: bold; color: #4f46e5; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; margin: 16px 0 8px 0; }
        .description { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; font-size: 11px; }
        .footer { margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 8px; text-align: center; font-size: 9px; color: #9ca3af; }
        .stamp-area { margin-top: 40px; text-align: right; }
        .stamp-area .line { border-top: 1px dashed #999; width: 200px; display: inline-block; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td style="width: 70%;">
                    <div class="title">{{ $transaction->type === 'revenue' ? 'REÇU DE REVENU' : 'JUSTIFICATIF DE DÉPENSE' }}</div>
                    <div class="subtitle">Réf: {{ $transaction->reference }} | Date: {{ $transaction->date_transaction->format('d/m/Y') }}</div>
                </td>
                <td style="width: 30%; text-align: right;">
                    <span class="badge {{ $transaction->type === 'revenue' ? 'badge-revenue' : 'badge-depense' }}">
                        {{ strtoupper($transaction->type) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <div class="amount {{ $transaction->type === 'revenue' ? 'amount-revenue' : 'amount-depense' }}">
        {{ $transaction->type === 'revenue' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} MAD
    </div>

    <div class="info-box">
        <table class="info-table">
            <tr><td class="info-label">Libellé</td><td>{{ $transaction->label }}</td></tr>
            <tr><td class="info-label">Catégorie</td><td>{{ $transaction->category->name ?? 'Non catégorisé' }}</td></tr>
            <tr><td class="info-label">Bénéficiaire</td><td>{{ $transaction->beneficiaire ?? '-' }}</td></tr>
            <tr><td class="info-label">Mode de paiement</td><td>{{ ucfirst($transaction->mode_paiement ?? '-') }}</td></tr>
            <tr><td class="info-label">Statut</td><td>{{ ucfirst(str_replace('_', ' ', $transaction->status)) }}</td></tr>
            <tr><td class="info-label">Créé par</td><td>{{ $transaction->creator->name ?? '-' }}</td></tr>
            <tr><td class="info-label">Date de création</td><td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td></tr>
        </table>
    </div>

    @if($transaction->description)
    <div class="section-title">Description / Justification</div>
    <div class="description">{{ $transaction->description }}</div>
    @endif

    @if($transaction->attachments->count() > 0)
    <div class="section-title">Pièces jointes ({{ $transaction->attachments->count() }})</div>
    <ul style="font-size: 10px;">
        @foreach($transaction->attachments as $att)
        <li>{{ $att->file_name }} ({{ $att->file_type }})</li>
        @endforeach
    </ul>
    @endif

    <div class="stamp-area">
        <p style="font-size: 10px; color: #666;">Cachet et signature</p>
        <div class="line"></div>
    </div>

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} | Système de gestion financière
    </div>
</body>
</html>
