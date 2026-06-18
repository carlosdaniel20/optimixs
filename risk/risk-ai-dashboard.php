<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard de Análisis IA</h1>
            <p class="text-gray-500 text-sm mt-1">Seguimiento de análisis bayesianos realizados por la inteligencia artificial</p>
        </div>
        <a href="/risk/ai-analisis" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-brain"></i> Nuevo Análisis IA
        </a>
    </div>

    <!-- Estadísticas generales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Análisis</p>
                    <p class="text-2xl font-bold text-gray-800" id="totalAnalisis">0</p>
                </div>
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-indigo-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Riesgo Promedio</p>
                    <p class="text-2xl font-bold text-gray-800" id="riesgoPromedio">0%</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-bar text-orange-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Riesgo Crítico</p>
                    <p class="text-2xl font-bold text-red-600" id="riesgoCritico">0</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Riesgo Alto</p>
                    <p class="text-2xl font-bold text-orange-600" id="riesgoAlto">0</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-arrow-up text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Filtrar por nivel de riesgo</label>
                <select id="filterNivel" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Todos los niveles --</option>
                    <option value="Crítico">🔴 Crítico (≥70%)</option>
                    <option value="Alto">🟠 Alto (50-69%)</option>
                    <option value="Moderado">🟡 Moderado (30-49%)</option>
                    <option value="Bajo">🟢 Bajo (<30%)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Filtrar por reporte</label>
                <input type="text" id="filterReporte" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Buscar por nombre del reporte...">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ordenar por</label>
                <select id="filterOrden" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="fecha_desc">📅 Fecha (más reciente)</option>
                    <option value="fecha_asc">📅 Fecha (más antiguo)</option>
                    <option value="riesgo_desc">⚠️ Riesgo (mayor a menor)</option>
                    <option value="riesgo_asc">⚠️ Riesgo (menor a mayor)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabla de resultados -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporte / Análisis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Probabilidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nivel de Riesgo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Evidencias</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Analista</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody id="dashboardTableBody" class="bg-white divide-y divide-gray-200">
                    <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Cargando análisis...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div class="flex justify-between items-center mt-6">
        <button id="btnPrevPage" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition">
            <i class="fas fa-chevron-left mr-1"></i> Anterior
        </button>
        <span id="pageInfo" class="text-sm text-gray-600">Página 1</span>
        <button id="btnNextPage" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition">
            Siguiente <i class="fas fa-chevron-right ml-1"></i>
        </button>
    </div>

    <!-- GRÁFICO DE DISTRIBUCIÓN DE RIESGOS -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 fade-in mt-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-chart-pie text-indigo-500 mr-2"></i>
                    Distribución por Nivel de Riesgo
                </h2>
                <p class="text-gray-500 text-sm">Distribución de los análisis IA según su nivel de riesgo calculado mediante el Teorema de Bayes.</p>
                <div class="mt-4 flex flex-wrap gap-2 justify-center md:justify-start" id="chartLegend"></div>
            </div>
            <div class="flex-1 max-w-sm mx-auto">
                <canvas id="riesgoChart" width="400" height="400" class="w-full h-auto"></canvas>
            </div>
        </div>
    </div>

    <!-- GRÁFICO DE EVOLUCIÓN DE RIESGO -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 fade-in mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-chart-line text-indigo-500 mr-2"></i>
                Evolución del Riesgo por Reporte
            </h2>
            <select id="selectReporteEvolucion" class="border border-gray-300 rounded-lg px-3 py-1 text-sm">
                <option value="">-- Seleccionar reporte --</option>
            </select>
        </div>
        <div class="relative h-80">
            <canvas id="evolucionChart"></canvas>
        </div>
        <p class="text-center text-xs text-gray-500 mt-4">Tendencia del riesgo a lo largo del tiempo para el reporte seleccionado</p>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
let allAnalisis = [];
let currentPage = 1;
let itemsPerPage = 10;
let riesgoChartInstance = null;
let evolucionChartInstance = null;

// Colores por nivel de riesgo
const nivelColors = {
    'Crítico': { bg: '#EF4444', light: '#FEE2E2', text: 'text-red-800' },
    'Alto': { bg: '#F97316', light: '#FFEDD5', text: 'text-orange-800' },
    'Moderado': { bg: '#F59E0B', light: '#FEF3C7', text: 'text-yellow-800' },
    'Bajo': { bg: '#10B981', light: '#D1FAE5', text: 'text-green-800' }
};

// Obtener nivel de riesgo según porcentaje
function getNivelRiesgo(porcentaje) {
    if (porcentaje >= 70) return 'Crítico';
    if (porcentaje >= 50) return 'Alto';
    if (porcentaje >= 30) return 'Moderado';
    return 'Bajo';
}

// Cargar estadísticas
async function loadStats() {
    try {
        const res = await fetch('/api/risk-ai/stats');
        const data = await res.json();
        if (data.success) {
            document.getElementById('totalAnalisis').innerText = data.total || 0;
            document.getElementById('riesgoPromedio').innerText = (data.promedio || 0).toFixed(1) + '%';
            document.getElementById('riesgoCritico').innerText = data.criticos || 0;
            document.getElementById('riesgoAlto').innerText = data.altos || 0;
        }
    } catch(e) { console.error('Error cargando estadísticas:', e); }
}

