<div class="container mx-auto px-4 py-6">
    <!-- ASISTENTE INTELIGENTE - OPTIMIXS RISK -->
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
                        <h2 class="text-base font-semibold text-gray-800">Optimixs Risk</h2>
                        <span class="px-1.5 py-0.5 bg-green-100 text-green-700 text-[10px] rounded-full font-medium">En línea</span>
                    </div>
                    
                    <div class="min-h-[80px]">
                        <p id="assistantMessage" class="text-sm text-gray-600 leading-relaxed">
                            <span id="typingText"></span>
                            <span id="typingCursor" class="inline-block w-0.5 h-4 bg-gray-400 ml-0.5 animate-pulse" style="vertical-align: middle;"></span>
                        </p>
                    </div>
                    
                    <div class="flex gap-2 mt-3 flex-wrap">
                        <button onclick="showAssistantTip('que-es')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📋 ¿Qué es la clasificación?</button>
                        <button onclick="showAssistantTip('importancia')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">🎯 Importancia estratégica</button>
                        <button onclick="showAssistantTip('uso')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📊 Uso en riesgos</button>
                        <button onclick="resetAssistantMessage()" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">⟳ Reiniciar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Clasificación del Riesgo</h1>
        <button onclick="riskOpenClasifModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md flex items-center gap-2 transition duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Crear Nuevo
        </button>
    </div>

    <!-- GRÁFICO DE BARRAS ESTABILIZADO -->
    <div class="mb-8 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl shadow-md border border-indigo-100 p-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    <svg class="w-6 h-6 inline-block mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Distribución de Clasificaciones
                </h2>
                <p class="text-gray-600 text-sm">Cantidad de clasificaciones por categoría de riesgo</p>
            </div>
            <div class="flex gap-2">
                <button onclick="toggleChartType()" class="px-3 py-1 bg-white rounded-lg text-sm font-medium text-indigo-600 hover:bg-indigo-50 shadow-sm transition">
                    Cambiar a <span id="chartTypeLabel">Barras Verticales</span>
                </button>
            </div>
        </div>
        
        <div class="mt-6">
            <canvas id="riskBarChart" width="800" height="400" style="width:100%; height:400px; max-height:400px;"></canvas>
        </div>
        
        <div class="mt-4 pt-4 border-t border-indigo-100 flex flex-wrap justify-between items-center gap-3">
            <div class="flex flex-wrap gap-4 text-sm" id="chartStats"></div>
            <div class="text-xs text-gray-500">* Haz clic en las barras para ver detalles</div>
        </div>
    </div>

    <!-- Tabla de clasificaciones -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody id="clasifTbody" class="divide-y divide-gray-200"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="riskClasifModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Editor de Clasificación</h3>
            <button onclick="riskCloseClasifModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="clasifForm">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría</label>
                <input type="text" id="categoria" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
                <textarea id="descripcion" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2" required></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="riskCloseClasifModal()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Guardar</button>
            </div>
        </form>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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

// Mensajes del asistente
const assistantMessages = {
    initial: "Bienvenido a Clasificación del Riesgo. Este módulo permite categorizar los riesgos según su naturaleza o área de impacto. Las clasificaciones ayudan a agrupar, analizar y reportar riesgos de manera más efectiva en toda la organización.",
    'que-es': "La clasificación del riesgo es una taxonomía que agrupa los riesgos por categorías como: Estratégicos, Operacionales, Financieros, Tecnológicos, Legales, entre otros. Cada clasificación permite un enfoque de gestión especializado.",
    importancia: "Clasificar los riesgos es fundamental para: 1) Asignar responsables especializados, 2) Definir estrategias de mitigación específicas, 3) Generar reportes por área, 4) Cumplir con requisitos normativos sectoriales.",
    uso: "Las clasificaciones se asignan a cada riesgo en la Matriz de Riesgos. Esto permite filtrar y analizar riesgos por categoría, identificar patrones y priorizar acciones según el tipo de riesgo. Revise los riesgos existentes para mantener coherencia."
};

// Mostrar tip con animación
window.showAssistantTip = function(tipo) {
    let message = assistantMessages[tipo] || assistantMessages.initial;
    setTypingMessage(message);
};

window.resetAssistantMessage = function() {
    setTypingMessage(assistantMessages.initial);
};

// === VARIABLES GLOBALES ===
let csrfTokenGlobal = '';
let currentEditId = null;
let barChartInstance = null;
let currentChartType = 'bar';
let isLoading = false;

