<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Evaluación Detallada del Riesgo</h1>
        <button onclick="openEvalModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md flex items-center gap-2 transition duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Nueva Evaluación
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-800 text-white text-xs uppercase tracking-wider">
                        <th colspan="4" class="px-3 py-3 border-b border-gray-700 text-center">Identificación</th>
                        <th colspan="3" class="px-3 py-3 border-b border-gray-700 text-center">Análisis</th>
                        <th colspan="12" class="px-3 py-3 border-b border-gray-700 text-center">Evaluación del Riesgo</th>
                        <th rowspan="2" class="px-3 py-3 border-b border-gray-700 text-center">Acciones</th>
                    </tr>
                    <tr class="bg-gray-700 text-white text-xs">
                        <th class="px-2 py-2">Descripción</th>
                        <th class="px-2 py-2">Causa Raíz</th>
                        <th class="px-2 py-2">Clasificación</th>
                        <th class="px-2 py-2">Prob.</th>
                        <th class="px-2 py-2">Peso</th>
                        <th class="px-2 py-2">Impacto</th>
                        <th class="px-2 py-2">Peso</th>
                        <th class="px-2 py-2">Zona</th>
                        <th class="px-2 py-2">Controles</th>
                        <th class="px-2 py-2">Tipo</th>
                        <th class="px-2 py-2">Peso Tipo</th>
                        <th class="px-2 py-2">Implementación</th>
                        <th class="px-2 py-2">Peso Impl.</th>
                        <th class="px-2 py-2">Documentado</th>
                        <th class="px-2 py-2">Frecuencia</th>
                        <th class="px-2 py-2">Evidencia</th>
                        <th class="px-2 py-2">Valoración</th>
                        <th class="px-2 py-2">Prob. Residual</th>
                        <th class="px-2 py-2">Impacto Residual</th>
                        <th class="px-2 py-2">Zona Residual</th>
                    </tr>
                </thead>
                <tbody id="evalTbody" class="divide-y divide-gray-200 bg-white">
                    <tr><td colspan="21" class="text-center py-8 text-gray-500">Cargando evaluaciones...</span></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal mejorado -->
