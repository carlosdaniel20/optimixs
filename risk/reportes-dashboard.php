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
                        <button onclick="showAssistantTip('que-es')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📊 ¿Qué es este dashboard?</button>
                        <button onclick="showAssistantTip('como-usar')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📋 Cómo usar los filtros</button>
                        <button onclick="showAssistantTip('reportes')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📁 Gestión de reportes</button>
                        <button onclick="resetAssistantMessage()" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">⟳ Reiniciar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📋 Dashboard de Reportes Finales</h1>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Filtrar por Organización</label>
                <select id="filterOrganizacion" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Todas las organizaciones --</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Seleccionar Reporte</label>
                <select id="filterReporte" class="w-full border border-gray-300 rounded-lg px-3 py-2" disabled>
                    <option value="">-- Primero selecciona una organización --</option>
                </select>
            </div>
        </div>
        <div class="mt-3 text-right">
            <button id="btnLimpiarFiltros" class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fas fa-eraser"></i> Limpiar filtros
            </button>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="rounded-xl shadow-md p-4 text-white" style="background-color: #1E3A8A;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Reportes</p>
                    <p class="text-2xl font-bold" id="totalReportes">0</p>
                </div>
                <i class="fas fa-file-alt text-3xl opacity-50"></i>
            </div>
        </div>
        <div class="rounded-xl shadow-md p-4 text-white" style="background-color: #2563EB;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Organizaciones</p>
                    <p class="text-2xl font-bold" id="totalOrganizaciones">0</p>
                </div>
                <i class="fas fa-building text-3xl opacity-50"></i>
            </div>
        </div>
        <div class="rounded-xl shadow-md p-4 text-white" style="background-color: #4F46E5;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Checklists</p>
                    <p class="text-2xl font-bold" id="totalChecklists">0</p>
                </div>
                <i class="fas fa-check-double text-3xl opacity-50"></i>
            </div>
        </div>
        <div class="rounded-xl shadow-md p-4 text-white" style="background-color: #0891B2;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Usuarios</p>
                    <p class="text-2xl font-bold" id="totalUsuarios">0</p>
                </div>
                <i class="fas fa-users text-3xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Tabla de reportes -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre del reporte</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organización</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Checklist</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario creador</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de creación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody id="reportesTableBody" class="bg-white divide-y divide-gray-200">
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Cargando reportes...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="flex justify-center items-center gap-4 mt-6">
        <button id="btnPrevPage" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition">
            <i class="fas fa-chevron-left mr-1"></i> Anterior
        </button>
        <span id="pageInfo" class="text-sm text-gray-600">Página 1</span>
        <button id="btnNextPage" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition">
            Siguiente <i class="fas fa-chevron-right ml-1"></i>
        </button>
    </div>

    <!-- Gráfico circular por Organización -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mt-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-chart-pie text-indigo-500 mr-2"></i>
                    Distribución de Reportes por Organización
                </h2>
                <p class="text-gray-500 text-sm">Cantidad de reportes generados por cada organización en el sistema</p>
                <div class="mt-4 flex flex-wrap gap-2 justify-center md:justify-start" id="chartLegend"></div>
            </div>
            <div class="flex-1 max-w-xs mx-auto">
                <canvas id="reportesChart" width="400" height="400" class="w-full h-auto"></canvas>
            </div>
        </div>
    </div>

    <!-- Contenedor del reporte cargado (se muestra cuando se carga un reporte) -->
    <div id="reporteContainer" class="space-y-6 mt-8"></div>
</div>

<style>
    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0; }
    }
    .animate-pulse {
        animation: blink 1s step-end infinite;
    }
    
    /* Estilos para el QR */
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
    
    /* Estilos para archivos adjuntos */
    .attachments-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }
    .attachments-container h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .attachment-item {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.2s ease;
    }
    .attachment-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-color: #cbd5e1;
    }
    .file-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f1f5f9;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
