<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attestation de Travail - {{ $employee->nom }} {{ $employee->prenom }}</title>
    <style>
        @page { margin: 25mm 20mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; line-height: 1.8; }
        .header { text-align: center; margin-bottom: 40px; }
        .org-name { font-size: 18px; font-weight: bold; color: #1e3a5f; }
        .doc-title { font-size: 22px; font-weight: bold; color: #4f46e5; margin: 30px 0; text-align: center; text-decoration: underline; text-underline-offset: 8px; }
        .content { margin: 20px 40px; text-align: justify; }
        .highlight { font-weight: bold; color: #1e3a5f; }
        .signature-area { margin-top: 60px; text-align: right; padding-right: 40px; }
        .signature-line { border-top: 1px solid #333; width: 200px; display: inline-block; margin-top: 60px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="org-name">
            @php
                $orgName = $association->firstWhere('key', 'association_name')?->value ?? 'ASSOCIATION INITIATIVE URBAINE';
                $orgAddress = $association->firstWhere('key', 'address')?->value ?? '';
                $orgCity = $association->firstWhere('key', 'city')?->value ?? '';
            @endphp
            {{ $orgName }}
        </div>
        @if($orgAddress)
        <div style="font-size: 10px; color: #666;">{{ $orgAddress }}{{ $orgCity ? ', ' . $orgCity : '' }}</div>
        @endif
    </div>

    <div class="doc-title">ATTESTATION DE TRAVAIL</div>

    <div class="content">
        <p>
            Je soussigné(e), représentant légal de <span class="highlight">{{ $orgName }}</span>, 
            atteste par la présente que :
        </p>

        <p style="margin: 20px 0; padding: 15px; background: #f8f9ff; border-left: 4px solid #4f46e5;">
            <span class="highlight">{{ $employee->gender === 'homme' ? 'Monsieur' : 'Madame' }} {{ strtoupper($employee->nom) }} {{ $employee->prenom }}</span><br>
            @if($employee->cin)
            CIN : <span class="highlight">{{ $employee->cin }}</span><br>
            @endif
            @if($employee->date_naissance)
            Né(e) le : {{ $employee->date_naissance->format('d/m/Y') }}<br>
            @endif
        </p>

        <p>
            {{ $employee->gender === 'homme' ? 'est employé' : 'est employée' }} au sein de notre {{ $orgName }} 
            @if($employee->date_embauche)
            depuis le <span class="highlight">{{ $employee->date_embauche->format('d/m/Y') }}</span>
            @endif
            en qualité de <span class="highlight">{{ $employee->poste ?? 'collaborateur' }}</span>
            @if($employee->departement)
            au département <span class="highlight">{{ $employee->departement }}</span>
            @endif
            sous contrat de type <span class="highlight">{{ $employee->contrat_type }}</span>.
        </p>

        <p>
            {{ $employee->gender === 'homme' ? 'L\'intéressé' : 'L\'intéressée' }} jouit de tous ses droits 
            et {{ $employee->status === 'active' ? 'occupe toujours son poste à ce jour' : 'a quitté notre organisation' }}.
        </p>

        <p>
            Cette attestation est délivrée à {{ $employee->gender === 'homme' ? 'l\'intéressé' : 'l\'intéressée' }} 
            pour servir et valoir ce que de droit.
        </p>
    </div>

    <div class="signature-area">
        <p>Fait à {{ $orgCity ?: '____________' }}, le {{ now()->format('d/m/Y') }}</p>
        <p style="margin-top: 10px; font-size: 10px; color: #666;">Cachet et signature</p>
        <div class="signature-line"></div>
    </div>

    <div class="footer">
        {{ $orgName }} | Attestation générée le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>
