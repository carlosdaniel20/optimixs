<?php
/** @var array $stats */
use App\Support\ConfigStore;
$isCatalogueMode = ConfigStore::isCatalogueMode();
?>
<!-- Dashboard de Riesgos -->
<div class="min-h-screen bg-gray-50 py-6">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- ASISTENTE INTELIGENTE - OPTIMIXS RISK -->
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
                        <h2 class="text-base font-semibold text-gray-800">Optimixs Risk</h2>
                        <span class="px-1.5 py-0.5 bg-green-100 text-green-700 text-[10px] rounded-full font-medium" id="assistantStatus">En línea</span>
                    </div>
                    <div class="min-h-[80px]">
                        <p id="assistantMessage" class="text-sm text-gray-600 leading-relaxed">
                            <span id="typingText"></span>
                            <span id="typingCursor" class="inline-block w-0.5 h-4 bg-gray-400 ml-0.5 animate-pulse" style="vertical-align: middle;"></span>
                        </p>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-comment-dots text-gray-400 text-sm"></i>
                            <span class="text-xs text-gray-500">Pregúntame algo sobre el sistema:</span>
                        </div>
                        <div class="flex gap-2 mt-2 flex-wrap">
                            <button onclick="askQuestion('modulos')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📦 ¿Qué módulos tiene Optimix Risk?</button>
                            <button onclick="askQuestion('organizacion')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">🏢 ¿Cómo gestiono organizaciones?</button>
                            <button onclick="askQuestion('checklist')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📋 ¿Qué es un checklist?</button>
                            <button onclick="askQuestion('reporte')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📄 ¿Cómo genero un reporte?</button>
                            <button onclick="openQuestionModal()" class="text-xs px-2.5 py-1 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded transition">💬 Hacer otra pregunta</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para preguntas -->
    <div id="questionModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Pregunta a Optimixs Risk</h3>
                <button onclick="closeQuestionModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Selecciona una pregunta:</label>
                <div class="space-y-2">
                    <button onclick="selectPredefinedQuestion('clasificacion')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-indigo-50 rounded-lg transition text-sm flex items-center gap-2">
                        <i class="fas fa-tags text-indigo-500"></i> <span>¿Cómo se crea una clasificación de riesgo?</span>
                    </button>
                    <button onclick="selectPredefinedQuestion('matriz')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-indigo-50 rounded-lg transition text-sm flex items-center gap-2">
                        <i class="fas fa-th text-indigo-500"></i> <span>¿Qué información contiene la Matriz de Riesgos?</span>
                    </button>
                    <button onclick="selectPredefinedQuestion('impactos')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-indigo-50 rounded-lg transition text-sm flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-orange-500"></i> <span>¿Cuáles son los niveles de impacto disponibles?</span>
                    </button>
                    <button onclick="selectPredefinedQuestion('criterios')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-indigo-50 rounded-lg transition text-sm flex items-center gap-2">
                        <i class="fas fa-sliders-h text-purple-500"></i> <span>¿Qué son los criterios de probabilidad?</span>
                    </button>
                    <button onclick="selectPredefinedQuestion('dashboard')" class="w-full text-left px-3 py-2 bg-gray-50 hover:bg-indigo-50 rounded-lg transition text-sm flex items-center gap-2">
                        <i class="fas fa-chart-pie text-green-500"></i> <span>¿Qué métricas muestra el Dashboard?</span>
                    </button>
                </div>
            </div>
            <div class="relative my-3">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-300"></div></div>
                <div class="relative flex justify-center text-xs"><span class="px-2 bg-white text-gray-500">O escribe tu pregunta</span></div>
            </div>
            <div class="mb-4">
                <textarea id="userQuestion" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Ej: ¿Cómo se calcula el nivel de riesgo?"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button onclick="closeQuestionModal()" class="px-4 py-2 bg-gray-200 rounded-lg">Cancelar</button>
                <button onclick="askCustomQuestion()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Preguntar</button>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8 fade-in">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-tachometer-alt text-gray-600 mr-3"></i>
            <?= __("Dashboard de Riesgos") ?>
        </h1>
        <p class="text-sm text-gray-600 mt-2"><?= __("Panorama general del sistema de gestión de riesgos") ?></p>
    </div>

    <!-- Risk Stats Cards - CORREGIDO -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8" id="riskStatsContainer">
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mb-3">
                <i class="fas fa-spinner fa-spin text-xl"></i>
            </div>
            <p class="text-2xl font-bold text-gray-300">--</p>
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Cargando...</p>
        </div>
    </div>

    <!-- Mensaje de bienvenida y botones -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-200 p-6 text-center mb-8">
        <i class="fas fa-chart-pie text-indigo-500 text-4xl mb-3"></i>
        <h2 class="text-xl font-semibold text-gray-800 mb-2"><?= __("Bienvenido a Optimixs Risk") ?></h2>
        <p class="text-gray-600"><?= __("Sistema Integral de Gestión de Riesgos Empresariales. A continuación, accede a cada módulo:") ?></p>
        <div class="mt-4 flex flex-wrap gap-3 justify-center">
            <a href="/risk/clasificacion" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"><i class="fas fa-tags mr-2"></i> Clasificación</a>
            <a href="/risk/matriz" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"><i class="fas fa-th mr-2"></i> Matriz</a>
            <a href="/risk/organizacion" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"><i class="fas fa-building mr-2"></i> Organizaciones</a>
            <a href="/risk/impactos" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"><i class="fas fa-exclamation-triangle mr-2"></i> Impactos</a>
            <a href="/risk/criterios" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"><i class="fas fa-sliders-h mr-2"></i> Criterios</a>
            <a href="/risk/reporte" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"><i class="fas fa-file-alt mr-2"></i> Reporte</a>
        </div>
    </div>

    <!-- GRÁFICO ANIMADO -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6 fade-in">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-chart-simple text-indigo-500 mr-2"></i>
                    <?= __("Distribución de Riesgos") ?>
                </h3>
                <p class="text-gray-500 text-sm"><?= __("Cantidad de registros por categoría en el sistema.") ?></p>
                <div class="mt-4 flex flex-wrap gap-2 justify-center md:justify-start">
                    <div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-500"></span><span class="text-xs">Clasificación</span></div>
                    <div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-indigo-500"></span><span class="text-xs">Matriz</span></div>
                    <div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500"></span><span class="text-xs">Organizaciones</span></div>
                    <div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-orange-500"></span><span class="text-xs">Impactos</span></div>
                    <div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-purple-500"></span><span class="text-xs">Criterios</span></div>
                    <div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-rose-500"></span><span class="text-xs">Reportes</span></div>
                </div>
            </div>
            <div class="flex-1 max-w-sm mx-auto">
                <canvas id="riskChart" width="400" height="400" class="w-full h-auto"></canvas>
            </div>
        </div>
    </div>

    <!-- 📅 CALENDARIO + PANEL DE TAREAS CON ASISTENTE -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-8 fade-in">
        <div class="mb-4">
            <h3 class="text-xl font-bold text-gray-800">
                <i class="fas fa-calendar-alt text-indigo-500 mr-2"></i>
                <?= __("Calendario de Tareas Programadas") ?>
            </h3>
            <p class="text-gray-500 text-sm"><?= __("Las fechas resaltadas tienen tareas de inspección programadas") ?></p>
        </div>
        
        <!-- Grid de 2 columnas: calendario + asistente -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna del calendario (ocupa 2/3) -->
            <div class="lg:col-span-2">
                <div id="taskCalendar"></div>
            </div>
            
            <!-- Columna del asistente (ocupa 1/3) -->
            <div class="lg:col-span-1">
                <div class="bg-gray-50 rounded-xl shadow-sm border border-gray-200 overflow-hidden h-full">
                    <div class="px-4 py-3">
                        <div class="flex items-start gap-2">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-robot text-gray-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="text-sm font-semibold text-gray-800">Optimixs Risk Assistant</h4>
                                    <span class="px-1.5 py-0.5 bg-green-100 text-green-700 text-[9px] rounded-full font-medium" id="calendarAssistantStatus">En línea</span>
                                </div>
                                <div class="min-h-[250px] max-h-[350px] overflow-y-auto">
                                    <p id="calendarAssistantMessage" class="text-xs text-gray-600 leading-relaxed whitespace-pre-wrap">
                                        <span id="calendarTypingText"></span>
                                        <span id="calendarTypingCursor" class="inline-block w-0.5 h-3 bg-gray-400 ml-0.5 animate-pulse" style="vertical-align: middle;"></span>
                                    </p>
                                </div>
                                <div class="mt-2 pt-2 border-t border-gray-200">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-calendar-day text-gray-400 text-xs"></i>
                                        <span class="text-[10px] text-gray-500" id="selectedDateBadge">Ninguna fecha seleccionada</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <div class="flex justify-center gap-6 flex-wrap text-xs">
                <span class="inline-flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-yellow-200 border border-yellow-400"></span> Días con tareas</span>
                <span class="inline-flex items-center gap-2"><i class="fas fa-mouse-pointer text-gray-400"></i> Haz clic en una fecha</span>
            </div>
        </div>
    </div>

  </div>
