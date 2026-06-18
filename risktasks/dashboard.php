<?php
$taskTypes = [
    'buque' => ['icono' => 'fa-ship', 'color' => 'primary', 'texto' => 'Buque'],
    'container' => ['icono' => 'fa-box', 'color' => 'info', 'texto' => 'Container'],
    'documental' => ['icono' => 'fa-file-alt', 'color' => 'secondary', 'texto' => 'Documental'],
    'inteligencia' => ['icono' => 'fa-brain', 'color' => 'purple', 'texto' => 'Inteligencia'],
    'logistica' => ['icono' => 'fa-truck', 'color' => 'warning', 'texto' => 'Logística'],
    'personal' => ['icono' => 'fa-users', 'color' => 'success', 'texto' => 'Personal']
];
$priorities = ['baja' => 'success', 'media' => 'warning', 'alta' => 'orange', 'critica' => 'danger'];
$statuses = [
    'pending' => ['texto' => 'Pendiente', 'color' => 'warning'],
    'in_progress' => ['texto' => 'En progreso', 'color' => 'info'],
    'completed' => ['texto' => 'Completada', 'color' => 'success'],
    'cancelled' => ['texto' => 'Cancelada', 'color' => 'secondary']
];
?>
<div class="container mx-auto px-4 py-6">
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
                        <button onclick="showAssistantTip('como-usar')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">📋 Cómo gestionar tareas</button>
                        <button onclick="showAssistantTip('estados')" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">🔄 Estados de tareas</button>
                        <button onclick="resetAssistantMessage()" class="text-xs px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition">⟳ Reiniciar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📋 Risk Tasks - Tareas Programadas</h1>
        <button id="btnNuevaTarea" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md flex items-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Tarea
        </button>
    </div>

    <!-- GRÁFICO CIRCULAR POR TIPO DE TAREA -->
    <div class="mb-8 bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-chart-pie text-indigo-500 mr-2"></i>
                    Distribución de Tareas por Tipo
                </h2>
                <p class="text-gray-500 text-sm">Cantidad de tareas por tipo de chequeo</p>
                <div class="mt-4 flex flex-wrap gap-2 justify-center md:justify-start" id="chartLegend"></div>
            </div>
            <div class="flex-1 max-w-xs mx-auto">
                <canvas id="tasksChart" width="400" height="400" class="w-full h-auto"></canvas>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Filtrar por Tipo</label>
                <select id="filterTipo" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Todos los tipos --</option>
                    <option value="buque">🚢 Buque</option>
                    <option value="container">📦 Container</option>
                    <option value="documental">📄 Documental</option>
                    <option value="inteligencia">🧠 Inteligencia</option>
                    <option value="logistica">🚚 Logística</option>
                    <option value="personal">👥 Personal</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Filtrar por Estado</label>
                <select id="filterEstado" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Todos los estados --</option>
                    <option value="pending">⏳ Pendiente</option>
                    <option value="in_progress">🔄 En progreso</option>
                    <option value="completed">✅ Completada</option>
                    <option value="cancelled">❌ Cancelada</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Filtrar por Prioridad</label>
                <select id="filterPrioridad" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Todas las prioridades --</option>
                    <option value="baja">🟢 Baja</option>
                    <option value="media">🟡 Media</option>
                    <option value="alta">🟠 Alta</option>
                    <option value="critica">🔴 Crítica</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Buscar por título</label>
                <input type="text" id="filterTitulo" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Buscar por título...">
            </div>
        </div>
        <div class="mt-3 text-right">
            <button id="btnLimpiarFiltros" class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fas fa-eraser"></i> Limpiar filtros
            </button>
        </div>
    </div>

    <!-- Tabla de tareas -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organización</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asignado a</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tasksTableBody" class="bg-white divide-y divide-gray-200">
                    <tr><td colspan="9" class="px-6 py-4 text-center text-gray-500">Cargando tareas...</span><span class="sd-tag">    </span></td>
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

