<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Organización y Procesos Clave</h1>

    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4" id="formTitle">Nueva organización</h2>
        <form id="orgForm" class="space-y-4">
            <input type="hidden" id="editId">
            
            <!-- Datos de la organización -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre *</label>
                    <input type="text" id="orgNombre" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">RUC / CI</label>
                    <input type="text" id="orgRuc" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Número de identificación tributaria o cédula">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Sector</label>
                    <input type="text" id="orgSector" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Ej: Agroindustria, Tecnología, Salud...">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
                    <input type="tel" id="orgTelefono" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Ej: (021) 123-4567">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Dirección</label>
                    <input type="text" id="orgDireccion" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Calle, número, ciudad, código postal">
                </div>
            </div>

            <!-- Proceso Clave -->
            <div class="border-t pt-4 mt-2">
                <h3 class="font-semibold text-gray-800 mb-3">Proceso Clave</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre del proceso *</label>
                        <input type="text" id="procesoNombre" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Justificación</label>
                        <textarea id="procesoJustificacion" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Describa la importancia y propósito de este proceso clave..."></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" id="btnCancelar" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition hidden">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-save mr-1"></i> Guardar
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
        <div class="px-6 py-3 border-b bg-gray-50">
            <h2 class="font-semibold text-gray-800">📋 Organizaciones registradas</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">RUC/CI</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sector</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dirección</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proceso clave</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody id="organizacionesTbody">
                    <tr><td colspan="7" class="text-center py-8 text-gray-500">Cargando...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const tbody = document.getElementById('organizacionesTbody');
    const form = document.getElementById('orgForm');
    const btnCancelar = document.getElementById('btnCancelar');
    const editId = document.getElementById('editId');
    const formTitle = document.getElementById('formTitle');

    async function cargar() {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-500">Cargando...</td></tr>';
        try {
            const res = await fetch('/api/risk/organizations');
            const data = await res.json();
            if (data.success && data.data && data.data.length) {
                tbody.innerHTML = data.data.map(o => `
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">${escapeHtml(o.nombre)}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">${escapeHtml(o.ruc || '-')}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">${escapeHtml(o.sector || '-')}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">${escapeHtml(o.telefono || '-')}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">${escapeHtml(o.direccion || '-')}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="font-medium text-gray-800">${escapeHtml(o.proceso_nombre)}</span>
                            ${o.proceso_justificacion ? `<br><span class="text-xs text-gray-500">${escapeHtml(o.proceso_justificacion.substring(0, 50))}${o.proceso_justificacion.length > 50 ? '...' : ''}</span>` : ''}
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <button onclick="editar(${o.id})" class="text-indigo-600 hover:text-indigo-800 mr-3 transition" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="eliminar(${o.id})" class="text-red-600 hover:text-red-800 transition" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-500">No hay organizaciones registradas</td></tr>';
            }
        } catch(e) { 
            console.error(e);
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-red-500">Error al cargar los datos</td></tr>'; 
        }
    }

    window.editar = async (id) => {
        try {
            const res = await fetch(`/api/risk/organizations/${id}`);
            const data = await res.json();
            if(data.success && data.data) {
                document.getElementById('orgNombre').value = data.data.nombre || '';
                document.getElementById('orgRuc').value = data.data.ruc || '';
                document.getElementById('orgSector').value = data.data.sector || '';
                document.getElementById('orgTelefono').value = data.data.telefono || '';
                document.getElementById('orgDireccion').value = data.data.direccion || '';
                document.getElementById('procesoNombre').value = data.data.proceso_nombre || '';
                document.getElementById('procesoJustificacion').value = data.data.proceso_justificacion || '';
                editId.value = id;
                formTitle.innerText = 'Editar organización';
                btnCancelar.classList.remove('hidden');
                document.getElementById('orgNombre').focus();
            }
        } catch(e) {
            console.error(e);
            alert('Error al cargar los datos de la organización');
        }
    };

    window.eliminar = async (id) => {
        if(!confirm('¿Eliminar esta organización permanentemente?')) return;
        const fd = new URLSearchParams();
        fd.append('csrf_token', csrf);
        try {
            const response = await fetch(`/api/risk/organizations/${id}/delete`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: fd
            });
            const result = await response.json();
            if (result.success) {
                alert('Organización eliminada correctamente');
                if(editId.value == id) limpiar();
                cargar();
            } else {
                alert('Error: ' + (result.error || 'No se pudo eliminar'));
            }
        } catch (err) {
            console.error(err);
            alert('Error de conexión: ' + err.message);
        }
    };

    function limpiar() { 
        form.reset(); 
        editId.value = ''; 
        formTitle.innerText = 'Nueva organización'; 
        btnCancelar.classList.add('hidden');
    }
    
    btnCancelar.onclick = limpiar;

    form.onsubmit = async (e) => {
        e.preventDefault();
        
        const nombre = document.getElementById('orgNombre').value.trim();
        if (!nombre) {
            alert('El nombre de la organización es obligatorio');
            document.getElementById('orgNombre').focus();
            return;
        }
        
        const procesoNombre = document.getElementById('procesoNombre').value.trim();
        if (!procesoNombre) {
            alert('El nombre del proceso clave es obligatorio');
            document.getElementById('procesoNombre').focus();
            return;
        }
        
        const fd = new URLSearchParams();
        fd.append('csrf_token', csrf);
        fd.append('nombre', nombre);
        fd.append('ruc', document.getElementById('orgRuc').value.trim());
        fd.append('sector', document.getElementById('orgSector').value.trim());
        fd.append('telefono', document.getElementById('orgTelefono').value.trim());
        fd.append('direccion', document.getElementById('orgDireccion').value.trim());
        fd.append('proceso_nombre', procesoNombre);
        fd.append('proceso_justificacion', document.getElementById('procesoJustificacion').value.trim());

        let url = '/api/risk/organizations';
        if (editId.value) {
            url = `/api/risk/organizations/${editId.value}/update`;
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: fd
            });
            const result = await response.json();
            if (result.success) {
                alert(editId.value ? 'Organización actualizada correctamente' : 'Organización creada correctamente');
                limpiar();
                cargar();
            } else {
                alert('Error: ' + (result.error || 'No se pudo guardar'));
            }
        } catch (err) {
            console.error(err);
            alert('Error de conexión: ' + err.message);
        }
    };

    cargar();
});

function escapeHtml(str) { 
    if (!str) return '';
    return String(str).replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m])); 
}
</script>