</div>

<style>
@keyframes blink { 0%, 50% { opacity: 1; } 51%, 100% { opacity: 0; } }
.animate-pulse { animation: blink 1s step-end infinite; }
.fade-in { animation: fadeIn 0.6s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* Estilos Flatpickr en español */
.flatpickr-calendar {
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    width: 100% !important;
    font-family: inherit;
    border: 1px solid #e5e7eb;
}
.flatpickr-month {
    height: 60px;
    background: #f8fafc;
    border-radius: 16px 16px 0 0;
}
.flatpickr-monthDropdown-months, .flatpickr-year-select {
    font-weight: 600;
    color: #1f2937;
}
.flatpickr-weekday {
    font-weight: 600;
    color: #4f46e5;
    font-size: 0.85rem;
    text-transform: uppercase;
}
.flatpickr-day.selected {
    background-color: #4f46e5 !important;
    border-color: #4f46e5 !important;
    color: white !important;
}
.flatpickr-day.today {
    border-color: #4f46e5 !important;
    background-color: #e0e7ff !important;
    color: #4f46e5 !important;
}
.flatpickr-day.has-task {
    background-color: #fef3c7 !important;
    color: #92400e !important;
    font-weight: bold !important;
    border-radius: 50% !important;
    position: relative;
}
.flatpickr-day.has-task:hover {
    background-color: #fde68a !important;
    transform: scale(1.02);
}
.flatpickr-day.has-task.selected {
    background-color: #f59e0b !important;
    color: white !important;
    border-color: #f59e0b !important;
}
.flatpickr-day.has-task.today {
    border: 2px solid #f59e0b !important;
    background-color: #fef3c7 !important;
    color: #92400e !important;
}

/* Scroll personalizado */
.min-h-[250px] {
    min-height: 250px;
}
.max-h-[350px] {
    max-height: 350px;
}
.overflow-y-auto {
    overflow-y: auto;
}
.overflow-y-auto::-webkit-scrollbar {
    width: 4px;
}
.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}
.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #c7d2fe;
    border-radius: 4px;
}