<!-- Modal Crear Tarea -->
<div id="createTaskModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Nueva Tarea de Riesgo</h3>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form id="createTaskForm">
            <input type="hidden" name="csrf_token" value="<?php echo App\Support\Csrf::ensureToken(); ?>">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                <input type="text" name="title" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                <textarea name="description" class="w-full border border-gray-300 rounded-lg px-3 py-2" rows="3" required></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de chequeo *</label>
                    <select name="task_type" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Seleccionar...</option>
                        <option value="buque">🚢 Buque</option>
                        <option value="container">📦 Container</option>
                        <option value="documental">📄 Documental</option>
                        <option value="inteligencia">🧠 Inteligencia</option>
                        <option value="logistica">🚚 Logística</option>
                        <option value="personal">👥 Personal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                    <select name="priority" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="baja">🟢 Baja</option>
                        <option value="media" selected>🟡 Media</option>
                        <option value="alta">🟠 Alta</option>
                        <option value="critica">🔴 Crítica</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Organización</label>
                    <select name="organization_id" id="orgSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Seleccionar...</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asignar a</label>
                    <select name="assigned_to" id="userSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Seleccionar...</option>
                    </select>
                </div>
            </div>
            <!-- UNICO CAMBIO: input de fecha con Flatpickr -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
                    <input type="text" name="scheduled_date" id="scheduledDate" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white cursor-pointer" placeholder="Seleccionar fecha" readonly required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora *</label>
                    <input type="time" name="scheduled_time" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeCreateModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">Cancelar</button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">Crear Tarea</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ver/Editar Tarea -->
<div id="viewTaskModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Detalle de Tarea</h3>
            <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <div id="taskDetailContent" class="mb-4">Cargando...</div>
        <div class="flex justify-end gap-2">
            <button onclick="closeViewModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">Cerrar</button>
        </div>
    </div>
</div>

<style>
    @keyframes blink { 0%, 50% { opacity: 1; } 51%, 100% { opacity: 0; } }
    .animate-pulse { animation: blink 1s step-end infinite; }
    
    /* Estilos Flatpickr en español */
    .flatpickr-calendar {
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        font-family: inherit;
        z-index: 9999;
    }
    .flatpickr-day.selected {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    .flatpickr-day.today {
        border-color: #4f46e5;
    }
    .flatpickr-day.today:hover {
        background-color: #4f46e5;
        color: white;
    }
</style>

<!-- Flatpickr CSS y JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
    initial: "Bienvenido al módulo Risk Tasks. Esta herramienta permite gestionar tareas de inspección y riesgo asociadas a organizaciones y tipos de chequeo específicos.",
    'que-es': "Risk Tasks permite asignar tareas de inspección a usuarios con fecha y hora específica. Cada tarea puede clasificarse por tipo (buque, container, documental, inteligencia, logística, personal), prioridad y estado.",
    'como-usar': "Para crear una tarea: 1) Haga clic en 'Nueva Tarea', 2) Complete el formulario con título, descripción, tipo, fecha/hora, 3) Asigne a un usuario y organización, 4) Guarde. Use los filtros para buscar tareas específicas.",
    estados: "Los estados disponibles son: Pendiente (tarea creada), En progreso (tarea iniciada), Completada (tarea finalizada), Cancelada (tarea anulada). Solo el usuario asignado o administrador puede cambiar el estado."
};

window.showAssistantTip = function(tipo) {
    let message = assistantMessages[tipo] || assistantMessages.initial;
    setTypingMessage(message);
};

window.resetAssistantMessage = function() {
    setTypingMessage(assistantMessages.initial);
};

// === CÓDIGO PRINCIPAL ===
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
let chartInstance = null;
let allTasks = [];
let currentPage = 1;
let itemsPerPage = 10;
let filteredTasks = [];
let flatpickrInstance = null;

// ========== INICIALIZACIÓN DEL CALENDARIO EN ESPAÑOL ==========
function initDatePicker() {
    const dateInput = document.getElementById('scheduledDate');
    if (dateInput && typeof flatpickr !== 'undefined') {
        if (flatpickrInstance) {
            flatpickrInstance.destroy();
        }
        flatpickrInstance = flatpickr(dateInput, {
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
            altInput: true,
            altFormat: 'j F, Y',
            allowInput: false,
            disableMobile: true
        });
    }
}