// === ANIMACIÓN DE ESCRITURA ===
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
    initial: "Bienvenido al Dashboard de Reportes Finales. Este módulo centraliza todos los reportes de riesgo generados en el sistema. Puede filtrar por organización, ver reportes específicos, y visualizar la distribución mediante el gráfico circular interactivo.",
    'que-es': "El Dashboard de Reportes Finales es un repositorio centralizado de todos los informes de evaluación de riesgos.",
    'como-usar': "Para usar el dashboard: 1) Seleccione una organización del filtro, 2) Elija un reporte específico, 3) Use la tabla para ver detalles.",
    reportes: "Los reportes pueden ser visualizados haciendo clic en el ojo (👁️) o eliminados con el botón de basura (🗑️)."
};

window.showAssistantTip = function(tipo) {
    let message = assistantMessages[tipo] || assistantMessages.initial;
    setTypingMessage(message);
};

window.resetAssistantMessage = function() {
    setTypingMessage(assistantMessages.initial);
};

// === CÓDIGO DEL DASHBOARD ===
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
let allReports = [];
let allOrganizations = [];
let allChecklistsData = [];
let chartInstance = null;
let currentPage = 1;
let itemsPerPage = 10;
let filteredReports = [];

// ======================== CARGAR DATOS INICIALES ========================
async function loadOrganizations() {
    try {
        const res = await fetch('/api/risk/organizations');
        const data = await res.json();
        if (data.success) {
            allOrganizations = data.data;
        }
    } catch(e) {
        console.error('Error cargando organizaciones:', e);
    }
}

async function loadChecklists() {
    try {
        const res = await fetch('/api/risk/checklist/dashboard');
        const data = await res.json();
        if (data.success && data.data) {
            allChecklistsData = data.data;
        }
    } catch(e) {
        console.error('Error cargando checklists:', e);
    }
}

async function loadReports() {
    try {
        const res = await fetch('/api/risk/reports');
        const result = await res.json();
        
        if (result.success && result.data) {
            // Filtrar excluyendo análisis IA
            allReports = result.data.filter(report => {
                if (report.nombre && report.nombre.includes('Análisis IA')) {
                    return false;
                }
                return true;
            });
            
            // Enriquecer reportes con datos de organización y checklist
            await enrichReportsWithData();
            
            // Actualizar selectores y UI
            updateOrganizacionSelector();
            updateSummaryCards();
            applyFilters();
        } else {
            document.getElementById('reportesTableBody').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No se pudieron cargar los reportes</td></tr>';
        }
    } catch(e) {
        console.error('Error cargando reportes:', e);
        document.getElementById('reportesTableBody').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error de conexión</td></tr>';
    }
}

// Enriquecer reportes con datos reales de organización y checklist
async function enrichReportsWithData() {
    for (const report of allReports) {
        // Extraer nombre de organización del nombre del reporte
        const orgNombre = extractOrganizacionFromNombre(report.nombre);
        const foundOrg = allOrganizations.find(o => o.nombre === orgNombre);
        report.organizacion_nombre = orgNombre;
        report.organizacion_id = foundOrg?.id || null;
        
        // Extraer nombre del checklist del nombre del reporte
        const checklistNombre = extractChecklistFromNombre(report.nombre);
        const foundChecklist = allChecklistsData.find(c => c.nombre_checklist === checklistNombre);
        report.checklist_nombre = checklistNombre;
        report.riesgo_id = foundChecklist?.riesgo_id || null;
        
        // Extraer fecha del nombre si existe
        report.fecha_extraida = extractFechaFromNombre(report.nombre);
    }
}

function extractOrganizacionFromNombre(nombreReporte) {
    if (!nombreReporte) return 'Sin organización';
    const partes = nombreReporte.split(' - ');
    if (partes.length >= 1 && partes[0].length > 0 && partes[0] !== 'Sin checklist') {
        return partes[0].trim();
    }
    return 'Sin organización';
}

function extractChecklistFromNombre(nombreReporte) {
    if (!nombreReporte) return 'Sin checklist';
    const partes = nombreReporte.split(' - ');
    if (partes.length >= 2) {
        return partes[1].trim();
    }
    return 'Sin checklist';
}

