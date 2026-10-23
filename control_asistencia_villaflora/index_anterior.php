<?php
$nombre_sistema = "Control de Asistencia";
$nombre_institucion = "Institución Educativa Villa Flora";

$secciones = [
    "inicio" => "Registrar asistencia",
    "estudiantes" => "Estudiantes",
    "clases" => "Horarios y clases",
    "asistencias" => "Consultar asistencias",
    "reportes" => "Reportes y estadísticas"
];

$iconos = [
    "inicio" => "fa-camera",
    "estudiantes" => "fa-users",
    "clases" => "fa-calendar-days",
    "asistencias" => "fa-clipboard-check",
    "reportes" => "fa-chart-column"
];

$descripciones = [
    "inicio" => "Registra la asistencia de los estudiantes mediante reconocimiento facial o registro manual.",
    "estudiantes" => "Administra los estudiantes, sus documentos y los grupos a los que pertenecen.",
    "clases" => "Organiza los docentes, las materias, los grupos y los horarios semanales.",
    "asistencias" => "Consulta presentes, tardanzas y ausencias. Revisa registros y gestiona excusas.",
    "reportes" => "Consulta estadísticas de asistencia, tardanzas y ausencias. Prepara los datos para Power BI."
];

$seccion = $_GET["seccion"] ?? "inicio";

