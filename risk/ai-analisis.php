<div class="container mx-auto px-4 py-6">
    <!-- Header con estilo consistente -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                <i class="fas fa-brain text-indigo-600 text-3xl"></i>
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-800">Optimixs Risk AI</h1>
                <p class="text-gray-600 mt-1">Análisis inteligente de reportes usando el Teorema de Bayes</p>
                <p class="text-gray-500 text-sm mt-1">Selecciona un reporte y la IA analizará el riesgo basándose en las evidencias encontradas</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-line text-4xl text-gray-300"></i>
            </div>
        </div>
    </div>

    <!-- Chat animado estilo IA con efecto de escritura -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-microchip text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-white text-lg">Asistente IA</h3>
                    <p class="text-xs text-indigo-100">Analista de riesgos · Online</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-sm text-white/80">En linea</span>
                <button id="toggleChatBtn" class="text-white/70 hover:text-white transition ml-2">
                    <i class="fas fa-chevron-up"></i>
                </button>
            </div>
        </div>
        
        <div id="chatContent" class="p-6 space-y-4 h-[500px] overflow-y-auto">
            <div class="flex justify-center items-center gap-2 text-gray-400 py-4" id="chatLoading">
                <i class="fas fa-circle-notch fa-spin"></i>
                <span class="text-sm">Inicializando asistente...</span>
            </div>
        </div>
    </div>

    <!-- Selector de reporte -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Seleccionar reporte para analizar</label>
                <select id="reporteSelector" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">-- Cargando reportes... --</option>
                </select>
            </div>
            <div class="md:col-span-1">
                <label class="block text-sm font-semibold text-gray-700 mb-1">&nbsp;</label>
                <button id="btnAnalizar" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <i class="fas fa-chart-line"></i> Analizar con IA Bayesiana
                </button>
            </div>
            <div class="md:col-span-1">
                <label class="block text-sm font-semibold text-gray-700 mb-1">&nbsp;</label>
                <button id="btnGuardar" class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <i class="fas fa-save"></i> Guardar Análisis
                </button>
            </div>
        </div>
    </div>

    <!-- Resultados del análisis -->
    <div id="resultadosContainer" class="hidden">
        <div id="loadingIndicator" class="bg-white rounded-xl shadow-md border border-gray-200 p-8 text-center hidden">
            <i class="fas fa-spinner fa-spin text-indigo-600 text-4xl mb-4"></i>
            <p class="text-gray-600">Analizando reporte con inteligencia artificial...</p>
        </div>

        <div id="resultadosContent" class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold" id="resultadoTitulo">Resultado del Análisis</h2>
                        <p class="text-gray-300 text-sm mt-1" id="resultadoReporte">Reporte: -</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400" id="resultadoFecha">Fecha: -</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Indicador de guardado -->
                <div id="saveIndicator" class="hidden mb-4 p-3 rounded-lg text-center"></div>

                <div id="conclusionCard" class="mb-6 p-4 rounded-xl border-l-4">
                    <p class="text-lg" id="conclusionTexto">Cargando...</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-sm text-gray-500 mb-2">Probabilidad a priori</p>
                        <p class="text-4xl font-bold text-indigo-600" id="priorValor">0%</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div id="priorBar" class="bg-indigo-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-sm text-gray-500 mb-2">Probabilidad posterior (Bayes)</p>
                        <p class="text-4xl font-bold text-purple-600" id="posteriorValor">0%</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div id="posteriorBar" class="bg-purple-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <div class="mb-6 p-4 rounded-xl text-center" id="riesgoCard">
                    <p class="text-sm font-semibold mb-1">Nivel de Riesgo</p>
                    <p class="text-2xl font-bold" id="riesgoNivel">-</p>
                </div>

                <div class="mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-search text-indigo-600"></i> Evidencias detectadas
                    </h3>
                    <div id="evidenciasList" class="space-y-2">
                        <p class="text-gray-500 text-sm">No se detectaron evidencias</p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-lightbulb text-yellow-600"></i> Recomendaciones de la IA
                    </h3>
                    <div id="recomendacionesList" class="space-y-2">
                        <p class="text-gray-500 text-sm">No hay recomendaciones disponibles</p>
                    </div>
                </div>

                <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-200">
                    <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                        <i class="fas fa-chart-line text-indigo-600"></i> Fórmula aplicada
                    </h3>
                    <p class="text-sm text-gray-700 font-mono mb-2">P(R|E) = P(E|R) × P(R) / [P(E|R) × P(R) + P(E|¬R) × (1-P(R))]</p>
                    <p class="text-xs text-gray-500">El teorema de Bayes actualiza la probabilidad de un riesgo basándose en las evidencias encontradas.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Usar el token CSRF global definido en layout (window.CSRF_TOKEN)
