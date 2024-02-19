-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 19-02-2024 a las 21:35:35
-- Versión del servidor: 8.0.30
-- Versión de PHP: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `helpdesk_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos_tickets`
--

CREATE TABLE `archivos_tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `modelo_tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelo_id` bigint UNSIGNED NOT NULL,
  `archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_original` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `archivos_tickets`
--

INSERT INTO `archivos_tickets` (`id`, `modelo_tipo`, `modelo_id`, `archivo`, `nombre_original`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Ticket', 1, '51216618203641.jpg', 'paisaje6.jpg', '2022-08-30 00:46:04', '2022-08-30 00:46:04'),
(3, 'App\\Models\\SolucionTicket', 1, '201516618204351.jpg', 'iabrain3.jpg', '2022-08-30 00:47:15', '2022-08-30 00:47:15'),
(4, 'App\\Models\\SolucionTicket', 3, '11916618204773.jpg', 'iabrain3.jpg', '2022-08-30 00:47:57', '2022-08-30 00:47:57'),
(5, 'App\\Models\\Ticket', 3, '51416618206373.jpg', 'Vídeos-de-paisajes-naturales.jpg', '2022-08-30 00:50:37', '2022-08-30 00:50:37'),
(6, 'App\\Models\\SolucionTicket', 4, '6116618206754.jpg', 'paisaje7.jpg', '2022-08-30 00:51:15', '2022-08-30 00:51:15'),
(7, 'App\\Models\\SolucionTicket', 4, '191716618206754.jpg', 'paisaje2.jpg', '2022-08-30 00:51:15', '2022-08-30 00:51:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracions`
--

CREATE TABLE `configuracions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razon_social` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `web` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actividad_economica` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `alias`, `razon_social`, `nit`, `ciudad`, `dir`, `fono`, `web`, `actividad_economica`, `correo`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'HELPDESK', 'HD', 'RAZON SOCIAL DE PRUEBA', '1111111111111111111', 'LA PAZ', 'ZONA LOS OLIVOS C. 4 #444', '2222222', '', 'ACTIVIDAD ECONOMICA', '', 'logo.png', NULL, '2022-08-17 15:36:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `tipo_soporte_id` bigint UNSIGNED NOT NULL,
  `pregunta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `respuesta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `faqs`
--

INSERT INTO `faqs` (`id`, `tipo_soporte_id`, `pregunta`, `respuesta`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 1, 'IS SIMPLY DUMMY TEXT OF THE PRINTING AND TYPESETTING INDUSTRY', 'LOREM IPSUM HAS BEEN THE INDUSTRY\'S STANDARD DUMMY TEXT EVER SINCE THE 1500S', '2022-08-29', '2022-08-30 00:37:33', '2022-08-30 00:37:33'),
(2, 2, 'LOREM IPSUM HAS BEEN THE INDUSTRY\'S STANDARD DUMMY TEXT EVER SINCE THE 1500S', 'T IS A LONG ESTABLISHED FACT THAT A READER WILL BE DISTRACTED BY THE READABLE CONTENT OF A PAGE WHEN LOOKING AT ITS LAYOUT. THE POINT OF USING LOREM IPSUM IS THAT IT HAS A MORE-OR-LESS NORMAL', '2022-08-29', '2022-08-30 00:45:14', '2022-08-30 00:45:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion_usuarios`
--

CREATE TABLE `informacion_usuarios` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `informacion_usuarios`
--

