 <div class="container mx-auto px-4 py-6">
    <!-- ASISTENTE INTELIGENTE - OPTIMIX RISK -->
    <div class="mb-6 bg-gray-50 rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4">
            <div class="flex items-start gap-3">
                <!-- Logo de cerebro -->
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
                        <button onclick="showAssistantTip('irg')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📐 Metodología IRG</button>
                        <button onclick="showAssistantTip('interpretacion')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">🎯 Interpretación</button>
                        <button onclick="resetAssistantMessage()" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">⟳ Reiniciar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📋 Dashboard de Checklists</h1>
    </div>

    <!-- GRÁFICO CIRCULAR POR ORGANIZACIÓN - UBICADO DESPUÉS DEL TÍTULO -->
    <div class="mb-8 bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-chart-pie text-indigo-500 mr-2"></i>
                    Distribución de Checklists por Organización
                </h2>
                <p class="text-gray-500 text-sm">Cantidad de checklists por organización</p>
                <div class="mt-4 flex flex-wrap gap-2 justify-center md:justify-start" id="chartLegend"></div>
            </div>
            <div class="flex-1 max-w-xs mx-auto">
                <canvas id="checklistChart" width="400" height="400" class="w-full h-auto"></canvas>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Filtrar por Organización</label>
                <select id="filterOrganizacion" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Todas las organizaciones --</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Filtrar por Riesgo</label>
                <select id="filterRiesgo" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Todos los riesgos --</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Buscar por checklist</label>
                <input type="text" id="filterNombre" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Buscar por nombre...">
            </div>
        </div>
        <div class="mt-3 text-right">
            <button id="btnLimpiarFiltros" class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fas fa-eraser"></i> Limpiar filtros
            </button>
        </div>
    </div>

    <!-- Tabla de checklists -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Checklist</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organización</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Riesgo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progreso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Última actualización</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody id="dashboardTableBody" class="bg-white divide-y divide-gray-200">
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Cargando checklists...</span><span class="sd-tag">    </span></td>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="flex justify-between items-center mt-4">
        <button id="btnPrevPage" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition">
            <i class="fas fa-chevron-left mr-1"></i> Anterior
        </button>
        <span id="pageInfo" class="text-sm text-gray-600">Página 1</span>
        <button id="btnNextPage" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition">
            Siguiente <i class="fas fa-chevron-right ml-1"></i>
        </button>
    </div>
</div>

<style>
    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0; }
    }
    .animate-pulse {
        animation: blink 1s step-end infinite;
    }
</style>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- Font Awesome para iconos -->
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

// Mensajes profesionales del asistente - Metodología IRG
const assistantMessages = {
    initial: "Bienvenido al Dashboard de Checklists. Este módulo centraliza la visualización de todos los procesos de verificación de controles realizados por organización y riesgo. Cada checklist registra el nivel de cumplimiento de evidencias, información clave para calcular el Índice de Riesgo Global (IRG).",
    'que-es': "El Dashboard de Checklists permite monitorear el estado de los procesos de auditoría y verificación de controles. Cada fila representa un checklist ejecutado, mostrando organización asociada, riesgo evaluado, progreso de cumplimiento y fecha de última actualización. Los filtros permiten segmentar el análisis.",
    irg: "El Índice de Riesgo Global (IRG) se calcula con la fórmula: IRG = (Inh × 0.6) + (Res × 0.4) - (Cump% × 0.15). Donde Inh = (Prob. Inherente × Impacto Inherente / 100), Res = (Prob. Residual × Impacto Residual / 100). El cumplimiento reduce el riesgo hasta en 15 puntos porcentuales. Rango: 0-100 (menor = mejor control).",
    interpretacion: "Interpretación de resultados: Progreso <25% (Rojo): Riesgo elevado, controles insuficientes. 25-50% (Naranja): Requiere mejora urgente. 50-75% (Amarillo): Cumplimiento parcial aceptable. >75% (Verde): Nivel óptimo de control. El gráfico circular muestra distribución de checklists por organización."
};

// Mostrar tip con animación
window.showAssistantTip = function(tipo) {
    let message = assistantMessages[tipo] || assistantMessages.initial;
    setTypingMessage(message);
};

window.resetAssistantMessage = function() {
    setTypingMessage(assistantMessages.initial);
};

// === CÓDIGO EXISTENTE DEL DASHBOARD ===
const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
let chartInstance = null;
let allChecklists = [];
let allOrganizaciones = [];
let allRiesgos = [];
let riesgosMap = {};
let currentPage = 1;
let itemsPerPage = 10;
let filteredChecklists = [];

// Cargar organizaciones
async function loadOrganizaciones() {
    try {
        const res = await fetch('/api/risk/organizations');
        const data = await res.json();
        if (data.success && data.data) {
            allOrganizaciones = data.data;
            updateOrganizacionSelector();
        }
    } catch(e) { console.error('Error cargando organizaciones:', e); }
}

