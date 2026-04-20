<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Certificado - {{ $event->name }}</title>
    <style>
        @page {
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            width: 100%;
            height: 100%;
        }
        .certificate {
            width: 100%;
            height: 100%;
            padding: 40px 60px;
            position: relative;
            background: #fff;
        }
        .certificate-border {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 3px solid #1a3a5c;
            border-radius: 8px;
        }
        .certificate-border-inner {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 1px solid #c9a84c;
        }
        .certificate-content {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 20px 40px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .certificate-header {
            margin-bottom: 10px;
        }
        .certificate-logos {
            width: 100%;
            margin-bottom: 12px;
            text-align: center;
        }
        .certificate-logo-block {
            display: inline-block;
            width: 24%;
            vertical-align: middle;
            text-align: center;
            padding: 0 8px;
        }
        .certificate-logo {
            max-height: 64px;
            max-width: 170px;
        }
        .certificate-logo-label {
            font-size: 9px;
            color: #777;
            margin-top: 4px;
        }
        .certificate-title {
            font-size: 36px;
            font-weight: bold;
            color: #1a3a5c;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .certificate-subtitle {
            font-size: 14px;
            color: #666;
            letter-spacing: 2px;
        }
        .certificate-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 15px 0;
        }
        .certificate-text {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 8px;
        }
        .participant-name {
            font-size: 28px;
            font-weight: bold;
            color: #1a3a5c;
            border-bottom: 2px solid #c9a84c;
            display: inline-block;
            padding-bottom: 5px;
            margin: 10px 0;
        }
        .event-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 8px 0;
        }
        .event-details {
            font-size: 13px;
            color: #666;
            margin: 5px 0;
        }
        .certificate-footer {
            margin-top: 10px;
        }
        .signatures {
            display: table;
            width: 100%;
            margin-top: 15px;
        }
        .signature-block {
            display: table-cell;
            width: 45%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 20px;
        }
        .signature-spacer {
            display: table-cell;
            width: 10%;
        }
        .signature-image {
            max-height: 60px;
            max-width: 180px;
            margin-bottom: 5px;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 80%;
            margin: 0 auto 5px;
        }
        .signature-name {
            font-size: 12px;
            font-weight: bold;
            color: #333;
        }
        .signature-role {
            font-size: 10px;
            color: #666;
        }
        .verification-code {
            font-size: 9px;
            color: #999;
            margin-top: 10px;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="certificate-border"></div>
        <div class="certificate-border-inner"></div>

        <div class="certificate-content">
            <div class="certificate-header">
                <div class="certificate-logos">
                    @if($siteLogoUrl && file_exists($siteLogoUrl))
                        <div class="certificate-logo-block">
                            <img src="data:image/{{ pathinfo($siteLogoUrl, PATHINFO_EXTENSION) }};base64,{{ base64_encode(file_get_contents($siteLogoUrl)) }}"
                                 class="certificate-logo" alt="Logo do site">
                            <div class="certificate-logo-label">Plataforma</div>
                        </div>
                    @endif
                    @if($ownerLogoUrl && file_exists($ownerLogoUrl))
                        <div class="certificate-logo-block">
                            <img src="data:image/{{ pathinfo($ownerLogoUrl, PATHINFO_EXTENSION) }};base64,{{ base64_encode(file_get_contents($ownerLogoUrl)) }}"
                                 class="certificate-logo" alt="Logo do organizador">
                            <div class="certificate-logo-label">Organizador</div>
                        </div>
                    @endif
                    @if($logoUrl && file_exists($logoUrl))
                        <div class="certificate-logo-block">
                            <img src="data:image/{{ pathinfo($logoUrl, PATHINFO_EXTENSION) }};base64,{{ base64_encode(file_get_contents($logoUrl)) }}"
                                 class="certificate-logo" alt="Logo do certificado">
                            <div class="certificate-logo-label">Marca do certificado</div>
                        </div>
                    @endif
                </div>
                <div class="certificate-title">Certificado</div>
                <div class="certificate-subtitle">de Participação</div>
            </div>

            <div class="certificate-body">
                <p class="certificate-text">Certificamos que</p>
                <div class="participant-name">{{ $participantName }}</div>
                <p class="certificate-text">participou do evento</p>
                <div class="event-name">{{ $event->name }}</div>
                @if($event->subtitle)
                    <p class="event-details">{{ $event->subtitle }}</p>
                @endif
                @if($event->certificate_hours)
                    <p class="event-details">Carga horária: {{ $event->certificate_hours }}</p>
                @endif
                @php
                    $minDate = $event->min_event_dates();
                    $maxDate = $event->max_event_dates();
                @endphp
                @if($minDate && $maxDate)
                    <p class="event-details">
                        @if($minDate == $maxDate)
                            Realizado em {{ \Carbon\Carbon::parse($minDate)->format('d/m/Y') }}
                        @else
                            Realizado de {{ \Carbon\Carbon::parse($minDate)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($maxDate)->format('d/m/Y') }}
                        @endif
                    </p>
                @endif
            </div>

            <div class="certificate-footer">
                <div class="signatures">
                    <div class="signature-block">
                        @if($signatureUrl && file_exists($signatureUrl))
                            <img src="data:image/{{ pathinfo($signatureUrl, PATHINFO_EXTENSION) }};base64,{{ base64_encode(file_get_contents($signatureUrl)) }}"
                                 class="signature-image" alt="Assinatura">
                            <br>
                        @endif
                        <div class="signature-line"></div>
                        <div class="signature-name">{{ $event->certificate_signature_name ?? 'Organizador' }}</div>
                        <div class="signature-role">Organizador</div>
                    </div>
                    <div class="signature-spacer"></div>
                    <div class="signature-block">
                        <div style="height: 65px;"></div>
                        <div class="signature-line"></div>
                        <div class="signature-name">{{ $participantName }}</div>
                        <div class="signature-role">Participante</div>
                    </div>
                </div>

                <div class="verification-code">
                    Código de verificação: {{ $certificate->code }}
                    | Verifique em: {{ url('/certificado/verificar') }}?code={{ $certificate->code }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