let currentAnalysisResult = null;
let currentReportName = '';

// Función de escritura tipo máquina (typing effect)
async function typeMessage(element, html, speed = 20) {
    element.innerHTML = '';
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;
    const plainText = tempDiv.textContent || tempDiv.innerText;
    
    let i = 0;
    const typingInterval = setInterval(() => {
        if (i < plainText.length) {
            element.innerHTML = plainText.substring(0, i + 1);
            i++;
        } else {
            clearInterval(typingInterval);
            element.innerHTML = html;
        }
    }, speed);
    await new Promise(resolve => setTimeout(resolve, plainText.length * speed + 500));
}

// Mensajes iniciales del chat
const chatMessages = [
    { icon: 'fa-brain', bg: 'bg-indigo-100', color: 'text-indigo-600', text: '¡Hola! Soy tu asistente de IA. El <strong>Análisis Bayesiano</strong> es un método estadístico que actualiza la probabilidad de un riesgo a medida que obtenemos nuevas evidencias.', delay: 0 },
    { icon: 'fa-chart-line', bg: 'bg-purple-100', color: 'text-purple-600', text: 'Su núcleo es el <strong>Teorema de Bayes</strong>, que combina conocimientos previos con datos nuevos para obtener resultados más precisos.<br><br><code class="text-xs bg-gray-100 p-1 rounded">P(R|E) = P(E|R) × P(R) / [P(E|R) × P(R) + P(E|¬R) × (1-P(R))]</code>', delay: 2000 },
    { icon: 'fa-cogs', bg: 'bg-green-100', color: 'text-green-600', text: '<strong>¿Cómo funciona en la práctica?</strong><br><br>1️⃣ <strong>Creencia previa (A priori):</strong> Establecemos una probabilidad inicial basada en tu experiencia.<br>2️⃣ <strong>Nueva evidencia:</strong> El sistema analiza el reporte en busca de palabras clave.<br>3️⃣ <strong>Probabilidad actualizada (A posteriori):</strong> Combinamos lo que sabíamos con los datos recientes.', delay: 4000 },
    { icon: 'fa-chart-bar', bg: 'bg-orange-100', color: 'text-orange-600', text: 'A diferencia de la estadística tradicional (que solo mira los datos actuales), el análisis bayesiano sigue un proceso <strong>iterativo</strong> que mejora cada predicción.', delay: 6000 }
];

async function initChat() {
    const container = document.getElementById('chatContent');
    container.innerHTML = '';
    
    for (let i = 0; i < chatMessages.length; i++) {
        const msg = chatMessages[i];
        
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex gap-3 opacity-0 transform translate-y-2 transition-all duration-500';
        messageDiv.style.transitionDelay = `${msg.delay}ms`;
        
        messageDiv.innerHTML = `
            <div class="w-8 h-8 ${msg.bg} rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas ${msg.icon} ${msg.color} text-xs"></i>
            </div>
            <div class="flex-1 bg-gray-50 rounded-xl p-3">
                <div class="message-text text-sm text-gray-700"></div>
            </div>
        `;
        
        container.appendChild(messageDiv);
        
        setTimeout(() => {
            messageDiv.classList.remove('opacity-0', 'translate-y-2');
            messageDiv.classList.add('opacity-100', 'translate-y-0');
        }, msg.delay);
        
        setTimeout(async () => {
            const textDiv = messageDiv.querySelector('.message-text');
            await typeMessage(textDiv, msg.text, 15);
        }, msg.delay + 500);
        
        await new Promise(resolve => setTimeout(resolve, msg.delay + 1000));
    }
    
    // Mensaje de bienvenida final
    const welcomeDiv = document.createElement('div');
    welcomeDiv.className = 'flex gap-3 opacity-0 transform translate-y-2 transition-all duration-500 mt-2';
    welcomeDiv.innerHTML = `
        <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-robot text-white text-xs"></i>
        </div>
        <div class="flex-1 bg-indigo-50 rounded-xl p-3 border border-indigo-200">
            <div class="message-text text-sm text-indigo-800 font-medium"></div>
        </div>
    `;
    container.appendChild(welcomeDiv);
    
    setTimeout(() => {
        welcomeDiv.classList.remove('opacity-0', 'translate-y-2');
        welcomeDiv.classList.add('opacity-100', 'translate-y-0');
    }, chatMessages[chatMessages.length-1].delay + 1500);
    
    setTimeout(async () => {
        const textDiv = welcomeDiv.querySelector('.message-text');
        await typeMessage(textDiv, '✨ Estoy aquí para ayudarte a analizar riesgos con inteligencia artificial. Selecciona un reporte del menú desplegable.', 20);
    }, chatMessages[chatMessages.length-1].delay + 2000);
}

