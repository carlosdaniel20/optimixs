<?php
$isAdmin = ($_SESSION['user']['tipo_utente'] ?? '') === 'admin';
?>
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
                        <button onclick="showAssistantTip('que-es')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📋 ¿Qué es este módulo?</button>
                        <button onclick="showAssistantTip('metodologia')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">🎯 Metodología bayesiana</button>
                        <button onclick="showAssistantTip('uso')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📊 Cómo usar checklist</button>
                        <button onclick="resetAssistantMessage()" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">⟳ Reiniciar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Checklist de Evidencias</h1>
        <div class="flex gap-2">
            <button id="btnSaveProgress" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Guardar progreso
            </button>
            <button id="btnRefreshProgress" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Recargar
            </button>
        </div>
    </div>

    <!-- Selección de Organización, Riesgo y nombre del checklist -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Organización / Empresa *</label>
                <select id="organizacionSelector" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Seleccionar organización --</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Riesgo de la matriz</label>
                <select id="riesgoSelector" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Seleccionar riesgo --</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre del checklist</label>
                <input type="text" id="nombreChecklist" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Ej: Auditoría de cumplimiento 2025">
                <p class="text-xs text-gray-400 mt-1">Identificador único para este proceso de verificación</p>
            </div>
        </div>
    </div>

    <!-- Tabs por área -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50">
            <div id="areaTabs" class="flex overflow-x-auto px-4 py-2 gap-2"></div>
        </div>
        <div id="checklistContent" class="p-4">
            <div class="text-center py-8 text-gray-500">Seleccione una organización y un riesgo para comenzar la evaluación.</div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar plantilla (solo admin) -->
<div id="templateModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold mb-4" id="modalTitle">Editar evidencia</h3>
        <form id="templateForm">
            <input type="hidden" id="editId" value="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold mb-1">Área *</label><select id="area" class="w-full border border-gray-300 rounded-lg px-3 py-2" required><option value="">Seleccionar</option><option value="doc">Documental</option><option value="cont">Contenedores</option><option value="pers">Personal</option><option value="buq">Buques</option><option value="log">Logística</option><option value="int">Inteligencia</option></select></div>
                <div><label class="block text-sm font-semibold mb-1">Orden</label><input type="number" id="orden" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="0"></div>
                <div class="md:col-span-2"><label class="block text-sm font-semibold mb-1">Descripción *</label><textarea id="descripcion" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2" required></textarea></div>
                <div><label class="flex items-center gap-2"><input type="checkbox" id="es_critica"> Es crítica (⚠️)</label></div>
                <div><label class="block text-sm font-semibold mb-1">P(E|Riesgo) %</label><input type="number" id="peh_riesgo" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="80" min="0" max="100"></div>
                <div><label class="block text-sm font-semibold mb-1">P(E|¬Riesgo) %</label><input type="number" id="penh_riesgo" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="10" min="0" max="100"></div>
            </div>
            <div class="flex justify-end gap-3 mt-6"><button type="button" onclick="closeTemplateModal()" class="px-4 py-2 bg-gray-200 rounded-lg">Cancelar</button><button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Guardar</button></div>
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

// Mensajes profesionales del asistente
const assistantMessages = {
    initial: "Bienvenido al Módulo de Checklist de Evidencias. Esta herramienta implementa una metodología de verificación basada en evidencia para evaluar el cumplimiento de controles asociados a riesgos específicos. Permite cuantificar la efectividad de las medidas implementadas mediante indicadores binarios (Sí/No).",
    'que-es': "El Checklist de Evidencias es un instrumento de auditoría que registra la presencia o ausencia de controles en seis áreas críticas: Documental, Contenedores, Personal, Buques, Logística e Inteligencia. Cada ítem incluye probabilidades condicionales P(E|Riesgo) y P(E|¬Riesgo) para análisis bayesiano.",
    metodologia: "Metodología: Se evalúa cada evidencia con 'Sí' (control presente) o 'No' (control ausente). La probabilidad P(E|Riesgo) indica la probabilidad de encontrar la evidencia si el riesgo está presente; P(E|¬Riesgo) si está ausente. Esto permite calcular la probabilidad posterior de ocurrencia del riesgo.",
    uso: "Instrucciones de uso: 1) Seleccione organización y riesgo, 2) Asigne un nombre al checklist, 3) Evalúe cada evidencia por área, 4) Guarde progreso periódicamente. El sistema mantiene el estado y calcula automáticamente el porcentaje de cumplimiento por área."
};

