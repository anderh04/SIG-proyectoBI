<?php
session_start();
require_once 'config/conexion.php';

// Verificar si el estudiante ya inició sesión
$estudiante_registrado = isset($_SESSION['id_estudiante']);
?>
<!DOCTYPE html>
<html lang="es">
 <style>
    :root {
        --primary-color: #3a0ca3;
        --primary-light: #4361ee;
        --secondary-color: #7209b7;
        --accent-color: #4cc9f0;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --border-radius: 12px;
        --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
        min-height: 100vh;
        padding: 1rem;
    }

    .main-container {
        display: flex;
        flex-direction: column;
        max-width: 1200px;
        margin: 0 auto;
        gap: 2rem;

    }

    header {
        padding: 2rem;
        border-radius: var(--border-radius);
        background: linear-gradient(45deg, rgb(93, 178, 226), rgb(93, 226, 95));
        text-align: center;
        box-shadow: var(--box-shadow);
    }


    .container {
        width: 100%;
        /* max-width: 800px; */
        /* margin: 10px; */
        padding: 3rem;
        background-color: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        position: relative;
        overflow: hidden;
    }

    .container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 8px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }


    .form-title {
        color: var(--light-color);
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 0.5rem;
        font-weight: 700;
        font-style: bold;
    }

    .form-subtitle {
        color: var(--dark-color);
        text-align: center;
        margin-bottom: 2.5rem;
        font-weight: normal;
        font-size: 1.1rem;
    }

    .highlight {
        color: var(--primary-color);
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .content-wrapper {
        background-color: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        position: relative;
    }

    .content-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 8px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }

    .form-section {
        padding: 2rem;
        background-color: transparent;
        border-radius: var(--border-radius);
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .form-section h3 {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        font-weight: 600;
        position: relative;
        padding-bottom: 0.5rem;
    }

    .form-section h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        border-radius: 2px;
    }

    .btn-group .btn {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        border-radius: var(--border-radius);
        transition: var(--transition);
        border: 2px solid transparent;
    }

    .btn-primary {
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(66, 99, 235, 0.3);
    }

    .btn-secondary {
        background-color: #f1f3f5;
        color: #495057;
        border-color: #e9ecef;
    }

    .btn-secondary:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
    }

    .form-control,
    .form-select {
        padding: 0.9rem 1.2rem;
        border: 2px solid #eaeaea;
        border-radius: var(--border-radius);
        transition: var(--transition);
        font-size: 1rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
    }

    .scanner-container {
        width: 100%;
        max-width: 500px;
        margin: 20px auto;
        border: 2px dashed #ccc;
        padding: 1rem;
        border-radius: var(--border-radius);
        background-color: #f9f9f9;
    }

    #scanner-view {
        width: 100%;
        height: 300px;
        background-color: #f0f0f0;
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
    }

    .hidden {
        display: none !important;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: var(--border-radius);
        border: none;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    /* Animaciones */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .content-wrapper {
        animation: fadeIn 0.6s ease-out;
    }

    /* Responsive Design */
    @media (min-width: 768px) {
        .main-container {
            flex-direction: row;
            align-items: flex-start;
        }


        .content-wrapper {
            flex: 2;
        }

        .row {
            display: flex;
            gap: 2rem;
        }

        .col-md-6 {
            flex: 1;
        }
    }

    @media (max-width: 767px) {
        .main-container {
            padding: 0.5rem;
        }

        .form-title {
            font-size: 2rem;
        }

        .form-section {
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn-group .btn {
            width: 100%;
            margin: 0;
        }

        .row {
            flex-direction: column;
        }

        .scanner-container {
            margin: 10px 0;
        }

        #scanner-view {
            height: 250px;
        }
    }

    @media (max-width: 576px) {
        body {
            padding: 0.5rem;
        }

        .form-section {
            padding: 1rem;
        }

        .form-title {
            font-size: 1.8rem;
        }

        .form-subtitle {
            font-size: 1rem;
        }

        header {
            padding: 1.5rem;
        }
    }

    /* Mejoras para botones activos */
    .btn.active {
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color)) !important;
        color: white !important;
        border-color: var(--primary-color) !important;
        box-shadow: 0 4px 15px rgba(66, 99, 235, 0.3);
    }

    /* Estilos para modales */
    .modal-content {
        border-radius: var(--border-radius);
        border: none;
        box-shadow: var(--box-shadow);
    }

    .modal-header {
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .modal-title {
        font-weight: 600;
    }

    .btn-close {
        filter: invert(1);
    }

    /* Estilos para el área de estudiante registrado */
    .welcome-section {
        text-align: center;
        padding: 3rem 2rem;
    }

    .welcome-section h3 {
        color: var(--primary-color);
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .student-info {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        margin: 2rem 0;
        border-left: 4px solid var(--primary-color);
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        max-width: 400px;
        margin: 0 auto;
    }

    @media (min-width: 576px) {
        .action-buttons {
            flex-direction: row;
            justify-content: center;
        }
    }
    </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Registro y Asistencia - CRU Barú</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.min.js"></script>
    <style>
    .scanner-container {
        width: 100%;
        max-width: 500px;
        margin: 20px auto;
        border: 2px dashed #ccc;
        padding: 10px;
    }

    #scanner-view {
        width: 100%;
        height: 300px;
        background-color: #f0f0f0;
    }

    .form-section {
        margin-bottom: 30px;
        padding: 20px;
        border-radius: 5px;
        background-color: #f8f9fa;
    }

    .hidden {
        display: none;
    }
    </style>
</head>

<body>
    <div class="container py-5">
        <h1 class="text-center mb-4">Sistema de Registro y Asistencia</h1>

        <?php if(!$estudiante_registrado): ?>
        <!-- Sección de Registro/Login -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-section">
                    <h3>Registro de Estudiante</h3>
                    <form id="registroForm" method="post" action="procesar_registro.php">
                        <div class="mb-3">
                            <label class="form-label">Método de Registro:</label>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-primary active" id="btnManual">Manual</button>
                                <button type="button" class="btn btn-secondary" id="btnQR">Escanear QR</button>
                                <button type="button" class="btn btn-secondary" id="btnOCR">Usar OCR</button>
                            </div>
                        </div>

                        <!-- Formulario Manual -->
                        <div id="formManual">
                            <div class="mb-3">
                                <label for="codigo_estudiante" class="form-label">Cédula</label>
                                <input type="text" class="form-control" id="codigo_estudiante" name="codigo_estudiante"
                                    required pattern="\d{3}-\d{4}-\d{5}" placeholder="000-0000-00000">
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Género</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="genero" id="hombre"
                                            value="Hombre" required>
                                        <label class="form-check-label" for="hombre">Hombre</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="genero" id="mujer"
                                            value="Mujer">
                                        <label class="form-check-label" for="mujer">Mujer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="genero" id="otro"
                                            value="Otro">
                                        <label class="form-check-label" for="otro">Otro</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="id_carrera" class="form-label">Carrera</label>
                                <select class="form-select" id="id_carrera" name="id_carrera" required>
                                    <option value="">Seleccione una carrera</option>
                                    <?php
        $sql = "SELECT id_carrera, nombre_carrera FROM carreras";
        $result = $conn->query($sql);
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='".$row['id_carrera']."'>".$row['nombre_carrera']."</option>";
        }
        ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono">
                            </div>
                            <button type="submit" class="btn btn-primary">Registrarse</button>
                        </div>

                        <!-- Lector QR -->
                        <div id="formQR" class="hidden">
                            <div class="scanner-container">
                                <div id="scanner-view"></div>
                            </div>
                            <div class="mb-3">
                                <button type="button" class="btn btn-danger w-100" id="stopScanner">Detener
                                    Escáner</button>
                            </div>
                            <div id="qrData" class="hidden">
                                <input type="hidden" id="qr_codigo" name="codigo_estudiante">
                                <input type="hidden" id="qr_nombre" name="nombre">
                                <input type="hidden" id="qr_apellido" name="apellido">
                                <input type="hidden" id="qr_genero" name="genero">
                                <input type="hidden" id="qr_carrera" name="id_carrera">
                            </div>
                        </div>

                        <!-- Formulario OCR -->
                        <div id="formOCR" class="hidden">
                            <div class="mb-3">
                                <label for="ocrImage" class="form-label">Subir imagen del carnet</label>
                                <input class="form-control" type="file" id="ocrImage" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <button type="button" class="btn btn-info w-100" id="processOCR">Procesar
                                    Imagen</button>
                            </div>
                            <div id="ocrData" class="hidden">
                                <input type="hidden" id="ocr_codigo" name="codigo_estudiante">
                                <input type="hidden" id="ocr_nombre" name="nombre">
                                <input type="hidden" id="ocr_apellido" name="apellido">
                                <input type="hidden" id="ocr_genero" name="genero">
                                <input type="hidden" id="ocr_carrera" name="id_carrera">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-section">
                    <h3>Iniciar Sesión</h3>
                    <form method="post" action="login.php">
                        <div class="mb-3">
                            <label for="login_codigo" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="login_codigo" name="codigo_estudiante" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                    </form>
                </div>
            </div>
        </div>

        <?php else: ?>
        <!-- Sección de Asistencia para estudiantes registrados -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-section text-center">
                    <h3>Bienvenido, <?php echo $_SESSION['nombre']; ?></h3>
                    <p>Cédula: <?php echo $_SESSION['codigo_estudiante']; ?></p>
                    <p>Carrera: <?php echo $_SESSION['carrera']; ?></p>

                    <div class="d-grid gap-3 mt-4">
                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#bibliotecaModal">
                            Registrar Asistencia a Biblioteca
                        </button>
                        <button class="btn btn-success btn-lg" data-bs-toggle="modal"
                            data-bs-target="#computadorasModal">
                            Registrar Uso de Computadoras
                        </button>
                        <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Biblioteca -->
        <div class="modal fade" id="bibliotecaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registro de Asistencia a Biblioteca</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="asistenciaBibliotecaForm">
                            <input type="hidden" name="tipo_asistencia" value="biblioteca">
                            <input type="hidden" name="id_estudiante" value="<?php echo $_SESSION['id_estudiante']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Seleccione acción:</label>
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-primary"
                                        id="entradaBiblioteca">Entrada</button>
                                    <button type="button" class="btn btn-warning" id="salidaBiblioteca">Salida</button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="observacionesBiblioteca" class="form-label">Observaciones (opcional)</label>
                                <textarea class="form-control" id="observacionesBiblioteca" name="observaciones"
                                    rows="2"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="confirmarBiblioteca">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Computadoras -->
        <div class="modal fade" id="computadorasModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registro de Uso de Computadoras</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="asistenciaComputadorasForm">
                            <input type="hidden" name="tipo_asistencia" value="computadoras">
                            <input type="hidden" name="id_estudiante" value="<?php echo $_SESSION['id_estudiante']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Seleccione acción:</label>
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-primary" id="entradaComputadoras">Inicio de
                                        Uso</button>
                                    <button type="button" class="btn btn-warning" id="salidaComputadoras">Fin de
                                        Uso</button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="equipoComputadoras" class="form-label">Número de Equipo</label>
                                <input type="text" class="form-control" id="equipoComputadoras" name="equipo">
                            </div>
                            <div class="mb-3">
                                <label for="observacionesComputadoras" class="form-label">Observaciones
                                    (opcional)</label>
                                <textarea class="form-control" id="observacionesComputadoras" name="observaciones"
                                    rows="2"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="confirmarComputadoras">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@4/dist/tesseract.min.js"></script>
    <script>
    // Control de pestañas del formulario
    document.getElementById('btnManual').addEventListener('click', function() {
        document.getElementById('formManual').classList.remove('hidden');
        document.getElementById('formQR').classList.add('hidden');
        document.getElementById('formOCR').classList.add('hidden');
        stopScanner();
        this.classList.add('active', 'btn-primary');
        this.classList.remove('btn-secondary');
        document.getElementById('btnQR').classList.remove('active', 'btn-primary');
        document.getElementById('btnQR').classList.add('btn-secondary');
        document.getElementById('btnOCR').classList.remove('active', 'btn-primary');
        document.getElementById('btnOCR').classList.add('btn-secondary');
    });

    document.getElementById('btnQR').addEventListener('click', function() {
        document.getElementById('formManual').classList.add('hidden');
        document.getElementById('formQR').classList.remove('hidden');
        document.getElementById('formOCR').classList.add('hidden');
        initScanner();
        this.classList.add('active', 'btn-primary');
        this.classList.remove('btn-secondary');
        document.getElementById('btnManual').classList.remove('active', 'btn-primary');
        document.getElementById('btnManual').classList.add('btn-secondary');
        document.getElementById('btnOCR').classList.remove('active', 'btn-primary');
        document.getElementById('btnOCR').classList.add('btn-secondary');
    });

    document.getElementById('btnOCR').addEventListener('click', function() {
        document.getElementById('formManual').classList.add('hidden');
        document.getElementById('formQR').classList.add('hidden');
        document.getElementById('formOCR').classList.remove('hidden');
        stopScanner();
        this.classList.add('active', 'btn-primary');
        this.classList.remove('btn-secondary');
        document.getElementById('btnManual').classList.remove('active', 'btn-primary');
        document.getElementById('btnManual').classList.add('btn-secondary');
        document.getElementById('btnQR').classList.remove('active', 'btn-primary');
        document.getElementById('btnQR').classList.add('btn-secondary');
    });

    // Lector QR
    let scannerActive = false;

    function initScanner() {
        if (scannerActive) return;

        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner-view'),
                constraints: {
                    width: 480,
                    height: 320,
                    facingMode: "environment"
                },
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader",
                    "code_39_vin_reader",
                    "codabar_reader", "upc_reader", "upc_e_reader", "qrcode_reader"
                ]
            },
        }, function(err) {
            if (err) {
                console.error(err);
                alert("Error al iniciar el escáner: " + err);
                return;
            }
            scannerActive = true;
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            const code = result.codeResult.code;
            processQRCode(code);
            stopScanner();
        });
    }

    function stopScanner() {
        if (scannerActive) {
            Quagga.stop();
            scannerActive = false;
        }
    }

    document.getElementById('stopScanner').addEventListener('click', stopScanner);

    function processQRCode(data) {
        try {
            // Suponemos que el QR contiene datos en formato JSON
            const studentData = JSON.parse(data);

            document.getElementById('qr_codigo').value = studentData.codigo;
            document.getElementById('qr_nombre').value = studentData.nombre;
            document.getElementById('qr_apellido').value = studentData.apellido;
            document.getElementById('qr_genero').value = studentData.genero;
            document.getElementById('qr_carrera').value = studentData.carrera_id;

            // Mostrar datos para confirmación
            alert(
                `Datos detectados:\nCédula: ${studentData.codigo}\nNombre: ${studentData.nombre} ${studentData.apellido}`
            );

            // Enviar formulario automáticamente
            document.getElementById('registroForm').submit();
        } catch (e) {
            alert("Error al procesar el código QR. Formato incorrecto.");
            console.error(e);
        }
    }

    // Procesamiento OCR
    document.getElementById('processOCR').addEventListener('click', function() {
        const fileInput = document.getElementById('ocrImage');
        if (!fileInput.files || fileInput.files.length === 0) {
            alert("Por favor seleccione una imagen primero");
            return;
        }

        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            Tesseract.recognize(
                e.target.result,
                'spa', {
                    logger: m => console.log(m)
                }
            ).then(({
                data: {
                    text
                }
            }) => {
                processOCRText(text);
            }).catch(err => {
                console.error(err);
                alert("Error al procesar la imagen con OCR");
            });
        };

        reader.readAsDataURL(file);
    });

    function processOCRText(text) {
        // Expresiones regulares para extraer datos del texto OCR
        const cedulaRegex = /Cédula:\s*([0-9-]+)/i;
        const nombreRegex = /Nombre:\s*([^\n]+)/i;
        const apellidoRegex = /Apellido:\s*([^\n]+)/i;
        const generoRegex = /Género:\s*(Hombre|Mujer|Otro)/i;
        const carreraRegex = /Escuela:\s*([^\n]+)/i;

        const cedulaMatch = text.match(cedulaRegex);
        const nombreMatch = text.match(nombreRegex);
        const apellidoMatch = text.match(apellidoRegex);
        const generoMatch = text.match(generoRegex);
        const carreraMatch = text.match(carreraRegex);

        if (cedulaMatch && nombreMatch && apellidoMatch) {
            document.getElementById('ocr_codigo').value = cedulaMatch[1].trim();
            document.getElementById('ocr_nombre').value = nombreMatch[1].trim();
            document.getElementById('ocr_apellido').value = apellidoMatch[1].trim();

            if (generoMatch) {
                document.getElementById('ocr_genero').value = generoMatch[1].trim();
            }

            // Mapear nombre de carrera a ID (simplificado)
            if (carreraMatch) {
                const carreraNombre = carreraMatch[1].trim().toLowerCase();
                let carreraId = 1; // Default

                if (carreraNombre.includes('econom')) carreraId = 2;
                if (carreraNombre.includes('admin')) carreraId = 3;

                document.getElementById('ocr_carrera').value = carreraId;
            }

            // Mostrar datos para confirmación
            alert(`Datos detectados:\nCédula: ${cedulaMatch[1]}\nNombre: ${nombreMatch[1]} ${apellidoMatch[1]}`);

            // Enviar formulario automáticamente
            document.getElementById('registroForm').submit();
        } else {
            alert("No se pudieron detectar todos los datos necesarios en la imagen. Por favor ingréselos manualmente.");
            console.log("Texto OCR:", text);
        }
    }

    // Registro de asistencia
    <?php if($estudiante_registrado): ?>
    document.getElementById('entradaBiblioteca').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('salidaBiblioteca').classList.remove('active');
    });

    document.getElementById('salidaBiblioteca').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('entradaBiblioteca').classList.remove('active');
    });

    document.getElementById('entradaComputadoras').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('salidaComputadoras').classList.remove('active');
    });

    document.getElementById('salidaComputadoras').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('entradaComputadoras').classList.remove('active');
    });

    document.getElementById('confirmarBiblioteca').addEventListener('click', function() {
        const form = document.getElementById('asistenciaBibliotecaForm');
        const formData = new FormData(form);

        // Determinar tipo de registro (entrada/salida)
        if (document.getElementById('entradaBiblioteca').classList.contains('active')) {
            formData.append('accion', 'entrada');
        } else if (document.getElementById('salidaBiblioteca').classList.contains('active')) {
            formData.append('accion', 'salida');
        } else {
            alert("Por favor seleccione Entrada o Salida");
            return;
        }

        fetch('registrar_asistencia.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    bootstrap.Modal.getInstance(document.getElementById('bibliotecaModal')).hide();
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Error al registrar asistencia");
            });
    });

    document.getElementById('confirmarComputadoras').addEventListener('click', function() {
        const form = document.getElementById('asistenciaComputadorasForm');
        const formData = new FormData(form);

        // Validar número de equipo
        const equipo = document.getElementById('equipoComputadoras').value;
        if (!equipo) {
            alert("Por favor ingrese el número de equipo");
            return;
        }

        // Determinar tipo de registro (entrada/salida)
        if (document.getElementById('entradaComputadoras').classList.contains('active')) {
            formData.append('accion', 'entrada');
        } else if (document.getElementById('salidaComputadoras').classList.contains('active')) {
            formData.append('accion', 'salida');
        } else {
            alert("Por favor seleccione Inicio o Fin de uso");
            return;
        }

        fetch('registrar_asistencia.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    bootstrap.Modal.getInstance(document.getElementById('computadorasModal')).hide();
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Error al registrar asistencia");
            });
    });
    <?php endif; ?>
    </script>
</body>

</html>