<div id="evalModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all duration-200">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800" id="evalModalTitle">Evaluación de Riesgo</h3>
            <button onclick="closeEvalModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="evalForm" class="p-6 space-y-4">
            <input type="hidden" name="csrf_token" id="csrf_eval" value="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción del Riesgo *</label>
                    <textarea id="descripcion" name="descripcion" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Causa Raíz *</label>
                    <textarea id="causaRaiz" name="causaRaiz" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Clasificación *</label>
                    <input type="text" id="clasificacion" name="clasificacion" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Probabilidad *</label>
                    <select id="probabilidad" name="probabilidad" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option>Muy Baja. Probabilidad 20%</option>
                        <option>Baja. Probabilidad 40%</option>
                        <option>Media. Probabilidad 60%</option>
                        <option>Alta. Probabilidad 80%</option>
                        <option>Muy Alta. Probabilidad 100%</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Peso Probabilidad *</label>
                    <input type="number" id="pesoProbabilidad" name="pesoProbabilidad" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Impacto *</label>
                    <select id="impacto" name="impacto" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option>Leve. Impacto 20%</option>
                        <option>Menor. Impacto 40%</option>
                        <option>Moderado. Impacto 60%</option>
                        <option>Mayor. Impacto 80%</option>
                        <option>Catastrófico. Impacto 100%</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Peso Impacto *</label>
                    <input type="number" id="pesoImpacto" name="pesoImpacto" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Zona de Riesgo *</label>
                    <select id="zonaRiesgo" name="zonaRiesgo" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option>Bajo</option><option>Moderado</option><option>Alto</option><option>Extremo</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Controles</label>
                    <textarea id="controles" name="controles" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo de Control</label>
                    <select id="tipo" name="tipo" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option>Preventivo</option><option>Detectivo</option><option>Correctivo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Peso Tipo</label>
                    <input type="text" id="pesoTipo" name="pesoTipo" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Implementación (%) *</label>
                    <input type="number" id="implementacion" name="implementacion" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Peso Implementación</label>
                    <input type="text" id="pesoImplementacion" name="pesoImplementacion" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Documentado</label>
                    <select id="documentado" name="documentado" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option>Si</option><option>No</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Frecuencia *</label>
                    <input id="frecuencia" name="frecuencia" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Evidencia *</label>
                    <input id="evidencia" name="evidencia" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Valoración *</label>
                    <input type="number" step="0.1" id="valoracion" name="valoracion" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Probabilidad Residual *</label>
                    <input id="probabilidadResidual" name="probabilidadResidual" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Impacto Residual *</label>
                    <input id="impactoResidual" name="impactoResidual" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Zona Residual *</label>
                    <select id="zonaResidual" name="zonaResidual" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option>Bajo</option><option>Moderado</option><option>Alto</option><option>Extremo</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEvalModal()" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-medium transition">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium shadow-sm transition">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    window.CSRF_TOKEN = csrf;
    let currentId = null;

    const tbody = document.getElementById('evalTbody');
    const modal = document.getElementById('evalModal');
    const form = document.getElementById('evalForm');

    function getZonaClass(zona) {
        switch(zona) {
            case 'Extremo': return 'bg-red-100 text-red-800 font-semibold';
            case 'Alto': return 'bg-orange-100 text-orange-800 font-semibold';
            case 'Moderado': return 'bg-yellow-100 text-yellow-800 font-semibold';
            case 'Bajo': return 'bg-green-100 text-green-800 font-semibold';
            default: return '';
        }
    }

    async function loadEvaluations() {
        tbody.innerHTML = '<tr><td colspan="21" class="text-center py-8 text-gray-500">Cargando...</span></td></tr>';
        const res = await fetch('/api/risk/evaluations');
        const data = await res.json();
        if (data.success && data.data.length) {
            tbody.innerHTML = data.data.map(e => `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-2 py-2 border-b">${escapeHtml(e.descripcion)}</td>
                <td class="px-2 py-2 border-b">${escapeHtml(e.causa_raiz)}</td>
                <td class="px-2 py-2 border-b">${escapeHtml(e.clasificacion)}</td>
                <td class="px-2 py-2 border-b">${escapeHtml(e.probabilidad)}</td>
                <td class="px-2 py-2 border-b text-center">${e.peso_probabilidad}</td>
                <td class="px-2 py-2 border-b">${escapeHtml(e.impacto)}</td>
                <td class="px-2 py-2 border-b text-center">${e.peso_impacto}</td>
                <td class="px-2 py-2 border-b text-center ${getZonaClass(e.zona_riesgo)}">${escapeHtml(e.zona_riesgo)}</td>
                <td class="px-2 py-2 border-b">${escapeHtml(e.controles)}</td>
                <td class="px-2 py-2 border-b text-center">${escapeHtml(e.tipo)}</td>
                <td class="px-2 py-2 border-b text-center">${e.peso_tipo}</td>
                <td class="px-2 py-2 border-b text-center">${e.implementacion}</td>
                <td class="px-2 py-2 border-b text-center">${e.peso_implementacion}</td>
                <td class="px-2 py-2 border-b text-center">${escapeHtml(e.documentado)}</td>
                <td class="px-2 py-2 border-b text-center">${escapeHtml(e.frecuencia)}</td>
                <td class="px-2 py-2 border-b">${escapeHtml(e.evidencia)}</td>
                <td class="px-2 py-2 border-b text-center">${e.valoracion}</td>
                <td class="px-2 py-2 border-b">${escapeHtml(e.probabilidad_residual)}</td>
                <td class="px-2 py-2 border-b">${escapeHtml(e.impacto_residual)}</td>
                <td class="px-2 py-2 border-b text-center ${getZonaClass(e.zona_residual)}">${escapeHtml(e.zona_residual)}</td>
                <td class="px-2 py-2 border-b text-center whitespace-nowrap">
                    <button onclick="editEval(${e.id})" class="text-indigo-600 hover:text-indigo-800 mx-1" title="Editar"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                    <button onclick="deleteEval(${e.id})" class="text-red-600 hover:text-red-800 mx-1" title="Eliminar"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                </td>
            </table>`).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="21" class="text-center py-8 text-gray-500">No hay evaluaciones registradas</span></td></tr>';
        }
    }

    window.openEvalModal = () => { 
        currentId = null; 
        form.reset(); 
        document.getElementById('csrf_eval').value = csrf; 
        modal.classList.remove('hidden'); 
        modal.classList.add('flex'); 
    };

    window.closeEvalModal = () => { 
        modal.classList.add('hidden'); 
        modal.classList.remove('flex'); 
    };

    window.editEval = async (id) => {
        console.log('Editando evaluación ID:', id);
        try {
            const res = await fetch(`/api/risk/evaluations/${id}`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();
            console.log('Datos recibidos:', data);
            if (data.success) {
                const e = data.data;
                currentId = id;
                document.getElementById('descripcion').value = e.descripcion || '';
                document.getElementById('causaRaiz').value = e.causa_raiz || '';
                document.getElementById('clasificacion').value = e.clasificacion || '';
                document.getElementById('probabilidad').value = e.probabilidad || '';
                document.getElementById('pesoProbabilidad').value = e.peso_probabilidad || '';
                document.getElementById('impacto').value = e.impacto || '';
                document.getElementById('pesoImpacto').value = e.peso_impacto || '';
                document.getElementById('zonaRiesgo').value = e.zona_riesgo || '';
                document.getElementById('controles').value = e.controles || '';
                document.getElementById('tipo').value = e.tipo || '';
                document.getElementById('pesoTipo').value = e.peso_tipo || '';
                document.getElementById('implementacion').value = e.implementacion || '';
                document.getElementById('pesoImplementacion').value = e.peso_implementacion || '';
                document.getElementById('documentado').value = e.documentado || '';
                document.getElementById('frecuencia').value = e.frecuencia || '';
                document.getElementById('evidencia').value = e.evidencia || '';
                document.getElementById('valoracion').value = e.valoracion || '';
                document.getElementById('probabilidadResidual').value = e.probabilidad_residual || '';
                document.getElementById('impactoResidual').value = e.impacto_residual || '';
                document.getElementById('zonaResidual').value = e.zona_residual || '';
                // Abrir modal después de asignar valores
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.getElementById('evalModalTitle').innerText = 'Editar Evaluación de Riesgo';
            } else {
                alert('Error al cargar los datos de la evaluación');
            }
        } catch (err) {
            console.error('Error en editEval:', err);
            alert('Error de conexión al cargar los datos');
        }
    };

    window.deleteEval = async (id) => {
        if(!confirm('¿Eliminar esta evaluación permanentemente?')) return;
        const fd = new URLSearchParams();
        fd.append('csrf_token', csrf);
        try {
            const response = await fetch(`/api/risk/evaluations/${id}/delete`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: fd
            });
            const result = await response.json();
            if (result.success) {
                alert('Evaluación eliminada correctamente');
                loadEvaluations();
            } else {
                alert('Error: ' + (result.error || 'No se pudo eliminar'));
            }
        } catch (err) {
            console.error(err);
            alert('Error de conexión: ' + err.message);
        }
    };

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Validaciones mínimas
        if (!document.getElementById('descripcion').value.trim()) {
            alert('La descripción es obligatoria');
            return;
        }
        if (!document.getElementById('causaRaiz').value.trim()) {
            alert('La causa raíz es obligatoria');
            return;
        }
        if (!document.getElementById('pesoProbabilidad').value) {
            alert('El peso de probabilidad es obligatorio');
            return;
        }
        if (!document.getElementById('pesoImpacto').value) {
            alert('El peso del impacto es obligatorio');
            return;
        }
        if (!document.getElementById('implementacion').value) {
            alert('La implementación es obligatoria');
            return;
        }
        if (!document.getElementById('valoracion').value) {
            alert('La valoración es obligatoria');
            return;
        }

        const formData = new URLSearchParams();
        formData.append('csrf_token', csrf);
        for (let el of form.elements) {
            if (el.name && el.value) {
                formData.append(el.name, el.value);
            }
        }

        let url = '/api/risk/evaluations';
        if (currentId) {
            url = `/api/risk/evaluations/${currentId}/update`;
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                alert(currentId ? 'Evaluación actualizada' : 'Evaluación creada');
                closeEvalModal();
                loadEvaluations();
            } else {
                alert('Error: ' + (result.error || 'No se pudo guardar'));
            }
        } catch (err) {
            console.error(err);
            alert('Error de conexión: ' + err.message);
        }
    });

    loadEvaluations();
});
function escapeHtml(str){ if(!str) return ''; return str.replace(/[&<>]/g, m=>({'&':'&amp;','<':'&lt;','>':'&gt;'}[m])); }
</script>