// Mostrar tip con animación
window.showAssistantTip = function(tipo) {
    let message = assistantMessages[tipo] || assistantMessages.initial;
    setTypingMessage(message);
};

window.resetAssistantMessage = function() {
    setTypingMessage(assistantMessages.initial);
};

// === CÓDIGO EXISTENTE DEL CHECKLIST ===
// Leer parámetros de la URL (para enlace desde dashboard)
const urlParams = new URLSearchParams(window.location.search);
const urlRiesgoId = urlParams.get('riesgo_id');
const urlNombre = urlParams.get('nombre');
const urlOrgId = urlParams.get('org_id');

const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
let currentRiesgoId = null;
let currentOrganizacionId = null;
let currentOrganizacionNombre = null;
let currentNombreChecklist = '';
let currentArea = 'doc';
let templatesByArea = {};
let allTemplates = [];
let progress = {};
let allOrganizaciones = [];

// Cargar lista de organizaciones
async function loadOrganizaciones() {
    try {
        const res = await fetch('/api/risk/organizations');
        const data = await res.json();
        const selector = document.getElementById('organizacionSelector');
        selector.innerHTML = '<option value="">-- Seleccionar organización --</option>';
        if (data.success && data.data) {
            allOrganizaciones = data.data;
            data.data.forEach(org => {
                const opt = document.createElement('option');
                opt.value = org.id;
                opt.textContent = org.nombre;
                selector.appendChild(opt);
            });
        }
        
        if (urlOrgId && data.data) {
            const orgExists = data.data.find(o => o.id == urlOrgId);
            if (orgExists) {
                selector.value = urlOrgId;
                currentOrganizacionId = parseInt(urlOrgId);
                currentOrganizacionNombre = orgExists.nombre;
                
                if (urlRiesgoId && urlNombre) {
                    document.getElementById('riesgoSelector').value = urlRiesgoId;
                    document.getElementById('nombreChecklist').value = urlNombre;
                    currentRiesgoId = parseInt(urlRiesgoId);
                    currentNombreChecklist = urlNombre;
                    setTimeout(() => cargarChecklist(currentRiesgoId, currentNombreChecklist), 500);
                }
            }
        }
    } catch(e) { console.error('Error cargando organizaciones:', e); }
}

// Cargar lista de riesgos
async function loadRiesgosList() {
    try {
        const res = await fetch('/api/risk/matrix');
        const data = await res.json();
        const selector = document.getElementById('riesgoSelector');
        selector.innerHTML = '<option value="">-- Seleccionar riesgo --</option>';
        if (data.success && data.data) {
            data.data.forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.id;
                opt.textContent = r.proceso;
                selector.appendChild(opt);
            });
        }
    } catch(e) { console.error('Error cargando riesgos:', e); }
}

// Cargar plantillas
async function loadTemplates() {
    try {
        const res = await fetch('/api/risk/checklist/templates');
        const data = await res.json();
        if (data.success) {
            allTemplates = data.data;
            templatesByArea = {};
            for (let t of allTemplates) {
                if (!templatesByArea[t.area]) templatesByArea[t.area] = [];
                templatesByArea[t.area].push(t);
            }
            renderAreaTabs();
            if (currentRiesgoId && currentNombreChecklist && templatesByArea[currentArea]) {
                renderChecklist();
            }
        }
    } catch(e) { console.error('Error cargando plantillas:', e); }
}

