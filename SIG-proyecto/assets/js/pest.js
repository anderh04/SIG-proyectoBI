document.addEventListener('DOMContentLoaded', function() {
    // Manejar pestañas
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Remover clase active de todos los botones y contenidos
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Agregar clase active al botón y contenido seleccionado
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    // Mostrar mensajes y resultados de sesión (rellenar desde PHP)
    if (window.mensaje) {
        alert(window.mensaje);
    }
    if (window.error) {
        alert("Error: " + window.error);
    }
    if (window.ocrResult) {
        mostrarResultadoOCR(window.ocrResult);
    }
    if (window.qrResult) {
        mostrarResultadoQR(window.qrResult);
    }
});

function mostrarResultadoOCR(resultado) {
    let html = '<div class="resultado-ocr"><h3>Texto extraído:</h3><pre>' + 
               resultado.texto + '</pre><h3>Información detectada:</h3><ul>';
    
    for (const [key, value] of Object.entries(resultado.info)) {
        html += `<li><strong>${key}:</strong> ${value}</li>`;
    }
    
    html += '</ul></div>';
    
    const scannerTab = document.getElementById('scanner');
    scannerTab.innerHTML += html;
}

function mostrarResultadoQR(resultado) {
    let html = '<div class="resultado-qr"><h3>Código QR leído:</h3><p>' + 
               resultado.codigo + '</p>';
    
    if (resultado.estudiante) {
        html += '<h3>Información del estudiante:</h3><ul>';
        html += `<li><strong>Nombre:</strong> ${resultado.estudiante.nombre} ${resultado.estudiante.apellido}</li>`;
        html += `<li><strong>Código:</strong> ${resultado.estudiante.codigo_estudiante}</li>`;
        html += `<li><strong>Carrera:</strong> ${resultado.estudiante.carrera}</li>`;
        html += '</ul>';
    } else {
        html += '<p>No se encontró un estudiante con este código</p>';
    }
    
    html += '</div>';
    
    const scannerTab = document.getElementById('scanner');
    scannerTab.innerHTML += html;
}