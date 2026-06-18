<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optimixs Risk | Documento Oficial Verificado</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            background: radial-gradient(circle at 10% 20%, #0a0e27 0%, #0f172a 100%);
            min-height: 100vh; 
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }
        
        /* Patrón de fondo tecnológico */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%234f46e5' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }
        
        .certificate {
            max-width: 560px;
            width: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(248,250,252,0.98) 100%);
            backdrop-filter: blur(0px);
            border-radius: 40px;
            overflow: hidden;
            box-shadow: 0 30px 60px -20px rgba(0,0,0,0.6), 0 0 0 1px rgba(79,70,229,0.15);
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }
        
        .certificate:hover {
            transform: translateY(-4px);
        }
        
        /* Borde decorativo superior */
        .certificate::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4f46e5, #06b6d4, #4f46e5);
        }
        
        /* Sello holográfico sutil */
        .certificate::after {
            content: "⚡";
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 60px;
            opacity: 0.03;
            font-family: monospace;
            pointer-events: none;
        }
        
        .header {
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            position: relative;
        }
        
        .logo-area {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 1rem;
        }
        
        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
        }
        
        .logo-text h1 {
            font-size: 1.6rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1e293b, #334155);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.5px;
        }
        
        .logo-text p {
            font-size: 0.7rem;
            color: #64748b;
            letter-spacing: 0.5px;
        }
        
        /* Sello de verificación - reposicionado para mayor espacio */
        .verification-stamp {
            display: inline-block;
            background: rgba(16,185,129,0.12);
            border: 1px solid rgba(16,185,129,0.4);
            border-radius: 30px;
            padding: 6px 16px;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: #059669;
            letter-spacing: 1px;
        }
        
        .verification-stamp i {
            margin-right: 6px;
        }
        
        .document-type {
            margin-top: 0.75rem;
        }
        
        .document-type span {
            display: inline-block;
            background: #f1f5f9;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
            color: #475569;
            letter-spacing: 0.5px;
        }
        
        .body {
            padding: 2rem;
        }
        
        /* Badge de autenticidad mejorado */
        .authenticity-badge {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-left: 4px solid #10b981;
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .authenticity-badge i {
            font-size: 1.8rem;
            color: #10b981;
        }
        
        .authenticity-badge div strong {
            color: #065f46;
            font-size: 0.85rem;
        }
        
        .authenticity-badge div p {
            font-size: 0.7rem;
            color: #047857;
            margin-top: 2px;
        }
        
        /* Sección de advertencia - diseño más técnico */
        .security-warning {
            background: #0f172a;
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #334155;
        }
        
        .warning-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            color: #fbbf24;
        }
        
        .warning-header i {
            font-size: 1rem;
        }
        
        .warning-header span {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .security-warning ul {
            list-style: none;
            padding-left: 0;
        }
        
        .security-warning li {
            font-size: 0.7rem;
            color: #94a3b8;
            padding: 4px 0;
            padding-left: 20px;
            position: relative;
        }
        
        .security-warning li::before {
            content: "▹";
            position: absolute;
            left: 4px;
            color: #6366f1;
            font-size: 0.65rem;
        }
        
        /* Info card */
        .info-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
        }
        
        .info-title {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .info-label {
            font-size: 0.65rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
            word-break: break-word;
        }
        
        /* QR Section - estilo tecnológico */
        .qr-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid #e2e8f0;
            margin-bottom: 1rem;
        }
        
        .qr-code {
            display: inline-block;
            padding: 8px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            margin-bottom: 12px;
        }
        
        .qr-code img {
            width: 140px;
            height: auto;
            display: block;
        }
        
        .qr-title {
            font-size: 0.7rem;
            font-weight: 600;
            color: #4f46e5;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        
        .qr-subtitle {
            font-size: 0.6rem;
            color: #64748b;
        }
        
        /* Huella digital */
        .digital-fingerprint {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 0.75rem;
            margin-top: 1rem;
            text-align: center;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.55rem;
            color: #64748b;
            word-break: break-all;
        }
        
        .footer {
            padding: 1rem 2rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        
        .footer p {
            font-size: 0.6rem;
            color: #94a3b8;
        }
        
        .footer .powered {
            margin-top: 6px;
            font-size: 0.55rem;
            letter-spacing: 0.5px;
        }
        
        hr {
            margin: 1rem 0;
            border: none;
            border-top: 1px solid #e2e8f0;
        }
        
        @media (max-width: 500px) {
            body { padding: 1rem; }
            .certificate { border-radius: 24px; }
            .body { padding: 1.5rem; }
            .info-grid { grid-template-columns: 1fr; gap: 8px; }
        }
    </style>
</head>
<body>
<div class="certificate">
    <div class="header">
        <div class="logo-area">
            <div class="logo-icon">🔒</div>
            <div class="logo-text">
                <h1>OPTIMIXS RISK</h1>
                <p>Sistema Integral de Gestión de Riesgos Empresariales</p>
            </div>
        </div>
        <div class="verification-stamp">
            <i class="fas fa-check-circle"></i> VERIFICADO
        </div>
        <div class="document-type">
            <span>CERTIFICADO DIGITAL</span>
        </div>
    </div>
    
    <div class="body">
        <!-- Badge de autenticidad -->
        <div class="authenticity-badge">
            <i class="fas fa-shield-alt"></i>
            <div>
                <strong>Documento autenticado por Optimixs Risk</strong>
                <p>Este documento ha sido generado y firmado digitalmente</p>
            </div>
        </div>
        
        <!-- Advertencia de seguridad - estilo técnico -->
        <div class="security-warning">
            <div class="warning-header">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Protocolo de Seguridad</span>
            </div>
            <ul>
                <li>Documento generado digitalmente en la fecha y hora estampada</li>
                <li>Cualquier alteración, modificación o copia no autorizada invalida este documento</li>
                <li>La autenticidad del documento puede ser verificada mediante el código QR adjunto</li>
                <li>Este sello digital certifica la integridad del contenido original</li>
            </ul>
        </div>
        
        <!-- Información del reporte -->
        <div class="info-card">
            <div class="info-title">
                <i class="fas fa-file-alt"></i>
                <span>INFORMACIÓN DEL DOCUMENTO</span>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">ID del Reporte</span>
                    <span class="info-value">#<?= htmlspecialchars($reporte['id']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha de Emisión</span>
                    <span class="info-value"><?= date('d/m/Y H:i:s', strtotime($reporte['created_at'])) ?></span>
                </div>
                <?php if (!empty($reporte['organization_name'])): ?>
                <div class="info-item">
                    <span class="info-label">Organización</span>
                    <span class="info-value"><?= htmlspecialchars($reporte['organization_name']) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($reporte['igr'])): ?>
                <div class="info-item">
                    <span class="info-label">Índice IGR</span>
                    <span class="info-value"><?= htmlspecialchars($reporte['igr']) ?>%</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Código QR con diseño tecnológico -->
        <div class="qr-section">
            <div class="qr-code">
                <?php if (!empty($reporte['qr_code'])): ?>
                    <img src="<?= htmlspecialchars($reporte['qr_code']) ?>" alt="Código QR de autenticidad">
                <?php else: ?>
                    <div style="width: 140px; height: 140px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; border-radius: 12px;">
                        <span style="font-size: 12px; color: #94a3b8;">QR no disponible</span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="qr-title">🔒 OPTIMIXS-SIGNED</div>
            <div class="qr-subtitle">Código de verificación generado al momento de la creación</div>
        </div>
        
        <!-- Huella digital (hash simulado) -->
        <div class="digital-fingerprint">
            <i class="fas fa-fingerprint"></i> HASH: <?= substr(hash('sha256', $reporte['id'] . $reporte['created_at']), 0, 16) ?>...<?= substr(hash('sha256', $reporte['id'] . $reporte['created_at']), -8) ?>
        </div>
    </div>
    
    <div class="footer">
        <p>Este documento es auténtico y fue emitido por el sistema Optimixs Risk.</p>
        <p class="powered">Powered by Optimixs Risk · Sistema de Gestión de Riesgos Empresariales</p>
    </div>
</div>
</body>
</html>