// Cargar progreso del riesgo
async function cargarChecklist(riesgoId, nombreChecklist) {
    if (!riesgoId) return;
    currentRiesgoId = riesgoId;
    currentNombreChecklist = nombreChecklist || '';
    let url = `/api/risk/checklist/riesgo/${riesgoId}`;
    if (currentNombreChecklist) {
        url += `?nombre_checklist=${encodeURIComponent(currentNombreChecklist)}`;
    }
    try {
        const res = await fetch(url);
        const data = await res.json();
        if (data.success) {
            progress = {};
            for (let item of data.data) {
                progress[item.id] = item.checked ? 'si' : 'no';
            }
            if (currentArea && templatesByArea[currentArea]) {
                renderChecklist();
            }
        }
    } catch(e) { console.error('Error cargando checklist:', e); }
}

// Renderizar tabs
function renderAreaTabs() {
    const areas = Object.keys(templatesByArea);
    const container = document.getElementById('areaTabs');
    container.innerHTML = '';
    if (areas.length === 0) {
        container.innerHTML = '<div class="text-gray-500 p-2">No hay áreas definidas</div>';
        return;
    }
    const areaNames = { 'doc': '📄 Documental', 'cont': '📦 Contenedores', 'pers': '👤 Personal', 'buq': '🚢 Buques', 'log': '🚛 Logística', 'int': '🕵️ Inteligencia' };
    for (let area of areas) {
        const btn = document.createElement('button');
        btn.className = `tab-btn px-4 py-2 rounded-lg font-medium transition ${currentArea === area ? 'bg-indigo-100 text-indigo-700 border-b-2 border-indigo-600' : 'text-gray-600 hover:bg-gray-100'}`;
        btn.textContent = areaNames[area] || area;
        btn.onclick = () => { currentArea = area; renderAreaTabs(); renderChecklist(); };
        container.appendChild(btn);
    }
    if (!currentArea && areas.length > 0) currentArea = areas[0];
}