/* Estilos para las tarjetas de estadísticas */
#riskStatsContainer > div {
    transition: all 0.2s ease;
}
#riskStatsContainer > div:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.1);
}
</style>

<!-- Flatpickr CSS y JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
// === KNOWLEDGE BASE ===
const knowledgeBase = {
    presentacion: "Soy Optimixs Risk, el asistente inteligente del Sistema Integral de Gestión de Riesgos Empresariales. Estoy aquí para ayudarte.",
    modulos: "Optimixs Risk cuenta con módulos: Dashboard, Clasificación, Matriz de Riesgos, Organizaciones, Impactos, Criterios de Probabilidad, Checklists y Reportes Finales.",
    organizacion: "El módulo de Organizaciones permite gestionar entidades como empresas o instituciones. Puedes crear, editar y eliminar organizaciones.",
    checklist: "Un checklist de evidencias es un formulario que evalúa el cumplimiento de controles asociados a un riesgo específico.",
    reporte: "Para generar un reporte, ve al módulo 'Reporte Final', selecciona organización y checklist, y haz clic en 'Generar Reporte'.",
    clasificacion: "Para crear una clasificación, ve al módulo 'Clasificación', haz clic en 'Crear Nuevo', completa categoría y descripción, y guarda.",
    matriz: "La Matriz de Riesgos contiene identificación del riesgo, análisis inherente, evaluación de controles, riesgo residual y plan de acción.",
    impactos: "Niveles de impacto: Leve(20%), Menor(40%), Moderado(60%), Mayor(80%), Catastrófico(100%).",
    criterios: "Criterios de probabilidad: Muy Baja(20%), Baja(40%), Media(60%), Alta(80%), Muy Alta(100%).",
    dashboard: "El Dashboard muestra métricas de clasificaciones, matriz, organizaciones, impactos, criterios y reportes."
};

