<div class="container mx-auto px-4 py-6">
    <!-- ASISTENTE INTELIGENTE - OPTIMIX RISK -->
    <div class="mb-6 bg-gray-50 rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gray-200 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h2 class="text-base font-semibold text-gray-800">Optimix Risk</h2>
                        <span class="px-1.5 py-0.5 bg-green-100 text-green-700 text-[10px] rounded-full font-medium">En línea</span>
                    </div>
                    <div class="min-h-[80px]">
                        <p id="assistantMessage" class="text-sm text-gray-600 leading-relaxed">
                            <span id="typingText"></span>
                            <span id="typingCursor" class="inline-block w-0.5 h-4 bg-gray-400 ml-0.5 animate-pulse" style="vertical-align: middle;"></span>
                        </p>
                    </div>
                    <div class="flex gap-2 mt-3 flex-wrap">
                        <button onclick="showAssistantTip('que-es')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📊 ¿Qué es este módulo?</button>
                        <button onclick="showAssistantTip('como-usar')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📋 Cómo generar un reporte</button>
                        <button onclick="showAssistantTip('archivos')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📎 Cómo adjuntar archivos</button>
                        <button onclick="resetAssistantMessage()" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">⟳ Reiniciar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Reporte Final de Riesgos</h1>
        <div class="flex gap-2">
            <button id="btnGuardarReporte" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow-md flex items-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Guardar reporte
            </button>
            <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg shadow-md flex items-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir / PDF
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Seleccionar Organización</label>
                <select id="organizacionSelector" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Seleccionar organización --</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Seleccionar Checklist</label>
                <select id="checklistSelector" class="w-full border border-gray-300 rounded-lg px-3 py-2" disabled>
                    <option value="">-- Primero selecciona una organización --</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end mt-4">
            <button id="btnGenerarReporte" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-sync-alt mr-1"></i> Generar Reporte
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden mb-6" id="reportFilesSection">
        <div class="bg-gray-800 text-white px-6 py-3">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                <i class="fas fa-paperclip"></i> Archivos adjuntos del reporte actual
            </h2>
        </div>
        <div class="p-6">
            <div id="reportFilesContainer">
                <div class="text-center py-4 text-gray-500">
                    <i class="fas fa-info-circle mr-2"></i> Genera y guarda un reporte para poder adjuntar archivos.
                </div>
            </div>
            <div class="mt-4 border-t pt-4">
                <div class="flex gap-3 items-center flex-wrap">
                    <input type="file" id="reportFileInput" class="border rounded-lg px-3 py-2 flex-1 min-w-[200px]" disabled>
                    <button id="btnAdjuntarArchivo" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-upload"></i> Adjuntar archivo
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-2">Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, GIF, TXT, ZIP, RAR (máx. 10 MB)</p>
                <p class="text-xs text-gray-400 mt-1" id="reportFileStatus"></p>
            </div>
        </div>
    </div>

    <div id="reporteContainer" class="space-y-6"></div>
</div>

<style>
    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0; }
    }
    .animate-pulse {
        animation: blink 1s step-end infinite;
    }
    .qr-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    .qr-container h2 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .qr-container img {
        width: 160px;
        height: auto;
        margin: 0 auto;
    }
    .qr-container p {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.5rem;
    }
    .qr-container .qr-badge {
        font-size: 0.625rem;
        color: #4f46e5;
        font-family: monospace;
        margin-top: 0.25rem;
        letter-spacing: 1px;
        font-weight: 600;
    }

    /* Estilos para el contenido del reporte */
    .report-content {
        font-size: 0.95rem;
        line-height: 1.6;
    }
    .report-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.5rem;
    }
    .report-content h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #334155;
        margin-top: 1.25rem;
        margin-bottom: 0.75rem;
    }
    .report-content p {
        margin-bottom: 0.75rem;
        color: #4b5563;
    }
    .report-content ul, .report-content ol {
        margin: 0.75rem 0 0.75rem 1.5rem;
    }
    .report-content li {
        margin-bottom: 0.25rem;
    }
    .report-content hr {
        margin: 1rem 0;
        border-color: #e2e8f0;
    }
    .report-content strong {
        font-weight: 600;
        color: #1e293b;
    }
</style>

<script>
let typingInterval = null;
let currentFullMessage = '';
let currentCharIndex = 0;

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
    initial: "Bienvenido al Módulo de Reporte Final de Riesgos. Esta herramienta permite consolidar toda la información de evaluación de riesgos en un documento estructurado profesional.",
    'que-es': "El Reporte Final de Riesgos es un documento ejecutivo que integra datos de la organización, resultados del checklist, análisis de riesgos y recomendaciones estratégicas.",
    'como-usar': "Para generar un reporte: 1) Seleccione una organización, 2) Elija un checklist, 3) Haga clic en 'Generar Reporte'. Luego puede guardarlo o imprimirlo.",
    archivos: "📎 Para adjuntar archivos, primero debe GENERAR y GUARDAR el reporte. Luego se habilitará la opción de adjuntar."
};