function extractFechaFromNombre(nombreReporte) {
    if (!nombreReporte) return null;
    const partes = nombreReporte.split(' - ');
    if (partes.length >= 3) {
        const posibleFecha = partes[partes.length - 1];
        if (posibleFecha.match(/\d{4}-\d{2}-\d{2}/)) {
            return posibleFecha;
        }
    }
    return null;
}

// Actualizar tarjetas de resumen
function updateSummaryCards() {
    const organizacionesUnicas = new Set();
    const checklistsUnicas = new Set();
    const usuariosUnicos = new Set();
    
    for (const report of allReports) {
        if (report.organizacion_nombre && report.organizacion_nombre !== 'Sin organización') {
            organizacionesUnicas.add(report.organizacion_nombre);
        }
        if (report.checklist_nombre && report.checklist_nombre !== 'Sin checklist') {
            checklistsUnicas.add(report.checklist_nombre);
        }
        if (report.usuario_nombre) {
            usuariosUnicos.add(report.usuario_nombre);
        } else if (report.usuario_id) {
            usuariosUnicos.add(`Usuario #${report.usuario_id}`);
        }
    }
    
    document.getElementById('totalReportes').textContent = allReports.length;
    document.getElementById('totalOrganizaciones').textContent = organizacionesUnicas.size;
    document.getElementById('totalChecklists').textContent = checklistsUnicas.size;
    document.getElementById('totalUsuarios').textContent = usuariosUnicos.size;
}

// Actualizar selector de organizaciones
function updateOrganizacionSelector() {
    const organizaciones = [...new Set(allReports.map(r => r.organizacion_nombre).filter(n => n && n !== 'Sin organización'))].sort();
    const select = document.getElementById('filterOrganizacion');
    select.innerHTML = '<option value="">-- Todas las organizaciones --</option>';
    organizaciones.forEach(org => {
        const count = allReports.filter(r => r.organizacion_nombre === org).length;
        const opt = document.createElement('option');
        opt.value = org;
        opt.textContent = `${org} (${count} reportes)`;
        select.appendChild(opt);
    });
}

// Cargar reportes por organización
function cargarReportesPorOrganizacion(organizacion) {
    const reportesSelect = document.getElementById('filterReporte');
    
    if (!organizacion) {
        reportesSelect.innerHTML = '<option value="">-- Primero selecciona una organización --</option>';
        reportesSelect.disabled = true;
        filteredReports = [...allReports];
    } else {
        const reportes = allReports.filter(r => r.organizacion_nombre === organizacion);
        
        if (reportes.length === 0) {
            reportesSelect.innerHTML = '<option value="">-- No hay reportes para esta organización --</option>';
            reportesSelect.disabled = true;
            filteredReports = [];
        } else {
            reportesSelect.innerHTML = '<option value="">-- Todos los reportes de esta organización --</option>';
            reportes.forEach(report => {
                const opt = document.createElement('option');
                opt.value = report.id;
                const fecha = report.fecha_extraida ? report.fecha_extraida : new Date(report.created_at).toLocaleDateString();
                opt.textContent = `${report.checklist_nombre} - ${fecha}`;
                reportesSelect.appendChild(opt);
            });
            reportesSelect.disabled = false;
            filteredReports = [...reportes];
        }
    }
    
    currentPage = 1;
    renderTable();
    updateChart();
}

// Filtrar por reporte específico
function filtrarPorReporteEspecifico(reporteId) {
    if (!reporteId) {
        const orgSeleccionada = document.getElementById('filterOrganizacion').value;
        if (orgSeleccionada) {
            filteredReports = allReports.filter(r => r.organizacion_nombre === orgSeleccionada);
        } else {
            filteredReports = [...allReports];
        }
    } else {
        filteredReports = allReports.filter(r => r.id == reporteId);
    }
    
    currentPage = 1;
    renderTable();
    updateChart();
}