// Mensaje cuando se selecciona un reporte
let lastSelectedReport = null;

async function onReportSelected(reportId, reportName) {
    if (!reportId || reportId === lastSelectedReport) return;
    lastSelectedReport = reportId;
    currentReportName = reportName;
    
    const container = document.getElementById('chatContent');
    
    const oldSelectionMsg = document.querySelector('.selection-message');
    if (oldSelectionMsg) oldSelectionMsg.remove();
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'selection-message flex gap-3 opacity-0 transform translate-y-2 transition-all duration-500 mt-2';
    messageDiv.innerHTML = `
        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check-circle text-green-600 text-xs"></i>
        </div>
        <div class="flex-1 bg-green-50 rounded-xl p-3 border border-green-200">
            <div class="message-text text-sm text-gray-700"></div>
        </div>
    `;
    container.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.classList.remove('opacity-0', 'translate-y-2');
        messageDiv.classList.add('opacity-100', 'translate-y-0');
        container.scrollTop = container.scrollHeight;
    }, 100);
    
    const selectionText = `✅ Perfecto, has seleccionado el reporte: <strong>${escapeHtml(reportName)}</strong>. Ahora haz clic en <strong>"Analizar con IA Bayesiana"</strong> para evaluar la probabilidad del riesgo.`;
    
    setTimeout(async () => {
        const textDiv = messageDiv.querySelector('.message-text');
        await typeMessage(textDiv, selectionText, 12);
        container.scrollTop = container.scrollHeight;
    }, 200);
}

// Mensaje después del análisis
async function addAnalysisMessage(reportName, posterior, prior) {
    const container = document.getElementById('chatContent');
    const nivel = posterior >= 70 ? 'CRÍTICO' : (posterior >= 50 ? 'ALTO' : (posterior >= 30 ? 'MODERADO' : 'BAJO'));
    const nivelColor = posterior >= 70 ? 'red' : (posterior >= 50 ? 'orange' : (posterior >= 30 ? 'yellow' : 'green'));
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex gap-3 opacity-0 transform translate-y-2 transition-all duration-500 mt-2';
    messageDiv.innerHTML = `
        <div class="w-8 h-8 bg-${nivelColor}-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-chart-line text-${nivelColor}-600 text-xs"></i>
        </div>
        <div class="flex-1 bg-${nivelColor}-50 rounded-xl p-3 border border-${nivelColor}-200">
            <div class="message-text text-sm text-gray-700"></div>
        </div>
    `;
    container.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.classList.remove('opacity-0', 'translate-y-2');
        messageDiv.classList.add('opacity-100', 'translate-y-0');
        container.scrollTop = container.scrollHeight;
    }, 100);
    
    const analysisText = `📊 He analizado el reporte "<strong>${escapeHtml(reportName)}</strong>" y los resultados son:<br><br>• La probabilidad a priori era: <strong>${prior}%</strong><br>• La probabilidad posterior calculada es: <strong>${posterior}%</strong><br>• Nivel de riesgo: <strong class="text-${nivelColor}-600">${nivel}</strong><br><br>Te recomiendo revisar las evidencias detectadas y las recomendaciones generadas en el panel de resultados.`;
    
    setTimeout(async () => {
        const textDiv = messageDiv.querySelector('.message-text');
        await typeMessage(textDiv, analysisText, 12);
        container.scrollTop = container.scrollHeight;
    }, 200);
}

