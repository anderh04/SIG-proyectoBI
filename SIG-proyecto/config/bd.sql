CREATE DATABASE IF NOT EXISTS cruba_biblioteca;

USE c2uba_biblioteca;

CREATE TABLE `carreras` (
    `id_carrera` int(11) NOT NULL,
    `nombre_carrera` varchar(100) NOT NULL,
    `id_facultad` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO
    `carreras` (
        `id_carrera`,
        `nombre_carrera`,
        `id_facultad`
    )
VALUES (
        1,
        'Ingeniería Informática',
        1
    ),
    (2, 'Medicina', 2),
    (3, 'Derecho', 3),
    (
        4,
        'Administración de Empresas',
        4
    ),
    (5, 'Psicología', 5),
    (6, 'Arquitectura', 6),
    (7, 'Ingeniería Civil', 1),
    (8, 'Contabilidad', 4),
    (9, 'Comunicación Social', 9),
    (10, 'Biología', 7),
    (11, 'Enfermería', 11),
    (12, 'Economía', 4),
    (13, 'Educación Primaria', 8),
    (14, 'Diseño Gráfico', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
    `id_categoria` int(11) NOT NULL,
    `nombre_categoria` varchar(100) NOT NULL,
    `descripcion` text DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO
    `categorias` (
        `id_categoria`,
        `nombre_categoria`,
        `descripcion`
    )
VALUES (
        1,
        'Ciencia Ficción',
        'Libros de ciencia ficción y fantasía'
    ),
    (
        2,
        'Terror',
        'Novelas y relatos de terror'
    ),
    (
        3,
        'Romance',
        'Novelas románticas'
    ),
    (
        4,
        'Historia',
        'Libros de historia universal'
    ),
    (
        5,
        'Ciencias Exactas',
        'Matemáticas, física, química'
    ),
    (
        6,
        'Literatura Clásica',
        'Obras clásicas de la literatura'
    ),
    (
        7,
        'Autoayuda',
        'Libros de desarrollo personal'
    ),
    (
        8,
        'Tecnología',
        'Libros sobre tecnología e informática'
    ),
    (
        9,
        'Medicina',
        'Libros de medicina y salud'
    ),
    (
        10,
        'Derecho',
        'Textos jurídicos y legales'
    ),
    (
        11,
        'Arte',
        'Libros sobre arte y artistas'
    ),
    (
        12,
        'Filosofía',
        'Obras filosóficas'
    ),
    (
        13,
        'Economía',
        'Libros de economía y finanzas'
    ),
    (
        14,
        'Infantil',
        'Libros para niños'
    );

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
    `id_estudiante` int(11) NOT NULL,
    `codigo_estudiante` varchar(20) NOT NULL,
    `nombre` varchar(100) NOT NULL,
    `apellido` varchar(100) NOT NULL,
    `genero` enum('Hombre', 'Mujer', 'Otro') NOT NULL,
    `id_carrera` int(11) NOT NULL,
    `email` varchar(100) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO
    `estudiantes` (
        `id_estudiante`,
        `codigo_estudiante`,
        `nombre`,
        `apellido`,
        `genero`,
        `id_carrera`,
        `email`
    )
VALUES (
        1,
        '20230001',
        'Juan',
        'Pérez',
        'Hombre',
        1,
        'juan.perez@email.com'
    ),
    (
        2,
        '20230002',
        'María',
        'González',
        'Mujer',
        2,
        'maria.gonzalez@email.com'
    ),
    (
        3,
        '20230003',
        'Carlos',
        'Rodríguez',
        'Hombre',
        3,
        'carlos.rodriguez@email.com'
    ),
    (
        4,
        '20230004',
        'Ana',
        'Martínez',
        'Mujer',
        4,
        'ana.martinez@email.com'
    ),
    (
        5,
        '20230005',
        'Luis',
        'Hernández',
        'Hombre',
        5,
        'luis.hernandez@email.com'
    ),
    (
        6,
        '20230006',
        'Laura',
        'López',
        'Mujer',
        6,
        'laura.lopez@email.com'
    ),
    (
        7,
        '20230007',
        'Pedro',
        'Díaz',
        'Hombre',
        7,
        'pedro.diaz@email.com'
    ),
    (
        8,
        '20230008',
        'Sofía',
        'Ramírez',
        'Mujer',
        8,
        'sofia.ramirez@email.com'
    ),
    (
        9,
        '20230009',
        'Jorge',
        'Torres',
        'Hombre',
        9,
        'jorge.torres@email.com'
    ),
    (
        10,
        '20230010',
        'Marta',
        'Flores',
        'Mujer',
        10,
        'marta.flores@email.com'
    ),
    (
        11,
        '20230011',
        'Andrés',
        'Vargas',
        'Hombre',
        11,
        'andres.vargas@email.com'
    ),
    (
        12,
        '20230012',
        'Patricia',
        'Castro',
        'Mujer',
        12,
        'patricia.castro@email.com'
    ),
    (
        13,
        '20230013',
        'Roberto',
        'Mendoza',
        'Hombre',
        13,
        'roberto.mendoza@email.com'
    ),
    (
        14,
        '20230014',
        'Isabel',
        'Rojas',
        'Mujer',
        14,
        'isabel.rojas@email.com'
    );

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facultades`
--

CREATE TABLE `facultades` (
    `id_facultad` int(11) NOT NULL,
    `nombre_facultad` varchar(100) NOT NULL,
    `codigo` varchar(10) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facultades`
--


CREATE TABLE `registro_asistencia` (
  `id` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `fecha_entrada` datetime DEFAULT NULL,
  `fecha_salida` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `registro_asistencia`
--
ALTER TABLE `registro_asistencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_estudiante` (`id_estudiante`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `registro_asistencia`
--
ALTER TABLE `registro_asistencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `registro_asistencia`
--
ALTER TABLE `registro_asistencia`
  ADD CONSTRAINT `registro_asistencia_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`);
COMMIT;


INSERT INTO
    `facultades` (
        `id_facultad`,
        `nombre_facultad`,
        `codigo`
    )
VALUES (
        1,
        'Facultad de Ingeniería',
        'FI'
    ),
    (
        2,
        'Facultad de Medicina',
        'FM'
    ),
    (
        3,
        'Facultad de Derecho',
        'FD'
    ),
    (
        4,
        'Facultad de Ciencias Económicas',
        'FCE'
    ),
    (
        5,
        'Facultad de Psicología',
        'FP'
    ),
    (
        6,
        'Facultad de Arquitectura',
        'FARQ'
    ),
    (
        7,
        'Facultad de Ciencias',
        'FC'
    ),
    (
        8,
        'Facultad de Educación',
        'FEDU'
    ),
    (
        9,
        'Facultad de Comunicación',
        'FCOM'
    ),
    (
        10,
        'Facultad de Artes',
        'FART'
    ),
    (
        11,
        'Facultad de Enfermería',
        'FENF'
    ),
    (
        12,
        'Facultad de Ciencias Sociales',
        'FCS'
    ),
    (
        13,
        'Facultad de Administración',
        'FADM'
    ),
    (
        14,
        'Facultad de Diseño',
        'FDIS'
    );

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
    `id_libro` int(11) NOT NULL,
    `titulo` varchar(200) NOT NULL,
    `id_categoria` int(11) NOT NULL,
    `autor` varchar(200) NOT NULL,
    `isbn` varchar(20) DEFAULT NULL,
    `año_publicacion` int(11) DEFAULT NULL,
    `editorial` varchar(100) DEFAULT NULL,
    `disponible` tinyint(1) DEFAULT 1,
    `portada` varchar(255) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO
    `libros` (
        `id_libro`,
        `titulo`,
        `id_categoria`,
        `autor`,
        `isbn`,
        `año_publicacion`,
        `editorial`,
        `disponible`,
        `portada`
    )
VALUES (
        1,
        '1984',
        1,
        'George Orwell',
        '9780451524935',
        1949,
        'Signet Classics',
        1,
        '1984.jpg'
    ),
    (
        2,
        'Cien años de soledad',
        6,
        'Gabriel García Márquez',
        '9780307474728',
        1967,
        'Penguin Random House',
        1,
        'cien_anos.jpg'
    ),
    (
        3,
        'El principito',
        14,
        'Antoine de Saint-Exupéry',
        '9780156012195',
        1943,
        'Harcourt Brace',
        1,
        'principito.jpg'
    ),
    (
        4,
        'Dracula',
        2,
        'Bram Stoker',
        '9780486411095',
        1897,
        'Dover Publications',
        1,
        'dracula.jpg'
    ),
    (
        5,
        'Orgullo y prejuicio',
        3,
        'Jane Austen',
        '9780141439518',
        1813,
        'Penguin Classics',
        1,
        'orgullo.jpg'
    ),
    (
        6,
        'Breve historia del tiempo',
        5,
        'Stephen Hawking',
        '9780553380163',
        1988,
        'Bantam Books',
        1,
        'tiempo.jpg'
    ),
    (
        7,
        'El arte de la guerra',
        12,
        'Sun Tzu',
        '9781590302255',
        500,
        'Shambhala',
        1,
        'arte_guerra.jpg'
    ),
    (
        8,
        'Clean Code',
        8,
        'Robert C. Martin',
        '9780132350884',
        2008,
        'Prentice Hall',
        1,
        'clean_code.jpg'
    ),
    (
        9,
        'Anatomía de Gray',
        9,
        'Henry Gray',
        '9780443066849',
        1858,
        'Churchill Livingstone',
        1,
        'anatomia.jpg'
    ),
    (
        10,
        'El contrato social',
        10,
        'Jean-Jacques Rousseau',
        '9780486426921',
        1762,
        'Dover Publications',
        1,
        'contrato.jpg'
    ),
    (
        11,
        'La riqueza de las naciones',
        13,
        'Adam Smith',
        '9780486478851',
        1776,
        'Dover Publications',
        1,
        'riqueza.jpg'
    ),
    (
        12,
        'El nombre de la rosa',
        6,
        'Umberto Eco',
        '9780151446476',
        1980,
        'Harcourt Brace',
        1,
        'rosa.jpg'
    ),
    (
        13,
        'IT',
        2,
        'Stephen King',
        '9781501142970',
        1986,
        'Scribner',
        1,
        'it.jpg'
    ),
    (
        14,
        'El alquimista',
        7,
        'Paulo Coelho',
        '9780062315007',
        1988,
        'HarperOne',
        1,
        'alquimista.jpg'
    );

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
    `id_prestamo` int(11) NOT NULL,
    `id_estudiante` int(11) NOT NULL,
    `id_libro` int(11) NOT NULL,
    `fecha_prestamo` datetime NOT NULL DEFAULT current_timestamp(),
    `fecha_devolucion_estimada` datetime NOT NULL,
    `fecha_devolucion_real` datetime DEFAULT NULL,
    `turno` enum('Mañana', 'Tarde', 'Noche') NOT NULL,
    `estado` enum(
        'Pendiente',
        'Devuelto',
        'Atrasado'
    ) DEFAULT 'Pendiente'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO
    `prestamos` (
        `id_prestamo`,
        `id_estudiante`,
        `id_libro`,
        `fecha_prestamo`,
        `fecha_devolucion_estimada`,
        `fecha_devolucion_real`,
        `turno`,
        `estado`
    )
VALUES (
        1,
        1,
        1,
        '2023-01-15 10:00:00',
        '2023-01-30 10:00:00',
        '2023-01-30 15:00:00',
        'Mañana',
        'Devuelto'
    ),
    (
        2,
        2,
        2,
        '2023-02-10 14:00:00',
        '2023-02-25 14:00:00',
        '2023-02-25 16:30:00',
        'Tarde',
        'Devuelto'
    ),
    (
        3,
        3,
        3,
        '2023-03-05 09:30:00',
        '2023-03-20 09:30:00',
        NULL,
        'Mañana',
        'Atrasado'
    ),
    (
        4,
        4,
        4,
        '2023-04-20 18:00:00',
        '2023-05-05 18:00:00',
        '2023-05-05 17:00:00',
        'Noche',
        'Devuelto'
    ),
    (
        5,
        5,
        5,
        '2023-05-12 11:00:00',
        '2023-05-27 11:00:00',
        NULL,
        'Mañana',
        'Pendiente'
    ),
    (
        6,
        6,
        6,
        '2023-06-08 15:30:00',
        '2023-06-23 15:30:00',
        '2023-06-23 14:00:00',
        'Tarde',
        'Devuelto'
    ),
    (
        7,
        7,
        7,
        '2023-07-03 19:00:00',
        '2023-07-18 19:00:00',
        '2023-07-18 18:30:00',
        'Noche',
        'Devuelto'
    ),
    (
        8,
        8,
        8,
        '2023-08-17 10:15:00',
        '2023-09-01 10:15:00',
        NULL,
        'Mañana',
        'Pendiente'
    ),
    (
        9,
        9,
        9,
        '2023-09-22 16:00:00',
        '2023-10-07 16:00:00',
        '2023-10-07 15:45:00',
        'Tarde',
        'Devuelto'
    ),
    (
        10,
        10,
        10,
        '2023-10-11 20:00:00',
        '2023-10-26 20:00:00',
        NULL,
        'Noche',
        'Atrasado'
    ),
    (
        11,
        11,
        11,
        '2023-11-05 09:45:00',
        '2023-11-20 09:45:00',
        '2023-11-20 10:30:00',
        'Mañana',
        'Devuelto'
    ),
    (
        12,
        12,
        12,
        '2023-12-14 13:30:00',
        '2023-12-29 13:30:00',
        NULL,
        'Tarde',
        'Pendiente'
    ),
    (
        13,
        13,
        13,
        '2024-01-09 17:45:00',
        '2024-01-24 17:45:00',
        '2024-01-24 19:00:00',
        'Noche',
        'Devuelto'
    ),
    (
        14,
        14,
        14,
        '2024-02-28 08:30:00',
        '2024-03-14 08:30:00',
        NULL,
        'Mañana',
        'Pendiente'
    );

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_permiso`
--

CREATE TABLE `solicitudes_permiso` (
    `id_solicitud` int(11) NOT NULL,
    `id_estudiante` int(11) NOT NULL,
    `fecha_solicitud` datetime NOT NULL DEFAULT current_timestamp(),
    `fecha_resolucion` datetime DEFAULT NULL,
    `estado` enum(
        'Pendiente',
        'Aprobada',
        'Rechazada'
    ) DEFAULT 'Pendiente',
    `motivo` text DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes_permiso`
--

INSERT INTO
    `solicitudes_permiso` (
        `id_solicitud`,
        `id_estudiante`,
        `fecha_solicitud`,
        `fecha_resolucion`,
        `estado`,
        `motivo`
    )
VALUES (
        1,
        1,
        '2023-01-10 09:00:00',
        '2023-01-12 10:00:00',
        'Aprobada',
        'Participación en conferencia'
    ),
    (
        2,
        2,
        '2023-02-05 14:30:00',
        '2023-02-07 15:00:00',
        'Aprobada',
        'Problemas de salud'
    ),
    (
        3,
        3,
        '2023-03-01 10:15:00',
        '2023-03-03 11:00:00',
        'Rechazada',
        'Viaje personal'
    ),
    (
        4,
        4,
        '2023-04-15 17:45:00',
        '2023-04-17 18:00:00',
        'Aprobada',
        'Compromiso familiar'
    ),
    (
        5,
        5,
        '2023-05-08 11:30:00',
        NULL,
        'Pendiente',
        'Consulta médica'
    ),
    (
        6,
        6,
        '2023-06-03 15:00:00',
        '2023-06-05 16:00:00',
        'Aprobada',
        'Taller extracurricular'
    ),
    (
        7,
        7,
        '2023-07-28 18:30:00',
        '2023-07-30 19:00:00',
        'Aprobada',
        'Emergencia familiar'
    ),
    (
        8,
        8,
        '2023-08-12 09:45:00',
        '2023-08-14 10:00:00',
        'Rechazada',
        'Viaje de placer'
    ),
    (
        9,
        9,
        '2023-09-18 16:20:00',
        '2023-09-20 17:00:00',
        'Aprobada',
        'Participación en torneo deportivo'
    ),
    (
        10,
        10,
        '2023-10-06 19:15:00',
        NULL,
        'Pendiente',
        'Problemas de transporte'
    ),
    (
        11,
        11,
        '2023-11-01 08:00:00',
        '2023-11-03 09:00:00',
        'Aprobada',
        'Congreso académico'
    ),
    (
        12,
        12,
        '2023-12-10 13:10:00',
        '2023-12-12 14:00:00',
        'Rechazada',
        'Motivos personales'
    ),
    (
        13,
        13,
        '2024-01-05 17:00:00',
        '2024-01-07 18:00:00',
        'Aprobada',
        'Trámite legal'
    ),
    (
        14,
        14,
        '2024-02-25 10:30:00',
        NULL,
        'Pendiente',
        'Consulta médica especializada'
    );

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
    `id_usuario` int(11) NOT NULL,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `nombre` varchar(100) NOT NULL,
    `rol` enum(
        'Administrador',
        'Bibliotecario',
        'Consulta'
    ) NOT NULL,
    `fecha_creacion` datetime DEFAULT current_timestamp(),
    `ultimo_login` datetime DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO
    `usuarios` (
        `id_usuario`,
        `username`,
        `password`,
        `nombre`,
        `rol`,
        `fecha_creacion`,
        `ultimo_login`
    )
VALUES (
        16,
        'el pelado',
        '$2y$10$deiABi7P/yE65cnNMBFQG.R0VfQ1gzR1F6fDND/f8PhUpBXEq5Sh.',
        'abdiel',
        '',
        '2025-04-21 23:53:31',
        '2025-04-22 00:29:57'
    );

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
ADD PRIMARY KEY (`id_carrera`),
ADD KEY `id_facultad` (`id_facultad`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias` ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
ADD PRIMARY KEY (`id_estudiante`),
ADD UNIQUE KEY `codigo_estudiante` (`codigo_estudiante`),
ADD KEY `id_carrera` (`id_carrera`);

--
-- Indices de la tabla `facultades`
--
ALTER TABLE `facultades` ADD PRIMARY KEY (`id_facultad`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
ADD PRIMARY KEY (`id_libro`),
ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
ADD PRIMARY KEY (`id_prestamo`),
ADD KEY `id_estudiante` (`id_estudiante`),
ADD KEY `id_libro` (`id_libro`);

--
-- Indices de la tabla `solicitudes_permiso`
--
ALTER TABLE `solicitudes_permiso`
ADD PRIMARY KEY (`id_solicitud`),
ADD KEY `id_estudiante` (`id_estudiante`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
ADD PRIMARY KEY (`id_usuario`),
ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
MODIFY `id_carrera` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 15;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 15;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 15;

--
-- AUTO_INCREMENT de la tabla `facultades`
--
ALTER TABLE `facultades`
MODIFY `id_facultad` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 15;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
MODIFY `id_libro` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 15;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
MODIFY `id_prestamo` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 15;

--
-- AUTO_INCREMENT de la tabla `solicitudes_permiso`
--
ALTER TABLE `solicitudes_permiso`
MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carreras`
--
ALTER TABLE `carreras`
ADD CONSTRAINT `carreras_ibfk_1` FOREIGN KEY (`id_facultad`) REFERENCES `facultades` (`id_facultad`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`);

--
-- Filtros para la tabla `libros`
--
ALTER TABLE `libros`
ADD CONSTRAINT `libros_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`);

--
-- Filtros para la tabla `solicitudes_permiso`
--
ALTER TABLE `solicitudes_permiso`
ADD CONSTRAINT `solicitudes_permiso_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;