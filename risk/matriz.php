<div class="container mx-auto px-4 py-6">
    <!-- ASISTENTE INTELIGENTE - OPTIMIX RISK (Estilo sobrio y profesional) -->
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
                    
                    <!-- Área de mensaje con animación de escritura -->
                    <div class="min-h-[80px]">
                        <p id="assistantMessage" class="text-sm text-gray-600 leading-relaxed">
                            <span id="typingText"></span>
                            <span id="typingCursor" class="inline-block w-0.5 h-4 bg-gray-400 ml-0.5 animate-pulse" style="vertical-align: middle;"></span>
                        </p>
                    </div>
                    
                    <!-- Botones de ayuda -->
                    <div class="flex gap-2 mt-3">
                        <button onclick="showAssistantTip('crear')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📝 Crear riesgo</button>
                        <button onclick="showAssistantTip('evaluar')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📊 Evaluar zonas</button>
                        <button onclick="showAssistantTip('seguir')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">✅ Dar seguimiento</button>
                        <button onclick="resetAssistantMessage()" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">⟳ Reiniciar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Matriz de Riesgos</h1>
        <button onclick="riskOpenMatrizModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md flex items-center gap-2 transition duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Nuevo Riesgo
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-800 text-white text-xs uppercase tracking-wider">
                        <th rowspan="2" class="px-3 py-3 border-b border-gray-700 text-center">Proceso</th>
                        <th colspan="5" class="px-3 py-3 border-b border-gray-700 text-center">Identificación del Riesgo</th>
                        <th colspan="3" class="px-3 py-3 border-b border-gray-700 text-center">Análisis</th>
                        <th colspan="3" class="px-3 py-3 border-b border-gray-700 text-center">Evaluación</th>
                        <th colspan="5" class="px-3 py-3 border-b border-gray-700 text-center">Plan de Acción</th>
                        <th rowspan="2" class="px-3 py-3 border-b border-gray-700 text-center">Seguimiento</th>
                        <th rowspan="2" class="px-3 py-3 border-b border-gray-700 text-center">Acciones</th>
                    </tr>
                    <tr class="bg-gray-700 text-white text-xs">
                        <th class="px-2 py-2">Tipo</th>
                        <th class="px-2 py-2">Descripción</th>
                        <th class="px-2 py-2">Causa Raíz</th>
                        <th class="px-2 py-2">Clasificación</th>
                        <th class="px-2 py-2">Consecuencias</th>
                        <th class="px-2 py-2">Probabilidad</th>
                        <th class="px-2 py-2">Impacto</th>
                        <th class="px-2 py-2">Zona Inherente</th>
                        <th class="px-2 py-2">Controles</th>
                        <th class="px-2 py-2">Prob. Residual</th>
                        <th class="px-2 py-2">Impacto Residual</th>
                        <th class="px-2 py-2">Zona Residual</th>
                        <th class="px-2 py-2">Actividades</th>
                        <th class="px-2 py-2">Responsables</th>
                        <th class="px-2 py-2">F. Implementación</th>
                        <th class="px-2 py-2">F. Seguimiento</th>
                    </tr>
                </thead>
                <tbody id="matrizTbody" class="divide-y divide-gray-200 bg-white">
                    <tr><td colspan="20" class="text-center py-8 text-gray-500">Cargando riesgos...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal (sin cambios) -->