// Renderizar checklist
function renderChecklist() {
    const container = document.getElementById('checklistContent');
    const items = templatesByArea[currentArea] || [];
    if (items.length === 0) {
        container.innerHTML = '<div class="text-center py-8 text-gray-500">No hay evidencias definidas para esta área.</div>';
        return;
    }
    let total = items.length;
    let checkedCount = 0;
    items.forEach(item => { if (progress[item.id] === 'si') checkedCount++; });
    const pct = total ? Math.round(checkedCount / total * 100) : 0;
    let html = `
        <div class="mb-4 p-3 bg-gray-50 rounded-lg flex flex-wrap justify-between items-center gap-2">
            <span class="font-semibold">Cumplimiento: ${checkedCount} / ${total} (${pct}%)</span>
            <div class="flex-1 max-w-xs h-2 bg-gray-200 rounded-full overflow-hidden"><div class="h-full bg-indigo-600 rounded-full" style="width: ${pct}%"></div></div>
        </div>
        <div class="space-y-2">
    `;
    items.forEach(item => {
        const estado = progress[item.id] || 'no';
        const siActivo = estado === 'si' ? 'bg-indigo-100 border-indigo-500 text-indigo-700' : 'bg-white hover:bg-gray-50';
        const noActivo = estado === 'no' ? 'bg-gray-200 border-gray-400 text-gray-800' : 'bg-white hover:bg-gray-50';
        html += `
            <div class="flex items-start gap-3 p-2 border-b hover:bg-gray-50">
                <div class="flex gap-1 flex-shrink-0">
                    <button data-id="${item.id}" data-value="si" class="btn-evidencia px-3 py-1 text-sm rounded-lg border transition ${siActivo}">Sí</button>
                    <button data-id="${item.id}" data-value="no" class="btn-evidencia px-3 py-1 text-sm rounded-lg border transition ${noActivo}">No</button>
                </div>
                <div class="flex-1">
                    <div class="font-medium">${escapeHtml(item.descripcion)}</div>
                    <div class="text-xs text-gray-500">P(E|R)=${item.peh_riesgo}% | P(E|¬R)=${item.penh_riesgo}% ${item.es_critica ? '| ⚠️ Crítica' : ''}</div>
                </div>
                <?php if ($isAdmin): ?>
                <div class="flex gap-1 flex-shrink-0">
                    <button onclick="editTemplate(${item.id})" class="text-blue-500 hover:text-blue-700 p-1" title="Editar">✏️</button>
                    <button onclick="deleteTemplate(${item.id})" class="text-red-500 hover:text-red-700 p-1" title="Eliminar">🗑️</button>
                </div>
                <?php endif; ?>
            </div>
        `;
    });
    html += `</div>`;
    container.innerHTML = html;
    
    document.querySelectorAll('.btn-evidencia').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const templateId = parseInt(btn.dataset.id);
            const valor = btn.dataset.value;
            const parentDiv = btn.closest('.flex.items-start');
            const siBtn = parentDiv.querySelector('.btn-evidencia[data-value="si"]');
            const noBtn = parentDiv.querySelector('.btn-evidencia[data-value="no"]');
            if (valor === 'si') {
                siBtn.classList.add('bg-indigo-100', 'border-indigo-500', 'text-indigo-700');
                siBtn.classList.remove('bg-white', 'hover:bg-gray-50');
                noBtn.classList.remove('bg-gray-200', 'border-gray-400', 'text-gray-800');
                noBtn.classList.add('bg-white', 'hover:bg-gray-50');
            } else {
                noBtn.classList.add('bg-gray-200', 'border-gray-400', 'text-gray-800');
                noBtn.classList.remove('bg-white', 'hover:bg-gray-50');
                siBtn.classList.remove('bg-indigo-100', 'border-indigo-500', 'text-indigo-700');
                siBtn.classList.add('bg-white', 'hover:bg-gray-50');
            }
            progress[templateId] = valor === 'si' ? 'si' : 'no';
            await guardarProgresoItem(templateId, valor === 'si' ? 1 : 0);
            actualizarBarraProgreso();
        });
    });
}

function actualizarBarraProgreso() {
    const items = templatesByArea[currentArea] || [];
    let total = items.length;
    let checkedCount = 0;
    items.forEach(item => { if (progress[item.id] === 'si') checkedCount++; });
    const pct = total ? Math.round(checkedCount / total * 100) : 0;
    const barContainer = document.querySelector('#checklistContent .mb-4');
    if (barContainer) {
        barContainer.innerHTML = `
            <span class="font-semibold">Cumplimiento: ${checkedCount} / ${total} (${pct}%)</span>
            <div class="flex-1 max-w-xs h-2 bg-gray-200 rounded-full overflow-hidden"><div class="h-full bg-indigo-600 rounded-full" style="width: ${pct}%"></div></div>
        `;
    }
}

// GUARDAR PROGRESO DE UN ITEM
async function guardarProgresoItem(templateId, checked) {
    if (!currentRiesgoId) {
        alert('Seleccione un riesgo primero');
        return;
    }
    
    let nombre = document.getElementById('nombreChecklist').value.trim();
    if (!nombre) {
        alert('Ingrese un nombre para el checklist');
        return;
    }
    
    // OBTENER ORGANIZACIÓN SELECCIONADA
    const orgSelect = document.getElementById('organizacionSelector');
    const organizacionId = orgSelect.value;
    const organizacionNombre = orgSelect.options[orgSelect.selectedIndex]?.text;
    
    if (!organizacionId) {
        alert('Debe seleccionar una organización');
        return;
    }
    
    if (nombre !== currentNombreChecklist) {
        currentNombreChecklist = nombre;
        await cargarChecklist(currentRiesgoId, currentNombreChecklist);
    }
    
    const formData = new URLSearchParams();
    formData.append('csrf_token', csrf);
    formData.append('riesgo_id', currentRiesgoId);
    formData.append('template_id', templateId);
    formData.append('checked', checked);
    formData.append('nombre_checklist', nombre);
    formData.append('area', currentArea);
    formData.append('organizacion_id', organizacionId);
    formData.append('organizacion_nombre', organizacionNombre);
    
    try {
        const res = await fetch('/api/risk/checklist/toggle-riesgo', { 
            method: 'POST', 
            body: formData, 
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' } 
        });
        const data = await res.json();
        if (!data.success) console.error('Error guardando:', data.error);
    } catch(e) { console.error('Error de red:', e); }
}