// === FUNCIÓN DE ELIMINACIÓN ===
window.riskDeleteClasif = async function(id) {
    if (!confirm('¿Eliminar esta clasificación permanentemente?')) {
        return;
    }
    const formData = new URLSearchParams();
    formData.append('csrf_token', csrfTokenGlobal);
    try {
        const response = await fetch(`/api/risk/classifications/${id}/delete`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            alert('Clasificación eliminada correctamente');
            if (window.loadClassificationsFunc) window.loadClassificationsFunc();
        } else {
            alert('Error: ' + (result.error || 'No se pudo eliminar'));
        }
    } catch (err) {
        console.error(err);
        alert('Error de conexión: ' + err.message);
    }
};

// === FUNCIÓN PARA ACTUALIZAR EL GRÁFICO - ESTABILIZADA ===
function updateRiskChart(classifications) {
    const canvas = document.getElementById('riskBarChart');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    
    if (!classifications || classifications.length === 0) {
        if (barChartInstance) {
            barChartInstance.destroy();
            barChartInstance = null;
        }
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.font = '14px Arial';
        ctx.fillStyle = '#9ca3af';
        ctx.textAlign = 'center';
        ctx.fillText('No hay datos para mostrar', canvas.width / 2, canvas.height / 2);
        document.getElementById('chartStats').innerHTML = '<span class="text-gray-400">Sin clasificaciones registradas</span>';
        return;
    }

    const categoryCount = {};
    classifications.forEach(item => {
        const cat = item.categoria;
        categoryCount[cat] = (categoryCount[cat] || 0) + 1;
    });

    const labels = Object.keys(categoryCount);
    const dataValues = Object.values(categoryCount);
    
    const colors = [
        'rgba(99, 102, 241, 0.8)', 'rgba(59, 130, 246, 0.8)', 'rgba(139, 92, 246, 0.8)',
        'rgba(236, 72, 153, 0.8)', 'rgba(245, 158, 11, 0.8)', 'rgba(16, 185, 129, 0.8)',
        'rgba(239, 68, 68, 0.8)', 'rgba(107, 114, 128, 0.8)'
    ];

    const backgroundColors = labels.map((_, i) => colors[i % colors.length]);
    const borderColors = backgroundColors.map(c => c.replace('0.8', '1'));

    // Destruir instancia anterior si existe
    if (barChartInstance) {
        barChartInstance.destroy();
        barChartInstance = null;
    }

    // Crear nuevo gráfico SIN ANIMACIÓN
    const chartConfig = {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Cantidad de Clasificaciones',
                data: dataValues,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1,
                borderRadius: 4,
                barPercentage: 0.7,
                categoryPercentage: 0.8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            indexAxis: currentChartType === 'horizontalBar' ? 'y' : 'x',
            animation: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: { size: 11 },
                        usePointStyle: true,
                        boxWidth: 8
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = dataValues.reduce((a, b) => a + b, 0);
                            const percent = total ? ((value / total) * 100).toFixed(1) : 0;
                            return `📊 ${value} clasificación(es) (${percent}% del total)`;
                        }
                    },
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#e5e7eb',
                    padding: 8,
                    cornerRadius: 6
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: currentChartType === 'horizontalBar' ? 'Categorías' : 'Cantidad',
                        font: { size: 11, weight: 'bold' }
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: currentChartType === 'horizontalBar' ? 'Cantidad' : 'Categorías',
                        font: { size: 11, weight: 'bold' }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            onClick: (event, activeElements) => {
                if (activeElements.length > 0) {
                    const index = activeElements[0].index;
                    const category = labels[index];
                    const count = dataValues[index];
                    alert(`Categoría: ${category}\nCantidad de clasificaciones: ${count}`);
                }
            }
        }
    };
    
    barChartInstance = new Chart(ctx, chartConfig);

    const total = dataValues.reduce((a, b) => a + b, 0);
    const maxCount = Math.max(...dataValues);
    const maxCategory = labels[dataValues.indexOf(maxCount)];
    const avg = (total / labels.length).toFixed(1);
    
    const statsDiv = document.getElementById('chartStats');
    if (statsDiv) {
        statsDiv.innerHTML = `
            <div class="flex items-center gap-3 flex-wrap">
                <span class="px-2 py-1 bg-indigo-100 rounded-full text-indigo-700">📈 Total: ${total}</span>
                <span class="px-2 py-1 bg-green-100 rounded-full text-green-700">🏆 Mayor: ${escapeHtml(maxCategory)} (${maxCount})</span>
                <span class="px-2 py-1 bg-blue-100 rounded-full text-blue-700">📊 Promedio: ${avg}</span>
            </div>
        `;
    }
}

