<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optimixs Risk | Inteligencia Marítima</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            background: white;
            min-height: 100vh; 
            padding: 2rem;
            position: relative;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .card {
            background: white;
            border-radius: 40px;
            overflow: hidden;
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4f46e5, #06b6d4, #4f46e5);
        }
        
        /* Animación del barco - solo durante loading */
        .boat-loading {
            position: fixed;
            bottom: 30px;
            left: -100px;
            z-index: 1000;
            pointer-events: none;
            transition: opacity 0.3s;
        }
        
        .boat-loading.sail {
            animation: sailAcross 8s ease-in-out infinite, floatBoat 2s ease-in-out infinite;
        }
        
        @keyframes sailAcross {
            0% { transform: translateX(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateX(calc(100vw + 100px)); opacity: 0; }
        }
        
        @keyframes floatBoat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(2deg); }
        }
        
        .boat-icon {
            font-size: 2.5rem;
            filter: drop-shadow(2px 4px 8px rgba(0,0,0,0.15));
        }
        
        .water-splash {
            position: absolute;
            bottom: -5px;
            left: 10px;
            font-size: 0.8rem;
            color: #06b6d4;
            animation: splash 0.5s ease-in-out infinite alternate;
        }
        
        @keyframes splash {
            from { transform: scale(0.8); opacity: 0.6; }
            to { transform: scale(1.2); opacity: 1; }
        }
        
        /* ASISTENTE INTELIGENTE */
        .assistant {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem;
        }
        
        .assistant-content {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .assistant-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
        }
        
        .assistant-text {
            flex: 1;
        }
        
        .assistant-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            flex-wrap: wrap;
        }
        
        .assistant-title h2 {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
        }
        
        .assistant-badge {
            background: #10b981;
            color: white;
            font-size: 0.6rem;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
        }
        
        .assistant-message {
            min-height: 80px;
        }
        
        .assistant-message p {
            color: #475569;
            font-size: 0.875rem;
            line-height: 1.5;
        }
        
        .typing-cursor {
            display: inline-block;
            width: 2px;
            height: 14px;
            background-color: #94a3b8;
            margin-left: 2px;
            vertical-align: middle;
            animation: blink 1s step-end infinite;
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }
        
        .assistant-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.75rem;
            flex-wrap: wrap;
        }
        
        .assistant-btn {
            background: #f1f5f9;
            border: none;
            border-radius: 20px;
            padding: 0.25rem 0.75rem;
            font-size: 0.7rem;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .assistant-btn:hover {
            background: #e2e8f0;
        }
        
        /* Header con botón de regreso */
        .header {
            padding: 1.5rem 2rem 1rem;
            text-align: center;
            position: relative;
        }
        
        .btn-back {
            position: absolute;
            top: 1.5rem;
            right: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            z-index: 20;
        }
        
        .btn-back:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        
        .logo-area {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 1rem;
        }
        
        .logo-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
            transition: transform 0.3s;
        }
        
        .logo-icon:hover {
            transform: rotate(5deg) scale(1.05);
        }
        
        .logo-text h1 {
            font-size: 1.5rem;
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
        
        .badge-status {
            display: inline-block;
            background: rgba(79,70,229,0.12);
            border: 1px solid rgba(79,70,229,0.4);
            border-radius: 30px;
            padding: 6px 16px;
            margin-top: 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: #4f46e5;
        }
        
        .btn-export-pdf {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            border: none;
            border-radius: 14px;
            padding: 0.5rem 1rem;
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-export-pdf:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220,38,38,0.4);
        }
        
        .search-section {
            padding: 2rem;
            background: white;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .search-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .search-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1e293b;
        }
        
        .search-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .search-box {
            background: #f8fafc;
            border-radius: 20px;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }
        
        .search-box:focus-within {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        }
        
        .search-box label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .search-input-group {
            display: flex;
            gap: 0.5rem;
        }
        
        .search-input-group input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.9rem;
            outline: none;
            background: white;
        }
        
        .search-input-group input:focus {
            border-color: #4f46e5;
        }
        
        .search-input-group button {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border: none;
            border-radius: 14px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .search-input-group button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79,70,229,0.4);
        }
        
        .btn-clear {
            background: #f1f5f9;
            border: none;
            border-radius: 14px;
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            color: #64748b;
            cursor: pointer;
        }
        
        .btn-clear:hover {
            background: #e2e8f0;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 3rem;
            position: relative;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 3px solid #e2e8f0;
            border-top-color: #4f46e5;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .results {
            padding: 2rem;
        }
        
        .vessel-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            margin-bottom: 1.5rem;
            transition: transform 0.2s;
        }
        
        .vessel-card:hover {
            transform: translateY(-4px);
        }
        
        .vessel-header {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            padding: 1.5rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .vessel-name {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .vessel-imo {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 0.25rem;
        }
        
        .risk-badge {
            padding: 0.5rem 1rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .risk-high { background: #dc2626; color: white; }
        .risk-medium { background: #f59e0b; color: white; }
        .risk-low { background: #10b981; color: white; }
        
        .vessel-body {
            padding: 1.5rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .info-item {
            background: #f8fafc;
            padding: 0.75rem;
            border-radius: 12px;
        }
        
        .info-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
            margin-top: 0.25rem;
        }
        
        .section-title {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #4f46e5;
            margin: 1rem 0 0.75rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .management-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .management-item {
            background: #f8fafc;
            padding: 0.75rem;
            border-radius: 12px;
        }
        
        .management-role {
            font-size: 0.7rem;
            font-weight: 600;
            color: #4f46e5;
        }
        
        .management-name {
            font-size: 0.85rem;
            font-weight: 500;
            color: #1e293b;
        }
        
        .inspections-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }
        
        .inspections-table th,
        .inspections-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .inspections-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #64748b;
        }
        
        .detention-yes { color: #dc2626; font-weight: 600; }
        .detention-no { color: #10b981; }
        
        .error-message {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 1rem;
            margin: 1rem;
            border-radius: 12px;
            color: #991b1b;
        }
        
        /* Estilos para el footer del PDF */
        .pdf-footer {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 0.7rem;
            color: #94a3b8;
        }
        
        .pdf-footer .logo-mini {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 0.5rem;
        }
        
        .pdf-footer .logo-mini-icon {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        @media (max-width: 768px) {
            .search-grid { grid-template-columns: 1fr; }
            body { padding: 1rem; }
            .vessel-header { flex-direction: column; text-align: center; }
            .inspections-table { display: block; overflow-x: auto; }
            .assistant-content { flex-direction: column; }
            .search-header { flex-direction: column; align-items: flex-start; }
            .btn-back { position: static; margin-bottom: 1rem; justify-content: center; }
            .header { padding-top: 1rem; }
        }
        
        @media print {
            body { background: white; padding: 0; }
            .assistant, .search-section, .btn-export-pdf, .btn-clear, .btn-back, .boat-loading { display: none; }
            .card { box-shadow: none; border: none; margin: 0; }
            .vessel-card { break-inside: avoid; border: 1px solid #e2e8f0; }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
<!-- Barco animado que aparece SOLO durante la carga -->
<div id="boatLoading" class="boat-loading" style="display: none;">
    <i class="fas fa-ship boat-icon" style="color: #4f46e5;"></i>
    <div class="water-splash">
        <i class="fas fa-water"></i><i class="fas fa-water" style="margin-left: 5px;"></i>
    </div>
</div>

<div class="container">
    <div class="card">
        <!-- ASISTENTE INTELIGENTE -->
        <div class="assistant">
            <div class="assistant-content">
                <div class="assistant-icon">
                    <i class="fas fa-ship text-white text-xl"></i>
                </div>
                <div class="assistant-text">
                    <div class="assistant-title">
                        <h2>Inteligencia Marítima</h2>
                        <span class="assistant-badge">Optimixs Marine Database</span>
                    </div>
                    <div class="assistant-message">
                        <p id="assistantMessage">
                            <span id="typingText"></span>
                            <span id="typingCursor" class="typing-cursor"></span>
                        </p>
                    </div>
                    <div class="assistant-buttons">
                        <button onclick="showAssistantTip('que-es')" class="assistant-btn">🚢 ¿Qué es este módulo?</button>
                        <button onclick="showAssistantTip('como-buscar')" class="assistant-btn">🔍 Cómo buscar un buque</button>
                        <button onclick="showAssistantTip('datos')" class="assistant-btn">📊 Datos disponibles</button>
                        <button onclick="resetAssistantMessage()" class="assistant-btn">⟳ Reiniciar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Header con botón de regreso -->
        <div class="header">
            <a href="/admin/dashboard" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
            <div class="logo-area">
                <div class="logo-icon">⚓</div>
                <div class="logo-text">
                    <h1>Sistema Integral de Inteligencia Marítima</h1>
                    <p>Optimixs Risk · Análisis de Riesgos y Seguridad</p>
                </div>
            </div>
            <div class="badge-status">
                <i class="fas fa-database"></i> Optimixs Marine Database · Datos Oficiales
            </div>
        </div>
        
        <!-- Buscador -->
        <div class="search-section">
            <div class="search-header">
                <div class="search-title">
                    <i class="fas fa-search text-indigo-500 mr-2"></i> Consulta de Buques
                </div>
                <button id="btnExportarPDF" class="btn-export-pdf" style="display: none;">
                    <i class="fas fa-file-pdf"></i> Exportar a PDF
                </button>
            </div>
            <div class="search-grid">
                <div class="search-box">
                    <label><i class="fas fa-hashtag"></i> BUSCAR POR IMO (7 DÍGITOS)</label>
                    <div class="search-input-group">
                        <input type="number" id="imoInput" placeholder="Ej: 9426192">
                        <button id="btnSearchIMO"><i class="fas fa-search"></i> BUSCAR</button>
                    </div>
                </div>
                <div class="search-box">
                    <label><i class="fas fa-ship"></i> BUSCAR POR NOMBRE</label>
                    <div class="search-input-group">
                        <input type="text" id="nameInput" placeholder="Ej: ECUADOR L, MAERSK...">
                        <button id="btnSearchName"><i class="fas fa-search"></i> BUSCAR</button>
                    </div>
                </div>
            </div>
            <div style="text-align: right; margin-top: 1rem;">
                <button id="btnLimpiar" class="btn-clear"><i class="fas fa-eraser"></i> Limpiar resultados</button>
            </div>
        </div>
        
        <!-- Loading -->
        <div id="loadingIndicator" class="loading">
            <div class="spinner"></div>
            <p style="color: #64748b;">Consultando base de datos Optimixs Marine...</p>
        </div>
        
        <!-- Resultados -->
        <div id="resultsContainer" class="results" style="display: none;"></div>
        
        <!-- Error -->
        <div id="errorMessage" class="error-message" style="display: none;"></div>
    </div>
</div>

<script>
// === ASISTENTE INTELIGENTE ===
let typingInterval = null;
let currentFullMessage = '';
let currentCharIndex = 0;
let currentVesselData = null;
let currentVesselHTML = '';

function typeWriter() {
    const typingSpan = document.getElementById('typingText');
    if (!typingSpan) return;
    if (currentCharIndex < currentFullMessage.length) {
        typingSpan.textContent += currentFullMessage.charAt(currentCharIndex);
        currentCharIndex++;
        setTimeout(typeWriter, 25);
    } else {
        const cursor = document.getElementById('typingCursor');
        if (cursor) cursor.style.opacity = '0';
    }
}

function setTypingMessage(message) {
    if (typingInterval) clearTimeout(typingInterval);
    currentFullMessage = message;
    currentCharIndex = 0;
    const typingSpan = document.getElementById('typingText');
    const cursor = document.getElementById('typingCursor');
    if (typingSpan) typingSpan.textContent = '';
    if (cursor) cursor.style.opacity = '1';
    typeWriter();
}

const assistantMessages = {
    initial: "Bienvenido al Sistema Integral de Inteligencia Marítima. Esta herramienta permite consultar información detallada de cualquier buque usando la base de datos Optimixs Marine, que contiene más de 85,000 buques mercantes a nivel mundial.",
    'que-es': "Este sistema consulta la base de datos oficial Equasis (Electronic Quality Shipping Information System), un sistema global de información sobre seguridad y calidad de buques mercantes, respaldado por la OMI y la Unión Europea.",
    'como-buscar': "Para buscar un buque: 1) Use el campo IMO con 7 dígitos (ej: 9426192) para resultados exactos, o 2) Use el campo NOMBRE con al menos 3 caracteres para búsqueda parcial. Los resultados mostrarán toda la información disponible del buque.",
    datos: "La información disponible incluye: datos generales del buque (IMO, nombre, bandera, tipo, arqueo, DWT), gestión (propietario registrado, gestor ISM, gestor comercial), inspecciones PSC (Control de Estado del Puerto) con detenciones y deficiencias, historial de nombres y banderas."
};

window.showAssistantTip = function(tipo) {
    let message = assistantMessages[tipo] || assistantMessages.initial;
    setTypingMessage(message);
};

window.resetAssistantMessage = function() {
    setTypingMessage(assistantMessages.initial);
};

// === FUNCIONES DEL MÓDULO ===
const imoInput = document.getElementById('imoInput');
const nameInput = document.getElementById('nameInput');
const btnSearchIMO = document.getElementById('btnSearchIMO');
const btnSearchName = document.getElementById('btnSearchName');
const btnLimpiar = document.getElementById('btnLimpiar');
const btnExportarPDF = document.getElementById('btnExportarPDF');
const loadingIndicator = document.getElementById('loadingIndicator');
const resultsContainer = document.getElementById('resultsContainer');
const errorMessage = document.getElementById('errorMessage');
const boatLoading = document.getElementById('boatLoading');
let boatAnimationInterval = null;

function startBoatAnimation() {
    if (boatLoading) {
        boatLoading.style.display = 'block';
        // Forzar reflow para reiniciar animación
        boatLoading.classList.remove('sail');
        void boatLoading.offsetWidth;
        boatLoading.classList.add('sail');
    }
}

function stopBoatAnimation() {
    if (boatLoading) {
        boatLoading.classList.remove('sail');
        boatLoading.style.display = 'none';
    }
}

function showLoading(show) {
    if (show) {
        loadingIndicator.style.display = 'block';
        startBoatAnimation();
    } else {
        loadingIndicator.style.display = 'none';
        stopBoatAnimation();
    }
}

function showError(message) {
    errorMessage.textContent = message;
    errorMessage.style.display = 'block';
    setTimeout(() => {
        errorMessage.style.display = 'none';
    }, 5000);
}

function clearResults() {
    resultsContainer.innerHTML = '';
    resultsContainer.style.display = 'none';
    btnExportarPDF.style.display = 'none';
    currentVesselData = null;
    currentVesselHTML = '';
    imoInput.value = '';
    nameInput.value = '';
}

function calculateRisk(inspections) {
    const detentions = inspections.filter(i => i.detention === 'Y').length;
    if (detentions >= 3) return { level: 'ALTO', class: 'risk-high', text: 'Alto' };
    if (detentions >= 1) return { level: 'MEDIO', class: 'risk-medium', text: 'Medio' };
    return { level: 'BAJO', class: 'risk-low', text: 'Bajo' };
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function formatDateTime() {
    const ahora = new Date();
    const dia = ahora.getDate();
    const mes = ahora.getMonth() + 1;
    const año = ahora.getFullYear();
    let horas = ahora.getHours();
    const minutos = ahora.getMinutes().toString().padStart(2, '0');
    const segundos = ahora.getSeconds().toString().padStart(2, '0');
    const ampm = horas >= 12 ? 'p. m.' : 'a. m.';
    horas = horas % 12 || 12;
    return `${dia}/${mes}/${año}, ${horas}:${minutos}:${segundos} ${ampm}`;
}

function displaySearchResults(results) {
    let html = '<div class="vessel-card"><div class="vessel-header"><div><div class="vessel-name">Resultados de búsqueda</div></div></div><div class="vessel-body">';
    html += '<table class="inspections-table"><thead><tr><th>IMO</th><th>Nombre</th><th>Tipo</th><th>Acción</th></tr></thead><tbody>';
    results.forEach(vessel => {
        html += `<tr>
            <td>${escapeHtml(vessel.imo || 'N/A')}</td>
            <td><strong>${escapeHtml(vessel.name || 'N/A')}</strong></td>
            <td>${escapeHtml(vessel.type || 'N/A')}</td>
            <td><button onclick="searchByIMODirect('${vessel.imo}')" style="background:#4f46e5; color:white; border:none; padding:0.25rem 0.75rem; border-radius:8px; cursor:pointer;">Ver detalles</button></td>
        </tr>`;
    });
    html += '</tbody></table></div></div>';
    resultsContainer.innerHTML = html;
    resultsContainer.style.display = 'block';
    btnExportarPDF.style.display = 'none';
}

window.searchByIMODirect = function(imo) {
    imoInput.value = imo;
    searchByIMO();
};

function generateVesselHTML(data) {
    const vessel = data.vessel;
    const management = data.management || [];
    const inspections = data.inspections || [];
    const historicalNames = data.historical_names || [];
    const historicalFlags = data.historical_flags || [];
    const risk = calculateRisk(inspections);
    const fechaActual = formatDateTime();
    
    return `
        <div class="vessel-card">
            <div class="vessel-header">
                <div>
                    <div class="vessel-name">${escapeHtml(vessel.name || 'N/A')}</div>
                    <div class="vessel-imo">IMO: ${vessel.imo || 'N/A'} | Bandera: ${escapeHtml(vessel.flag || vessel.flag_code || 'N/A')}</div>
                </div>
                <div class="risk-badge ${risk.class}">⚠️ Riesgo ${risk.text}</div>
            </div>
            <div class="vessel-body">
                <div class="section-title"><i class="fas fa-microchip"></i> CARACTERÍSTICAS TÉCNICAS</div>
                <div class="info-grid">
                    <div class="info-item"><div class="info-label">Tipo de buque</div><div class="info-value">${escapeHtml(vessel.vessel_type || 'N/A')}</div></div>
                    <div class="info-item"><div class="info-label">GT (Arqueo Bruto)</div><div class="info-value">${vessel.gross_tonnage || 'N/A'}</div></div>
                    <div class="info-item"><div class="info-label">DWT</div><div class="info-value">${vessel.dwt || 'N/A'}</div></div>
                    <div class="info-item"><div class="info-label">Año de construcción</div><div class="info-value">${vessel.year_built || 'N/A'}</div></div>
                    <div class="info-item"><div class="info-label">Estado</div><div class="info-value">${escapeHtml(vessel.status || 'N/A')}</div></div>
                    <div class="info-item"><div class="info-label">Última actualización</div><div class="info-value">${vessel.last_update || 'N/A'}</div></div>
                </div>
                
                ${management.length > 0 ? `
                <div class="section-title"><i class="fas fa-building"></i> PROPIEDAD Y GESTIÓN</div>
                <div class="management-list">
                    ${management.map(m => `
                        <div class="management-item">
                            <div class="management-role">${escapeHtml(m.role || '')}</div>
                            <div class="management-name">${escapeHtml(m.name || '')}</div>
                            ${m.address ? `<div style="font-size:0.7rem; color:#64748b; margin-top:0.25rem;">${escapeHtml(m.address)}</div>` : ''}
                            ${m.date_effect ? `<div style="font-size:0.65rem; color:#94a3b8;">Desde: ${m.date_effect}</div>` : ''}
                        </div>
                    `).join('')}
                </div>
                ` : ''}
                
                <div class="section-title"><i class="fas fa-clipboard-list"></i> INSPECCIONES PSC</div>
                ${inspections.length > 0 ? `
                <table class="inspections-table">
                    <thead><tr><th>Fecha</th><th>Puerto</th><th>Organización</th><th>Detención</th><th>Deficiencias</th></tr></thead>
                    <tbody>
                        ${inspections.slice(0, 15).map(i => `
                            <tr>
                                <td>${i.date || 'N/A'}</td>
                                <td>${escapeHtml(i.port || 'N/A')}</td>
                                <td>${escapeHtml(i.psc_organization || 'N/A')}</td>
                                <td class="${i.detention === 'Y' ? 'detention-yes' : 'detention-no'}">${i.detention === 'Y' ? 'SÍ' : 'NO'}</td>
                                <td>${i.deficiencies || '0'}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
                ` : '<p style="color:#64748b;">Sin inspecciones registradas</p>'}
                
                ${historicalNames.length > 0 ? `
                <div class="section-title"><i class="fas fa-history"></i> NOMBRES ANTERIORES</div>
                <div class="info-grid">
                    ${historicalNames.map(n => `<div class="info-item"><div class="info-value">${escapeHtml(n.name)}</div><div class="info-label">${n.date_effect || 'N/A'}</div></div>`).join('')}
                </div>
                ` : ''}
                
                ${historicalFlags.length > 0 ? `
                <div class="section-title"><i class="fas fa-flag-checkered"></i> BANDERAS ANTERIORES</div>
                <div class="info-grid">
                    ${historicalFlags.map(f => `<div class="info-item"><div class="info-value">${escapeHtml(f.flag)}</div><div class="info-label">${f.date_effect || 'N/A'}</div></div>`).join('')}
                </div>
                ` : ''}
                
                <div class="pdf-footer">
                    <div class="logo-mini">
                        <span class="logo-mini-icon">⚓</span>
                        <span>Optimixs Risk</span>
                    </div>
                    <div>Documento generado por Optimixs Risk - Sistema de Inteligencia Marítima</div>
                    <div>Fecha: ${fechaActual}</div>
                </div>
            </div>
        </div>
    `;
}

function displayVesselData(data) {
    currentVesselHTML = generateVesselHTML(data);
    resultsContainer.innerHTML = currentVesselHTML;
    resultsContainer.style.display = 'block';
    btnExportarPDF.style.display = 'flex';
}

function exportToPDF() {
    if (!currentVesselHTML) {
        showError('No hay datos para exportar a PDF');
        return;
    }
    
    const element = document.getElementById('resultsContainer');
    const opt = {
        margin: [0.5, 0.5, 0.5, 0.5],
        filename: `buque_${currentVesselData?.vessel?.imo || 'reporte'}_optimixs.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, letterRendering: true, useCORS: true },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };
    
    showLoading(true);
    html2pdf().set(opt).from(element).save().then(() => {
        showLoading(false);
    }).catch(err => {
        showLoading(false);
        showError('Error al generar PDF: ' + err.message);
    });
}

async function searchByIMO() {
    const imo = imoInput.value.trim();
    if (!imo || imo.length !== 7 || !/^\d+$/.test(imo)) {
        showError('El IMO debe ser un número de 7 dígitos');
        return;
    }
    
    showLoading(true);
    clearResults();
    
    try {
        const response = await fetch(`/api/maritime/vessel/${imo}`);
        const result = await response.json();
        
        if (result.error) {
            showError(result.error);
        } else if (result.success) {
            currentVesselData = result;
            displayVesselData(result);
        } else {
            showError('Buque no encontrado');
        }
    } catch (error) {
        showError('Error de conexión con el servidor');
    } finally {
        showLoading(false);
    }
}

async function searchByName() {
    const name = nameInput.value.trim();
    if (name.length < 3) {
        showError('El nombre debe tener al menos 3 caracteres');
        return;
    }
    
    showLoading(true);
    clearResults();
    
    try {
        const response = await fetch(`/api/maritime/search?name=${encodeURIComponent(name)}`);
        const result = await response.json();
        
        if (result.error) {
            showError(result.error);
        } else if (result.success && result.results && result.results.length > 0) {
            displaySearchResults(result.results);
        } else {
            showError('No se encontraron buques con ese nombre');
        }
    } catch (error) {
        showError('Error de conexión con el servidor');
    } finally {
        showLoading(false);
    }
}

// Event listeners
btnSearchIMO.addEventListener('click', searchByIMO);
btnSearchName.addEventListener('click', searchByName);
btnLimpiar.addEventListener('click', clearResults);
btnExportarPDF.addEventListener('click', exportToPDF);

imoInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') searchByIMO(); });
nameInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') searchByName(); });

// Inicializar asistente
setTimeout(() => {
    setTypingMessage(assistantMessages.initial);
}, 500);
</script>
</body>
</html>