<div id="riskMatrizModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800" id="matrizModalTitle">Editor de Riesgo</h3>
            <button onclick="riskCloseMatrizModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="matrizForm" class="p-6 space-y-4">
            <input type="hidden" name="csrf_token" id="csrf_matriz" value="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Proceso *</label>
                    <input type="text" id="proceso" name="proceso" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo</label>
                    <select id="tipo" name="tipo" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="Riesgo">Riesgo</option>
                        <option value="Oportunidad">Oportunidad</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción del Riesgo *</label>
                    <textarea id="descripcion" name="descripcion" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2" required></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Causa Raíz *</label>
                    <textarea id="causaRaiz" name="causaRaiz" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2" required></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Consecuencias</label>
                    <textarea id="consecuencias" name="consecuencias" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Clasificación *</label>
                    <select id="clasificacion" name="clasificacion" class="w-full border border-gray-300 rounded-lg px-3 py-2" required></select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Probabilidad *</label>
                    <select id="probabilidad" name="probabilidad" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Seleccionar</option>
                        <option>Muy Baja. Probabilidad 20%</option>
                        <option>Baja. Probabilidad 40%</option>
                        <option>Media. Probabilidad 60%</option>
                        <option>Alta. Probabilidad 80%</option>
                        <option>Muy Alta. Probabilidad 100%</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Impacto *</label>
                    <select id="impacto" name="impacto" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Seleccionar</option>
                        <option>Leve. Impacto 20%</option>
                        <option>Menor. Impacto 40%</option>
                        <option>Moderado. Impacto 60%</option>
                        <option>Mayor. Impacto 80%</option>
                        <option>Catastrófico. Impacto 100%</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Zona Inherente *</label>
                    <select id="zonaInherente" name="zonaInherente" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option>Bajo</option><option>Moderado</option><option>Alto</option><option>Extremo</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Controles</label>
                    <textarea id="controles" name="controles" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Probabilidad Residual *</label>
                    <select id="probabilidadResidual" name="probabilidadResidual" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option>Muy Baja. Probabilidad 20%</option>
                        <option>Baja. Probabilidad 40%</option>
                        <option>Media. Probabilidad 60%</option>
                        <option>Alta. Probabilidad 80%</option>
                        <option>Muy Alta. Probabilidad 100%</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Impacto Residual *</label>
                    <select id="impactoResidual" name="impactoResidual" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option>Leve. Impacto 20%</option>
                        <option>Menor. Impacto 40%</option>
                        <option>Moderado. Impacto 60%</option>
                        <option>Mayor. Impacto 80%</option>
                        <option>Catastrófico. Impacto 100%</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Zona Residual *</label>
                    <select id="zonaResidual" name="zonaResidual" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option>Bajo</option><option>Moderado</option><option>Alto</option><option>Extremo</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Actividades del Plan</label>
                    <textarea id="actividades" name="actividades" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Responsables</label>
                    <input type="text" id="responsables" name="responsables" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Implementación</label>
                    <input type="date" id="fechaImplementacion" name="fechaImplementacion" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Seguimiento</label>
                    <input type="date" id="fechaSeguimiento" name="fechaSeguimiento" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Seguimiento y Revisión</label>
                    <textarea id="seguimiento" name="seguimiento" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="riskCloseMatrizModal()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium shadow-sm transition">Guardar</button>
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
        // Ocultar cursor cuando termina
        const cursor = document.getElementById('typingCursor');
        if (cursor) cursor.style.opacity = '0';
    }
}

function setTypingMessage(message) {
    // Limpiar intervalo anterior
    if (typingInterval) clearTimeout(typingInterval);
    
    // Reiniciar
    currentFullMessage = message;
    currentCharIndex = 0;
    const typingSpan = document.getElementById('typingText');
    const cursor = document.getElementById('typingCursor');
    
    if (typingSpan) typingSpan.textContent = '';
    if (cursor) cursor.style.opacity = '1';
    
    // Iniciar escritura
    typeWriter();
}

// Mensajes del asistente
const assistantMessages = {
    initial: "Buenos días. Soy Optimix Risk, asistente para gestión de riesgos. Este módulo permite identificar, analizar, evaluar y dar seguimiento a los riesgos organizacionales. Utilice los botones inferiores para obtener ayuda específica.",
    crear: "Para crear un riesgo, presione 'Nuevo Riesgo' complete el formulario. Los campos con (*) son obligatorios. La zona inherente se calcula automáticamente según la combinación de probabilidad e impacto. Recomiendo definir controles efectivos para reducir la zona residual.",
    evaluar: "La evaluación de riesgos compara la zona inherente (sin controles) versus la zona residual (con controles). El objetivo estratégico es mitigar los riesgos hasta zonas BAJO o MODERADO. Los códigos de color facilitan la identificación de criticidad.",
    seguir: "El seguimiento requiere fechas periódicas de revisión. Documente en 'Seguimiento y Revisión' los avances, cambios en controles o variaciones en probabilidad/impacto. Se recomienda auditoría trimestral para riesgos críticos."
};

// Mostrar tip con animación
window.showAssistantTip = function(tipo) {
    let message = '';
    if (tipo === 'crear') message = assistantMessages.crear;
    else if (tipo === 'evaluar') message = assistantMessages.evaluar;
    else if (tipo === 'seguir') message = assistantMessages.seguir;
    
    if (message) setTypingMessage(message);
};

window.resetAssistantMessage = function() {
    setTypingMessage(assistantMessages.initial);
};

// Función para calcular zona automáticamente
function calculateZone(probabilidad, impacto) {
    const probValues = {
        'Muy Baja. Probabilidad 20%': 20,
        'Baja. Probabilidad 40%': 40,
        'Media. Probabilidad 60%': 60,
        'Alta. Probabilidad 80%': 80,
        'Muy Alta. Probabilidad 100%': 100
    };
    const impactValues = {
        'Leve. Impacto 20%': 20,
        'Menor. Impacto 40%': 40,
        'Moderado. Impacto 60%': 60,
        'Mayor. Impacto 80%': 80,
        'Catastrófico. Impacto 100%': 100
    };
    
    const p = probValues[probabilidad] || 0;
    const i = impactValues[impacto] || 0;
    const score = (p * i) / 100;
    
    if (score >= 70) return 'Extremo';
    if (score >= 50) return 'Alto';
    if (score >= 25) return 'Moderado';
    return 'Bajo';
}