// Función para guardar el análisis - VERSIÓN CORREGIDA CON NOMBRE PROPER
async function guardarAnalisis() {
    if (!currentAnalysisResult) {
        alert('Primero debes analizar un reporte');
        return;
    }
    
    const btnGuardar = document.getElementById('btnGuardar');
    const originalText = btnGuardar.innerHTML;
    
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    
    // Usar el token CSRF global
    const csrfTokenValue = window.CSRF_TOKEN || '';
    
    if (!csrfTokenValue) {
        console.error('No CSRF token found');
        alert('Error de seguridad: No se encontró token CSRF');
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = originalText;
        return;
    }
    
    // Obtener el nombre del reporte correctamente
    let nombreReporte = '';
    
    if (currentAnalysisResult.report_nombre) {
        nombreReporte = currentAnalysisResult.report_nombre;
    } else if (currentAnalysisResult.report_name) {
        nombreReporte = currentAnalysisResult.report_name;
    } else if (currentAnalysisResult.nombre) {
        nombreReporte = currentAnalysisResult.nombre;
    } else {
        nombreReporte = `Reporte #${currentAnalysisResult.report_id || 'desconocido'}`;
    }
    
    // Generar nombre descriptivo para el análisis guardado
    const fechaStr = new Date().toISOString().slice(0, 19).replace('T', ' ');
    const posterior = currentAnalysisResult.posterior || 0;
    const nivel = posterior >= 70 ? 'CRÍTICO' : (posterior >= 50 ? 'ALTO' : (posterior >= 30 ? 'MODERADO' : 'BAJO'));
    
    // Nombre completo para mostrar en el dashboard
    const nombreAnalisis = `📊 Análisis IA - ${nombreReporte.substring(0, 50)} - ${nivel} - ${fechaStr}`;
    
    // Preparar datos para enviar
    const datosAGuardar = {
        report_id: parseInt(currentAnalysisResult.report_id) || 0,
        report_nombre: nombreAnalisis,
        prior: parseFloat(currentAnalysisResult.prior) || 50,
        posterior: parseFloat(currentAnalysisResult.posterior) || 0,
        evidencias: Array.isArray(currentAnalysisResult.evidencias) ? currentAnalysisResult.evidencias : [],
        recomendaciones: Array.isArray(currentAnalysisResult.recomendaciones) ? currentAnalysisResult.recomendaciones : []
    };
    
    console.log('Enviando datos:', JSON.stringify(datosAGuardar, null, 2));
    
    try {
        const response = await fetch('/api/risk-ai/save', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfTokenValue
            },
            body: JSON.stringify(datosAGuardar)
        });
        
        const responseText = await response.text();
        console.log('Response:', responseText);
        
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            console.error('Error parsing JSON:', e);
            throw new Error('Respuesta inválida del servidor');
        }
        
        const saveIndicator = document.getElementById('saveIndicator');
        
        if (data.success) {
            saveIndicator.className = 'mb-4 p-3 rounded-lg text-center bg-green-100 text-green-800 border border-green-300';
            saveIndicator.innerHTML = `<i class="fas fa-check-circle"></i> ✅ Análisis guardado correctamente (ID: ${data.analysis_id})`;
            saveIndicator.classList.remove('hidden');
            
            if (typeof addSaveConfirmationMessage === 'function') {
                await addSaveConfirmationMessage(data.analysis_id);
            }
            
            setTimeout(() => {
                saveIndicator.classList.add('hidden');
            }, 5000);
        } else {
            saveIndicator.className = 'mb-4 p-3 rounded-lg text-center bg-red-100 text-red-800 border border-red-300';
            saveIndicator.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ❌ Error: ${data.error || 'Error desconocido'}`;
            saveIndicator.classList.remove('hidden');
            
            setTimeout(() => {
                saveIndicator.classList.add('hidden');
            }, 5000);
        }
    } catch (error) {
        console.error('Error:', error);
        const saveIndicator = document.getElementById('saveIndicator');
        saveIndicator.className = 'mb-4 p-3 rounded-lg text-center bg-red-100 text-red-800 border border-red-300';
        saveIndicator.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ❌ Error: ${error.message}`;
        saveIndicator.classList.remove('hidden');
        
        setTimeout(() => {
            saveIndicator.classList.add('hidden');
        }, 5000);
    } finally {
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = originalText;
    }
}

