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
                        <button onclick="showAssistantTip('que-es')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📊 ¿Qué son los criterios?</button>
                        <button onclick="showAssistantTip('niveles')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">🎯 Niveles y porcentajes</button>
                        <button onclick="showAssistantTip('matriz')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">🔥 Uso en la matriz de calor</button>
                        <button onclick="resetAssistantMessage()" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">⟳ Reiniciar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Criterios de Probabilidad</h1>
        <button onclick="openProbModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md flex items-center gap-2 transition duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Editar niveles
        </button>
    </div>

    <!-- GRÁFICO DE TARTA (PIE CHART) - ARRIBA DE LA MATRIZ -->
    <div class="mb-8 bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    <svg class="w-6 h-6 inline-block mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                    Distribución de Probabilidades
                </h2>
                <p class="text-gray-500 text-sm">Distribución porcentual de los niveles de probabilidad</p>
                <div class="mt-4 flex flex-wrap gap-3 justify-center md:justify-start" id="pieChartLegend"></div>
            </div>
            <div class="flex-1 max-w-xs mx-auto">
                <canvas id="pieChart" width="400" height="400" class="w-full h-auto"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabla de niveles -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-800 text-white text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-center">Nivel</th>
                        <th class="px-4 py-3 text-center">Porcentaje</th>
                        <th class="px-4 py-3 text-center">Frecuencia</th>
                        <th class="px-4 py-3 text-center">Color</th>
                    </tr>
                </thead>
                <tbody id="probTbody" class="divide-y divide-gray-200 bg-white">
                    <tr><td colspan="4" class="text-center py-8 text-gray-500">Cargando niveles...</span><span class="sd-tag">    </span></td>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Matriz de calor mejorada -->
    <div class="mt-8 bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Matriz de Riesgo (Heatmap)</h2>
        <div class="overflow-x-auto">
            <div class="grid min-w-[600px]" style="grid-template-columns:100px repeat(5,1fr); gap:2px; background:#e5e7eb; border-radius:8px; overflow:hidden;">
                <div class="bg-gray-200 p-2 text-center font-semibold text-gray-700">Probabilidad / Impacto</div>
                <div class="bg-gray-200 p-2 text-center font-semibold text-gray-700">Leve (20%)</div>
                <div class="bg-gray-200 p-2 text-center font-semibold text-gray-700">Menor (40%)</div>
                <div class="bg-gray-200 p-2 text-center font-semibold text-gray-700">Moderado (60%)</div>
                <div class="bg-gray-200 p-2 text-center font-semibold text-gray-700">Mayor (80%)</div>
                <div class="bg-gray-200 p-2 text-center font-semibold text-gray-700">Catastrófico (100%)</div>

                <div class="bg-gray-100 p-2 text-center font-medium">Muy Alta (100%)</div>
                <div class="bg-yellow-200 p-2 text-center">Moderado</div>
                <div class="bg-orange-200 p-2 text-center">Alto</div>
                <div class="bg-orange-300 p-2 text-center">Alto</div>
                <div class="bg-red-200 p-2 text-center">Extremo</div>
                <div class="bg-red-300 p-2 text-center">Extremo</div>

                <div class="bg-gray-100 p-2 text-center font-medium">Alta (80%)</div>
                <div class="bg-green-200 p-2 text-center">Bajo</div>
                <div class="bg-yellow-200 p-2 text-center">Moderado</div>
                <div class="bg-orange-200 p-2 text-center">Alto</div>
                <div class="bg-orange-300 p-2 text-center">Alto</div>
                <div class="bg-red-200 p-2 text-center">Extremo</div>

                <div class="bg-gray-100 p-2 text-center font-medium">Media (60%)</div>
                <div class="bg-green-200 p-2 text-center">Bajo</div>
                <div class="bg-green-300 p-2 text-center">Bajo</div>
                <div class="bg-yellow-200 p-2 text-center">Moderado</div>
                <div class="bg-orange-200 p-2 text-center">Alto</div>
                <div class="bg-orange-300 p-2 text-center">Alto</div>

                <div class="bg-gray-100 p-2 text-center font-medium">Baja (40%)</div>
                <div class="bg-green-200 p-2 text-center">Bajo</div>
                <div class="bg-green-200 p-2 text-center">Bajo</div>
                <div class="bg-green-300 p-2 text-center">Bajo</div>
                <div class="bg-yellow-200 p-2 text-center">Moderado</div>
                <div class="bg-orange-200 p-2 text-center">Alto</div>

                <div class="bg-gray-100 p-2 text-center font-medium">Muy Baja (20%)</div>
                <div class="bg-green-200 p-2 text-center">Bajo</div>
                <div class="bg-green-200 p-2 text-center">Bajo</div>
                <div class="bg-green-200 p-2 text-center">Bajo</div>
                <div class="bg-green-300 p-2 text-center">Bajo</div>
                <div class="bg-yellow-200 p-2 text-center">Moderado</div>
            </div>
        </div>
        <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-600 justify-end">
            <span><span class="inline-block w-4 h-4 bg-green-200 mr-1"></span> Bajo</span>
            <span><span class="inline-block w-4 h-4 bg-yellow-200 mr-1"></span> Moderado</span>
            <span><span class="inline-block w-4 h-4 bg-orange-200 mr-1"></span> Alto</span>
            <span><span class="inline-block w-4 h-4 bg-red-200 mr-1"></span> Extremo</span>
        </div>
    </div>
