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
                    
                    <!-- Área de mensaje con animación de escritura -->
                    <div class="min-h-[80px]">
                        <p id="assistantMessage" class="text-sm text-gray-600 leading-relaxed">
                            <span id="typingText"></span>
                            <span id="typingCursor" class="inline-block w-0.5 h-4 bg-gray-400 ml-0.5 animate-pulse" style="vertical-align: middle;"></span>
                        </p>
                    </div>
                    
                    <!-- Botones de ayuda -->
                    <div class="flex gap-2 mt-3 flex-wrap">
                        <button onclick="showAssistantTip('que-es')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📊 ¿Qué es la Matriz de Impacto?</button>
                        <button onclick="showAssistantTip('niveles')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">🎯 Niveles de impacto</button>
                        <button onclick="showAssistantTip('descripciones')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📝 Descripciones por área</button>
                        <button onclick="resetAssistantMessage()" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">⟳ Reiniciar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Matriz de Impacto</h1>
        <button onclick="openImpactoModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md flex items-center gap-2 transition duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Nuevo Nivel
        </button>
    </div>

    <!-- Tabla de Impactos -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden mb-6">
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
            <h2 class="font-semibold text-gray-700">📋 Niveles de Impacto</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-800 text-white text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-center">Nivel</th>
                        <th class="px-4 py-3 text-center">Impacto</th>
                        <th class="px-4 py-3 text-center">Descripción Económica</th>
                        <th class="px-4 py-3 text-center">Descripción Reputacional</th>
                        <th class="px-4 py-3 text-center">Otros Impactos</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="impactosTbody" class="divide-y divide-gray-200 bg-white">
                    <tr><td colspan="6" class="text-center py-8 text-gray-500">Cargando niveles de impacto...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- GRÁFICO CIRCULAR POR NIVEL DE IMPACTO -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 fade-in">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-chart-pie text-indigo-500 mr-2"></i>
                    Distribución por Nivel de Impacto
                </h2>
                <p class="text-gray-500 text-sm">Cada porción representa un nivel de impacto. Pasa el ratón para ver la cantidad de registros.</p>
                <div class="mt-4 flex flex-wrap gap-2 justify-center md:justify-start" id="chartLegend"></div>
            </div>
            <div class="flex-1 max-w-sm mx-auto">
                <canvas id="impactoChart" width="400" height="400" class="w-full h-auto"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="impactoModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Nivel de Impacto</h3>
            <button onclick="closeImpactoModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="impactoForm" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nivel (%) *</label>
                <select id="nivel" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <option value="">Seleccionar</option>
                    <option>20%</option><option>40%</option><option>60%</option><option>80%</option><option>100%</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Impacto *</label>
                <select id="impacto" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <option value="">Seleccionar</option>
                    <option>Leve</option><option>Menor</option><option>Moderado</option><option>Mayor</option><option>Catastrófico</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción Económica</label>
                <textarea id="eco" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción Reputacional</label>
                <textarea id="repu" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Otros Impactos</label>
                <textarea id="otros" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeImpactoModal()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">Cancelar</button>
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

// Mensajes del asistente
const assistantMessages = {
    initial: "Bienvenido a la Matriz de Impacto. Este módulo permite definir y gestionar los niveles de impacto que afectan a la organización. Cada nivel (20% a 100%) se asocia a una categoría (Leve a Catastrófico) con descripciones específicas por área económica, reputacional y otros impactos.",
    'que-es': "La Matriz de Impacto es una herramienta fundamental para la gestión de riesgos. Define la severidad de las consecuencias cuando un riesgo se materializa. Los niveles están codificados por colores: Azul (Leve), Verde (Menor), Amarillo (Moderado), Naranja (Mayor) y Rojo (Catastrófico).",
    niveles: "Niveles disponibles: 20% (Leve) - Daños mínimos, 40% (Menor) - Afectación limitada, 60% (Moderado) - Daños significativos, 80% (Mayor) - Afectación grave, 100% (Catastrófico) - Daño extremo. Cada nivel requiere descripciones específicas por área.",
    descripciones: "Para cada nivel de impacto debe definir tres tipos de descripciones: Económica (pérdidas financieras), Reputacional (daño a la imagen/marca) y Otros Impactos (operacionales, legales, ambientales, etc.). Complete estos campos al crear o editar un nivel."
};