let typingInterval = null;
let currentFullMessage = '';
let currentCharIndex = 0;

let calendarTypingInterval = null;
let calendarCurrentFullMessage = '';
let calendarCurrentCharIndex = 0;

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

function calendarTypeWriter() {
    const typingSpan = document.getElementById('calendarTypingText');
    if (!typingSpan) return;
    if (calendarCurrentCharIndex < calendarCurrentFullMessage.length) {
        typingSpan.textContent += calendarCurrentFullMessage.charAt(calendarCurrentCharIndex);
        calendarCurrentCharIndex++;
        setTimeout(calendarTypeWriter, 25);
    } else {
        const cursor = document.getElementById('calendarTypingCursor');
        if (cursor) cursor.style.opacity = '0';
    }
}

function setCalendarTypingMessage(message) {
    if (calendarTypingInterval) clearTimeout(calendarTypingInterval);
    calendarCurrentFullMessage = message;
    calendarCurrentCharIndex = 0;
    const typingSpan = document.getElementById('calendarTypingText');
    const cursor = document.getElementById('calendarTypingCursor');
    if (typingSpan) typingSpan.textContent = '';
    if (cursor) cursor.style.opacity = '1';
    calendarTypeWriter();
}

function askQuestion(topic) {
    setTypingMessage(knowledgeBase[topic] || knowledgeBase.presentacion);
    closeQuestionModal();
}

function selectPredefinedQuestion(topic) {
    setTypingMessage(knowledgeBase[topic] || knowledgeBase.presentacion);
    closeQuestionModal();
}