async function loadOrganizations() {
    try {
        const res = await fetch('/api/risktasks/organizations');
        const data = await res.json();
        const select = document.getElementById('orgSelect');
        select.innerHTML = '<option value="">Seleccionar...</option>';
        data.forEach(org => {
            select.innerHTML += `<option value="${org.id}">${escapeHtml(org.nombre)}</option>`;
        });
    } catch(e) { console.error('Error cargando organizaciones:', e); }
}

async function loadUsers() {
    try {
        const res = await fetch('/api/risktasks/users');
        const data = await res.json();
        const select = document.getElementById('userSelect');
        select.innerHTML = '<option value="">Seleccionar...</option>';
        data.forEach(user => {
            select.innerHTML += `<option value="${user.id}">${escapeHtml(user.name)} (${escapeHtml(user.email)})</option>`;
        });
    } catch(e) { console.error('Error cargando usuarios:', e); }
}

async function loadTasks() {
    try {
        const res = await fetch('/api/risktasks/dashboard-data');
        const data = await res.json();
        if (data.success && data.tasks) {
            allTasks = data.tasks;
            applyFilters();
        }
    } catch(e) {
        console.error('Error cargando tareas:', e);
        document.getElementById('tasksTableBody').innerHTML = '<tr><td colspan="9" class="px-6 py-4 text-center text-red-500">Error de conexión</span><span class="sd-tag">    </span></td>';
    }
}

function applyFilters() {
    const tipoFiltro = document.getElementById('filterTipo').value;
    const estadoFiltro = document.getElementById('filterEstado').value;
    const prioridadFiltro = document.getElementById('filterPrioridad').value;
    const tituloFiltro = document.getElementById('filterTitulo').value.trim().toLowerCase();
    
    filteredTasks = allTasks.filter(task => {
        if (tipoFiltro && task.task_type !== tipoFiltro) return false;
        if (estadoFiltro && task.status !== estadoFiltro) return false;
        if (prioridadFiltro && task.priority !== prioridadFiltro) return false;
        if (tituloFiltro && !task.title.toLowerCase().includes(tituloFiltro)) return false;
        return true;
    });
    
    currentPage = 1;
    renderTable();
    updateChart();
}