// Aplicar filtros
function applyFilters() {
    const orgFiltro = document.getElementById('filterOrganizacion').value;
    const reporteFiltro = document.getElementById('filterReporte').value;
    
    if (orgFiltro && reporteFiltro) {
        filteredReports = allReports.filter(r => r.id == reporteFiltro);
    } else if (orgFiltro) {
        filteredReports = allReports.filter(r => r.organizacion_nombre === orgFiltro);
    } else {
        filteredReports = [...allReports];
    }
    
    currentPage = 1;
    renderTable();
    updateChart();
}

// Limpiar filtros
function limpiarFiltros() {
    document.getElementById('filterOrganizacion').value = '';
    document.getElementById('filterReporte').innerHTML = '<option value="">-- Primero selecciona una organización --</option>';
    document.getElementById('filterReporte').disabled = true;
    filteredReports = [...allReports];
    currentPage = 1;
    renderTable();
    updateChart();
}

// Renderizar tabla
function renderTable() {
    const totalPages = Math.ceil(filteredReports.length / itemsPerPage);
    const start = (currentPage - 1) * itemsPerPage;
    const paginatedItems = filteredReports.slice(start, start + itemsPerPage);
    
    const btnPrev = document.getElementById('btnPrevPage');
    const btnNext = document.getElementById('btnNextPage');
    const pageInfo = document.getElementById('pageInfo');
    
    btnPrev.disabled = currentPage <= 1;
    btnNext.disabled = currentPage >= totalPages || totalPages === 0;
    pageInfo.textContent = `Página ${currentPage} de ${totalPages || 1}`;

    const tbody = document.getElementById('reportesTableBody');
    if (!paginatedItems.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay reportes que coincidan con los filtros</td></tr>';
        return;
    }

    let html = '';
    for (const report of paginatedItems) {
        const fecha = report.fecha_extraida ? new Date(report.fecha_extraida).toLocaleString() : new Date(report.created_at).toLocaleString();
        const nombre = escapeHtml(report.nombre);
        const organizacion = escapeHtml(report.organizacion_nombre || 'Sin organización');
        const checklistNombre = escapeHtml(report.checklist_nombre || 'Sin checklist');
        const usuario = report.usuario_nombre ? escapeHtml(report.usuario_nombre) : (report.usuario_id ? `Usuario #${report.usuario_id}` : 'Sistema');
        
        html += `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-sm font-medium text-gray-900 max-w-xs truncate" title="${nombre}">${nombre}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <span class="inline-flex items-center gap-1"><i class="fas fa-building text-gray-400"></i> ${organizacion}</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 max-w-md truncate" title="${checklistNombre}">
                    <span class="inline-flex items-center gap-1"><i class="fas fa-check-double text-blue-400"></i> ${checklistNombre}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <i class="fas fa-user-circle text-gray-400 mr-1"></i> ${usuario}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${fecha}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex gap-2">
                        <button onclick="verReporte(${report.id})" class="text-indigo-600 hover:text-indigo-800" title="Ver detalle">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="deleteReport(${report.id})" class="text-red-600 hover:text-red-800" title="Eliminar reporte">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }
    tbody.innerHTML = html;
}

// ======================== FUNCIÓN PARA CARGAR REPORTE CON QR Y ARCHIVOS ADJUNTOS ========================
async function cargarReporteDesdeDashboard(reportId) {
    try {
        const response = await fetch(`/api/risk/reports/${reportId}`);
        const result = await response.json();
        
        if (result.success && result.data) {
            // Buscar el contenedor del reporte
            let reporteContainer = document.getElementById('reporteContainer');
            
            // Si no existe, crearlo
            if (!reporteContainer) {
                const mainContainer = document.querySelector('.container');
                const newSection = document.createElement('div');
                newSection.id = 'reporteContainer';
                newSection.className = 'space-y-6 mt-8';
                mainContainer.appendChild(newSection);
                reporteContainer = newSection;
            }
            
            let contenido = result.data.contenido || '';
            const hasQR = contenido.includes('Código QR de Autenticidad') || contenido.includes('qr-container');
            
            // Si el reporte tiene QR en la BD y no está en el contenido, lo añadimos
            let qrHtml = '';
            if (result.data.qr_code && !hasQR) {
                qrHtml = `
                <div class="qr-container">
                    <h2><i class="fas fa-qrcode text-indigo-600"></i> Código QR de Autenticidad</h2>
                    <img src="${result.data.qr_code}" alt="Código QR del reporte">
                    <p>🔒 Escanea este código para verificar la autenticidad del reporte</p>
                    <div class="qr-badge">QR ORIGINAL RISK · OPTIMIX RISK</div>
                </div>`;
            }
            
            // === GENERAR HTML PARA ARCHIVOS ADJUNTOS ===
            let adjuntosHtml = '';
            
            // Verificar diferentes nombres posibles para los adjuntos
            const adjuntos = result.data.adjuntos || result.data.archivos_adjuntos || result.data.attachments || [];
            
            if (adjuntos && adjuntos.length > 0) {
                adjuntosHtml = `
                <div class="attachments-container">
                    <h3><i class="fas fa-paperclip text-indigo-500"></i> Archivos Adjuntos (${adjuntos.length})</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        ${adjuntos.map(adjunto => `
                            <div class="attachment-item">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <div class="file-icon">
                                            ${getFileIcon(adjunto.nombre || adjunto.archivo || adjunto.file_name)}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate" title="${adjunto.nombre_original || adjunto.original_name || adjunto.nombre || 'Archivo adjunto'}">
                                                ${adjunto.nombre_original || adjunto.original_name || adjunto.nombre || 'Archivo adjunto'}
                                            </p>
                                            <p class="text-xs text-gray-500">${formatFileSize(adjunto.tamaño || adjunto.size || adjunto.tamano)}</p>
                                        </div>
                                    </div>
                                    <a href="${adjunto.url || adjunto.ruta || adjunto.path}" 
                                       target="_blank" 
                                       class="text-indigo-600 hover:text-indigo-800 ml-2"
                                       title="Descargar archivo">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>`;
            }
            
            // También buscar enlaces de descarga dentro del contenido HTML
            let downloadLinksHtml = '';
            const downloadLinks = extractDownloadLinks(contenido);
            if (downloadLinks.length > 0 && (!adjuntos || adjuntos.length === 0)) {
                downloadLinksHtml = `
                <div class="attachments-container">
                    <h3><i class="fas fa-file-download text-green-500"></i> Enlaces de Descarga</h3>
                    <div class="space-y-2">
                        ${downloadLinks.map(link => `
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-700 truncate">${link.texto || 'Archivo'}</span>
                                <a href="${link.url}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                    <i class="fas fa-external-link-alt"></i> Abrir
                                </a>
                            </div>
                        `).join('')}
                    </div>
                </div>`;
            }
            
            // Mostrar el contenido completo del reporte
            reporteContainer.innerHTML = qrHtml + contenido + adjuntosHtml + downloadLinksHtml;
            
            // Guardar el ID actual
            window.currentReportId = result.data.id;
            
            // Desplazar hacia el reporte
            reporteContainer.scrollIntoView({ behavior: 'smooth' });
            
            // Mostrar mensaje de éxito con información de archivos
            const totalArchivos = adjuntos ? adjuntos.length : 0;
            if (totalArchivos > 0) {
                mostrarNotificacion(`✅ Reporte cargado correctamente. Incluye ${totalArchivos} archivo(s) adjunto(s).`, 'success');
            } else if (result.data.qr_code) {
                mostrarNotificacion('✅ Reporte cargado correctamente. Incluye código QR de autenticidad.', 'success');
            } else {
                mostrarNotificacion('✅ Reporte cargado correctamente.', 'success');
            }
        } else {
            mostrarNotificacion('Error al cargar el reporte: ' + (result.error || 'Datos no encontrados'), 'error');
        }
    } catch(e) { 
        console.error('Error cargando reporte:', e);
        mostrarNotificacion('Error al cargar el reporte: ' + e.message, 'error');
    }
}