// Cargar todos los análisis
async function loadAnalisis() {
    try {
        const res = await fetch('/api/risk-ai/all');
        const data = await res.json();
        if (data.success) {
            allAnalisis = data.data || [];
            console.log('Análisis cargados:', allAnalisis.length);
            if (allAnalisis.length > 0) {
                console.log('Primer análisis:', allAnalisis[0]);
            }
            applyFilters();
            
            // Cargar opciones para el gráfico de evolución usando riesgo_nombre
            const selectEvolucion = document.getElementById('selectReporteEvolucion');
            const reportesUnicos = [...new Map(allAnalisis.map(a => [a.report_id, a.riesgo_nombre])).entries()];
            selectEvolucion.innerHTML = '<option value="">-- Seleccionar reporte --</option>';
            reportesUnicos.forEach(([id, nombre]) => {
                const nombreMostrar = nombre && nombre !== 'Sin nombre' ? nombre : `Reporte #${id}`;
                selectEvolucion.innerHTML += `<option value="${id}">${escapeHtml(nombreMostrar)}</option>`;
            });
        }
    } catch(e) { console.error('Error cargando análisis:', e); }
}

// Aplicar filtros
function applyFilters() {
    const nivelFiltro = document.getElementById('filterNivel').value;
    const reporteFiltro = document.getElementById('filterReporte').value.trim().toLowerCase();
    const ordenFiltro = document.getElementById('filterOrden').value;
    
    let filtered = [...allAnalisis];
    
    // Filtrar por nivel
    if (nivelFiltro) {
        filtered = filtered.filter(a => getNivelRiesgo(a.posterior) === nivelFiltro);
    }
    
    // Filtrar por nombre de reporte (usando riesgo_nombre)
    if (reporteFiltro) {
        filtered = filtered.filter(a => a.riesgo_nombre && a.riesgo_nombre.toLowerCase().includes(reporteFiltro));
    }
    
    // Ordenar
    switch(ordenFiltro) {
        case 'fecha_desc':
            filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            break;
        case 'fecha_asc':
            filtered.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            break;
        case 'riesgo_desc':
            filtered.sort((a, b) => b.posterior - a.posterior);
            break;
        case 'riesgo_asc':
            filtered.sort((a, b) => a.posterior - b.posterior);
            break;
        default:
            filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    }
    
    renderTable(filtered);
    updateChartDistribution(filtered);
}

// Renderizar tabla
function renderTable(filteredData) {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    const start = (currentPage - 1) * itemsPerPage;
    const paginatedItems = filteredData.slice(start, start + itemsPerPage);
    
    // Actualizar botones de paginación
    const btnPrev = document.getElementById('btnPrevPage');
    const btnNext = document.getElementById('btnNextPage');
    const pageInfo = document.getElementById('pageInfo');
    
    btnPrev.disabled = currentPage <= 1;
    btnNext.disabled = currentPage >= totalPages || totalPages === 0;
    pageInfo.textContent = `Página ${currentPage} de ${totalPages || 1}`;
    
    const tbody = document.getElementById('dashboardTableBody');
    if (!paginatedItems.length) {
        tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay análisis que coincidan con los filtros</span><span class="sd-tag">    </span></td>';
        return;
    }
    
    let html = '';
    for (const item of paginatedItems) {
        const nivel = getNivelRiesgo(item.posterior);
        const nivelColor = nivelColors[nivel];
        const fecha = new Date(item.created_at).toLocaleString();
        const evidenciasCount = item.evidencias_detectadas ? (Array.isArray(item.evidencias_detectadas) ? item.evidencias_detectadas.length : 0) : 0;
        // Usar riesgo_nombre en lugar de report_nombre
        const nombreMostrar = item.riesgo_nombre && item.riesgo_nombre !== 'Sin nombre' ? item.riesgo_nombre : `Análisis #${item.id}`;
        
        html += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm">
                    <div class="font-medium text-gray-900">${escapeHtml(nombreMostrar)}</div>
                    <div class="text-xs text-gray-400 mt-1">ID Reporte: ${item.report_id}</div>
                </span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex flex-col">
                        <span class="font-bold text-lg">${item.posterior}%</span>
                        <div class="w-24 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full" style="width: ${item.posterior}%; background-color: ${nivelColor.bg}"></div>
                        </div>
                    </div>
                </span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold ${nivelColor.text}" style="background-color: ${nivelColor.light}">
                        ${nivel}
                    </span>
                </span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <div class="flex items-center gap-1">
                        <i class="fas fa-search text-indigo-500"></i>
                        <span>${evidenciasCount} evidencia(s)</span>
                    </div>
                </span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <i class="fas fa-user-circle text-gray-400 mr-1"></i> ${escapeHtml(item.usuario_nombre || 'Sistema')}
                </span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${fecha}</span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <button onclick="verDetalle(${item.id})" class="text-indigo-600 hover:text-indigo-900 mr-2" title="Ver detalle">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="eliminarAnalisis(${item.id})" class="text-red-600 hover:text-red-900" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </span><span class="sd-tag">    </span></td>
            </tr>
        `;
    }
    tbody.innerHTML = html;
}

// Actualizar gráfico de distribución
function updateChartDistribution(analisis) {
    if (riesgoChartInstance) riesgoChartInstance.destroy();
    
    const niveles = ['Crítico', 'Alto', 'Moderado', 'Bajo'];
    const counts = [0, 0, 0, 0];
    const colors = ['#EF4444', '#F97316', '#F59E0B', '#10B981'];
    
    for (const item of analisis) {
        const nivel = getNivelRiesgo(item.posterior);
        const index = niveles.indexOf(nivel);
        if (index !== -1) counts[index]++;
    }
    
    const ctx = document.getElementById('riesgoChart').getContext('2d');
    riesgoChartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: niveles,
            datasets: [{
                data: counts,
                backgroundColor: colors,
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
                            const total = counts.reduce((a, b) => a + b, 0);
                            const percent = total ? Math.round((context.raw / total) * 100) : 0;
                            return `${context.label}: ${context.raw} (${percent}%)`;
                        }
                    }
                }
            }
        }
    });
    
    // Leyenda personalizada
    const legendDiv = document.getElementById('chartLegend');
    if (legendDiv) {
        let legendHtml = '';
        for (let i = 0; i < niveles.length; i++) {
            if (counts[i] > 0) {
                legendHtml += `<div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full" style="background-color: ${colors[i]}"></span><span class="text-xs">${niveles[i]} (${counts[i]})</span></div>`;
            }
        }
        legendDiv.innerHTML = legendHtml || '<span class="text-xs text-gray-400">No hay datos</span>';
    }
}