window.showAssistantTip = function(tipo) {
    let message = assistantMessages[tipo] || assistantMessages.initial;
    setTypingMessage(message);
};

window.resetAssistantMessage = function() {
    setTypingMessage(assistantMessages.initial);
};

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
let currentReportId = null;
let allRiesgos = [];
let allEvaluations = [];
let allChecklists = [];
let currentOrganization = null;

function enableFileUpload(enable) {
    const fileInput = document.getElementById('reportFileInput');
    const uploadBtn = document.getElementById('btnAdjuntarArchivo');
    const statusSpan = document.getElementById('reportFileStatus');
    
    if (fileInput) fileInput.disabled = !enable;
    if (uploadBtn) uploadBtn.disabled = !enable;
    
    if (statusSpan) {
        if (enable && currentReportId) {
            statusSpan.innerHTML = '<i class="fas fa-check-circle text-green-500"></i> Puedes adjuntar archivos a este reporte';
            statusSpan.className = 'text-xs text-green-600 mt-1';
        } else {
            statusSpan.innerHTML = '<i class="fas fa-info-circle text-gray-400"></i> Genera y guarda un reporte para poder adjuntar archivos';
            statusSpan.className = 'text-xs text-gray-400 mt-1';
        }
    }
}

async function loadOrganizaciones() {
    try {
        const res = await fetch('/api/risk/organizations');
        const data = await res.json();
        if (data.success) {
            const selector = document.getElementById('organizacionSelector');
            selector.innerHTML = '<option value="">-- Seleccionar organización --</option>';
            data.data.forEach(org => {
                const opt = document.createElement('option');
                opt.value = org.id;
                opt.textContent = org.nombre;
                selector.appendChild(opt);
            });
        }
    } catch(e) { console.error('Error cargando organizaciones:', e); }
}

async function loadRiesgos() {
    try {
        const res = await fetch('/api/risk/matrix');
        const data = await res.json();
        if (data.success) {
            allRiesgos = data.data || [];
        }
    } catch(e) { console.error('Error cargando riesgos:', e); }
}

async function loadEvaluations() {
    try {
        const res = await fetch('/api/risk/evaluations');
        const data = await res.json();
        if (data.success) {
            allEvaluations = data.data || [];
        }
    } catch(e) { console.error('Error cargando evaluaciones:', e); }
}

async function loadAllChecklists() {
    try {
        const res = await fetch('/api/risk/checklist/dashboard');
        const data = await res.json();
        if (data.success && data.data) {
            allChecklists = data.data;
        }
    } catch(e) { console.error('Error cargando checklists:', e); }
}

async function loadReportFiles(reportId) {
    try {
        const response = await fetch(`/api/risk/reports/${reportId}/files`);
        const result = await response.json();
        if (result.success) {
            renderFileList(result.data, reportId);
        }
    } catch(e) {
        console.error('Error cargando archivos:', e);
    }
}