// Función auxiliar para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo = 'info') {
    // Crear elemento de notificación
    const notificacion = document.createElement('div');
    notificacion.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all transform translate-x-0 ${
        tipo === 'success' ? 'bg-green-500' : tipo === 'error' ? 'bg-red-500' : 'bg-blue-500'
    } text-white`;
    notificacion.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="fas ${tipo === 'success' ? 'fa-check-circle' : tipo === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <span>${mensaje}</span>
        </div>
    `;
    document.body.appendChild(notificacion);
    
    // Auto-eliminar después de 3 segundos
    setTimeout(() => {
        notificacion.style.transform = 'translateX(100%)';
        setTimeout(() => notificacion.remove(), 300);
    }, 3000);
}

// Función auxiliar para obtener el icono según el tipo de archivo
function getFileIcon(filename) {
    if (!filename) return '<i class="fas fa-file text-gray-400 text-xl"></i>';
    
    const ext = filename.split('.').pop().toLowerCase();
    const icons = {
        'pdf': '<i class="fas fa-file-pdf text-red-500 text-xl"></i>',
        'doc': '<i class="fas fa-file-word text-blue-500 text-xl"></i>',
        'docx': '<i class="fas fa-file-word text-blue-500 text-xl"></i>',
        'xls': '<i class="fas fa-file-excel text-green-500 text-xl"></i>',
        'xlsx': '<i class="fas fa-file-excel text-green-500 text-xl"></i>',
        'jpg': '<i class="fas fa-file-image text-purple-500 text-xl"></i>',
        'jpeg': '<i class="fas fa-file-image text-purple-500 text-xl"></i>',
        'png': '<i class="fas fa-file-image text-purple-500 text-xl"></i>',
        'gif': '<i class="fas fa-file-image text-purple-500 text-xl"></i>',
        'zip': '<i class="fas fa-file-archive text-yellow-500 text-xl"></i>',
        'rar': '<i class="fas fa-file-archive text-yellow-500 text-xl"></i>',
        '7z': '<i class="fas fa-file-archive text-yellow-500 text-xl"></i>',
        'txt': '<i class="fas fa-file-alt text-gray-500 text-xl"></i>',
        'md': '<i class="fas fa-file-alt text-gray-500 text-xl"></i>',
        'mp3': '<i class="fas fa-file-audio text-purple-500 text-xl"></i>',
        'mp4': '<i class="fas fa-file-video text-purple-500 text-xl"></i>',
        'ppt': '<i class="fas fa-file-powerpoint text-orange-500 text-xl"></i>',
        'pptx': '<i class="fas fa-file-powerpoint text-orange-500 text-xl"></i>'
    };
    
    return icons[ext] || '<i class="fas fa-file text-gray-400 text-xl"></i>';
}