// Guardar progreso completo del área actual
async function saveProgress() {
    if (!currentRiesgoId) {
        alert('Seleccione un riesgo primero');
        return;
    }
    let nombre = document.getElementById('nombreChecklist').value.trim();
    if (!nombre && currentOrganizacionNombre) {
        nombre = currentOrganizacionNombre;
        document.getElementById('nombreChecklist').value = nombre;
    }
    if (!nombre) {
        alert('Ingrese un nombre para el checklist');
        return;
    }
    if (nombre !== currentNombreChecklist) {
        currentNombreChecklist = nombre;
        await cargarChecklist(currentRiesgoId, currentNombreChecklist);
    }
    
    const items = templatesByArea[currentArea] || [];
    let saved = 0;
    let errors = 0;
    
    for (const item of items) {
        const templateId = item.id;
        const estado = progress[templateId] || 'no';
        const checked = estado === 'si' ? 1 : 0;
        
        const formData = new URLSearchParams();
        formData.append('csrf_token', csrf);
        formData.append('riesgo_id', currentRiesgoId);
        formData.append('template_id', templateId);
        formData.append('checked', checked);
        formData.append('nombre_checklist', nombre);
        formData.append('area', currentArea);
        
        try {
            const res = await fetch('/api/risk/checklist/toggle-riesgo', { 
                method: 'POST', 
                body: formData, 
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' } 
            });
            const data = await res.json();
            if (data.success) saved++; else errors++;
        } catch(e) { errors++; }
    }
    
    alert(`Progreso guardado para el área "${currentArea}": ${saved} ítems actualizados, ${errors} errores.`);
}

// CRUD plantillas
function openTemplateModal(editId = null) {
    const modal = document.getElementById('templateModal');
    modal.classList.remove('hidden'); modal.classList.add('flex');
    document.getElementById('editId').value = editId || '';
    document.getElementById('templateForm').reset();
    if (editId) {
        const t = allTemplates.find(x => x.id == editId);
        if (t) {
            document.getElementById('area').value = t.area;
            document.getElementById('descripcion').value = t.descripcion;
            document.getElementById('orden').value = t.orden;
            document.getElementById('es_critica').checked = t.es_critica == 1;
            document.getElementById('peh_riesgo').value = t.peh_riesgo;
            document.getElementById('penh_riesgo').value = t.penh_riesgo;
            document.getElementById('modalTitle').innerText = 'Editar evidencia';
        }
    } else {
        document.getElementById('modalTitle').innerText = 'Nueva evidencia';
    }
}
function closeTemplateModal() {
    const modal = document.getElementById('templateModal');
    modal.classList.add('hidden'); modal.classList.remove('flex');
}
window.editTemplate = (id) => openTemplateModal(id);
window.deleteTemplate = async (id) => {
    if (!confirm('¿Eliminar esta evidencia permanentemente?')) return;
    const formData = new URLSearchParams(); formData.append('csrf_token', csrf);
    const res = await fetch(`/api/risk/checklist/templates/${id}/delete`, { method: 'POST', body: formData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } });
    const data = await res.json();
    if (data.success) {
        alert('Evidencia eliminada');
        loadTemplates();
        if (currentRiesgoId && currentNombreChecklist) cargarChecklist(currentRiesgoId, currentNombreChecklist);
    } else alert('Error: ' + (data.error || 'No se pudo eliminar'));
};
document.getElementById('templateForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const editId = document.getElementById('editId').value;
    const formData = new URLSearchParams();
    formData.append('csrf_token', csrf);
    formData.append('area', document.getElementById('area').value);
    formData.append('descripcion', document.getElementById('descripcion').value);
    formData.append('orden', document.getElementById('orden').value);
    formData.append('es_critica', document.getElementById('es_critica').checked ? '1' : '0');
    formData.append('peh_riesgo', document.getElementById('peh_riesgo').value);
    formData.append('penh_riesgo', document.getElementById('penh_riesgo').value);
    let url = '/api/risk/checklist/templates', method = 'POST';
    if (editId) { url = `/api/risk/checklist/templates/${editId}/update`; method = 'POST'; }
    const res = await fetch(url, { method, body: formData, headers: { 'Content-Type': 'application/x-www-form-urlencoded' } });
    const data = await res.json();
    if (data.success) {
        alert(editId ? 'Actualizado' : 'Creado');
        closeTemplateModal();
        loadTemplates();
        if (currentRiesgoId && currentNombreChecklist) cargarChecklist(currentRiesgoId, currentNombreChecklist);
    } else alert('Error: ' + (data.error || 'No se pudo guardar'));
});