function renderTable() {
    const totalPages = Math.ceil(filteredTasks.length / itemsPerPage);
    const start = (currentPage - 1) * itemsPerPage;
    const paginatedItems = filteredTasks.slice(start, start + itemsPerPage);
    
    const btnPrev = document.getElementById('btnPrevPage');
    const btnNext = document.getElementById('btnNextPage');
    const pageInfo = document.getElementById('pageInfo');
    
    btnPrev.disabled = currentPage <= 1;
    btnNext.disabled = currentPage >= totalPages || totalPages === 0;
    pageInfo.textContent = `Página ${currentPage} de ${totalPages || 1}`;
    
    const tbody = document.getElementById('tasksTableBody');
    if (!paginatedItems.length) {
        tbody.innerHTML = '<tr><td colspan="9" class="px-6 py-4 text-center text-gray-500">No hay tareas que coincidan con los filtros</span><span class="sd-tag">    </span></td>';
        return;
    }
    
    let html = '';
    for (const task of paginatedItems) {
        const fecha = task.scheduled_date ? new Date(task.scheduled_date).toLocaleDateString() : '-';
        const prioridadClass = { baja: 'success', media: 'warning', alta: 'orange', critica: 'danger' }[task.priority] || 'secondary';
        const estadoClass = { pending: 'warning', in_progress: 'info', completed: 'success', cancelled: 'secondary' }[task.status] || 'secondary';
        const estadoText = { pending: 'Pendiente', in_progress: 'En progreso', completed: 'Completada', cancelled: 'Cancelada' }[task.status] || task.status;
        
        html += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">${task.id}</span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">${escapeHtml(task.title)}<br><small class="text-gray-500">${escapeHtml(task.description?.substring(0, 60))}...</small></span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 text-sm text-gray-600"><i class="fas fa-${task.task_type === 'buque' ? 'ship' : (task.task_type === 'container' ? 'box' : (task.task_type === 'documental' ? 'file-alt' : (task.task_type === 'inteligencia' ? 'brain' : (task.task_type === 'logistica' ? 'truck' : 'users'))))} mr-1"></i> ${task.task_type}</span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 text-sm text-gray-600">${escapeHtml(task.organization_name || '-')}</span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 text-sm text-gray-600">${escapeHtml(task.assigned_name || 'No asignado')}</span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 text-sm text-gray-600">${fecha}<br><small>${task.scheduled_time || '-'}</small></span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4"><span class="inline-flex px-2 py-1 text-xs rounded-full bg-${prioridadClass}-100 text-${prioridadClass}-700">${task.priority}</span></span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4"><span class="inline-flex px-2 py-1 text-xs rounded-full bg-${estadoClass}-100 text-${estadoClass}-700">${estadoText}</span></span><span class="sd-tag">    </span></td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex gap-2">
                        <button onclick="verTarea(${task.id})" class="text-indigo-600 hover:text-indigo-800" title="Ver detalle"><i class="fas fa-eye"></i></button>
                        ${task.status === 'pending' ? `<button onclick="cambiarEstado(${task.id}, 'in_progress')" class="text-yellow-600 hover:text-yellow-800" title="Iniciar"><i class="fas fa-play"></i></button>` : ''}
                        ${task.status === 'in_progress' ? `<button onclick="cambiarEstado(${task.id}, 'completed')" class="text-green-600 hover:text-green-800" title="Completar"><i class="fas fa-check"></i></button>` : ''}
                        <button onclick="eliminarTarea(${task.id})" class="text-red-600 hover:text-red-800" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </span><span class="sd-tag">    </span></td>
            </tr>
        `;
    }
    tbody.innerHTML = html;
}

function updateChart() {
    if (chartInstance) chartInstance.destroy();
    
    const countsByType = {};
    for (const task of filteredTasks) {
        countsByType[task.task_type] = (countsByType[task.task_type] || 0) + 1;
    }
    
    const labels = Object.keys(countsByType);
    const dataValues = Object.values(countsByType);
    const colores = ['#3B82F6', '#6366F1', '#10B981', '#F97316', '#8B5CF6', '#F43F5E'];
    const typeNames = { buque: 'Buque', container: 'Container', documental: 'Documental', inteligencia: 'Inteligencia', logistica: 'Logística', personal: 'Personal' };
    
    if (labels.length === 0) {
        document.getElementById('chartLegend').innerHTML = '<span class="text-gray-500 text-sm">No hay datos</span>';
        const ctx = document.getElementById('tasksChart')?.getContext('2d');
        if (ctx) { ctx.clearRect(0, 0, 400, 400); ctx.fillText('No hay datos', 200, 200); }
        return;
    }
    
    const ctx = document.getElementById('tasksChart').getContext('2d');
    chartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: { labels: labels.map(l => typeNames[l] || l), datasets: [{ data: dataValues, backgroundColor: colores.slice(0, labels.length), borderColor: '#ffffff', borderWidth: 2, hoverOffset: 12, cutout: '55%' }] },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom' }, tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.raw} (${Math.round((ctx.raw / dataValues.reduce((a,b)=>a+b,0))*100)}%)` } } } }
    });
    
    const legendDiv = document.getElementById('chartLegend');
    let legendHtml = '';
    for (let i = 0; i < labels.length; i++) {
        legendHtml += `<div class="flex items-center gap-1"><span class="w-3 h-3 rounded-full" style="background-color: ${colores[i]}"></span><span class="text-xs">${typeNames[labels[i]] || labels[i]} (${dataValues[i]})</span></div>`;
    }
    legendDiv.innerHTML = legendHtml;
}

function limpiarFiltros() {
    document.getElementById('filterTipo').value = '';
    document.getElementById('filterEstado').value = '';
    document.getElementById('filterPrioridad').value = '';
    document.getElementById('filterTitulo').value = '';
    applyFilters();
}

// Modal functions
function openCreateModal() { 
    document.getElementById('createTaskModal').classList.remove('hidden');
    setTimeout(initDatePicker, 100);
}
function closeCreateModal() { 
    document.getElementById('createTaskModal').classList.add('hidden');
}
function closeViewModal() { 
    document.getElementById('viewTaskModal').classList.add('hidden');
}