function openQuestionModal() {
    const modal = document.getElementById('questionModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('userQuestion').value = '';
}

function closeQuestionModal() {
    const modal = document.getElementById('questionModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function askCustomQuestion() {
    const question = document.getElementById('userQuestion').value.trim().toLowerCase();
    if (!question) { alert('Escribe una pregunta'); return; }
    closeQuestionModal();
    
    if (question.includes('clasificación')) setTypingMessage(knowledgeBase.clasificacion);
    else if (question.includes('matriz')) setTypingMessage(knowledgeBase.matriz);
    else if (question.includes('impacto')) setTypingMessage(knowledgeBase.impactos);
    else if (question.includes('probabilidad')) setTypingMessage(knowledgeBase.criterios);
    else if (question.includes('dashboard')) setTypingMessage(knowledgeBase.dashboard);
    else if (question.includes('organización')) setTypingMessage(knowledgeBase.organizacion);
    else if (question.includes('checklist')) setTypingMessage(knowledgeBase.checklist);
    else if (question.includes('reporte')) setTypingMessage(knowledgeBase.reporte);
    else setTypingMessage("No tengo información específica sobre eso. ¿Puedo ayudarte con otro tema?");
}

window.resetAssistantMessage = function() { setTypingMessage(knowledgeBase.presentacion); };

// === DASHBOARD STATS - CORREGIDO ===
async function loadRiskStats() {
    const container = document.getElementById('riskStatsContainer');
    if (!container) return;

    const endpoints = {
        classifications: '/api/risk/classifications',
        matrix: '/api/risk/matrix',
        organizations: '/api/risk/organizations',
        impacts: '/api/risk/impacts',
        probabilities: '/api/risk/probabilities',
        reports: '/api/risk/reports'
    };

    const titles = {
        classifications: 'CLASIFICACIÓN',
        matrix: 'MATRIZ',
        organizations: 'ORGANIZACIONES',
        impacts: 'IMPACTOS',
        probabilities: 'CRITERIOS',
        reports: 'REPORTES'
    };

    const icons = {
        classifications: 'fa-tags',
        matrix: 'fa-th',
        organizations: 'fa-building',
        impacts: 'fa-exclamation-triangle',
        probabilities: 'fa-sliders-h',
        reports: 'fa-file-alt'
    };

    const colors = {
        classifications: 'blue',
        matrix: 'indigo',
        organizations: 'green',
        impacts: 'orange',
        probabilities: 'purple',
        reports: 'rose'
    };

    const stats = {};
    await Promise.all(Object.entries(endpoints).map(async ([key, url]) => {
        try {
            const res = await fetch(url);
            const data = await res.json();
            stats[key] = (data.success && Array.isArray(data.data)) ? data.data.length : 0;
        } catch(e) { 
            console.error(`Error loading ${key}:`, e);
            stats[key] = 0; 
        }
    }));

    let html = '';
    for (const [key, count] of Object.entries(stats)) {
        html += `
            <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-${colors[key]}-100 text-${colors[key]}-600 mb-3">
                        <i class="fas ${icons[key]} text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">${count}</p>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mt-1">${titles[key]}</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">${count === 1 ? 'registro' : 'registros'}</p>
                </div>
            </div>
        `;
    }
    container.innerHTML = html;
    updateChart(stats);
}

let chartInstance = null;
function updateChart(stats) {
    const ctx = document.getElementById('riskChart').getContext('2d');
    const labels = ['Clasificación', 'Matriz', 'Organizaciones', 'Impactos', 'Criterios', 'Reportes'];
    const data = [stats.classifications||0, stats.matrix||0, stats.organizations||0, stats.impacts||0, stats.probabilities||0, stats.reports||0];
    const colors = ['#3B82F6', '#6366F1', '#10B981', '#F97316', '#8B5CF6', '#F43F5E'];
    if (chartInstance) chartInstance.destroy();
    chartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: { labels, datasets: [{ data, backgroundColor: colors, borderColor: '#fff', borderWidth: 2, cutout: '60%' }] },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 10 } } } } }
    });
}

// === CALENDARIO CON ASISTENTE ===
let taskCalendar = null;
let tasksData = [];

async function loadTasksForCalendar() {
    try {
        const res = await fetch('/api/risktasks/dashboard-data');
        const data = await res.json();
        if (data.success && data.tasks && data.tasks.length > 0) {
            tasksData = data.tasks;
            console.log('Tareas cargadas:', tasksData.length);
        } else {
            tasksData = [];
        }
        highlightDaysWithTasks();
        setCalendarTypingMessage("✅ Sistema actualizado. Selecciona una fecha en el calendario para ver las tareas programadas.");
    } catch(e) {
        console.error('Error cargando tareas:', e);
        tasksData = [];
        highlightDaysWithTasks();
        setCalendarTypingMessage("⚠️ No se pudieron cargar las tareas. Verifica tu conexión.");
        const statusSpan = document.getElementById('calendarAssistantStatus');
        if (statusSpan) {
            statusSpan.className = 'px-1.5 py-0.5 bg-red-100 text-red-700 text-[9px] rounded-full font-medium';
            statusSpan.textContent = 'Fuera de línea';
        }
    }
}

function getTasksByDateForCalendar(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const dateStr = `${year}-${month}-${day}`;
    return tasksData.filter(t => t.scheduled_date === dateStr);
}

function getStatusTextCalendar(status) {
    switch(status) {
        case 'pending': return 'Pendiente';
        case 'in_progress': return 'En progreso';
        case 'completed': return 'Completada';
        case 'cancelled': return 'Cancelada';
        default: return status;
    }
}