</div>

<!-- Modal mejorado para editar probabilidades -->
<div id="probModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Editar niveles de probabilidad</h3>
            <button onclick="closeProbModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="probForm" class="p-6 space-y-4">
            <div id="probList" class="space-y-3"></div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeProbModal()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium shadow-sm transition">Guardar cambios</button>
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

const assistantMessages = {
    initial: "Bienvenido a Criterios de Probabilidad. Este módulo define los niveles de probabilidad utilizados en toda la plataforma de gestión de riesgos. Cada nivel tiene un porcentaje (20% a 100%), una frecuencia asociada y un color distintivo para facilitar su identificación visual.",
    'que-es': "Los criterios de probabilidad cuantifican la posibilidad de que un riesgo se materialice. Van desde Muy Baja (20%) hasta Muy Alta (100%). Estos criterios se utilizan en conjunto con los impactos para calcular el nivel de riesgo en la Matriz de Calor.",
    niveles: "Niveles disponibles: Muy Baja (20% - Rara vez), Baja (40% - Improbable), Media (60% - Posible), Alta (80% - Probable), Muy Alta (100% - Casi cierto). Cada nivel tiene un color asociado y una frecuencia que describe su ocurrencia esperada.",
    matriz: "Estos criterios se combinan con los niveles de impacto en la Matriz de Riesgo. Por ejemplo: Probabilidad Muy Alta (100%) + Impacto Catastrófico (100%) = Riesgo Extremo. La matriz de calor visualiza estas combinaciones con colores que indican la criticidad."
};

window.showAssistantTip = function(tipo) {
    let message = assistantMessages[tipo] || assistantMessages.initial;
    setTypingMessage(message);
};

window.resetAssistantMessage = function() {
    setTypingMessage(assistantMessages.initial);
};

const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
let pieChartInstance = null;

async function loadProbabilities() {
    const res = await fetch('/api/risk/probabilities');
    const data = await res.json();
    const tbody = document.getElementById('probTbody');
    if (data.success && data.data.length) {
        tbody.innerHTML = data.data.map(p => `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 border-b text-center font-medium">${escapeHtml(p.nivel)}</span><span class="sd-tag">    </span></td>
                <td class="px-4 py-3 border-b text-center">${p.porcentaje}%</span><span class="sd-tag">    </span></td>
                <td class="px-4 py-3 border-b">${escapeHtml(p.frecuencia)}</span><span class="sd-tag">    </span></td>
                <td class="px-4 py-3 border-b"><div style="width:30px;height:20px;background:${p.color}; border-radius:4px;"></div></span><span class="sd-tag">    </span></td>
            </tr>
        `).join('');
        
        updatePieChart(data.data);
    } else {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center py-8 text-gray-500">No hay niveles configurados</span><span class="sd-tag">    </span></tr>';
        updatePieChart([]);
    }
}