// Función auxiliar para formatear el tamaño del archivo
function formatFileSize(bytes) {
    if (!bytes) return 'Tamaño desconocido';
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    if (bytes < 1024 * 1024 * 1024) return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    return (bytes / (1024 * 1024 * 1024)).toFixed(2) + ' GB';
}

// Función para extraer enlaces de descarga del contenido HTML
function extractDownloadLinks(html) {
    const links = [];
    if (!html) return links;
    
    const regex = /<a[^>]+href=["']([^"']+)["'][^>]*>([^<]*)<\/a>/gi;
    let match;
    while ((match = regex.exec(html)) !== null) {
        const url = match[1];
        const texto = match[2].trim();
        if (url && (url.includes('/download/') || url.includes('/uploads/') || url.includes('/storage/') || url.match(/\.(pdf|doc|docx|xls|xlsx|jpg|png|zip|rar|mp3|mp4|ppt|pptx)$/i))) {
            links.push({ url, texto });
        }
    }
    
    return links;
}

// Función para ver el reporte - CARGA EN LA MISMA PÁGINA CON QR Y ARCHIVOS
function verReporte(reportId) {
    cargarReporteDesdeDashboard(reportId);
}

// Actualizar gráfico circular
function updateChart() {
    if (chartInstance) chartInstance.destroy();

    const countsByOrganizacion = {};
    for (const report of filteredReports) {
        const org = report.organizacion_nombre;
        if (org && org !== 'Sin organización') {
            countsByOrganizacion[org] = (countsByOrganizacion[org] || 0) + 1;
        }
    }

    const labels = Object.keys(countsByOrganizacion);
    const dataValues = Object.values(countsByOrganizacion);
    const colores = ['#2563EB', '#3B82F6', '#60A5FA', '#1D4ED8', '#4F46E5', '#6366F1', '#06B6D4', '#0EA5E9', '#0284C7', '#0369A1'];

    if (labels.length === 0) {
        document.getElementById('chartLegend').innerHTML = '<span class="text-gray-500 text-sm">No hay datos para mostrar</span>';
        const ctx = document.getElementById('reportesChart').getContext('2d');
        ctx.clearRect(0, 0, 400, 400);
        ctx.font = '14px Arial';
        ctx.fillStyle = '#9ca3af';
        ctx.textAlign = 'center';
        ctx.fillText('No hay datos', 200, 200);
        return;
    }

    const ctx = document.getElementById('reportesChart').getContext('2d');
    chartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: colores.slice(0, labels.length),
                borderColor: '#ffffff',
                borderWidth: 2,
                hoverOffset: 12,
                cutout: '55%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11 }, usePointStyle: true, boxWidth: 8 }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percent = total ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} (${percent}%)`;
                        }
                    }
                }
            }
        }
    });

    const legendDiv = document.getElementById('chartLegend');
    let legendHtml = '';
    for (let i = 0; i < labels.length; i++) {
        legendHtml += `<div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full" style="background-color: ${colores[i]}"></span><span class="text-xs">${escapeHtml(labels[i])} (${dataValues[i]})</span></div>`;
    }
    legendDiv.innerHTML = legendHtml;
}