INSERT INTO `informacion_usuarios` (`id`, `user_id`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `dir`, `correo`, `fono`, `cel`, `foto`, `created_at`, `updated_at`) VALUES
(1, 2, 'JUAN', 'PERES', '', '1111', 'LP', 'ZONA LOS OLIVOS CALLE 3 #333', '', '222222', '7777777', 'default.png', '2022-08-30 00:36:43', '2022-08-30 00:36:43'),
(2, 3, 'MARIA', 'MAMANI', '', '2222', 'CB', 'ZONA LOS OLIVOS', 'victorgonzalo.as@gmail.com', '22222', '777777', 'default.png', '2022-08-30 00:37:02', '2022-08-30 00:37:02'),
(3, 4, 'PEDRO', 'RAMIRES', '', '3333', 'LP', 'LOS OLIVOS', 'pedro@gmail.com', '22222', '777777', '1661820601_PRAMIRES.jpg', '2022-08-30 00:50:01', '2022-08-30 00:50:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2022_08_14_000000_create_informacion_usuarios_table', 1),
(5, '2022_08_17_131935_create_personals_table', 1),
(6, '2022_08_17_132012_create_tipo_soportes_table', 1),
(7, '2022_08_17_132121_create_tickets_table', 1),
(8, '2022_08_17_132134_create_solucion_tickets_table', 1),
(9, '2022_08_17_132146_create_faqs_table', 1),
(10, '2022_08_17_134837_create_archivos_tickets_table', 1),
(11, '2022_08_17_131904_create_configuracions_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personals`
--

CREATE TABLE `personals` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personals`
--

INSERT INTO `personals` (`id`, `user_id`) VALUES
(1, 3),
(2, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` bigint UNSIGNED NOT NULL,
  `pregunta` text COLLATE utf8mb4_general_ci NOT NULL,
  `respuesta` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id`, `pregunta`, `respuesta`, `created_at`, `updated_at`) VALUES
(1, 'Mi computadora no enciende', 'Verifica que esté conectada a una fuente de energía, intenta encenderla con otro cable de alimentación y asegúrate de que el interruptor de la fuente de alimentación esté en la posición correcta. Si sigue sin encender, podría haber un problema con la fuente de poder o la placa madre.', NULL, NULL),
(2, 'Mi computadora está lenta', 'Puedes intentar desfragmentar el disco duro, eliminar programas innecesarios, aumentar la memoria RAM o considerar la posibilidad de cambiar a un disco de estado sólido (SSD). También es útil realizar un escaneo en busca de malware y asegurarse de que los controladores estén actualizados', NULL, NULL),
(3, 'Por qué mi conexión a Internet es inestable', 'Puede deberse a problemas con el router, interferencias de otros dispositivos, o incluso problemas con el proveedor de servicios. Intenta reiniciar el router, cambiar de canal Wi-Fi, o contactar a tu proveedor de servicios de Internet para obtener asistencia', NULL, NULL),
(4, 'Mi impresora no imprime correctamente', 'Verifica que haya suficiente papel y tinta/toner. Actualiza los controladores de la impresora, limpia los cabezales y asegúrate de que no haya atascos de papel. Si el problema persiste, considera reinstalar la impresora', NULL, NULL),
(5, 'He perdido archivos importantes. ¿Cómo puedo recuperarlos?', 'Intenta utilizar software de recuperación de datos. También verifica la papelera de reciclaje o la carpeta de archivos temporales. En el futuro, realiza copias de seguridad periódicas para evitar la pérdida de datos', NULL, NULL),
(6, 'Mi computadora muestra una pantalla azul (BSOD)', 'La pantalla azul indica un error del sistema. Puede ser causado por problemas de hardware, controladores incompatibles o fallas en el sistema operativo. Anota el código de error y busca información específica sobre el problema en línea o consulta a un profesional de soporte técnico', NULL, NULL),
(7, 'Mi software no se instala correctamente. ¿Cómo puedo solucionarlo?', 'Asegúrate de que el software sea compatible con tu sistema operativo. Intenta desinstalar y reinstalar el programa. Si el problema persiste, verifica si hay conflictos con otros programas o contacta al soporte técnico del fabricante', NULL, NULL),
(8, 'Mi computadora se reinicia de forma inesperada. ¿Cuál podría ser la causa?', 'Este problema podría estar relacionado con sobrecalentamiento, problemas de fuente de alimentación, o incluso malware. Verifica la temperatura del sistema, asegúrate de que el ventilador esté funcionando correctamente y realiza un escaneo antivirus. Si el problema persiste, considera revisar la fuente de poder o buscar asistencia profesional.', NULL, NULL),
(9, '¿Cómo puedo solucionar problemas de audio en mi computadora?', 'Asegúrate de que los altavoces o auriculares estén conectados correctamente. Verifica el control de volumen y los ajustes de audio en el sistema operativo. Actualiza los controladores de audio y asegúrate de que no haya conflictos con otros dispositivos', NULL, NULL),
(10, 'Mi computadora no reconoce un dispositivo USB. ¿Cómo puedo resolver esto?', 'Prueba conectando el dispositivo a otro puerto USB. Asegúrate de que el dispositivo esté correctamente enchufado y funcione en otra computadora. Intenta desinstalar y reinstalar los controladores USB. Si nada funciona, podría ser un problema de hardware en la computadora o el dispositivo.', NULL, NULL),
(11, 'Mi sistema operativo muestra mensajes de error al iniciar. ¿Qué debo hacer?', 'Anota el mensaje de error y realiza una búsqueda en línea para obtener información sobre el problema específico. Intenta iniciar en modo seguro y desinstalar recientemente instalado o actualizado software que pueda estar causando conflictos. En casos extremos, puedes considerar la reinstalación del sistema operativo.', NULL, NULL),
(12, '¿Cómo puedo proteger mi computadora contra virus y malware?', 'Utiliza software antivirus actualizado y realiza escaneos periódicos. Mantén el sistema operativo y todos los programas actualizados. Evita hacer clic en enlaces sospechosos o descargar archivos de fuentes no confiables. Realiza copias de seguridad regulares para proteger tus datos en caso de un ataque.', NULL, NULL),
(13, 'Mi pantalla muestra píxeles muertos o distorsiones. ¿Cómo puedo resolver este problema?', 'Verifica si el cable de conexión entre la computadora y el monitor está bien conectado. Si el problema persiste, prueba con otro cable o puerto. También puedes actualizar los controladores de la tarjeta gráfica y realizar pruebas con otro monitor para descartar problemas de hardware.', NULL, NULL),
(14, 'Mi laptop se calienta mucho. ¿Cómo puedo evitar el sobrecalentamiento?', 'Asegúrate de que las ventilaciones estén limpias y sin obstrucciones. Utiliza una base de enfriamiento para elevar la laptop y permitir un mejor flujo de aire. Cierra programas innecesarios que puedan estar consumiendo recursos y considera la aplicación de pasta térmica en el procesador si es necesario.', NULL, NULL),
(15, 'Mi ratón o teclado inalámbrico no responde. ¿Cómo puedo solucionarlo?', 'Verifica si los dispositivos tienen batería y si están encendidos. Intenta volver a emparejarlos con su receptor USB. Si eso no funciona, prueba con otro puerto USB o reinicia la computadora. Si es un problema recurrente, puede ser necesario actualizar los controladores o reemplazar los dispositivos.', NULL, NULL),
(16, 'Mi conexión Wi-Fi es lenta o intermitente. ¿Cómo puedo mejorarla?', 'Ubica el router en un lugar central y libre de obstrucciones. Actualiza el firmware del router y ajusta el canal Wi-Fi para evitar interferencias. También puedes considerar la actualización de tu tarjeta de red o utilizar un extensor de alcance para mejorar la cobertura.', NULL, NULL),
(17, 'Mi sistema operativo muestra mensajes de error de \"falta de memoria\". ¿Cómo puedo abordar esto?', 'Cierra programas innecesarios que consuman demasiada memoria. Considera agregar más memoria RAM a tu computadora si es posible. También puedes identificar y solucionar posibles fugas de memoria mediante la supervisión del Administrador de tareas o Activity Monitor.', NULL, NULL),
(18, 'Mi software se bloquea o se congela con frecuencia. ¿Qué debo hacer?', 'Asegúrate de que el software esté actualizado. Verifica si hay actualizaciones de los controladores de hardware relacionados. Si el problema persiste, intenta desinstalar y reinstalar el software. También puedes revisar los registros de errores para obtener pistas sobre la causa del bloqueo.', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solucion_tickets`
--

CREATE TABLE `solucion_tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tipo_incidencia` enum('REGISTRO','SOLUCION') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `asunto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `envio` enum('ENVIADO','RECIBIDO') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('PENDIENTE','SOLUCIONADO') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `solucion_tickets`
--

INSERT INTO `solucion_tickets` (`id`, `ticket_id`, `user_id`, `tipo_incidencia`, `asunto`, `descripcion`, `envio`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'SOLUCION', 'PRUEBA SOLUCION PENDIENTE', 'DESCRIPCION', 'RECIBIDO', 'PENDIENTE', '2022-08-30 00:47:15', '2022-08-30 00:47:15'),
(2, 1, 3, 'REGISTRO', 'PRUEBA RESPUESTA', 'DESCRIPCION', 'RECIBIDO', 'PENDIENTE', '2022-08-30 00:47:43', '2022-08-30 00:47:43'),
(3, 1, 2, 'SOLUCION', 'SOLUCIONADO', 'PRUEBA SOLUCION', 'RECIBIDO', 'SOLUCIONADO', '2022-08-30 00:47:57', '2022-08-30 00:47:57'),
(4, 3, 2, 'SOLUCION', 'SOLUCION RAPIDA', 'DESCRIPCION SOLUCION CON ARCHIVOS', 'RECIBIDO', 'SOLUCIONADO', '2022-08-30 00:51:15', '2022-08-30 00:51:15'),
(5, 2, 2, 'SOLUCION', 'PRUEBA SOLUCION', 'DESCRIPCION SOLUCION', 'RECIBIDO', 'PENDIENTE', '2022-08-30 00:58:48', '2022-08-30 00:58:48'),
(6, 5, 2, 'SOLUCION', 'SOLUCIÓN', 'PUEDE DEBERSE A PROBLEMAS CON EL ROUTER, INTERFERENCIAS DE OTROS DISPOSITIVOS, O INCLUSO PROBLEMAS CON EL PROVEEDOR DE SERVICIOS. INTENTA REINICIAR EL ROUTER, CAMBIAR DE CANAL WI-FI, O CONTACTAR A TU PROVEEDOR DE SERVICIOS DE INTERNET PARA OBTENER ASISTENCIA', 'RECIBIDO', 'SOLUCIONADO', '2024-02-19 21:11:58', '2024-02-19 21:11:58'),
(7, 10, 2, 'SOLUCION', 'ASUNTO #2', 'INTENTA UTILIZAR SOFTWARE DE RECUPERACIÓN DE DATOS. TAMBIÉN VERIFICA LA PAPELERA DE RECICLAJE O LA CARPETA DE ARCHIVOS TEMPORALES. EN EL FUTURO, REALIZA COPIAS DE SEGURIDAD PERIÓDICAS PARA EVITAR LA PÉRDIDA DE DATOS', 'RECIBIDO', 'SOLUCIONADO', '2024-02-19 21:24:00', '2024-02-19 21:24:00'),
(8, 11, 2, 'SOLUCION', 'ASUNTO #3', 'LA PANTALLA AZUL INDICA UN ERROR DEL SISTEMA. PUEDE SER CAUSADO POR PROBLEMAS DE HARDWARE, CONTROLADORES INCOMPATIBLES O FALLAS EN EL SISTEMA OPERATIVO. ANOTA EL CÓDIGO DE ERROR Y BUSCA INFORMACIÓN ESPECÍFICA SOBRE EL PROBLEMA EN LÍNEA O CONSULTA A UN PROFESIONAL DE SOPORTE TÉCNICO', 'RECIBIDO', 'SOLUCIONADO', '2024-02-19 21:24:51', '2024-02-19 21:24:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `tipo_soporte_id` bigint UNSIGNED NOT NULL,
  `prioridad` enum('ALTO','MEDIO','BAJO') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `asunto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('PENDIENTE','SOLUCIONADO') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_registro` date NOT NULL,
  `envio` enum('ENVIADO','RECIBIDO') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `eliminado` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tickets`
--

INSERT INTO `tickets` (`id`, `tipo_soporte_id`, `prioridad`, `asunto`, `descripcion`, `estado`, `editable`, `fecha_registro`, `envio`, `user_id`, `eliminado`, `created_at`, `updated_at`) VALUES
(1, 1, 'ALTO', 'TICKET PRIORIDAD ALTO', 'IT IS A LONG ESTABLISHED FACT THAT A READER WILL BE DISTRACTED BY THE READABLE CONTENT OF A PAGE WHEN LOOKING AT ITS LAYOUT. THE POINT OF USING LOREM IPSUM IS THAT IT HAS A MORE-OR-LESS NORMAL DISTRIBUTION OF LETTERS, AS OPPOSED TO USING \'CONTENT HERE', 'SOLUCIONADO', 1, '2022-08-29', 'RECIBIDO', 3, 0, '2022-08-30 00:46:04', '2022-08-30 01:43:37'),
(2, 2, 'MEDIO', 'TICKET PRIORIDAD MEDIO HARDWARE', 'IT IS A LONG ESTABLISHED FACT THAT A READER WILL BE DISTRACTED BY THE READABLE CONTENT OF A PAGE WHEN LOOKING AT ITS LAYOUT. THE POINT OF USING LOREM IPSUM IS THAT IT HAS A MORE-OR-LESS NORMAL DISTRIBUTION OF LETTERS, AS OPPOSED TO USING \'CONTENT HERE', 'PENDIENTE', 1, '2022-08-29', 'RECIBIDO', 3, 0, '2022-08-30 00:46:04', '2022-08-30 01:43:22'),
(3, 1, 'BAJO', 'TICKET PRIORDAD BAJO', 'DESCRIPCION TICKET CON PRIORDAD BAJO', 'SOLUCIONADO', 1, '2022-08-29', 'RECIBIDO', 4, 0, '2022-08-30 00:50:37', '2022-08-30 01:43:22'),
(5, 1, 'ALTO', 'ASUNTO #1', 'POR QUÉ MI CONEXIÓN A INTERNET ES INESTABLE', 'SOLUCIONADO', 1, '2024-02-19', 'RECIBIDO', 3, 0, '2024-02-19 21:11:58', '2024-02-19 21:11:58'),
(10, 1, 'ALTO', 'ASUNTO #2', 'HE PERDIDO ARCHIVOS IMPORTANTES. CÓMO PUEDO RECUPERARLOS?', 'SOLUCIONADO', 1, '2024-02-19', 'RECIBIDO', 3, 0, '2024-02-19 21:24:00', '2024-02-19 21:24:00'),
(11, 1, 'ALTO', 'ASUNTO #3', 'MI COMPUTADORA MUESTRA UNA PANTALLA AZUL BSOD', 'PENDIENTE', 1, '2024-02-19', 'RECIBIDO', 3, 0, '2024-02-19 21:24:51', '2024-02-19 21:24:51'),
(12, 2, 'MEDIO', 'TICKET MEDIO', 'DESCRIPCION PROBLEMA MEDIO', 'PENDIENTE', 1, '2024-02-19', 'ENVIADO', 3, 0, '2024-02-19 21:26:15', '2024-02-19 21:26:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_soportes`
--

CREATE TABLE `tipo_soportes` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_soportes`
--

INSERT INTO `tipo_soportes` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'SOFTWARE', '', '2022-08-18 14:15:31', '2022-08-18 14:15:31'),
(2, 'HARDWARE', 'DESCRIPCION DE HARDWARE', '2022-08-18 14:15:56', '2022-08-18 14:16:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('ADMINISTRADOR','PERSONAL','TÉCNICO') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `password`, `tipo`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$cDSOdzTsMDQAfqcb6.WFtu40s.wmQ4Jl8poIwW69MSZnnedD3prKu', 'ADMINISTRADOR', '2022-08-17', NULL, NULL),
(2, 'JPERES', '$2y$10$sdHndqy.H9DSG5YWSjrSqOgndjg75dr8Re9.9lcXQitNE9n5zSGJC', 'TÉCNICO', '2022-08-29', '2022-08-30 00:36:43', '2022-08-30 00:36:43'),
(3, 'MMAMANI', '$2y$10$gG3KUa579RZx7NbBMO7jSekfP0VaF3pKn7jLWlgXk5EQKWzOAhUXC', 'PERSONAL', '2022-08-29', '2022-08-30 00:37:02', '2022-08-30 00:37:02'),
(4, 'PRAMIRES', '$2y$10$FzEu7qEcjIj2sPfX2Os6h.h2Ma44PnuZI/qGjXHkHaci4ipjTWoim', 'PERSONAL', '2022-08-29', '2022-08-30 00:50:01', '2022-08-30 00:50:01');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `archivos_tickets`
--
ALTER TABLE `archivos_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `informacion_usuarios`
--
ALTER TABLE `informacion_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `informacion_usuarios_correo_unique` (`correo`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personals`
--
ALTER TABLE `personals`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solucion_tickets`
--
ALTER TABLE `solucion_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solucion_tickets_ticket_id_foreign` (`ticket_id`),
  ADD KEY `solucion_tickets_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_soportes`
--
ALTER TABLE `tipo_soportes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `archivos_tickets`
--
ALTER TABLE `archivos_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `informacion_usuarios`
--
ALTER TABLE `informacion_usuarios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `personals`
--
ALTER TABLE `personals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `solucion_tickets`
--
ALTER TABLE `solucion_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `tipo_soportes`
--
ALTER TABLE `tipo_soportes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `solucion_tickets`
--
ALTER TABLE `solucion_tickets`
  ADD CONSTRAINT `solucion_tickets_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`),
  ADD CONSTRAINT `solucion_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