(function() {
    document.addEventListener('DOMContentLoaded', () => {
        // Iniciar mensaje de bienvenida con animación
        setTimeout(() => {
            setTypingMessage(assistantMessages.initial);
        }, 500);
        
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        window.CSRF_TOKEN = csrf;
        const modal = document.getElementById('riskMatrizModal');
        const form = document.getElementById('matrizForm');
        let currentId = null;

        // Auto-cálculo de zonas
        const probSelect = document.getElementById('probabilidad');
        const impactSelect = document.getElementById('impacto');
        const zonaInherente = document.getElementById('zonaInherente');
        
        const probResSelect = document.getElementById('probabilidadResidual');
        const impactResSelect = document.getElementById('impactoResidual');
        const zonaResidual = document.getElementById('zonaResidual');
        
        function updateZones() {
            if (probSelect && impactSelect && zonaInherente && probSelect.value && impactSelect.value) {
                zonaInherente.value = calculateZone(probSelect.value, impactSelect.value);
            }
            if (probResSelect && impactResSelect && zonaResidual && probResSelect.value && impactResSelect.value) {
                zonaResidual.value = calculateZone(probResSelect.value, impactResSelect.value);
            }
        }
        
        if (probSelect && impactSelect) {
            probSelect.addEventListener('change', updateZones);
            impactSelect.addEventListener('change', updateZones);
        }
        if (probResSelect && impactResSelect) {
            probResSelect.addEventListener('change', updateZones);
            impactResSelect.addEventListener('change', updateZones);
        }

        async function loadClasificaciones() {
            const resp = await fetch('/api/risk/classifications');
            const data = await resp.json();
            const select = document.getElementById('clasificacion');
            if (data.success) {
                select.innerHTML = '<option value="">Seleccionar</option>' + data.data.map(c => `<option value="${escapeHtml(c.categoria)}">${escapeHtml(c.categoria)}</option>`).join('');
            }
        }

        function formatDateForInput(dateStr) {
            if (!dateStr) return '';
            if (typeof dateStr === 'string' && dateStr.includes('/')) {
                const parts = dateStr.split('/');
                if (parts.length === 3) return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return dateStr;
        }

        async function loadMatriz() {
            const tbody = document.getElementById('matrizTbody');
            tbody.innerHTML = '<tr><td colspan="20" class="text-center py-8 text-gray-500">Cargando riesgos...</td></tr>';
            const resp = await fetch('/api/risk/matrix');
            const data = await resp.json();
            if (data.success && data.data.length) {
                tbody.innerHTML = data.data.map(row => `
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-2 py-2 border-b text-center font-medium">${escapeHtml(row.proceso)}</td>
                    <td class="px-2 py-2 border-b text-center">${escapeHtml(row.tipo)}</td>
                    <td class="px-2 py-2 border-b">${escapeHtml(row.descripcion)}</td>
                    <td class="px-2 py-2 border-b">${escapeHtml(row.causa_raiz)}</td>
                    <td class="px-2 py-2 border-b">${escapeHtml(row.clasificacion)}</td>
                    <td class="px-2 py-2 border-b">${escapeHtml(row.consecuencias)}</td>
                    <td class="px-2 py-2 border-b">${escapeHtml(row.probabilidad)}</td>
                    <td class="px-2 py-2 border-b">${escapeHtml(row.impacto)}</td>
                    <td class="px-2 py-2 border-b font-semibold text-center ${getZoneClass(row.zona_inherente)}">${escapeHtml(row.zona_inherente)}</td>
                    <td class="px-2 py-2 border-b">${escapeHtml(row.controles)}</td>
                    <td class="px-2 py-2 border-b">${escapeHtml(row.probabilidad_residual)}</td>
                    <td class="px-2 py-2 border-b">${escapeHtml(row.impacto_residual)}</td>
                    <td class="px-2 py-2 border-b font-semibold text-center ${getZoneClass(row.zona_residual)}">${escapeHtml(row.zona_residual)}</td>
                    <td class="px-2 py-2 border-b">${escapeHtml(row.actividades)}</td>
                    <td class="px-2 py-2 border-b">${escapeHtml(row.responsables)}</td>
                    <td class="px-2 py-2 border-b text-center">${row.fecha_implementacion || '-'}</td>
                    <td class="px-2 py-2 border-b text-center">${row.fecha_seguimiento || '-'}</td>
                    <td class="px-2 py-2 border-b">${escapeHtml(row.seguimiento)}</td>
                    <td class="px-2 py-2 border-b text-center whitespace-nowrap">
                        <button onclick="openEditMatriz(${row.id})" class="text-indigo-600 hover:text-indigo-800 mx-1" title="Editar"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                        <button onclick="deleteMatriz(${row.id})" class="text-red-600 hover:text-red-800 mx-1" title="Eliminar"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                    </td>
                </tr>`).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="20" class="text-center py-8 text-gray-500">No hay riesgos registrados</td></tr>';
            }
        }

        function getZoneClass(zone) {
            switch(zone) {
                case 'Extremo': return 'bg-red-100 text-red-800';
                case 'Alto': return 'bg-orange-100 text-orange-800';
                case 'Moderado': return 'bg-yellow-100 text-yellow-800';
                case 'Bajo': return 'bg-green-100 text-green-800';
                default: return '';
            }
        }

        window.riskOpenMatrizModal = () => {
            currentId = null;
            form.reset();
            document.getElementById('csrf_matriz').value = csrf;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            loadClasificaciones();
        };

        window.riskCloseMatrizModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            currentId = null;
        };

        window.openEditMatriz = async (id) => {
            await loadClasificaciones();
            const resp = await fetch(`/api/risk/matrix/${id}`);
            const data = await resp.json();
            if (data.success) {
                const r = data.data;
                currentId = id;
                document.getElementById('proceso').value = r.proceso || '';
                document.getElementById('tipo').value = r.tipo || 'Riesgo';
                document.getElementById('descripcion').value = r.descripcion || '';
                document.getElementById('causaRaiz').value = r.causa_raiz || '';
                document.getElementById('consecuencias').value = r.consecuencias || '';
                document.getElementById('clasificacion').value = r.clasificacion || '';
                document.getElementById('probabilidad').value = r.probabilidad || '';
                document.getElementById('impacto').value = r.impacto || '';
                document.getElementById('zonaInherente').value = r.zona_inherente || '';
                document.getElementById('controles').value = r.controles || '';
                document.getElementById('probabilidadResidual').value = r.probabilidad_residual || '';
                document.getElementById('impactoResidual').value = r.impacto_residual || '';
                document.getElementById('zonaResidual').value = r.zona_residual || '';
                document.getElementById('actividades').value = r.actividades || '';
                document.getElementById('responsables').value = r.responsables || '';
                document.getElementById('fechaImplementacion').value = formatDateForInput(r.fecha_implementacion);
                document.getElementById('fechaSeguimiento').value = formatDateForInput(r.fecha_seguimiento);
                document.getElementById('seguimiento').value = r.seguimiento || '';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                alert('Error al cargar los datos del riesgo');
            }
        };

        window.deleteMatriz = async (id) => {
            if (!confirm('¿Eliminar este riesgo permanentemente?')) return;
            const formData = new URLSearchParams();
            formData.append('csrf_token', csrf);
            try {
                const response = await fetch(`/api/risk/matrix/${id}/delete`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    alert('Riesgo eliminado correctamente');
                    loadMatriz();
                } else {
                    alert('Error: ' + (result.error || 'No se pudo eliminar'));
                }
            } catch (err) {
                console.error(err);
                alert('Error de conexión: ' + err.message);
            }
        };

        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                if (!document.getElementById('proceso').value.trim()) {
                    alert('El campo "Proceso" es obligatorio');
                    return;
                }
                if (!document.getElementById('descripcion').value.trim()) {
                    alert('La descripción del riesgo es obligatoria');
                    return;
                }
                if (!document.getElementById('causaRaiz').value.trim()) {
                    alert('La causa raíz es obligatoria');
                    return;
                }
                if (!document.getElementById('clasificacion').value) {
                    alert('Debe seleccionar una clasificación');
                    return;
                }

                const formData = new URLSearchParams();
                formData.append('csrf_token', csrf);
                for (let el of form.elements) {
                    if (el.name && el.value) {
                        formData.append(el.name, el.value);
                    }
                }

                let url = '/api/risk/matrix';
                if (currentId) {
                    url = `/api/risk/matrix/${currentId}/update`;
                }

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: formData
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert(currentId ? 'Riesgo actualizado correctamente' : 'Riesgo creado correctamente');
                        riskCloseMatrizModal();
                        loadMatriz();
                    } else {
                        alert('Error: ' + (result.error || 'No se pudo guardar'));
                    }
                } catch (err) {
                    console.error(err);
                    alert('Error de conexión: ' + err.message);
                }
            });
        }

        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) riskCloseMatrizModal();
            });
        }

        loadMatriz();
    });

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m]));
    }
})();
</script>
