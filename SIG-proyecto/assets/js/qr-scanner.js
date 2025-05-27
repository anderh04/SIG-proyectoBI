// Nuevo archivo para el escáner QR con cámara
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('qr-video');
    const canvas = document.getElementById('qr-canvas');
    const resultContainer = document.getElementById('qr-result');
    const startBtn = document.getElementById('start-camera');
    const stopBtn = document.getElementById('stop-camera');
    
    let scanning = false;
    let stream = null;
    
    // Iniciar cámara frontal
    startBtn.addEventListener('click', async function() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "user", // Usar cámara frontal
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: false
            });
            
            video.srcObject = stream;
            video.play();
            startBtn.style.display = 'none';
            stopBtn.style.display = 'inline-block';
            scanning = true;
            
            // Iniciar detección de QR
            scanQRCode();
        } catch (err) {
            console.error("Error al acceder a la cámara: ", err);
            resultContainer.innerHTML = "Error al acceder a la cámara: " + err.message;
        }
    });
    
    // Detener cámara
    stopBtn.addEventListener('click', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            video.srcObject = null;
            startBtn.style.display = 'inline-block';
            stopBtn.style.display = 'none';
            scanning = false;
        }
    });
    
    // Función para escanear códigos QR
    function scanQRCode() {
        if (!scanning) return;
        
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            try {
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });
                
                if (code) {
                    resultContainer.innerHTML = `<strong>Código QR detectado:</strong><br>${code.data}`;
                    
                    // Enviar el código al servidor para buscar al estudiante
                    buscarEstudiante(code.data);
                    
                    // Opcional: detener la cámara después de detectar
                    stopBtn.click();
                }
            } catch (e) {
                console.error("Error al procesar QR:", e);
            }
        }
        
        if (scanning) {
            requestAnimationFrame(scanQRCode);
        }
    }
    
    // Función para buscar estudiante por código QR
    function buscarEstudiante(codigo) {
        fetch('buscar_estudiante_qr.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `codigo=${encodeURIComponent(codigo)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.estudiante) {
                resultContainer.innerHTML += `
                    <h4>Información del Estudiante:</h4>
                    <ul>
                        <li><strong>Nombre:</strong> ${data.estudiante.nombre} ${data.estudiante.apellido}</li>
                        <li><strong>Código:</strong> ${data.estudiante.codigo_estudiante}</li>
                        <li><strong>Carrera:</strong> ${data.estudiante.carrera}</li>
                    </ul>
                `;
                
                // Opcional: registrar asistencia automáticamente
                if (confirm('¿Desea registrar la entrada de este estudiante?')) {
                    registrarAsistencia(data.estudiante.id_estudiante);
                }
            } else {
                resultContainer.innerHTML += `<p>No se encontró un estudiante con el código: ${codigo}</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultContainer.innerHTML += `<p>Error al buscar estudiante</p>`;
        });
    }
    
    // Función para registrar asistencia
    function registrarAsistencia(idEstudiante) {
        const ahora = new Date();
        const fechaHora = ahora.toISOString().slice(0, 16); // Formato YYYY-MM-DDTHH:MM
        
        fetch('guardar_asistencia.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `registrar_asistencia=1&id_estudiante=${idEstudiante}&fecha_entrada=${fechaHora}`
        })
        .then(response => response.text())
        .then(result => {
            alert("Asistencia registrada exitosamente");
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Error al registrar asistencia");
        });
    }
});