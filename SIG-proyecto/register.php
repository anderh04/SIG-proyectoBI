<?php
require_once 'config/connection.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $nombre = trim($_POST['nombre']);
    $rol = 'Usuario'; // Rol por defecto

    // Validaciones
    if (empty($username) || empty($password) || empty($confirm_password) || empty($nombre)) {
        $error = "Por favor complete todos los campos";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden";
    } elseif (strlen($password) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres";
    } else {
        // Verificar si el usuario ya existe
        $stmt = ejecutarConsulta("SELECT * FROM usuarios WHERE username = ?", [$username]);
        if ($stmt->rowCount() > 0) {
            $error = "El nombre de usuario ya está en uso";
        } else {
            // Hash de la contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insertar nuevo usuario
            $sql = "INSERT INTO usuarios (username, password, nombre, rol) VALUES (?, ?, ?, ?)";
            try {
                ejecutarConsulta($sql, [$username, $hashed_password, $nombre, $rol]);
                $success = "Usuario registrado exitosamente. Ahora puedes iniciar sesión.";
            } catch (PDOException $e) {
                $error = "Error al registrar el usuario: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Biblioteca CRUBA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    .register-container {
        width: 100%;
        max-width: 500px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .register-header {
        background-color: var(--primary);
        color: white;
        padding: 1.5rem;
        text-align: center;
    }

    .register-header h1 {
        font-size: 1.5rem;
        font-weight: 600;
    }

    .register-body {
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

    .success-message {
        color: #1cc88a;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        text-align: center;
    }

    .register-footer {
        text-align: center;
        padding: 1rem;
        border-top: 1px solid #e3e6f0;
        font-size: 0.875rem;
    }

    .register-footer a {
        color: var(--primary);
        text-decoration: none;
    }

    .register-footer a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-header">
            <h1><i class="fas fa-user-plus"></i> Registro de Usuario</h1>
        </div>

        <div class="register-body">
            <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="nombre" name="nombre" placeholder="Ingrese su nombre completo" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="username">Nombre de Usuario</label>
                    <div class="input-group">
                        <i class="fas fa-at"></i>
                        <input type="text" id="username" name="username" placeholder="Ingrese su nombre de usuario"
                            required>
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

                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password"
                            placeholder="Confirme su contraseña" required>
                    </div>
                </div>

                <button type="submit" class="btn">Registrarse</button>
            </form>
        </div>

        <div class="register-footer">
            <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </div>
    </div>
</body>

</html>