// Cargar evolución de riesgo para un reporte
async function loadEvolucion(reportId) {
    if (!reportId) {
        if (evolucionChartInstance) evolucionChartInstance.destroy();
        return;
    }
    
    try {
        const res = await fetch(`/api/risk-ai/evolucion/${reportId}`);
        const data = await res.json();
        if (data.success && data.data && data.data.length > 0) {
            const fechas = data.data.map(item => new Date(item.created_at).toLocaleDateString());
            const valores = data.data.map(item => item.posterior);
            
            if (evolucionChartInstance) evolucionChartInstance.destroy();
            
            const ctx = document.getElementById('evolucionChart').getContext('2d');
            evolucionChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: fechas,
                    datasets: [{
                        label: 'Riesgo (%)',
                        data: valores,
                        borderColor: '#6366F1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: valores.map(v => v >= 70 ? '#EF4444' : (v >= 50 ? '#F97316' : (v >= 30 ? '#F59E0B' : '#10B981'))),
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            min: 0,
                            max: 100,
                            title: { display: true, text: 'Probabilidad de Riesgo (%)' }
                        },
                        x: { title: { display: true, text: 'Fecha del Análisis' } }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Riesgo: ${context.raw}%`;
                                }
                            }
                        }
                    }
                }
            });
        } else if (evolucionChartInstance) {
            evolucionChartInstance.destroy();
            const ctx = document.getElementById('evolucionChart').getContext('2d');
            ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
        }
    } catch(e) { console.error('Error cargando evolución:', e); }
}

// Ver detalle del análisis
function verDetalle(id) {
    window.open(`/api/risk-ai/analysis/${id}/view`, '_blank');
}

// Eliminar análisis
async function eliminarAnalisis(id) {
    if (!confirm('¿Eliminar este análisis permanentemente?')) return;
    
    const formData = new URLSearchParams();
    formData.append('csrf_token', csrfToken);
    
    try {
        const res = await fetch(`/api/risk-ai/delete/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData
        });
        const data = await res.json();
        if (data.success) {
            alert('Análisis eliminado correctamente');
            loadAnalisis();
            loadStats();
        } else {
            alert('Error: ' + (data.error || 'No se pudo eliminar'));
        }
    } catch(e) { alert('Error de conexión: ' + e.message); }
}

// Eventos
document.getElementById('filterNivel').addEventListener('change', () => { currentPage = 1; applyFilters(); });
document.getElementById('filterReporte').addEventListener('input', () => { currentPage = 1; applyFilters(); });
document.getElementById('filterOrden').addEventListener('change', () => { currentPage = 1; applyFilters(); });
document.getElementById('btnPrevPage').addEventListener('click', () => { if (currentPage > 1) { currentPage--; applyFilters(); } });
document.getElementById('btnNextPage').addEventListener('click', () => { currentPage++; applyFilters(); });
document.getElementById('selectReporteEvolucion').addEventListener('change', (e) => { loadEvolucion(e.target.value); });

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m]));
}

// Inicializar
loadStats();
loadAnalisis();
</script>

<style>
.fade-in {
    animation: fadeInUp 0.6s ease-out;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