// Mensaje de confirmación de guardado en el chat
async function addSaveConfirmationMessage(analysisId) {
    const container = document.getElementById('chatContent');
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex gap-3 opacity-0 transform translate-y-2 transition-all duration-500 mt-2';
    messageDiv.innerHTML = `
        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-database text-blue-600 text-xs"></i>
        </div>
        <div class="flex-1 bg-blue-50 rounded-xl p-3 border border-blue-200">
            <div class="message-text text-sm text-gray-700"></div>
        </div>
    `;
    container.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.classList.remove('opacity-0', 'translate-y-2');
        messageDiv.classList.add('opacity-100', 'translate-y-0');
        container.scrollTop = container.scrollHeight;
    }, 100);
    
    const saveText = `💾 El análisis ha sido guardado en la base de datos con ID <strong>${analysisId}</strong>. Podrás consultarlo más tarde en el historial de análisis.`;
    
    setTimeout(async () => {
        const textDiv = messageDiv.querySelector('.message-text');
        await typeMessage(textDiv, saveText, 12);
        container.scrollTop = container.scrollHeight;
    }, 200);
}

// Toggle del chat
document.getElementById('toggleChatBtn')?.addEventListener('click', function() {
    const chatContent = document.getElementById('chatContent');
    const icon = this.querySelector('i');
    if (chatContent.classList.contains('h-[500px]')) {
        chatContent.classList.remove('h-[500px]');
        chatContent.classList.add('h-48');
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    } else {
        chatContent.classList.remove('h-48');
        chatContent.classList.add('h-[500px]');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    }
});

async function loadReports() {
    try {
        const res = await fetch('/api/ai/reports/list');
        const data = await res.json();
        if (data.success) {
            const selector = document.getElementById('reporteSelector');
            selector.innerHTML = '<option value="">-- Seleccionar un reporte --</option>';
            data.data.forEach(report => {
                const opt = document.createElement('option');
                opt.value = report.id;
                opt.textContent = `${report.nombre} - ${report.riesgo_nombre} - ${report.usuario_nombre}`;
                selector.appendChild(opt);
            });
            document.getElementById('btnAnalizar').disabled = false;
        }
    } catch(e) { console.error(e); }
}

async function analizarReporte() {
    const reportId = document.getElementById('reporteSelector').value;
    const reportName = document.getElementById('reporteSelector').options[document.getElementById('reporteSelector').selectedIndex]?.text || '';
    
    if (!reportId) {
        alert('Selecciona un reporte para analizar');
        return;
    }
    
    document.getElementById('resultadosContainer').classList.remove('hidden');
    document.getElementById('loadingIndicator').classList.remove('hidden');
    document.getElementById('resultadosContent').classList.add('hidden');
    document.getElementById('btnGuardar').disabled = true;
    
    const csrfTokenValue = window.CSRF_TOKEN || '';
    
    try {
        const res = await fetch(`/api/ai/reports/${reportId}/analyze`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-Token': csrfTokenValue
            }
        });
        const data = await res.json();
        
        if (data.success) {
            // Asegurar que report_nombre esté presente
            if (!data.report_nombre && reportName) {
                data.report_nombre = reportName;
            }
            currentAnalysisResult = data;
            renderResults(data);
            await addAnalysisMessage(reportName, data.posterior, data.prior);
            document.getElementById('btnGuardar').disabled = false;
        } else {
            alert('Error: ' + (data.error || 'No se pudo analizar'));
        }
    } catch(e) {
        alert('Error de conexión: ' + e.message);
    } finally {
        document.getElementById('loadingIndicator').classList.add('hidden');
        document.getElementById('resultadosContent').classList.remove('hidden');
    }
}