function renderFileList(files, reportId) {
    const container = document.getElementById('reportFilesContainer');
    if (!container) return;
    
    if (!files || files.length === 0) {
        container.innerHTML = '<div class="text-center py-4 text-gray-500">📎 No hay archivos adjuntos. Usa el botón "Adjuntar archivo" para agregar.</div>';
        return;
    }
    
    let html = '<div class="grid grid-cols-1 gap-2">';
    files.forEach(file => {
        const icon = getFileIcon(file.original_name);
        const sizeKB = (file.file_size / 1024).toFixed(2);
        html += `
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border hover:bg-gray-100">
                <div class="flex items-center gap-3">
                    <i class="fas ${icon} text-blue-500 text-lg"></i>
                    <div>
                        <span class="font-medium text-gray-700">${escapeHtml(file.original_name)}</span>
                        <span class="text-xs text-gray-400 ml-2">(${sizeKB} KB)</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="/api/risk/reports/${reportId}/files/${file.id}/download" class="text-green-600 hover:text-green-800" title="Descargar">
                        <i class="fas fa-download"></i>
                    </a>
                    <button onclick="deleteReportFile(${file.id}, ${reportId})" class="text-red-600 hover:text-red-800" title="Eliminar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const icons = {
        pdf: 'fa-file-pdf',
        doc: 'fa-file-word', docx: 'fa-file-word',
        xls: 'fa-file-excel', xlsx: 'fa-file-excel',
        jpg: 'fa-file-image', jpeg: 'fa-file-image', png: 'fa-file-image', gif: 'fa-file-image',
        zip: 'fa-file-archive', rar: 'fa-file-archive',
        txt: 'fa-file-alt'
    };
    return icons[ext] || 'fa-file';
}

async function uploadReportFile() {
    if (!currentReportId) {
        alert('Primero genera y guarda un reporte');
        return;
    }
    
    const fileInput = document.getElementById('reportFileInput');
    const file = fileInput.files[0];
    if (!file) {
        alert('Selecciona un archivo primero');
        return;
    }
    
    const formData = new FormData();
    formData.append('file', file);
    
    try {
        const response = await fetch(`/api/risk/reports/files/upload?report_id=${currentReportId}`, {
            method: 'POST',
            headers: { 'X-CSRF-Token': csrfToken },
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            alert('Archivo subido correctamente');
            fileInput.value = '';
            loadReportFiles(currentReportId);
        } else {
            alert('Error: ' + result.error);
        }
    } catch(e) {
        alert('Error de conexión: ' + e.message);
    }
}

async function deleteReportFile(fileId, reportId) {
    if (!confirm('¿Eliminar este archivo permanentemente?')) return;
    
    const formData = new URLSearchParams();
    formData.append('csrf_token', csrfToken);
    
    try {
        const response = await fetch(`/api/risk/reports/files/${fileId}/delete`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            loadReportFiles(reportId);
        } else {
            alert('Error: ' + result.error);
        }
    } catch(e) {
        alert('Error de conexión');
    }
}

// ======================== FUNCIÓN DE ANÁLISIS ========================
function calcularAnalisisGeneral(riesgo, checklistItem) {
    const extractPercent = (str) => {
        let m = String(str || '').match(/(\d+)%/);
        return m ? parseInt(m[1]) : 50;
    };
    
    const probInherente = extractPercent(riesgo.probabilidad);
    const impactoInherente = extractPercent(riesgo.impacto);
    const ponderacionInherente = (probInherente * impactoInherente) / 100;
    
    const probResidual = extractPercent(riesgo.probabilidad_residual);
    const impactoResidual = extractPercent(riesgo.impacto_residual);
    const ponderacionResidual = (probResidual * impactoResidual) / 100;
    
    let factorEvidencias = 0;
    let nivelCumplimiento = 'Sin datos';
    let totalEvidencias = 0;
    let afirmativas = 0;
    
    if (checklistItem && checklistItem.total_evidencias > 0) {
        afirmativas = checklistItem.afirmativas || 0;
        totalEvidencias = checklistItem.total_evidencias;
        factorEvidencias = (afirmativas / totalEvidencias) * 100;
        if (factorEvidencias >= 80) nivelCumplimiento = 'Excelente';
        else if (factorEvidencias >= 60) nivelCumplimiento = 'Bueno';
        else if (factorEvidencias >= 40) nivelCumplimiento = 'Regular';
        else if (factorEvidencias >= 20) nivelCumplimiento = 'Bajo';
        else nivelCumplimiento = 'Crítico';
    }
    
    const irg = Math.min(100, Math.max(0, (ponderacionInherente * 0.6) + (ponderacionResidual * 0.4) - (factorEvidencias * 0.15)));
    
    let nivelGeneral = '', colorNivel = '', icono = '';
    let recomendaciones = [];
    
    const recomendacionesPorNivel = {
        critico: ["🚨 Implementar plan de contingencia inmediato.", "📊 Realizar auditoría externa especializada."],
        alto: ["⚠️ Establecer monitoreo semanal.", "📈 Capacitar al personal en protocolos."],
        moderado: ["📋 Realizar revisiones mensuales.", "🔄 Establecer rotación de personal."],
        bajo: ["✅ Mantener controles existentes.", "📚 Capacitar al personal en buenas prácticas."],
        muyBajo: ["👍 Continuar con el monitoreo estándar.", "📋 Documentar lecciones aprendidas."]
    };
    
    if (irg >= 80) { nivelGeneral = 'Crítico'; colorNivel = 'bg-red-100 text-red-800'; icono = '🔴'; recomendaciones = recomendacionesPorNivel.critico; }
    else if (irg >= 60) { nivelGeneral = 'Alto'; colorNivel = 'bg-orange-100 text-orange-800'; icono = '🟠'; recomendaciones = recomendacionesPorNivel.alto; }
    else if (irg >= 40) { nivelGeneral = 'Moderado'; colorNivel = 'bg-yellow-100 text-yellow-800'; icono = '🟡'; recomendaciones = recomendacionesPorNivel.moderado; }
    else if (irg >= 20) { nivelGeneral = 'Bajo'; colorNivel = 'bg-green-100 text-green-800'; icono = '🟢'; recomendaciones = recomendacionesPorNivel.bajo; }
    else { nivelGeneral = 'Muy Bajo'; colorNivel = 'bg-blue-100 text-blue-800'; icono = '🔵'; recomendaciones = recomendacionesPorNivel.muyBajo; }
    
    const brechaReduccion = ponderacionInherente - ponderacionResidual;
    let efectividadControles = '';
    if (brechaReduccion > 30) efectividadControles = 'Excelente';
    else if (brechaReduccion > 15) efectividadControles = 'Buena';
    else if (brechaReduccion > 5) efectividadControles = 'Moderada';
    else efectividadControles = 'Baja';
    
    return {
        irg: Math.round(irg), nivelGeneral, colorNivel, icono, 
        recomendaciones: recomendaciones,
        ponderacionInherente: Math.round(ponderacionInherente),
        ponderacionResidual: Math.round(ponderacionResidual),
        brechaReduccion: Math.round(brechaReduccion),
        efectividadControles, factorEvidencias: Math.round(factorEvidencias),
        nivelCumplimiento, afirmativas, totalEvidencias
    };
}

function agregarAnalisisGeneral(riesgo, checklistItem) {
    const a = calcularAnalisisGeneral(riesgo, checklistItem);
    return `
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl shadow-md border border-indigo-200 p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line text-indigo-600"></i>
            📊 Análisis General del Riesgo
        </h2>
        <div class="text-center mb-6">
            <div class="inline-block p-4 rounded-full ${a.colorNivel} border-2 mb-2">
                <span class="text-4xl font-bold">${a.icono} ${a.irg}%</span>
            </div>
            <p class="text-lg font-semibold">Índice de Riesgo General (IRG)</p>
            <p class="text-xs text-gray-500">Fórmula: (Inherente × 0.6) + (Residual × 0.4) - (Evidencias × 0.15)</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg p-3 text-center border">
                <p class="text-xs text-gray-500">Riesgo Inherente</p>
                <p class="text-2xl font-bold text-red-600">${a.ponderacionInherente}%</p>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1"><div class="bg-red-600 h-1.5 rounded-full" style="width: ${a.ponderacionInherente}%"></div></div>
            </div>
            <div class="bg-white rounded-lg p-3 text-center border">
                <p class="text-xs text-gray-500">Riesgo Residual</p>
                <p class="text-2xl font-bold text-green-600">${a.ponderacionResidual}%</p>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1"><div class="bg-green-600 h-1.5 rounded-full" style="width: ${a.ponderacionResidual}%"></div></div>
            </div>
            <div class="bg-white rounded-lg p-3 text-center border">
                <p class="text-xs text-gray-500">Efectividad de Controles</p>
                <p class="text-lg font-bold text-blue-600">${a.efectividadControles}</p>
                <p class="text-xs text-gray-500">Reducción: ${a.brechaReduccion}%</p>
            </div>
        </div>
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full text-sm"><thead class="bg-gray-100"><tr><th class="px-3 py-2">Componente</th><th class="px-3 py-2">Valor</th><th class="px-3 py-2">Interpretación</th></tr></thead>
                <tbody>
                    <tr class="border-b"><td class="px-3 py-2">🎯 Riesgo Inherente</td><td class="px-3 py-2 font-medium">${a.ponderacionInherente}%</td><td class="px-3 py-2 text-gray-600">Riesgo sin considerar controles</td></tr>
                    <tr class="border-b"><td class="px-3 py-2">🛡️ Riesgo Residual</td><td class="px-3 py-2 font-medium">${a.ponderacionResidual}%</td><td class="px-3 py-2 text-gray-600">Riesgo después de controles</td></tr>
                    <tr class="border-b"><td class="px-3 py-2">📋 Cumplimiento Evidencias</td><td class="px-3 py-2 font-medium">${a.factorEvidencias}% (${a.afirmativas}/${a.totalEvidencias})</td><td class="px-3 py-2 text-gray-600">Nivel: <strong>${a.nivelCumplimiento}</strong></td></tr>
                    <tr class="border-b"><td class="px-3 py-2">📉 Brecha de Control</td><td class="px-3 py-2 font-medium">${a.brechaReduccion}%</td><td class="px-3 py-2 text-gray-600">Reducción lograda por controles</td></tr>
                </tbody>
            </table>
        </div>
        <div class="rounded-lg p-4 ${a.colorNivel} border">
            <div class="flex items-start gap-3">
                <i class="fas fa-lightbulb text-xl mt-0.5"></i>
                <div>
                    <p class="font-semibold mb-1">Recomendaciones preventivas:</p>
                    <ul class="text-sm list-disc list-inside space-y-1">
                        <li>${a.recomendaciones[0]}</li>
                        <li>${a.recomendaciones[1]}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mt-4 text-xs text-gray-500 text-center border-t pt-3"><strong>Fórmula IRG:</strong> (Prob. Inherente × Impacto Inherente / 100 × 0.6) + (Prob. Residual × Impacto Residual / 100 × 0.4) - (% Evidencias × 0.15)</div>
    </div>`;
}

async function generarReporte() {
    const orgId = document.getElementById('organizacionSelector').value;
    const checklistNombre = document.getElementById('checklistSelector').value;
    
    if (!orgId) {
        alert('Debes seleccionar una organización');
        return;
    }
    
    if (!checklistNombre) {
        alert('Debes seleccionar un checklist');
        return;
    }
    
    const container = document.getElementById('reporteContainer');
    container.innerHTML = '<div class="text-center py-8 text-gray-500">Generando reporte...</div>';
    
    enableFileUpload(false);
    
    await Promise.all([loadRiesgos(), loadEvaluations(), loadAllChecklists()]);
    
    const orgRes = await fetch('/api/risk/organizations');
    const orgData = await orgRes.json();
    const organization = orgData.data?.find(o => o.id == orgId);
    
    if (!organization) {
        container.innerHTML = '<div class="text-center py-8 text-red-500">No se encontró la organización</div>';
        return;
    }
    
    const checklist = allChecklists.find(item => item.nombre_checklist === checklistNombre);
    if (!checklist) {
        container.innerHTML = '<div class="text-center py-8 text-red-500">No se encontró el checklist seleccionado</div>';
        return;
    }
    
    const riesgo = allRiesgos.find(r => r.id == checklist.riesgo_id);
    if (!riesgo) {
        container.innerHTML = '<div class="text-center py-8 text-red-500">No se encontró el riesgo asociado al checklist</div>';
        return;
    }
    
    const extractPercent = (str) => { let m = String(str || '').match(/(\d+)%/); return m ? parseInt(m[1])/100 : 0.5; };
    const calcZone = (prob, impact) => { let v = extractPercent(prob) * extractPercent(impact); if(v>=0.80) return 'Extremo'; if(v>=0.64) return 'Alto'; if(v>=0.36) return 'Moderado'; return 'Bajo'; };
    const getZoneClass = (zone) => {
        switch(zone) {
            case 'Extremo': return 'bg-red-100 text-red-800 font-semibold';
            case 'Alto': return 'bg-orange-100 text-orange-800 font-semibold';
            case 'Moderado': return 'bg-yellow-100 text-yellow-800 font-semibold';
            case 'Bajo': return 'bg-green-100 text-green-800 font-semibold';
            default: return '';
        }
    };
    
    const fechaActual = new Date().toLocaleString();
    const usuarioActual = document.querySelector('meta[name="user-name"]')?.content || 'Usuario';
    
    let etapa1Html = `
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="bg-gray-800 text-white px-6 py-3 flex justify-between items-center">
            <h2 class="text-lg font-semibold">Etapa 1: Organización</h2>
            <div class="text-xs opacity-80"><i class="far fa-calendar-alt mr-1"></i> ${fechaActual} | <i class="far fa-user mr-1"></i> ${usuarioActual}</div>
        </div>
        <div class="p-6"><div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>Nombre:</strong> ${escapeHtml(organization.nombre)}</div>
            <div><strong>RUC / CI:</strong> ${escapeHtml(organization.ruc || 'No registrado')}</div>
            <div><strong>Sector:</strong> ${escapeHtml(organization.sector || 'No especificado')}</div>
            <div><strong>Teléfono:</strong> ${escapeHtml(organization.telefono || 'No registrado')}</div>
            <div class="md:col-span-2"><strong>Dirección:</strong> ${escapeHtml(organization.direccion || 'No registrada')}</div>
            <div class="md:col-span-2"><strong>Proceso clave:</strong> ${escapeHtml(organization.proceso_nombre)}</div>
            <div class="md:col-span-2"><strong>Justificación:</strong> ${escapeHtml(organization.proceso_justificacion || 'Sin justificación')}</div>
        </div></div>
    </div>`;
    
    const porcentaje = Math.round(checklist.afirmativas / checklist.total_evidencias * 100);
    const fechaChecklist = new Date(checklist.updated_at).toLocaleString();
    
    let checklistInfo = `
    <div class="bg-blue-50 rounded-xl shadow-md border border-blue-200 p-4 mb-6">
        <h2 class="text-lg font-semibold text-blue-800 mb-3">📋 Información del Checklist</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div><strong>Nombre:</strong> ${escapeHtml(checklist.nombre_checklist)}</div>
            <div><strong>Progreso:</strong> <span class="text-lg font-bold">${porcentaje}%</span><div class="w-full h-2 bg-gray-200 rounded-full mt-1"><div class="h-full rounded-full bg-green-500" style="width: ${porcentaje}%"></div></div></div>
            <div><strong>Respuestas afirmativas:</strong> ${checklist.afirmativas} de ${checklist.total_evidencias}</div>
            <div><strong>Última actualización:</strong> ${fechaChecklist}</div>
        </div>
        <div class="mt-3 pt-3 border-t border-blue-200 flex justify-end">
            <a href="/risk/checklist?riesgo_id=${riesgo.id}&nombre=${encodeURIComponent(checklist.nombre_checklist)}&org_id=${organization.id}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-sm">
                <i class="fas fa-check-double"></i> Ver checklist completo
            </a>
        </div>
    </div>`;
    
    const analisisGeneralHtml = agregarAnalisisGeneral(riesgo, checklist);
    
    let etapa2Html = `
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="bg-gray-800 text-white px-6 py-3"><h2 class="text-lg font-semibold">Etapa 2: Identificación de riesgos</h2></div>
        <div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-gray-100"><tr><th class="px-3 py-2">Proceso</th><th class="px-3 py-2">Descripción</th><th class="px-3 py-2">Clasificación</th></tr></thead><tbody><tr><td class="px-3 py-2">${escapeHtml(riesgo.proceso)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2">${escapeHtml(riesgo.descripcion)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2">${escapeHtml(riesgo.clasificacion)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td></tbody></table>
    </div>
    </div>`;
    
    let etapa3Html = `
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="bg-gray-800 text-white px-6 py-3"><h2 class="text-lg font-semibold">Etapa 3: Causas y consecuencias</h2></div>
        <div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-gray-100"><tr><th class="px-3 py-2">Proceso</th><th class="px-3 py-2">Causa Raíz</th><th class="px-3 py-2">Consecuencias</th></tr></thead><tbody><tr><td class="px-3 py-2">${escapeHtml(riesgo.proceso)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2">${escapeHtml(riesgo.causa_raiz)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2">${escapeHtml(riesgo.consecuencias)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td></tbody></table>
    </div>
    </div>`;
    
    let etapa4Html = `
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="bg-gray-800 text-white px-6 py-3"><h2 class="text-lg font-semibold">Etapa 4: Evaluación inherente</h2></div>
        <div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-gray-100"><tr><th class="px-3 py-2">Proceso</th><th class="px-3 py-2">Probabilidad</th><th class="px-3 py-2">Impacto</th><th class="px-3 py-2">Zona</th></tr></thead><tbody><tr><td class="px-3 py-2">${escapeHtml(riesgo.proceso)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2">${riesgo.probabilidad}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2">${riesgo.impacto}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2 text-center ${getZoneClass(calcZone(riesgo.probabilidad, riesgo.impacto))}">${calcZone(riesgo.probabilidad, riesgo.impacto)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td></tbody></table>
    </div>
    </div>`;
    
    let etapa5Html = `
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="bg-gray-800 text-white px-6 py-3"><h2 class="text-lg font-semibold">Etapa 5: Controles</h2></div>
        <div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-gray-100"><tr><th class="px-3 py-2">Proceso</th><th class="px-3 py-2">Controles</th></tr></thead><tbody><tr><td class="px-3 py-2">${escapeHtml(riesgo.proceso)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2">${escapeHtml(riesgo.controles)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td></tbody></table>
    </div>
    </div>`;
    
    let etapa6Html = `
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="bg-gray-800 text-white px-6 py-3"><h2 class="text-lg font-semibold">Etapa 6: Riesgo residual</h2></div>
        <div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-gray-100"><tr><th class="px-3 py-2">Proceso</th><th class="px-3 py-2">Prob. Residual</th><th class="px-3 py-2">Impacto Residual</th><th class="px-3 py-2">Zona</th></tr></thead><tbody><tr><td class="px-3 py-2">${escapeHtml(riesgo.proceso)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2">${riesgo.probabilidad_residual}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2">${riesgo.impacto_residual}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2 text-center ${getZoneClass(calcZone(riesgo.probabilidad_residual, riesgo.impacto_residual))}">${calcZone(riesgo.probabilidad_residual, riesgo.impacto_residual)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td></tbody></table>
    </div>
    </div>`;
    
    let etapa7Html = `<div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden"><div class="bg-gray-800 text-white px-6 py-3"><h2 class="text-lg font-semibold">Etapa 7: Evidencias</h2></div><div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-gray-100"><tr><th class="px-3 py-2">Descripción</th><th class="px-3 py-2">Evidencia</th><th class="px-3 py-2">Tipo</th><th class="px-3 py-2">Frecuencia</th><th class="px-3 py-2">Documentado</th><th class="px-3 py-2">Valoración</th></tr></thead><tbody>${allEvaluations.map(e => `<tr><td class="px-3 py-2">${escapeHtml(e.descripcion)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2">${escapeHtml(e.evidencia)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2">${escapeHtml(e.tipo)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2">${escapeHtml(e.frecuencia)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2">${escapeHtml(e.documentado)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2 text-center">${e.valoracion}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td>`).join('')}</tbody></table></div></div>`;
    
    let etapa8Html = `
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="bg-gray-800 text-white px-6 py-3"><h2 class="text-lg font-semibold">Etapa 8: Matriz final</h2></div>
        <div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-gray-100"><tr><th class="px-3 py-2">Proceso</th><th class="px-3 py-2">Zona Inherente</th><th class="px-3 py-2">Zona Residual</th></tr></thead><tbody><tr><td class="px-3 py-2">${escapeHtml(riesgo.proceso)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2 text-center ${getZoneClass(calcZone(riesgo.probabilidad, riesgo.impacto))}">${calcZone(riesgo.probabilidad, riesgo.impacto)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td><td class="px-3 py-2 text-center ${getZoneClass(calcZone(riesgo.probabilidad_residual, riesgo.impacto_residual))}">${calcZone(riesgo.probabilidad_residual, riesgo.impacto_residual)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td></tbody></table>
    </div>
    </div>`;
    
    const headerInfo = `
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl shadow-md border border-indigo-200 p-4 mb-6">
        <div class="flex justify-between items-center">
            <div><h2 class="text-xl font-bold text-gray-800">Reporte de Riesgo - ${escapeHtml(organization.nombre)}</h2><p class="text-sm text-gray-600 mt-1">Generado el ${fechaActual} por ${usuarioActual}</p></div>
            <div class="text-right"><p class="text-sm font-semibold text-gray-700">Checklist: ${escapeHtml(checklist.nombre_checklist)}</p><p class="text-sm text-gray-600">Riesgo: ${escapeHtml(riesgo.proceso)}</p></div>
        </div>
    </div>`;
    
    // Construir el HTML completo del reporte
    const reporteHtml = analisisGeneralHtml + headerInfo + etapa1Html + checklistInfo + etapa2Html + etapa3Html + etapa4Html + etapa5Html + etapa6Html + etapa7Html + etapa8Html;
    
    container.innerHTML = reporteHtml;
}

// Función para guardar el reporte y mostrar el QR
async function saveCurrentReport() {
    const orgSelect = document.getElementById('organizacionSelector');
    const checklistSelect = document.getElementById('checklistSelector');
    
    if (!orgSelect.value) {
        alert('Primero genera un reporte seleccionando una organización');
        return;
    }
    
    if (!checklistSelect.value) {
        alert('Primero genera un reporte seleccionando un checklist');
        return;
    }
    
    const orgNombre = orgSelect.options[orgSelect.selectedIndex]?.text || 'Sin organización';
    const checklistNombre = checklistSelect.options[checklistSelect.selectedIndex]?.text || 'Sin checklist';
    const fechaStr = new Date().toISOString().slice(0, 19).replace('T', ' ');
    const nombre = `${orgNombre} - ${checklistNombre.substring(0, 50)} - ${fechaStr}`;
    
    const contenido = document.getElementById('reporteContainer').innerHTML;
    if (!contenido || contenido.includes('Generando reporte...') || contenido.includes('Selecciona')) {
        alert('Primero genera un reporte válido');
        return;
    }
    
    const formData = new URLSearchParams();
    formData.append('csrf_token', csrfToken);
    formData.append('nombre', nombre);
    formData.append('contenido', contenido);
    
    try {
        const response = await fetch('/api/risk/reports', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            currentReportId = result.id;
            alert(`Reporte "${nombre}" guardado correctamente`);
            enableFileUpload(true);
            
            const filesContainer = document.getElementById('reportFilesContainer');
            if (filesContainer) {
                filesContainer.innerHTML = '<div class="text-center py-4 text-gray-500">📎 No hay archivos adjuntos. Usa el botón "Adjuntar archivo" para agregar.</div>';
            }
            
            // Cargar el QR en el reporte actual
            await cargarQRReporte(currentReportId);
        } else {
            alert('Error: ' + (result.error || 'No se pudo guardar'));
        }
    } catch (err) {
        alert('Error de conexión: ' + err.message);
    }
}

// Función para cargar el QR y agregarlo al reporte
async function cargarQRReporte(reportId) {
    try {
        const response = await fetch(`/api/risk/reports/${reportId}`);
        const result = await response.json();
        if (result.success && result.data && result.data.qr_code) {
            const container = document.getElementById('reporteContainer');
            const contenidoActual = container.innerHTML;
            const hasQR = contenidoActual.includes('qr-container');
            
            if (!hasQR) {
                const qrHtml = `
                <div class="qr-container">
                    <h2><i class="fas fa-qrcode text-indigo-600"></i> Código QR de Autenticidad</h2>
                    <img src="${result.data.qr_code}" alt="Código QR del reporte">
                    <p>🔒 Escanea este código para verificar la autenticidad del reporte</p>
                    <div class="qr-badge">QR ORIGINAL RISK · OPTIMIX RISK</div>
                </div>`;
                container.innerHTML = qrHtml + contenidoActual;
            }
        }
    } catch(e) {
        console.error('Error cargando QR:', e);
    }
}

async function updateChecklistsByOrganization(orgId) {
    const checklistSelector = document.getElementById('checklistSelector');
    
    if (!orgId) {
        checklistSelector.innerHTML = '<option value="">-- Primero selecciona una organización --</option>';
        checklistSelector.disabled = true;
        return;
    }
    
    if (allChecklists.length === 0) {
        await loadAllChecklists();
    }
    
    const orgRes = await fetch('/api/risk/organizations');
    const orgData = await orgRes.json();
    const organization = orgData.data?.find(o => o.id == orgId);
    
    if (!organization) {
        checklistSelector.innerHTML = '<option value="">-- Organización no encontrada --</option>';
        checklistSelector.disabled = true;
        return;
    }
    
    currentOrganization = organization;
    
    const checklistsFiltrados = allChecklists.filter(item => 
        item.nombre_checklist && item.nombre_checklist.toLowerCase().includes(organization.nombre.toLowerCase())
    );
    
    if (checklistsFiltrados.length === 0) {
        checklistSelector.innerHTML = `<option value="">-- No hay checklists para ${escapeHtml(organization.nombre)} --</option>`;
        checklistSelector.disabled = true;
        
        const container = document.getElementById('reporteContainer');
        container.innerHTML = `
            <div class="bg-yellow-50 rounded-xl shadow-md border border-yellow-200 p-8 text-center">
                <i class="fas fa-exclamation-triangle text-4xl text-yellow-600 mb-3"></i>
                <h3 class="text-xl font-semibold text-yellow-800 mb-2">No hay checklists para esta organización</h3>
                <p class="text-gray-700">La organización <strong>"${escapeHtml(organization.nombre)}"</strong> no tiene checklists asociados.</p>
                <div class="mt-4">
                    <a href="/risk/checklist" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                        <i class="fas fa-plus"></i> Crear checklist para esta organización
                    </a>
                </div>
            </div>
        `;
        return;
    }
    
    checklistSelector.innerHTML = '<option value="">-- Seleccionar checklist --</option>';
    checklistsFiltrados.forEach(checklist => {
        const riesgo = allRiesgos.find(r => r.id == checklist.riesgo_id);
        const riesgoNombre = riesgo ? riesgo.proceso : 'Riesgo desconocido';
        const porcentaje = Math.round(checklist.afirmativas / checklist.total_evidencias * 100);
        const fecha = new Date(checklist.updated_at).toLocaleDateString();
        const option = document.createElement('option');
        option.value = checklist.nombre_checklist;
        option.textContent = `${checklist.nombre_checklist} - ${riesgoNombre} (${porcentaje}% - ${fecha})`;
        checklistSelector.appendChild(option);
    });
    checklistSelector.disabled = false;
}

// Event listeners
document.getElementById('organizacionSelector').addEventListener('change', (e) => {
    updateChecklistsByOrganization(e.target.value);
});
document.getElementById('btnGenerarReporte').addEventListener('click', generarReporte);
document.getElementById('btnGuardarReporte').addEventListener('click', saveCurrentReport);
document.getElementById('btnAdjuntarArchivo').addEventListener('click', () => uploadReportFile());

setTimeout(() => {
    setTypingMessage(assistantMessages.initial);
}, 500);

// Inicializar
loadOrganizaciones();
loadAllChecklists();

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}
</script>