// Cargar riesgos
async function loadRiesgos() {
    try {
        const res = await fetch('/api/risk/matrix');
        const data = await res.json();
        if (data.success && data.data) {
            allRiesgos = data.data;
            data.data.forEach(r => {
                riesgosMap[r.id] = r.proceso;
            });
            updateRiesgoSelector();
        }
    } catch(e) { console.error('Error cargando riesgos:', e); }
}

// Cargar checklists desde la base de datos
async function loadChecklists() {
    try {
        const res = await fetch('/api/risk/checklist/dashboard');
        const data = await res.json();
        if (data.success && data.data) {
            allChecklists = data.data;
            console.log('Checklists cargados:', allChecklists.length);
            applyFilters();
        } else {
            document.getElementById('dashboardTableBody').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay checklists registrados</span><span class="sd-tag">    </span></td>';
        }
    } catch(e) {
        console.error('Error cargando checklists:', e);
        document.getElementById('dashboardTableBody').innerHTML = '<td><td colspan="6" class="px-6 py-4 text-center text-red-500">Error de conexión</span><span class="sd-tag">    </span></td>';
    }
}

// Actualizar selector de organizaciones
function updateOrganizacionSelector() {
    const select = document.getElementById('filterOrganizacion');
    select.innerHTML = '<option value="">-- Todas las organizaciones --</option>';
    allOrganizaciones.forEach(org => {
        const opt = document.createElement('option');
        opt.value = org.id;
        opt.textContent = org.nombre;
        select.appendChild(opt);
    });
}

// Actualizar selector de riesgos
function updateRiesgoSelector() {
    const select = document.getElementById('filterRiesgo');
    select.innerHTML = '<option value="">-- Todos los riesgos --</option>';
    allRiesgos.forEach(riesgo => {
        const opt = document.createElement('option');
        opt.value = riesgo.id;
        opt.textContent = riesgo.proceso;
        select.appendChild(opt);
    });
}

// Aplicar filtros
function applyFilters() {
    const orgFiltro = document.getElementById('filterOrganizacion').value;
    const riesgoFiltro = document.getElementById('filterRiesgo').value;
    const nombreFiltro = document.getElementById('filterNombre').value.trim().toLowerCase();
    
    let filtered = [...allChecklists];
    
    if (orgFiltro) {
        filtered = filtered.filter(item => item.organizacion_id == orgFiltro);
    }
    
    if (riesgoFiltro) {
        filtered = filtered.filter(item => item.riesgo_id == riesgoFiltro);
    }
    
    if (nombreFiltro) {
        filtered = filtered.filter(item => 
            item.nombre_checklist && item.nombre_checklist.toLowerCase().includes(nombreFiltro)
        );
    }
    
    filteredChecklists = filtered;
    currentPage = 1;
    renderTable();
    updateChart(filtered);
}

