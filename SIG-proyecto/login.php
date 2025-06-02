<?php
require_once 'config/connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = ejecutarConsulta(" SELECT * FROM usuarios WHERE username = ?", [$username]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            session_start();
            $_SESSION['usuario'] = $usuario['username'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            // Actualizar último login
            ejecutarConsulta(" UPDATE usuarios SET ultimo_login = NOW() WHERE id_usuario = ?", [$usuario['id_usuario']]);

            header("Location: dashboard.php");
            exit();
        } else {
            // Mensaje más específico para error de autenticación
            if (!$usuario) {
                $error = "El usuario ingresado no existe";
            } else {
                $error = "La contraseña ingresada es incorrecta";

                // Registrar intento fallido de inicio de sesión
                $intentos = isset($_SESSION['intentos_login']) ? $_SESSION['intentos_login'] + 1 : 1;
                $_SESSION['intentos_login'] = $intentos;

                if ($intentos >= 3) {
                    $error .= ". Has excedido el número de intentos permitidos. Por favor, espera unos minutos antes de intentar nuevamente.";
                    // Aquí podrías implementar un bloqueo temporal
                }
            }
        }
    } else {
        $error = "Por favor complete todos los campos";
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Biblioteca CRUBA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <style>
        :root {
            --primary: #4e73df;
            --primary-dark: #2e59d9;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(rgba(78, 115, 223, 0.5), rgba(78, 115, 223, 0.5)), url('assets/images/library-bg.jpg');
            background-size: cover;
            background-position: center;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .login-header {
            background-color: var(--primary);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .login-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .input-group {
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .input-group input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #d1d3e2;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0.35rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn:hover {
            background-color: var(--primary-dark);
        }

        .error-message {
            color: #e74a3b;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            text-align: center;
        }

        .login-footer {
            text-align: center;
            padding: 1rem;
            border-top: 1px solid #e3e6f0;
            font-size: 0.875rem;
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .btn-outline-primary {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
            border-radius: 0.35rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .scanner-container {
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            border: 2px dashed #ccc;
            padding: 10px;
            background-color: #fff;
        }

        #scanner-view {
            width: 100%;
            height: 300px;
            background-color: #f0f0f0;
            position: relative;
            overflow: hidden;
        }

        #scanner-view video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #stopScanner {
            margin-top: 10px;
            width: 100%;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-book"></i> Biblioteca CRUBA</h1>
        </div>

        <div class="login-body">
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Usuario</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Ingrese su usuario" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Ingrese su contraseña"
                            required>
                    </div>
                </div>

                <button type="submit" class="btn">Iniciar Sesión</button>
            </form>

        </div>

        <div class="login-footer">
            <p>¿No tienes una cuenta? <a href="register.php" class="btn btn-outline-primary">Regístrate aquí</a></p>
            <p class="mt-2">Sistema de Información Gerencial - CRUBA &copy; <?php echo date('Y'); ?></p>
        </div>
    </div>
</body>

</html>