function renderResults(resultData) {
    document.getElementById('resultadoTitulo').innerText = '📊 Resultado del Análisis Bayesiano';
    document.getElementById('resultadoReporte').innerHTML = `<strong>Reporte:</strong> ${escapeHtml(resultData.report_nombre)}`;
    document.getElementById('resultadoFecha').innerHTML = `<strong>Fecha análisis:</strong> ${resultData.fecha_analisis}`;
    document.getElementById('conclusionTexto').innerHTML = resultData.conclusion;
    
    const nivel = resultData.nivel_riesgo;
    const conclusionCard = document.getElementById('conclusionCard');
    const riesgoCard = document.getElementById('riesgoCard');
    
    conclusionCard.classList.remove('border-red-500', 'border-orange-500', 'border-yellow-500', 'border-green-500');
    riesgoCard.classList.remove('bg-red-100', 'bg-orange-100', 'bg-yellow-100', 'bg-green-100');
    
    if (nivel.nivel === 'Crítico') {
        conclusionCard.classList.add('border-red-500', 'bg-red-50');
        riesgoCard.classList.add('bg-red-100', 'text-red-800');
    } else if (nivel.nivel === 'Alto') {
        conclusionCard.classList.add('border-orange-500', 'bg-orange-50');
        riesgoCard.classList.add('bg-orange-100', 'text-orange-800');
    } else if (nivel.nivel === 'Moderado') {
        conclusionCard.classList.add('border-yellow-500', 'bg-yellow-50');
        riesgoCard.classList.add('bg-yellow-100', 'text-yellow-800');
    } else {
        conclusionCard.classList.add('border-green-500', 'bg-green-50');
        riesgoCard.classList.add('bg-green-100', 'text-green-800');
    }
    
    document.getElementById('priorValor').innerText = resultData.prior + '%';
    document.getElementById('posteriorValor').innerText = resultData.posterior + '%';
    document.getElementById('priorBar').style.width = resultData.prior + '%';
    document.getElementById('posteriorBar').style.width = resultData.posterior + '%';
    
    document.getElementById('riesgoNivel').innerHTML = `<span class="px-4 py-2 rounded-full ${nivel.bg} ${nivel.text} font-bold">${nivel.nivel}</span>`;
    
    const evidenciasDiv = document.getElementById('evidenciasList');
    if (resultData.evidencias && resultData.evidencias.length > 0) {
        let evHtml = '';
        resultData.evidencias.forEach(e => {
            evHtml += `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <span class="font-medium text-gray-800">🔍 ${escapeHtml(e.descripcion)}</span>
                        <p class="text-xs text-gray-500 mt-1">P(E|R): ${e.peh}% | P(E|¬R): ${e.penh}%</p>
                    </div>
                    <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full">Detectado</span>
                </div>
            `;
        });
        evidenciasDiv.innerHTML = evHtml;
    } else {
        evidenciasDiv.innerHTML = '<p class="text-gray-500 text-sm">No se detectaron evidencias específicas</p>';
    }
    
    const recomendacionesDiv = document.getElementById('recomendacionesList');
    if (resultData.recomendaciones && resultData.recomendaciones.length > 0) {
        let recHtml = '';
        resultData.recomendaciones.forEach(rec => {
            recHtml += `
                <div class="flex items-start gap-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <i class="fas fa-lightbulb text-yellow-600 mt-0.5"></i>
                    <p class="text-sm text-gray-700">${escapeHtml(rec)}</p>
                </div>
            `;
        });
        recomendacionesDiv.innerHTML = recHtml;
    } else {
        recomendacionesDiv.innerHTML = '<p class="text-gray-500 text-sm">No hay recomendaciones adicionales</p>';
    }
}

// Eventos
document.getElementById('reporteSelector').addEventListener('change', (e) => {
    const reportId = e.target.value;
    const reportName = e.target.options[e.target.selectedIndex]?.text || '';
    document.getElementById('btnAnalizar').disabled = !reportId;
    document.getElementById('btnGuardar').disabled = true;
    currentAnalysisResult = null;
    if (reportId) {
        onReportSelected(reportId, reportName);
    }
});
document.getElementById('btnAnalizar').addEventListener('click', analizarReporte);
document.getElementById('btnGuardar').addEventListener('click', guardarAnalisis);

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m]));
}

// Inicializar
initChat();
loadReports();
</script>

<style>
.h-500px {
    height: 500px;
}
.h-48 {
    height: 12rem;
}
.transition-all {
    transition: all 0.3s ease-in-out;
}
.message-text code {
    display: inline-block;
    background: #f3f4f6;
    padding: 4px 8px;
    border-radius: 6px;
    font-family: monospace;
    font-size: 11px;
}
#chatContent::-webkit-scrollbar {
    width: 6px;
}
#chatContent::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}
#chatContent::-webkit-scrollbar-thumb {
    background: #c7d2fe;
    border-radius: 3px;
}
#chatContent::-webkit-scrollbar-thumb:hover {
    background: #818cf8;
}
</style>