// Renderizar tabla con paginación
function renderTable() {
    const start = (currentPage - 1) * itemsPerPage;
    const paginatedItems = filteredChecklists.slice(start, start + itemsPerPage);
    const totalPages = Math.ceil(filteredChecklists.length / itemsPerPage);
    
    const btnPrev = document.getElementById('btnPrevPage');
    const btnNext = document.getElementById('btnNextPage');
    const pageInfo = document.getElementById('pageInfo');
    
    btnPrev.disabled = currentPage <= 1;
    btnNext.disabled = currentPage >= totalPages || totalPages === 0;
    pageInfo.textContent = `Página ${currentPage} de ${totalPages || 1}`;
    
    const tbody = document.getElementById('dashboardTableBody');
    
    if (!paginatedItems.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay checklists que coincidan con los filtros</span><span class="sd-tag">    </span></td>';
        return;
    }
    
    let html = '';
    for (const item of paginatedItems) {
        const porcentaje = item.total_evidencias ? Math.round(item.afirmativas / item.total_evidencias * 100) : 0;
        const fecha = new Date(item.updated_at).toLocaleString();
        const riesgoNombre = riesgosMap[item.riesgo_id] || `Riesgo #${item.riesgo_id}`;
        const organizacion = allOrganizaciones.find(o => o.id == item.organizacion_id);
        const organizacionNombre = organizacion ? organizacion.nombre : (item.organizacion_nombre || 'Sin organización');
        
        let barColor = 'bg-green-500';
        let textColorClass = '';
        if (porcentaje >= 75) { barColor = 'bg-green-500'; textColorClass = 'text-green-600'; }
        else if (porcentaje >= 50) { barColor = 'bg-yellow-500'; textColorClass = 'text-yellow-600'; }
        else if (porcentaje >= 25) { barColor = 'bg-orange-500'; textColorClass = 'text-orange-600'; }
        else { barColor = 'bg-red-500'; textColorClass = 'text-red-600'; }
        
        html += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">${escapeHtml(item.nombre_checklist)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <span class="inline-flex items-center gap-1"><i class="fas fa-building text-gray-400"></i> ${escapeHtml(organizacionNombre)}</span>
                </span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(riesgoNombre)}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                        <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="${barColor} h-2 rounded-full" style="width: ${porcentaje}%"></div>
                        </div>
                        <span class="text-sm font-medium ${textColorClass}">${porcentaje}% (${item.afirmativas}/${item.total_evidencias})</span>
                    </div>
                </span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${fecha}</span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex gap-2">
                        <a href="/risk/checklist?riesgo_id=${item.riesgo_id}&nombre=${encodeURIComponent(item.nombre_checklist)}&org_id=${item.organizacion_id}" class="text-indigo-600 hover:text-indigo-900" title="Ver detalle">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button onclick="eliminarChecklist('${escapeHtml(item.nombre_checklist)}', ${item.riesgo_id})" class="text-red-600 hover:text-red-800" title="Eliminar checklist">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </span><span class="sd-tag">    </span></span><span class="sd-tag">    </span></td>
            
        `;
    }
    tbody.innerHTML = html;
}

// Eliminar checklist
window.eliminarChecklist = async function(nombreChecklist, riesgoId) {
    if (!confirm(`¿Eliminar permanentemente el checklist "${nombreChecklist}"?`)) return;
    
    const formData = new URLSearchParams();
    formData.append('csrf_token', csrf);
    formData.append('nombre_checklist', nombreChecklist);
    formData.append('riesgo_id', riesgoId);
    
    try {
        const response = await fetch('/api/risk/checklist/delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            alert('Checklist eliminado correctamente');
            loadChecklists();
        } else {
            alert('Error: ' + (result.error || 'No se pudo eliminar'));
        }
    } catch(e) {
        console.error(e);
        alert('Error de conexión: ' + e.message);
    }
};

// Actualizar gráfico circular
function updateChart(checklists) {
    if (chartInstance) chartInstance.destroy();
    
    const countsByOrganizacion = {};
    for (const item of checklists) {
        const orgId = item.organizacion_id;
        if (orgId) {
            countsByOrganizacion[orgId] = (countsByOrganizacion[orgId] || 0) + 1;
        }
    }
    
    const labels = [];
    const dataValues = [];
    const backgroundColors = [];
    const colores = ['#3B82F6', '#6366F1', '#10B981', '#F97316', '#8B5CF6', '#F43F5E', '#06B6D4', '#84CC16', '#EC4899', '#6B7280'];
    
    let idx = 0;
    for (const [orgId, count] of Object.entries(countsByOrganizacion)) {
        const org = allOrganizaciones.find(o => o.id == orgId);
        const orgNombre = org ? org.nombre : `Organización #${orgId}`;
        labels.push(orgNombre);
        dataValues.push(count);
        backgroundColors.push(colores[idx % colores.length]);
        idx++;
    }
    
    if (labels.length === 0) {
        document.getElementById('chartLegend').innerHTML = '<span class="text-gray-500 text-sm">No hay datos</span>';
        const ctx = document.getElementById('checklistChart').getContext('2d');
        if (ctx) {
            ctx.clearRect(0, 0, 400, 400);
            ctx.font = '14px Arial';
            ctx.fillStyle = '#9ca3af';
            ctx.textAlign = 'center';
            ctx.fillText('No hay datos', 200, 200);
        }
        return;
    }
    
    const ctx = document.getElementById('checklistChart').getContext('2d');
    chartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: backgroundColors,
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
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = dataValues.reduce((a, b) => a + b, 0);
                            const percent = total ? Math.round((context.raw / total) * 100) : 0;
                            return `${context.label}: ${context.raw} (${percent}%)`;
                        }
                    }
                }
            }
        }
    });
    
    const legendDiv = document.getElementById('chartLegend');
    let legendHtml = '';
    for (let i = 0; i < labels.length; i++) {
        legendHtml += `<div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full" style="background-color: ${backgroundColors[i]}"></span><span class="text-xs">${escapeHtml(labels[i])} (${dataValues[i]})</span></div>`;
    }
    legendDiv.innerHTML = legendHtml;
}

// Limpiar filtros
function limpiarFiltros() {
    document.getElementById('filterOrganizacion').value = '';
    document.getElementById('filterRiesgo').value = '';
    document.getElementById('filterNombre').value = '';
    applyFilters();
}

// Eventos
document.getElementById('filterOrganizacion').addEventListener('change', applyFilters);
document.getElementById('filterRiesgo').addEventListener('change', applyFilters);
document.getElementById('filterNombre').addEventListener('input', applyFilters);
document.getElementById('btnLimpiarFiltros').addEventListener('click', limpiarFiltros);
document.getElementById('btnPrevPage').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderTable();
    }
});
document.getElementById('btnNextPage').addEventListener('click', () => {
    const totalPages = Math.ceil(filteredChecklists.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderTable();
    }
});

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m]));
}

// Inicializar mensaje de bienvenida
setTimeout(() => {
    setTypingMessage(assistantMessages.initial);
}, 500);

// Inicializar
async function init() {
    await loadOrganizaciones();
    await loadRiesgos();
    await loadChecklists();
}

init();
</script>
