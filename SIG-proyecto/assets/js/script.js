// Funciones generales para el sistema

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Manejar el menú móvil
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.querySelector('.dashboard-container').classList.toggle('sidebar-collapsed');
        });
    }
    
    // Manejar dropdowns
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.preventDefault();
            const menu = this.nextElementSibling;
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        });
    });
    
    // Cerrar dropdowns al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.matches('.dropdown-toggle')) {
            const dropdowns = document.querySelectorAll('.dropdown-menu');
            dropdowns.forEach(dropdown => {
                dropdown.style.display = 'none';
            });
        }
    });
    
    // Manejar modales
    const modalButtons = document.querySelectorAll('[data-toggle="modal"]');
    modalButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            document.querySelector(target).style.display = 'block';
        });
    });
    
    const closeButtons = document.querySelectorAll('.modal-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });
    
    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    });
    
    // Manejar tabs
    const tabLinks = document.querySelectorAll('.nav-tabs a');
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const tabId = this.getAttribute('href');
            
            // Ocultar todos los paneles
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });
            
            // Mostrar el panel seleccionado
            document.querySelector(tabId).classList.add('active');
            
            // Actualizar tabs activos
            this.parentNode.querySelectorAll('a').forEach(tab => {
                tab.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
});

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo = 'success') {
    const notificacion = document.createElement('div');
    notificacion.className = `alert alert-${tipo} alert-dismissible fade show`;
    notificacion.role = 'alert';
    notificacion.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.querySelector('.notificaciones') || document.body;
    container.prepend(notificacion);
    
    setTimeout(() => {
        notificacion.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notificacion.classList.remove('show');
        setTimeout(() => {
            notificacion.remove();
        }, 300);
    }, 5000);
}

// Función para confirmar acciones
function confirmarAccion(mensaje, callback) {
    const modal = document.createElement('div');
    modal.className = 'modal-confirm';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar acción</h5>
            </div>
            <div class="modal-body">
                <p>${mensaje}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancelar">Cancelar</button>
                <button type="button" class="btn btn-primary confirmar">Confirmar</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    modal.querySelector('.cancelar').addEventListener('click', function() {
        modal.remove();
    });
    
    modal.querySelector('.confirmar').addEventListener('click', function() {
        callback();
        modal.remove();
    });
}

// Función para cargar datos mediante AJAX
function cargarDatos(url, callback, metodo = 'GET', datos = null) {
    const xhr = new XMLHttpRequest();
    xhr.open(metodo, url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (this.status >= 200 && this.status < 300) {
            try {
                const respuesta = JSON.parse(this.responseText);
                callback(respuesta);
            } catch (e) {
                console.error('Error al parsear respuesta:', e);
            }
        } else {
            console.error('Error en la petición:', this.statusText);
        }
    };
    
    xhr.onerror = function() {
        console.error('Error de conexión');
    };
    
    xhr.send(datos);
}

// Función para formatear fechas
function formatearFecha(fecha) {
    if (!fecha) return '';
    
    const opciones = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    
    return new Date(fecha).toLocaleDateString('es-ES', opciones);
}