function updatePieChart(probabilities) {
    if (pieChartInstance) {
        pieChartInstance.destroy();
    }
    
    if (!probabilities || probabilities.length === 0) {
        const ctx = document.getElementById('pieChart').getContext('2d');
        ctx.clearRect(0, 0, 400, 400);
        ctx.font = '14px Arial';
        ctx.fillStyle = '#9ca3af';
        ctx.textAlign = 'center';
        ctx.fillText('No hay datos', 200, 200);
        document.getElementById('pieChartLegend').innerHTML = '<span class="text-gray-400 text-sm">No hay niveles registrados</span>';
        return;
    }
    
    const labels = probabilities.map(p => p.nivel);
    const dataValues = probabilities.map(p => p.porcentaje);
    const backgroundColors = probabilities.map(p => p.color);
    
    const ctx = document.getElementById('pieChart').getContext('2d');
    pieChartInstance = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: dataValues,
                backgroundColor: backgroundColors,
                borderColor: '#ffffff',
                borderWidth: 2,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percent = total ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value}% (${percent}% del total)`;
                        }
                    }
                }
            }
        }
    });
    
    const legendDiv = document.getElementById('pieChartLegend');
    let legendHtml = '';
    for (let i = 0; i < labels.length; i++) {
        legendHtml += `
            <div class="flex items-center gap-2 px-3 py-1 bg-gray-100 rounded-full">
                <span class="w-3 h-3 rounded-full" style="background-color: ${backgroundColors[i]}"></span>
                <span class="text-sm font-medium">${escapeHtml(labels[i])}</span>
                <span class="text-xs text-gray-500">${dataValues[i]}%</span>
            </div>
        `;
    }
    legendDiv.innerHTML = legendHtml;
}

window.openProbModal = async () => {
    const res = await fetch('/api/risk/probabilities');
    const data = await res.json();
    const container = document.getElementById('probList');
    container.innerHTML = '';
    if (data.success && data.data.length) {
        data.data.forEach((p, idx) => {
            const div = document.createElement('div');
            div.className = 'grid grid-cols-1 md:grid-cols-5 gap-3 items-center border-b border-gray-100 pb-3 mb-2';
            div.innerHTML = `
                <input type="text" name="nivel_${idx}" value="${escapeHtml(p.nivel)}" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nivel" required>
                <input type="number" name="porcentaje_${idx}" value="${p.porcentaje}" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="%" required>
                <input type="text" name="frecuencia_${idx}" value="${escapeHtml(p.frecuencia)}" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Frecuencia">
                <input type="color" name="color_${idx}" value="${p.color}" class="h-10 w-full border border-gray-300 rounded-lg cursor-pointer">
                <input type="hidden" name="id_${idx}" value="${p.id}">
            `;
            container.appendChild(div);
        });
    }
    document.getElementById('probModal').classList.remove('hidden');
    document.getElementById('probModal').classList.add('flex');
};

window.closeProbModal = () => {
    document.getElementById('probModal').classList.add('hidden');
    document.getElementById('probModal').classList.remove('flex');
};

document.getElementById('probForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new URLSearchParams();
    formData.append('csrf_token', csrf);
    
    const items = document.querySelectorAll('#probList > div');
    const probabilities = [];
    items.forEach(div => {
        const idInput = div.querySelector('input[name^="id_"]');
        const nivelInput = div.querySelector('input[name^="nivel_"]');
        const porcentajeInput = div.querySelector('input[name^="porcentaje_"]');
        const frecuenciaInput = div.querySelector('input[name^="frecuencia_"]');
        const colorInput = div.querySelector('input[name^="color_"]');
        if (idInput && nivelInput && porcentajeInput) {
            probabilities.push({
                id: idInput.value,
                nivel: nivelInput.value,
                porcentaje: parseInt(porcentajeInput.value),
                frecuencia: frecuenciaInput ? frecuenciaInput.value : '',
                color: colorInput ? colorInput.value : '#000000'
            });
        }
    });
    
    formData.append('probabilities', JSON.stringify(probabilities));
    const response = await fetch('/api/risk/probabilities/bulk', { 
        method: 'POST', 
        body: formData, 
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' } 
    });
    const result = await response.json();
    if (result.success) {
        alert('Niveles de probabilidad actualizados correctamente');
        closeProbModal();
        loadProbabilities();
    } else {
        alert('Error: ' + (result.error || 'No se pudieron guardar los cambios'));
    }
});

setTimeout(() => {
    setTypingMessage(assistantMessages.initial);
}, 500);

loadProbabilities();

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m]));
}
</script>