// Cambiar entre tipos de gráfico
window.toggleChartType = function() {
    if (currentChartType === 'bar') {
        currentChartType = 'horizontalBar';
        document.getElementById('chartTypeLabel').innerText = 'Barras Horizontales';
    } else {
        currentChartType = 'bar';
        document.getElementById('chartTypeLabel').innerText = 'Barras Verticales';
    }
    window.loadClassificationsFunc();
};

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

window.riskOpenClasifModal = function() {
    currentEditId = null;
    document.getElementById('categoria').value = '';
    document.getElementById('descripcion').value = '';
    document.getElementById('modalTitle').innerText = 'Nueva Clasificación';
    document.getElementById('riskClasifModal').classList.remove('hidden');
    document.getElementById('riskClasifModal').classList.add('flex');
};

window.riskCloseClasifModal = function() {
    document.getElementById('riskClasifModal').classList.add('hidden');
    document.getElementById('riskClasifModal').classList.remove('flex');
    currentEditId = null;
};

window.riskEditClasif = function(id, categoria, descripcion) {
    currentEditId = id;
    document.getElementById('categoria').value = categoria;
    document.getElementById('descripcion').value = descripcion;
    document.getElementById('modalTitle').innerText = 'Editar Clasificación';
    document.getElementById('riskClasifModal').classList.remove('hidden');
    document.getElementById('riskClasifModal').classList.add('flex');
};

// Función para cargar la tabla y actualizar gráfico
window.loadClassificationsFunc = async function() {
    if (isLoading) return;
    isLoading = true;
    
    const tbody = document.getElementById('clasifTbody');
    if (!tbody) {
        isLoading = false;
        return;
    }
    tbody.innerHTML = '<tr><td colspan="3" class="text-center py-8">Cargando...</td></tr>';
    try {
        const response = await fetch('/api/risk/classifications');
        const result = await response.json();
        if (result.success && Array.isArray(result.data)) {
            updateRiskChart(result.data);
            
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center py-8 text-gray-500">No hay clasificaciones</span><span class="sd-tag">    </span></td>';
            } else {
                tbody.innerHTML = result.data.map(row => `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">${escapeHtml(row.categoria)}</span><span class="sd-tag">    </span></td>
                        <td class="px-6 py-4 text-gray-600">${escapeHtml(row.descripcion)}</span><span class="sd-tag">    </span></td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <button onclick="riskEditClasif(${row.id}, '${escapeHtml(row.categoria)}', '${escapeHtml(row.descripcion)}')" class="text-indigo-600 hover:text-indigo-800 mr-3 transition" title="Editar">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button onclick="riskDeleteClasif(${row.id})" class="text-red-600 hover:text-red-800 transition" title="Eliminar">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </span><span class="sd-tag">    </span></td>
                    </tr>
                `).join('');
            }
        } else {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center py-8 text-red-500">Error al cargar</span><span class="sd-tag">    </span></tr>';
            updateRiskChart([]);
        }
    } catch (err) {
        console.error(err);
        tbody.innerHTML = '<tr><td colspan="3" class="text-center py-8 text-red-500">Error de conexión</span><span class="sd-tag">    </span></tr>';
        updateRiskChart([]);
    } finally {
        isLoading = false;
    }
};

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    csrfTokenGlobal = token;
    window.CSRF_TOKEN = token;

    // Iniciar mensaje de bienvenida
    setTimeout(() => {
        setTypingMessage(assistantMessages.initial);
    }, 500);

    const form = document.getElementById('clasifForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const categoria = document.getElementById('categoria').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();
            if (!categoria || !descripcion) {
                alert('Completa todos los campos');
                return;
            }
            const formData = new URLSearchParams();
            formData.append('csrf_token', csrfTokenGlobal);
            formData.append('categoria', categoria);
            formData.append('descripcion', descripcion);

            let url = '/api/risk/classifications';
            if (currentEditId) {
                url = `/api/risk/classifications/${currentEditId}/update`;
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    alert(currentEditId ? 'Actualizado correctamente' : 'Creado correctamente');
                    window.riskCloseClasifModal();
                    window.loadClassificationsFunc();
                } else {
                    alert('Error: ' + (result.error || 'No se pudo guardar'));
                }
            } catch (err) {
                console.error(err);
                alert('Error de conexión: ' + err.message);
            }
        });
    }

    const modal = document.getElementById('riskClasifModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) window.riskCloseClasifModal();
        });
    }

    window.loadClassificationsFunc();
});
</script>