if (!array_key_exists($seccion, $secciones)) {
    $seccion = "inicio";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($secciones[$seccion]); ?> | Villa Flora</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #f1f5f9;
            min-height: 100vh;
        }

        .sidebar {
            width: 255px;
            background: #1e293b;
            border-right: 1px solid #334155;
            position: fixed;
            inset: 0 auto 0 0;
            padding: 25px 16px;
            overflow-y: auto;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 8px 25px;
            border-bottom: 1px solid #334155;
        }

        .brand-icon {
            background: #3b82f6;
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 20px;
        }

        .brand h2 {
            font-size: 15px;
            margin-bottom: 5px;
        }

        .brand p {
            font-size: 11px;
            color: #94a3b8;
        }

        .menu-title {
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 27px 10px 12px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 12px;
            margin: 5px 0;
            border-radius: 8px;
            color: #cbd5e1;
            font-size: 13px;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }

        .menu-item:hover {
            background: #334155;
            color: #ffffff;
        }

        .menu-item.active {
            background: #3b82f6;
            color: #ffffff;
        }

        .menu-item i {
            width: 19px;
            text-align: center;
            font-size: 15px;
        }

        .sidebar-footer {
            margin: 35px 6px 10px;
            padding-top: 18px;
            border-top: 1px solid #334155;
            color: #94a3b8;
            font-size: 12px;
            line-height: 1.8;
        }

        main {
            margin-left: 255px;
            padding: 32px;
            min-width: 0;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .topbar h1 {
            font-size: 27px;
            margin-bottom: 8px;
        }

        .topbar p {
            color: #94a3b8;
            font-size: 14px;
            line-height: 1.6;
        }

        .date-badge {
            background: #1e293b;
            border: 1px solid #334155;
            padding: 12px 15px;
            border-radius: 10px;
            font-size: 12px;
            color: #cbd5e1;
            white-space: nowrap;
        }

        .section-heading {
            font-size: 17px;
            margin-bottom: 16px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .stat-card, .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 14px;
            min-width: 0;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 18px;
            color: #94a3b8;
            font-size: 13px;
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .blue {
            background: #1d4ed833;
            color: #60a5fa;
        }

        .green {
            background: #05966933;
            color: #34d399;
        }

        .yellow {
            background: #d9770633;
            color: #fbbf24;
        }

        .stat-number {
            font-size: 29px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .stat-note {
            color: #94a3b8;
            font-size: 12px;
            line-height: 1.5;
        }

        .workspace {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #334155;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .card-header h2 {
            font-size: 15px;
            line-height: 1.5;
        }

        .card-body {
            padding: 20px;
        }

        .camera-area {
            background: #0b1220;
            border: 1px dashed #475569;
            border-radius: 12px;
            min-height: 245px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
            text-align: center;
            padding: 22px;
        }

        .camera-icon {
            font-size: 43px;
            color: #60a5fa;
        }

        .camera-area h3 {
            font-size: 16px;
        }

        .camera-area p {
            color: #94a3b8;
            font-size: 13px;
            line-height: 1.7;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #334155;
            color: #cbd5e1;
            padding: 7px 10px;
            border-radius: 20px;
            font-size: 11px;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            background: #fbbf24;
            border-radius: 50%;
        }

        .button {
            border: none;
            background: #3b82f6;
            color: #ffffff;
            padding: 11px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .button:hover {
            background: #2563eb;
        }

        .button-secondary {
            background: #334155;
            color: #e2e8f0;
        }

        .button-secondary:hover {
            background: #475569;
        }

        .button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .camera-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-top: 18px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            text-align: left;
            color: #94a3b8;
            font-weight: normal;
            padding: 12px 8px;
            border-bottom: 1px solid #334155;
        }

        td {
            padding: 15px 8px;
            border-bottom: 1px solid #334155;
        }

        .empty-state {
            text-align: center;
            color: #94a3b8;
            padding: 32px 10px;
            font-size: 13px;
            line-height: 1.7;
        }

        .empty-state i {
            display: block;
            font-size: 27px;
            margin-bottom: 12px;
            color: #64748b;
        }

        .page-intro {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 14px;
            padding: 26px;
            margin-bottom: 22px;
        }

        .page-intro-icon {
            width: 52px;
            height: 52px;
            border-radius: 13px;
            background: #1d4ed833;
            color: #60a5fa;
            display: grid;
            place-items: center;
            font-size: 23px;
            margin-bottom: 18px;
        }

        .page-intro h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .page-intro p {
            color: #94a3b8;
            font-size: 14px;
            line-height: 1.8;
            max-width: 700px;
        }

        .notice {
            border: 1px solid #334155;
            border-radius: 10px;
            background: #0f172a;
            color: #cbd5e1;
            padding: 16px;
            font-size: 13px;
            line-height: 1.7;
        }

        .notice i {
            color: #60a5fa;
            margin-right: 8px;
        }

        .bottom-note {
            color: #64748b;
            font-size: 12px;
            text-align: center;
            margin-top: 28px;
            line-height: 1.7;
        }

        @media (max-width: 1050px) {
            .sidebar {
                width: 225px;
            }

            main {
                margin-left: 225px;
                padding: 22px;
            }

            .workspace {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 650px) {
            .sidebar {
                position: static;
                width: 100%;
            }

            .sidebar-footer {
                margin-top: 22px;
            }

            main {
                margin-left: 0;
                padding: 18px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .topbar h1 {
                font-size: 23px;
            }

            .date-badge {
                white-space: normal;
            }

            .card-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <h2>Villa Flora</h2>
                <p>Sistema académico</p>
            </div>
        </div>

        <p class="menu-title">Menú principal</p>

        <?php foreach ($secciones as $clave => $nombre): ?>
            <a
                class="menu-item <?php echo $seccion === $clave ? 'active' : ''; ?>"
                href="index.php?seccion=<?php echo urlencode($clave); ?>"
                <?php echo $seccion === $clave ? 'aria-current="page"' : ''; ?>
            >
                <i class="fa-solid <?php echo $iconos[$clave]; ?>"></i>
                <span><?php echo htmlspecialchars($nombre); ?></span>
            </a>
        <?php endforeach; ?>

        <div class="sidebar-footer">
            <i class="fa-solid fa-shield-halved"></i>
            Proyecto académico<br>
            Institución Educativa Villa Flora
        </div>
    </aside>

    <main>
        <header class="topbar">
            <div>
                <h1><?php echo htmlspecialchars($secciones[$seccion]); ?></h1>
                <p>
                    <?php echo htmlspecialchars($nombre_institucion); ?>
                    · Sistema de control de asistencia estudiantil.
                </p>
            </div>

            <div class="date-badge">
                <i class="fa-regular fa-calendar"></i>
                <span id="fechaActual">Cargando fecha...</span>
            </div>
        </header>

        <?php if ($seccion === "inicio"): ?>

            <h2 class="section-heading">Resumen de asistencia</h2>

            <section class="stats">
                <article class="stat-card">
                    <div class="stat-top">
                        Total de estudiantes
                        <span class="stat-icon blue">
                            <i class="fa-solid fa-users"></i>
                        </span>
                    </div>
                    <div class="stat-number">—</div>
                    <p class="stat-note">Pendiente de conectar con MySQL.</p>
                </article>

                <article class="stat-card">
                    <div class="stat-top">
                        Presentes hoy
                        <span class="stat-icon green">
                            <i class="fa-solid fa-user-check"></i>
                        </span>
                    </div>
                    <div class="stat-number">—</div>
                    <p class="stat-note">Se mostrarán los registros reales.</p>
                </article>

                <article class="stat-card">
                    <div class="stat-top">
                        Llegadas tarde
                        <span class="stat-icon yellow">
                            <i class="fa-solid fa-clock"></i>
                        </span>
                    </div>
                    <div class="stat-number">—</div>
                    <p class="stat-note">Según el horario de cada clase.</p>
                </article>
            </section>

            <section class="workspace">
                <article class="card">
                    <div class="card-header">
                        <h2><i class="fa-solid fa-camera"></i> Reconocimiento facial</h2>
                        <span class="status">
                            <span class="status-dot"></span>
                            Pendiente de configurar
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="camera-area">
                            <i class="fa-solid fa-camera camera-icon"></i>
                            <h3>Cámara de asistencia</h3>
                            <p>
                                Aquí conectaremos el modelo de reconocimiento
                                facial de Teachable Machine.
                            </p>
                            <p>
                                La asistencia se guardará en MySQL cuando
                                configuremos el reconocimiento.
                            </p>
                        </div>

                        <div class="camera-actions">
                            <button class="button" type="button" disabled>
                                <i class="fa-solid fa-camera"></i>
                                Activar cámara
                            </button>

                            <button class="button button-secondary" type="button" disabled>
                                <i class="fa-solid fa-keyboard"></i>
                                Registro manual
                            </button>
                        </div>
                    </div>
                </article>

                <article class="card">
                    <div class="card-header">
                        <h2>
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            Registros recientes
                        </h2>
                    </div>

                    <div class="card-body">
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Hora</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="3">
                                            <div class="empty-state">
                                                <i class="fa-regular fa-clipboard"></i>
                                                Todavía no hay registros.<br>
                                                Aquí aparecerán las asistencias guardadas.
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </article>
            </section>

        <?php else: ?>

            <section class="page-intro">
                <div class="page-intro-icon">
                    <i class="fa-solid <?php echo $iconos[$seccion]; ?>"></i>
                </div>

                <h2><?php echo htmlspecialchars($secciones[$seccion]); ?></h2>

                <p><?php echo htmlspecialchars($descripciones[$seccion]); ?></p>
            </section>

            <section class="card">
                <div class="card-header">
                    <h2>Espacio de trabajo</h2>
                    <span class="status">
                        <span class="status-dot"></span>
                        En preparación
                    </span>
                </div>

                <div class="card-body">
                    <div class="notice">
                        <i class="fa-solid fa-circle-info"></i>
                        Esta sección ya tiene su propia página dentro del
                        sistema. En el siguiente paso implementaremos sus
                        funciones y la conexión con la base de datos.
                        Por ahora no se han guardado ni modificado datos.
                    </div>

                    <div style="margin-top: 20px;">
                        <a class="button" href="index.php">
                            <i class="fa-solid fa-arrow-left"></i>
                            Volver al registro de asistencia
                        </a>
                    </div>
                </div>
            </section>

        <?php endif; ?>

        <p class="bottom-note">
            <?php echo htmlspecialchars($nombre_institucion); ?><br>
            Sistema de Control de Asistencia
        </p>
    </main>

    <script>
        const fecha = new Date();

        document.getElementById("fechaActual").textContent =
            fecha.toLocaleDateString("es-CO", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric"
            });
    </script>
</body>
</html>