// Eliminar reporte
window.deleteReport = async function(reportId) {
    if (!confirm('¿Estás seguro de que deseas eliminar este reporte permanentemente?')) return;
    
    const formData = new URLSearchParams();
    formData.append('csrf_token', csrfToken);
    
    try {
        const response = await fetch(`/api/risk/reports/${reportId}/delete`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData
        });
        const result = await response.json();
        
        if (result.success) {
            mostrarNotificacion('✅ Reporte eliminado correctamente', 'success');
            
            // Limpiar el contenedor del reporte si estaba cargado
            const reporteContainer = document.getElementById('reporteContainer');
            if (reporteContainer) {
                reporteContainer.innerHTML = '';
            }
            
            // Recargar datos
            await loadOrganizations();
            await loadChecklists();
            await loadReports();
        } else {
            mostrarNotificacion('Error: ' + (result.error || 'No se pudo eliminar'), 'error');
        }
    } catch(e) {
        console.error(e);
        mostrarNotificacion('Error de conexión: ' + e.message, 'error');
    }
};

// Eventos
document.getElementById('filterOrganizacion').addEventListener('change', (e) => {
    cargarReportesPorOrganizacion(e.target.value);
});
document.getElementById('filterReporte').addEventListener('change', (e) => {
    filtrarPorReporteEspecifico(e.target.value);
});
document.getElementById('btnLimpiarFiltros').addEventListener('click', limpiarFiltros);
document.getElementById('btnPrevPage').addEventListener('click', () => {
    if (currentPage > 1) { currentPage--; renderTable(); }
});
document.getElementById('btnNextPage').addEventListener('click', () => {
    const totalPages = Math.ceil(filteredReports.length / itemsPerPage);
    if (currentPage < totalPages) { currentPage++; renderTable(); }
});

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m]));
}

// Inicializar
setTimeout(() => { setTypingMessage(assistantMessages.initial); }, 500);

(async function init() {
    await loadOrganizations();
    await loadChecklists();
    await loadReports();
})();
</script>