// Eventos
document.getElementById('btnSaveProgress').addEventListener('click', saveProgress);
document.getElementById('btnRefreshProgress').addEventListener('click', () => {
    if (currentRiesgoId && currentNombreChecklist) {
        cargarChecklist(currentRiesgoId, currentNombreChecklist);
    } else {
        alert('Seleccione una organización, un riesgo y asigne un nombre primero');
    }
});

document.getElementById('organizacionSelector').addEventListener('change', async (e) => {
    const id = e.target.value;
    if (id) {
        currentOrganizacionId = parseInt(id);
        const org = allOrganizaciones.find(o => o.id == currentOrganizacionId);
        currentOrganizacionNombre = org ? org.nombre : '';
        
        if (currentOrganizacionNombre && !document.getElementById('nombreChecklist').value.trim()) {
            document.getElementById('nombreChecklist').value = currentOrganizacionNombre;
        }
    } else {
        currentOrganizacionId = null;
        currentOrganizacionNombre = null;
    }
});

document.getElementById('riesgoSelector').addEventListener('change', async (e) => {
    const id = e.target.value;
    if (id) {
        currentRiesgoId = parseInt(id);
        let nombre = document.getElementById('nombreChecklist').value.trim();
        if (!nombre && currentOrganizacionNombre) {
            nombre = currentOrganizacionNombre;
            document.getElementById('nombreChecklist').value = nombre;
        }
        if (nombre) {
            currentNombreChecklist = nombre;
            await cargarChecklist(currentRiesgoId, currentNombreChecklist);
        } else {
            document.getElementById('checklistContent').innerHTML = '<div class="text-center py-8 text-gray-500">Asigne un nombre y presione "Guardar progreso".</div>';
        }
    } else {
        currentRiesgoId = null;
        currentNombreChecklist = '';
        document.getElementById('checklistContent').innerHTML = '<div class="text-center py-8 text-gray-500">Seleccione un riesgo.</div>';
    }
});

document.getElementById('nombreChecklist').addEventListener('change', () => {
    if (currentRiesgoId) {
        const nombre = document.getElementById('nombreChecklist').value.trim();
        if (nombre) {
            currentNombreChecklist = nombre;
            cargarChecklist(currentRiesgoId, currentNombreChecklist);
        }
    }
});

// Inicializar mensaje de bienvenida
setTimeout(() => {
    setTypingMessage(assistantMessages.initial);
}, 500);

// Inicializar
loadOrganizaciones();
loadRiesgosList();
loadTemplates();

function escapeHtml(str) { if (!str) return ''; return str.replace(/[&<>]/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[m])); }
</script>
