<?php

require_once 'config/connection.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Metadatos -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca CRUBA - <?php echo $titulo_pagina ?? 'Gestion estudiante'; ?></title>

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- CSS Externos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

    <!-- CSS Internos -->
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- Scripts Externos -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
    </script>

    <!-- Scripts Internos -->
    <script src="assets/js/notificaciones.js"></script>
    <script src="assets/js/script.js"></script>
</head>

<body>
    <header class="p-3 mb-3 border-bottom" style="background-color: #6ea8fe; padding: 10px;">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start"> <a
                    href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                    <img src="" alt="">
                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">

                        <li><a href="#" class="nav-link px-2 link-body-emphasis"></a></li>

                    </ul>
                    <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search"> <input type="search"
                            class="form-control" placeholder="Search..." aria-label="Search"> </form>
                    <div class="dropdown text-end"> <a href="#"
                            class="d-block link-body-emphasis text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false"> <img src="https://github.com/mdo.png"
                                alt="mdo" width="32" height="32" class="rounded-circle"> </a>
                        <ul class="dropdown-menu text-small">
                            <li><a class="dropdown-item" href="#">New project...</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Sign out</a></li>
                        </ul>
                    </div>
            </div>
        </div>
    </header>
</body>

</html>