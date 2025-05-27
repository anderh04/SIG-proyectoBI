// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo) {
    // Crear el contenedor de notificaciones si no existe
    if (!$('#notificaciones').length) {
        $('body').append('<div id="notificaciones"></div>');
    }

    // Crear la notificación
    const notificacion = $(`
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `);

    // Agregar la notificación al contenedor
    $('#notificaciones').append(notificacion);

    // Eliminar la notificación después de 5 segundos
    setTimeout(() => {
        notificacion.alert('close');
    }, 5000);
}

// Función para manejar errores AJAX
function manejarErrorAjax(xhr, status, error) {
    let mensaje = 'Error al procesar la solicitud';
    if (xhr.responseJSON && xhr.responseJSON.message) {
        mensaje = xhr.responseJSON.message;
    } else if (error) {
        mensaje += ': ' + error;
    }
    mostrarNotificacion(mensaje, 'danger');
}

// Función para manejar respuestas AJAX exitosas
function manejarExitoAjax(response) {
    if (response.success) {
        mostrarNotificacion(response.message, 'success');
        if (response.redirect) {
            setTimeout(() => {
                window.location.href = response.redirect;
            }, 1500);
        } else if (response.reload) {
            setTimeout(() => {
                location.reload();
            }, 1500);
        }
    } else {
        mostrarNotificacion(response.message, 'danger');
    }
} 