// Mostrar tip con animación
window.showAssistantTip = function(tipo) {
    let message = assistantMessages[tipo] || assistantMessages.initial;
    setTypingMessage(message);
};

window.resetAssistantMessage = function() {
    setTypingMessage(assistantMessages.initial);
};

// === CÓDIGO EXISTENTE DE LA MATRIZ DE IMPACTO ===
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        // Iniciar mensaje de bienvenida
        setTimeout(() => {
            setTypingMessage(assistantMessages.initial);
        }, 500);

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        window.CSRF_TOKEN = csrfToken;

        const modal = document.getElementById('impactoModal');
        const form = document.getElementById('impactoForm');
        const tbody = document.getElementById('impactosTbody');
        const nivelInput = document.getElementById('nivel');
        const impactoInput = document.getElementById('impacto');
        const ecoInput = document.getElementById('eco');
        const repuInput = document.getElementById('repu');
        const otrosInput = document.getElementById('otros');
        const modalTitle = document.getElementById('modalTitle');

        let currentId = null;
        let chartInstance = null;

        // Colores para el gráfico basados en el nivel de impacto
        const impactoColors = {
            'Catastrófico': '#EF4444',
            'Mayor': '#F97316',
            'Moderado': '#F59E0B',
            'Menor': '#10B981',
            'Leve': '#3B82F6'
        };

        async function loadImpactos() {
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-500">Cargando...</td></tr>';
            try {
                const response = await fetch('/api/risk/impacts');
                const result = await response.json();
                if (result.success && Array.isArray(result.data)) {
                    if (result.data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-500">No hay niveles de impacto registrados</td></tr>';
                        updateChart([]);
                        return;
                    }
                    tbody.innerHTML = result.data.map(row => `
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 border-b text-center font-medium">${escapeHtml(row.nivel)}</td>
                            <td class="px-4 py-3 border-b text-center ${getImpactoClass(row.impacto)}">${escapeHtml(row.impacto)}</td>
                            <td class="px-4 py-3 border-b">${escapeHtml(row.descripcion_economica)}</td>
                            <td class="px-4 py-3 border-b">${escapeHtml(row.descripcion_reputacional)}</td>
                            <td class="px-4 py-3 border-b">${escapeHtml(row.otros_impactos)}</td>
                            <td class="px-4 py-3 border-b text-center whitespace-nowrap">
                                <button onclick="editImpacto(${row.id})" class="text-indigo-600 hover:text-indigo-800 mr-3" title="Editar">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button onclick="deleteImpacto(${row.id})" class="text-red-600 hover:text-red-800" title="Eliminar">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    `).join('');
                    
                    updateChart(result.data);
                } else {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-red-500">Error al cargar datos</td></tr>';
                }
            } catch (err) {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-red-500">Error de conexión</td></tr>';
            }
        }

        function updateChart(impactos) {
            if (chartInstance) {
                chartInstance.destroy();
            }

            const counts = {
                'Catastrófico': 0,
                'Mayor': 0,
                'Moderado': 0,
                'Menor': 0,
                'Leve': 0
            };

            for (const item of impactos) {
                const impacto = item.impacto;
                if (counts.hasOwnProperty(impacto)) {
                    counts[impacto]++;
                }
            }

            const labels = [];
            const dataValues = [];
            const backgroundColors = [];
            
            const orden = ['Leve', 'Menor', 'Moderado', 'Mayor', 'Catastrófico'];
            
            for (const impacto of orden) {
                if (counts[impacto] > 0) {
                    labels.push(impacto);
                    dataValues.push(counts[impacto]);
                    backgroundColors.push(impactoColors[impacto]);
                }
            }

            if (labels.length === 0) {
                const ctx = document.getElementById('impactoChart').getContext('2d');
                ctx.clearRect(0, 0, 400, 400);
                ctx.font = '14px Arial';
                ctx.fillStyle = '#9ca3af';
                ctx.textAlign = 'center';
                ctx.fillText('No hay datos', 200, 200);
                document.getElementById('chartLegend').innerHTML = '<span class="text-gray-400 text-sm">No hay niveles registrados</span>';
                return;
            }

            const ctx = document.getElementById('impactoChart').getContext('2d');
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
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 1500,
                        easing: 'easeOutBounce'
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 11 },
                                usePointStyle: true,
                                boxWidth: 8
                            }
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
                legendHtml += `<div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full" style="background-color: ${backgroundColors[i]}"></span><span class="text-xs">${escapeHtml(labels[i])} (${dataValues[i]})</span></div>`;
            }
            legendDiv.innerHTML = legendHtml;
        }

        function getImpactoClass(impacto) {
            switch(impacto) {
                case 'Catastrófico': return 'bg-red-100 text-red-800 font-semibold';
                case 'Mayor': return 'bg-orange-100 text-orange-800 font-semibold';
                case 'Moderado': return 'bg-yellow-100 text-yellow-800 font-semibold';
                case 'Menor': return 'bg-green-100 text-green-800 font-semibold';
                case 'Leve': return 'bg-blue-100 text-blue-800 font-semibold';
                default: return '';
            }
        }

        window.openImpactoModal = function() {
            currentId = null;
            nivelInput.value = '';
            impactoInput.value = '';
            ecoInput.value = '';
            repuInput.value = '';
            otrosInput.value = '';
            modalTitle.innerText = 'Nuevo Nivel de Impacto';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        window.closeImpactoModal = function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            currentId = null;
        };

        window.editImpacto = async function(id) {
            try {
                const response = await fetch(`/api/risk/impacts/${id}`);
                const result = await response.json();
                if (result.success) {
                    currentId = id;
                    nivelInput.value = result.data.nivel;
                    impactoInput.value = result.data.impacto;
                    ecoInput.value = result.data.descripcion_economica || '';
                    repuInput.value = result.data.descripcion_reputacional || '';
                    otrosInput.value = result.data.otros_impactos || '';
                    modalTitle.innerText = 'Editar Nivel de Impacto';
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                } else {
                    alert('Error al cargar los datos');
                }
            } catch (err) {
                alert('Error de conexión');
            }
        };

        window.deleteImpacto = async function(id) {
            if (!confirm('¿Eliminar este nivel de impacto permanentemente?')) return;
            
            const formData = new URLSearchParams();
            formData.append('csrf_token', csrfToken);
            try {
                const response = await fetch(`/api/risk/impacts/${id}/delete`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    alert('Impacto eliminado correctamente');
                    loadImpactos();
                } else {
                    alert('Error: ' + (result.error || 'No se pudo eliminar'));
                }
            } catch (err) {
                console.error(err);
                alert('Error de conexión: ' + err.message);
            }
        };

        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const nivel = nivelInput.value.trim();
                const impacto = impactoInput.value.trim();
                if (!nivel || !impacto) {
                    alert('Completa todos los campos obligatorios');
                    return;
                }

                const formData = new URLSearchParams();
                formData.append('csrf_token', csrfToken);
                formData.append('nivel', nivel);
                formData.append('impacto', impacto);
                formData.append('descripcion_economica', ecoInput.value);
                formData.append('descripcion_reputacional', repuInput.value);
                formData.append('otros_impactos', otrosInput.value);

                const url = currentId ? `/api/risk/impacts/${currentId}` : '/api/risk/impacts';
                const method = currentId ? 'PUT' : 'POST';

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: formData
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert(currentId ? 'Impacto actualizado' : 'Impacto creado');
                        window.closeImpactoModal();
                        loadImpactos();
                    } else {
                        alert('Error: ' + (result.error || 'No se pudo guardar'));
                    }
                } catch (err) {
                    alert('Error de conexión: ' + err.message);
                }
            });
        }

        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    window.closeImpactoModal();
                }
            });
        }

        loadImpactos();
    });

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
})();
</script>