function getPriorityText(priority) {
    switch(priority) {
        case 'baja': return 'Baja';
        case 'media': return 'Media';
        case 'alta': return 'Alta';
        case 'critica': return 'Crítica';
        default: return priority || 'Media';
    }
}

function highlightDaysWithTasks() {
    if (!taskCalendar) return;
    
    const allDays = document.querySelectorAll('.flatpickr-day');
    allDays.forEach(day => day.classList.remove('has-task'));
    
    tasksData.forEach(task => {
        if (task.scheduled_date) {
            const selector = `.flatpickr-day[aria-label="${task.scheduled_date}"]`;
            const dayElement = document.querySelector(selector);
            if (dayElement) {
                dayElement.classList.add('has-task');
            }
        }
    });
}

function updateCalendarAssistant(date, tasks) {
    const formattedDate = date.toLocaleDateString('es-ES', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    const dateBadge = document.getElementById('selectedDateBadge');
    if (dateBadge) {
        dateBadge.innerHTML = `<i class="fas fa-calendar-check mr-1"></i> ${formattedDate.substring(0, 30)}${formattedDate.length > 30 ? '...' : ''}`;
    }
    
    if (tasks.length === 0) {
        setCalendarTypingMessage(`📅 ${formattedDate}\n\n❌ No hay tareas programadas.\n\n¿Te gustaría crear una nueva tarea?`);
    } else {
        let taskList = tasks.map(t => {
            return `${t.title} - Tipo: ${t.task_type} - Asignado: ${t.assigned_name || 'No asignado'} - Empresa: ${t.organization_name || '-'} - Hora: ${t.scheduled_time || '-'} - Prioridad: ${getPriorityText(t.priority)} - Estado: ${getStatusTextCalendar(t.status)}`;
        }).join('\n\n');
        
        let message = `📅 ${formattedDate}\n\n📋 ${tasks.length} tarea${tasks.length !== 1 ? 's' : ''} encontrada${tasks.length !== 1 ? 's' : ''}:\n\n${taskList}`;
        
        if (message.length > 1800) {
            message = message.substring(0, 1750) + "...\n\n(Contenido truncado)";
        }
        
        setCalendarTypingMessage(message);
    }
}

function showTasksForSelectedDate(selectedDates, dateStr, instance) {
    if (selectedDates.length === 0) return;
    const date = selectedDates[0];
    const tasks = getTasksByDateForCalendar(date);
    updateCalendarAssistant(date, tasks);
}

function initTaskCalendar() {
    const container = document.getElementById('taskCalendar');
    if (!container) {
        console.log('Contenedor del calendario no encontrado');
        return;
    }
    
    taskCalendar = flatpickr(container, {
        inline: true,
        locale: {
            months: {
                shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
            },
            weekdays: {
                shorthand: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                longhand: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']
            },
            firstDayOfWeek: 1
        },
        dateFormat: 'Y-m-d',
        onChange: showTasksForSelectedDate,
        onMonthChange: function(selectedDates, dateStr, instance) {
            setTimeout(() => highlightDaysWithTasks(), 50);
        },
        onValueUpdate: function(selectedDates, dateStr, instance) {
            setTimeout(() => highlightDaysWithTasks(), 50);
        }
    });
    
    setTimeout(() => {
        highlightDaysWithTasks();
    }, 100);
    
    loadTasksForCalendar();
}

window.refreshTaskCalendar = function() {
    loadTasksForCalendar();
};

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, iniciando dashboard...');
    setTimeout(() => {
        setTypingMessage(knowledgeBase.presentacion);
        setCalendarTypingMessage("👋 Hola, soy Optimixs Risk Assistant.\n\nSelecciona una fecha en el calendario para ver las tareas programadas.\n\n📌 Las fechas con tareas aparecen resaltadas en AMARILLO.");
    }, 500);
    loadRiskStats();
    initTaskCalendar();
});

document.getElementById('questionModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeQuestionModal();
});
</script>