document.getElementById('createTaskForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    try {
        const res = await fetch('/api/risktasks/create', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
        const result = await res.json();
        if (result.success) { alert('Tarea creada correctamente'); closeCreateModal(); location.reload(); }
        else alert('Error: ' + (result.error || 'No se pudo crear'));
    } catch(e) { alert('Error de conexión'); }
});

async function verTarea(id) {
    if (!id) {
        alert('ID de tarea no válido');
        return;
    }
    
    try {
        const res = await fetch('/api/risktasks/get-task', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, csrf_token: csrfToken })
        });
        const task = await res.json();
        
        if (task.error) {
            alert('Error: ' + task.error);
            return;
        }
        
        const estadoText = { pending: 'Pendiente', in_progress: 'En progreso', completed: 'Completada', cancelled: 'Cancelada' }[task.status] || task.status;
        const prioridadClass = { baja: 'success', media: 'warning', alta: 'orange', critica: 'danger' }[task.priority] || 'secondary';
        
        document.getElementById('taskDetailContent').innerHTML = `
            <div class="space-y-3">
                <div><strong>Título:</strong> ${escapeHtml(task.title)}</div>
                <div><strong>Descripción:</strong><br><div class="p-2 bg-gray-50 rounded">${escapeHtml(task.description)}</div></div>
                <div class="grid grid-cols-2 gap-2">
                    <div><strong>Tipo:</strong> ${task.task_type}</div>
                    <div><strong>Organización:</strong> ${escapeHtml(task.organization_name || '-')}</div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div><strong>Asignado a:</strong> ${escapeHtml(task.assigned_name || 'No asignado')}</div>
                    <div><strong>Prioridad:</strong> <span class="inline-flex px-2 py-1 text-xs rounded-full bg-${prioridadClass}-100 text-${prioridadClass}-700">${task.priority}</span></div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div><strong>Fecha:</strong> ${task.scheduled_date}</div>
                    <div><strong>Hora:</strong> ${task.scheduled_time}</div>
                </div>
                <div><strong>Estado:</strong> ${estadoText}</div>
            </div>
        `;
        document.getElementById('viewTaskModal').classList.remove('hidden');
    } catch(e) {
        console.error(e);
        alert('Error al cargar la tarea: ' + e.message);
    }
}

async function cambiarEstado(id, status) {
    if (!confirm('¿Cambiar estado de esta tarea?')) return;
    try {
        const res = await fetch(`/api/risktasks/${id}/status`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ status, csrf_token: csrfToken }) });
        const result = await res.json();
        if (result.success) location.reload();
        else alert('Error al cambiar estado');
    } catch(e) { alert('Error de conexión'); }
}

async function eliminarTarea(id) {
    if (!confirm('¿Eliminar esta tarea permanentemente?')) return;
    try {
        const res = await fetch(`/api/risktasks/${id}/delete`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ csrf_token: csrfToken }) });
        const result = await res.json();
        if (result.success) location.reload();
        else alert('Error al eliminar');
    } catch(e) { alert('Error de conexión'); }
}

function escapeHtml(str) { if (!str) return ''; return str.replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m])); }

// Event Listeners
document.getElementById('btnNuevaTarea')?.addEventListener('click', openCreateModal);
document.getElementById('filterTipo')?.addEventListener('change', applyFilters);
document.getElementById('filterEstado')?.addEventListener('change', applyFilters);
document.getElementById('filterPrioridad')?.addEventListener('change', applyFilters);
document.getElementById('filterTitulo')?.addEventListener('input', applyFilters);
document.getElementById('btnLimpiarFiltros')?.addEventListener('click', limpiarFiltros);
document.getElementById('btnPrevPage')?.addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderTable(); } });
document.getElementById('btnNextPage')?.addEventListener('click', () => { const totalPages = Math.ceil(filteredTasks.length / itemsPerPage); if (currentPage < totalPages) { currentPage++; renderTable(); } });

setTimeout(() => { 
    setTypingMessage(assistantMessages.initial);
    initDatePicker();
}, 500);

loadOrganizations();
loadUsers();
loadTasks();
</script>
