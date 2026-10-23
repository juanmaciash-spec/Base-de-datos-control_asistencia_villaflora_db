<?php
// =============================================
// CONEXIÓN CON MYSQL
// =============================================
$conexion = new mysqli("localhost", "root", "", "control_asistencia_villaflora_db");
if ($conexion->connect_error) {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode(["ok" => false, "mensaje" => "No se pudo conectar con la base de datos."]);
        exit;
    }
    die("Error de conexión con MySQL: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");

// =============================================
// ENDPOINTS AJAX: BUSCAR ESTUDIANTE, LISTAR CLASES Y REGISTRAR ASISTENCIA
// =============================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json; charset=utf-8");
    $accion = $_POST["accion"] ?? "";

    if ($accion === "buscar_estudiante") {
        $etiqueta = trim($_POST["etiqueta"] ?? "");
        if ($etiqueta === "") {
            echo json_encode(["ok" => false, "mensaje" => "No se recibió una etiqueta del modelo."]);
            exit;
        }
        $stmt = $conexion->prepare("SELECT e.id_estudiante, e.nombre_completo, e.documento, e.id_grupo, g.nombre_grupo FROM estudiante e LEFT JOIN grupo g ON g.id_grupo = e.id_grupo WHERE e.clase_teachable = ? AND e.activo = 1 LIMIT 1");
        if (!$stmt) { echo json_encode(["ok" => false, "mensaje" => "No se pudo preparar la consulta del estudiante."]); exit; }
        $stmt->bind_param("s", $etiqueta);
        $stmt->execute();
        $estudiante = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$estudiante) {
            echo json_encode(["ok" => false, "mensaje" => "No se encontró un estudiante activo con la etiqueta: " . $etiqueta]);
            exit;
        }
        echo json_encode(["ok" => true, "estudiante" => $estudiante]);
        exit;
    }

    if ($accion === "listar_clases") {
        $id_estudiante = filter_input(INPUT_POST, "id_estudiante", FILTER_VALIDATE_INT);
        if (!$id_estudiante) { echo json_encode(["ok" => false, "mensaje" => "El estudiante no es válido.", "clases" => []]); exit; }
        $stmt = $conexion->prepare("SELECT c.id_clase, c.dia_semana, c.hora_inicio, c.hora_fin FROM clase c INNER JOIN estudiante e ON e.id_grupo = c.id_grupo WHERE e.id_estudiante = ? AND e.activo = 1 AND c.activo = 1 ORDER BY c.dia_semana, c.hora_inicio");
        if (!$stmt) { echo json_encode(["ok" => false, "mensaje" => "No se pudieron consultar las clases.", "clases" => []]); exit; }
        $stmt->bind_param("i", $id_estudiante);
        $stmt->execute();
        $res = $stmt->get_result();
        $clases = [];
        while ($row = $res->fetch_assoc()) { $clases[] = $row; }
        $stmt->close();
        echo json_encode(["ok" => true, "clases" => $clases]);
        exit;
    }

    if ($accion === "registrar_asistencia") {
        $id_estudiante = filter_input(INPUT_POST, "id_estudiante", FILTER_VALIDATE_INT);
        $id_clase = filter_input(INPUT_POST, "id_clase", FILTER_VALIDATE_INT);
        if (!$id_estudiante || !$id_clase) { echo json_encode(["ok" => false, "mensaje" => "Selecciona un estudiante y una clase válidos."]); exit; }

        $stmt = $conexion->prepare("SELECT e.id_estudiante FROM estudiante e INNER JOIN clase c ON c.id_grupo = e.id_grupo WHERE e.id_estudiante = ? AND e.activo = 1 AND c.id_clase = ? AND c.activo = 1 LIMIT 1");
        if (!$stmt) { echo json_encode(["ok" => false, "mensaje" => "No se pudo verificar el estudiante y la clase."]); exit; }
        $stmt->bind_param("ii", $id_estudiante, $id_clase);
        $stmt->execute();
        $valido = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        if (!$valido) { echo json_encode(["ok" => false, "mensaje" => "El estudiante no está activo o la clase no corresponde a su grupo."]); exit; }

        $stmt = $conexion->prepare("SELECT id_asistencia FROM registro_asistencia WHERE id_estudiante = ? AND id_clase = ? AND fecha = CURDATE() LIMIT 1");
        if (!$stmt) { echo json_encode(["ok" => false, "mensaje" => "No se pudo verificar si ya existe la asistencia."]); exit; }
        $stmt->bind_param("ii", $id_estudiante, $id_clase);
        $stmt->execute();
        $duplicada = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        if ($duplicada) { echo json_encode(["ok" => false, "mensaje" => "La asistencia ya fue registrada hoy para esta clase."]); exit; }

        
        // Consultar el horario de inicio y la hora actual de MySQL
        $stmtHora = $conexion->prepare("
        SELECT hora_inicio, CURTIME() AS hora_actual
        FROM clase
        WHERE id_clase = ? AND activo = 1
        LIMIT 1
        ");

        if (!$stmtHora) {
        echo json_encode([
        "ok" => false,
        "mensaje" => "No se pudo consultar el horario de la clase."
    ]);
    exit;
}

        $stmtHora->bind_param("i", $id_clase);
        $stmtHora->execute();
        $datosHora = $stmtHora->get_result()->fetch_assoc();
        $stmtHora->close();

        if (!$datosHora) {
        echo json_encode([
        "ok" => false,
        "mensaje" => "No se encontró el horario de la clase."
    ]);
    exit;
}

// Margen de tardanza: 0 minutos
$horaInicio = $datosHora["hora_inicio"];
$horaActual = $datosHora["hora_actual"];

$estado = ($horaActual > $horaInicio) ? 2 : 1;

$metodo = "Teachable Machine";

$stmt = $conexion->prepare("
    INSERT INTO registro_asistencia
    (id_estudiante, id_clase, id_estado, fecha, hora_llegada, metodo_registro)
    VALUES (?, ?, ?, CURDATE(), CURTIME(), ?)
");

if (!$stmt) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "No se pudo preparar el registro de asistencia."
    ]);
    exit;
}

$stmt->bind_param("iiis", $id_estudiante, $id_clase, $estado, $metodo);

if ($stmt->execute()) {
    $mensajeEstado = ($estado === 2) ? "Tarde" : "Presente";

    echo json_encode([
        "ok" => true,
        "mensaje" => "Asistencia registrada correctamente. Estado: " . $mensajeEstado
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "mensaje" => "No se pudo guardar la asistencia."
    ]);
}

        $stmt->close();
        exit;
        $metodo = "Teachable Machine";
        $stmt = $conexion->prepare("INSERT INTO registro_asistencia (id_estudiante, id_clase, id_estado, fecha, hora_llegada, metodo_registro) VALUES (?, ?, ?, CURDATE(), CURTIME(), ?)");
        if (!$stmt) { echo json_encode(["ok" => false, "mensaje" => "No se pudo preparar el registro de asistencia."]); exit; }
        $stmt->bind_param("iiis", $id_estudiante, $id_clase, $estado, $metodo);
        if ($stmt->execute()) { echo json_encode(["ok" => true, "mensaje" => "Asistencia registrada correctamente."]); }
        else { echo json_encode(["ok" => false, "mensaje" => "No se pudo guardar la asistencia."]); }
        $stmt->close();
        exit;
    }

    echo json_encode(["ok" => false, "mensaje" => "Acción no reconocida."]);
    exit;
}

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

// =============================================
// DATOS REALES PARA EL PANEL PRINCIPAL
// =============================================
$totalEstudiantes = 0;
$totalPresentes = 0;
$totalTardes = 0;
$registrosRecientes = [];
$errorPanel = false;

// Total de estudiantes activos
$sql = "SELECT COUNT(*) AS total
        FROM estudiante
        WHERE activo = 1";
$resultado = $conexion->query($sql);

if ($resultado) {
    $totalEstudiantes = (int) $resultado->fetch_assoc()["total"];
} else {
    $errorPanel = true;
}

// Presentes registrados hoy
$sql = "SELECT COUNT(*) AS total
        FROM registro_asistencia
        WHERE fecha = CURDATE()
        AND id_estado = 1";
$resultado = $conexion->query($sql);

if ($resultado) {
    $totalPresentes = (int) $resultado->fetch_assoc()["total"];
} else {
    $errorPanel = true;
}

// Llegadas tarde registradas hoy
$sql = "SELECT COUNT(*) AS total
        FROM registro_asistencia
        WHERE fecha = CURDATE()
        AND id_estado = 2";
$resultado = $conexion->query($sql);

if ($resultado) {
    $totalTardes = (int) $resultado->fetch_assoc()["total"];
} else {
    $errorPanel = true;
}

// Últimas asistencias registradas
$sql = "SELECT
            e.nombre_completo,
            r.hora_llegada,
            r.id_estado
        FROM registro_asistencia r
        INNER JOIN estudiante e
            ON e.id_estudiante = r.id_estudiante
        ORDER BY r.fecha DESC, r.hora_llegada DESC
        LIMIT 8";

$resultado = $conexion->query($sql);

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $registrosRecientes[] = $fila;
    }
} else {
    $errorPanel = true;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($secciones[$seccion]); ?> | Villa Flora</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@1.7.4/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@0.8/dist/teachablemachine-image.min.js"></script>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Arial,sans-serif;background:#0f172a;color:#f1f5f9;min-height:100vh}
        .sidebar{width:255px;background:#1e293b;border-right:1px solid #334155;position:fixed;inset:0 auto 0 0;padding:25px 16px;overflow-y:auto}
        .brand{display:flex;align-items:center;gap:12px;padding:0 8px 25px;border-bottom:1px solid #334155}
        .brand-icon{background:#3b82f6;width:42px;height:42px;flex-shrink:0;border-radius:12px;display:grid;place-items:center;font-size:20px}
        .brand h2{font-size:15px;margin-bottom:5px}.brand p{font-size:11px;color:#94a3b8}
        .menu-title{color:#64748b;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin:27px 10px 12px}
        .menu-item{display:flex;align-items:center;gap:12px;padding:13px 12px;margin:5px 0;border-radius:8px;color:#cbd5e1;font-size:13px;text-decoration:none;transition:background .2s,color .2s}
        .menu-item:hover{background:#334155;color:#fff}.menu-item.active{background:#3b82f6;color:#fff}
        .menu-item i{width:19px;text-align:center;font-size:15px}
        .sidebar-footer{margin:35px 6px 10px;padding-top:18px;border-top:1px solid #334155;color:#94a3b8;font-size:12px;line-height:1.8}
        main{margin-left:255px;padding:32px;min-width:0}
        .topbar{display:flex;justify-content:space-between;align-items:center;gap:20px;margin-bottom:30px}
        .topbar h1{font-size:27px;margin-bottom:8px}.topbar p{color:#94a3b8;font-size:14px;line-height:1.6}
        .date-badge{background:#1e293b;border:1px solid #334155;padding:12px 15px;border-radius:10px;font-size:12px;color:#cbd5e1;white-space:nowrap}
        .section-heading{font-size:17px;margin-bottom:16px}
        .stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;margin-bottom:30px}
        .stat-card,.card{background:#1e293b;border:1px solid #334155;border-radius:14px;min-width:0}
        .stat-card{padding:20px}.stat-top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:18px;color:#94a3b8;font-size:13px}
        .stat-icon{width:38px;height:38px;display:grid;place-items:center;border-radius:10px;flex-shrink:0}
        .blue{background:#1d4ed833;color:#60a5fa}.green{background:#05966933;color:#34d399}.yellow{background:#d9770633;color:#fbbf24}
        .stat-number{font-size:29px;font-weight:bold;margin-bottom:7px}.stat-note{color:#94a3b8;font-size:12px;line-height:1.5}
        .workspace{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}
        .card-header{padding:20px;border-bottom:1px solid #334155;display:flex;align-items:center;justify-content:space-between;gap:10px}
        .card-header h2{font-size:15px;line-height:1.5}.card-body{padding:20px}
        .camera-area{background:#0b1220;border:1px dashed #475569;border-radius:12px;min-height:245px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;text-align:center;padding:22px}
        .camera-icon{font-size:43px;color:#60a5fa}.camera-area h3{font-size:16px}.camera-area p{color:#94a3b8;font-size:13px;line-height:1.7}
        #webcam-container canvas{display:block;width:100%;max-width:320px;height:auto;border-radius:10px}
        #label-container p{overflow-wrap:anywhere}
        .status{display:inline-flex;align-items:center;gap:7px;background:#334155;color:#cbd5e1;padding:7px 10px;border-radius:20px;font-size:11px}
        .status-dot{width:7px;height:7px;background:#fbbf24;border-radius:50%}
        .button{border:none;background:#3b82f6;color:#fff;padding:11px 15px;border-radius:8px;cursor:pointer;font-size:13px;display:inline-flex;justify-content:center;align-items:center;gap:8px;text-decoration:none}
        .button:hover{background:#2563eb}.button-secondary{background:#334155;color:#e2e8f0}.button-secondary:hover{background:#475569}
        .button:disabled{opacity:.5;cursor:not-allowed}.camera-actions{display:flex;flex-wrap:wrap;justify-content:center;gap:10px;margin-top:18px}
        .capture-preview{margin-top:18px;padding:18px;background:#0b1220;border:1px solid #334155;border-radius:12px;text-align:left}
        .capture-preview h3{font-size:16px;margin-bottom:14px}
        .capture-details{color:#cbd5e1;font-size:13px;line-height:1.8;overflow-wrap:anywhere}
        .capture-details strong{color:#f8fafc}
        .form-select{width:100%;padding:12px;border-radius:8px;background:#0f172a;color:#f1f5f9;border:1px solid #475569}
        .table-wrap{overflow-x:auto}table{width:100%;border-collapse:collapse;font-size:12px}th{text-align:left;color:#94a3b8;font-weight:normal;padding:12px 8px;border-bottom:1px solid #334155}td{padding:15px 8px;border-bottom:1px solid #334155}
        .empty-state{text-align:center;color:#94a3b8;padding:32px 10px;font-size:13px;line-height:1.7}.empty-state i{display:block;font-size:27px;margin-bottom:12px;color:#64748b}
        .page-intro{background:#1e293b;border:1px solid #334155;border-radius:14px;padding:26px;margin-bottom:22px}
        .page-intro-icon{width:52px;height:52px;border-radius:13px;background:#1d4ed833;color:#60a5fa;display:grid;place-items:center;font-size:23px;margin-bottom:18px}
        .page-intro h2{font-size:20px;margin-bottom:10px}.page-intro p{color:#94a3b8;font-size:14px;line-height:1.8;max-width:700px}
        .notice{border:1px solid #334155;border-radius:10px;background:#0f172a;color:#cbd5e1;padding:16px;font-size:13px;line-height:1.7}
        .notice i{color:#60a5fa;margin-right:8px}.bottom-note{color:#64748b;font-size:12px;text-align:center;margin-top:28px;line-height:1.7}
        @media(max-width:1050px){.sidebar{width:225px}main{margin-left:225px;padding:22px}.workspace{grid-template-columns:1fr}.stats{grid-template-columns:1fr}}
        @media(max-width:650px){.sidebar{position:static;width:100%}.sidebar-footer{margin-top:22px}main{margin-left:0;padding:18px}.topbar{align-items:flex-start;flex-direction:column}.topbar h1{font-size:23px}.date-badge{white-space:normal}.card-header{align-items:flex-start;flex-direction:column}}
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="brand">
        <div class="brand-icon"><i class="fa-solid fa-graduation-cap"></i></div>
        <div><h2>Villa Flora</h2><p>Sistema académico</p></div>
    </div>
    <p class="menu-title">Menú principal</p>
    <?php foreach ($secciones as $clave => $nombre): ?>
        <a class="menu-item <?php echo $seccion === $clave ? 'active' : ''; ?>"
           href="<?php echo $clave === 'estudiantes'
               ? 'estudiantes.php'
               : 'index.php?seccion=' . urlencode($clave); ?>"
           <?php echo $seccion === $clave ? 'aria-current="page"' : ''; ?>>
            <i class="fa-solid <?php echo $iconos[$clave]; ?>"></i>
            <span><?php echo htmlspecialchars($nombre); ?></span>
        </a>
    <?php endforeach; ?>
    <div class="sidebar-footer"><i class="fa-solid fa-shield-halved"></i><br>Proyecto académico<br>Institución Educativa Villa Flora</div>
</aside>

<main>
    <header class="topbar">
        <div>
            <h1><?php echo htmlspecialchars($secciones[$seccion]); ?></h1>
            <p><?php echo htmlspecialchars($nombre_institucion); ?> · Sistema de control de asistencia estudiantil.</p>
        </div>
        <div class="date-badge"><i class="fa-regular fa-calendar"></i> <span id="fechaActual">Cargando fecha...</span></div>
    </header>

    <?php if ($seccion === "inicio"): ?>
        <h2 class="section-heading">Resumen de asistencia</h2>
        <section class="stats">
            <article class="stat-card">
                <div class="stat-top">Total de estudiantes <span class="stat-icon blue"><i class="fa-solid fa-users"></i></span></div>
                <div class="stat-number">—</div><p class="stat-note">Pendiente de conectar con MySQL.</p>
            </article>
            <article class="stat-card">
                <div class="stat-top">Presentes hoy <span class="stat-icon green"><i class="fa-solid fa-user-check"></i></span></div>
                <div class="stat-number">—</div><p class="stat-note">Se mostrarán los registros reales.</p>
            </article>
            <article class="stat-card">
                <div class="stat-top">Llegadas tarde <span class="stat-icon yellow"><i class="fa-solid fa-clock"></i></span></div>
                <div class="stat-number">—</div><p class="stat-note">Según el horario de cada clase.</p>
            </article>
        </section>

        <section class="workspace">
            <article class="card">
                <div class="card-header">
                    <h2><i class="fa-solid fa-camera"></i> Reconocimiento facial</h2>
                    <span class="status"><span class="status-dot" id="statusDot"></span><span id="statusBadge">Cámara apagada</span></span>
                </div>
                <div class="card-body">
                    <div class="camera-area">
                        <i class="fa-solid fa-camera camera-icon" id="cameraIcon"></i>
                        <h3 id="cameraTitle">Cámara de asistencia</h3>
                        <p id="cameraMessage">Activa la cámara para iniciar el reconocimiento con tu modelo de Teachable Machine.</p>
                        <div id="webcam-container" style="width:100%;display:flex;justify-content:center;overflow:hidden"></div>
                        <div id="label-container" style="width:100%;max-width:320px"></div>
                        <p id="cameraStatus" role="status" aria-live="polite">Cámara apagada.</p>
                    </div>
                    <div class="camera-actions">
                        <button class="button" id="btnActivarCamara" type="button"><i class="fa-solid fa-camera"></i> Activar cámara</button>
                        <button class="button button-secondary" id="btnDetenerCamara" type="button" disabled><i class="fa-solid fa-stop"></i> Detener cámara</button>
                        <button class="button" id="btnCapturar" type="button" disabled><i class="fa-solid fa-camera-retro"></i> Capturar imagen</button>
                    </div>
                    <div id="resultadoCaptura" class="capture-preview" style="display:none" aria-live="polite">
                        <h3><i class="fa-solid fa-image"></i> Imagen capturada</h3>
                        <canvas id="canvasCaptura" width="320" height="240" style="display:block;width:100%;max-width:320px;height:auto;margin:0 auto 15px;border-radius:10px;border:1px solid #475569"></canvas>
                        <div class="capture-details">
                            <p><strong>Clase detectada:</strong> <span id="claseCapturada">Pendiente</span></p>
                            <p><strong>Confianza del modelo:</strong> <span id="confianzaCapturada">Pendiente</span></p>
                            <p id="mensajeCaptura" style="margin-top:8px;color:#94a3b8">Esta imagen es una vista previa y no se ha guardado.</p>
                        </div>
                        <div class="camera-actions"><button class="button button-secondary" id="btnOcultarCaptura" type="button"><i class="fa-solid fa-eye-slash"></i> Ocultar vista previa</button></div>
                    </div>
                    <div id="panelRegistro" class="capture-preview" style="display:none" aria-live="polite">
                        <h3><i class="fa-solid fa-user-check"></i> Verificar estudiante</h3>
                        <div class="capture-details">
                            <p><strong>Nombre:</strong> <span id="estudianteNombre">—</span></p>
                            <p><strong>Documento:</strong> <span id="estudianteDocumento">—</span></p>
                            <p><strong>Grupo:</strong> <span id="estudianteGrupo">—</span></p>
                        </div>
                        <label for="selectClase" style="display:block;margin:18px 0 8px">Selecciona la clase correspondiente</label>
                        <select id="selectClase" class="form-select"><option value="">Esperando estudiante...</option></select>
                        <p id="mensajeRegistro" role="status" aria-live="polite" style="font-size:13px;line-height:1.7;margin-top:12px;color:#cbd5e1"></p>
                        <div class="camera-actions">
                            <button class="button" id="btnRegistrarAsistencia" type="button" disabled><i class="fa-solid fa-floppy-disk"></i> Registrar asistencia</button>
                        </div>
                    </div>
                    <p class="stat-note" style="margin-top:14px;text-align:center">El reconocimiento es una sugerencia y no guarda asistencia automáticamente.</p>
                </div>
            </article>

            <article class="card">
                <div class="card-header"><h2><i class="fa-solid fa-clock-rotate-left"></i> Registros recientes</h2></div>
                <div class="card-body">
                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>Estudiante</th><th>Hora</th><th>Estado</th></tr></thead>
                            
                                <tbody>
                                    <?php if ($errorPanel): ?>
                                        <tr>
                                          <td colspan="3">
                                                <div class="empty-state">
                                                  No se pudieron consultar todos los datos.
                                                  Revisa la conexión y las tablas de MySQL.
                                                </div>
                                            </td>
                                        </tr>
                                    <?php elseif (empty($registrosRecientes)): ?>
                                        <tr>
                                            <td colspan="3">
                                                <div class="empty-state">
                                                   <i class="fa-regular fa-clipboard"></i>
                                                   Todavía no hay registros.<br>
                                                   Aquí aparecerán las asistencias guardadas.
                                                </div>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($registrosRecientes as $registro): ?>
                                            <tr>
                                                <td>
                                                    <?php echo htmlspecialchars(
                                                        $registro["nombre_completo"]
                                                    ); ?>
                                                </td>
                                            <td>
                                                <?php echo htmlspecialchars(
                                                    $registro["hora_llegada"]
                                                ); ?>
                                            </td>
                                            <td>
                                                <?php
                                                $estadoTexto = match ((int) $registro["id_estado"]) {
                                                    1 => "Presente",
                                                    2 => "Tarde",
                                                    3 => "Ausente",
                                                    default => "Desconocido"
                                                };

                                                echo htmlspecialchars($estadoTexto);
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </article>
        </section>
    <?php elseif ($seccion === "estudiantes"): ?>
        <?php require "estudiantes.php"; ?>
    <?php else: ?>
        <section class="page-intro">
            <div class="page-intro-icon"><i class="fa-solid <?php echo $iconos[$seccion]; ?>"></i></div>
            <h2><?php echo htmlspecialchars($secciones[$seccion]); ?></h2>
            <p><?php echo htmlspecialchars($descripciones[$seccion]); ?></p>
        </section>
        <section class="card">
            <div class="card-header"><h2>Espacio de trabajo</h2><span class="status"><span class="status-dot"></span>En preparación</span></div>
            <div class="card-body">
                <div class="notice"><i class="fa-solid fa-circle-info"></i>Esta sección ya tiene su propia página dentro del sistema. En el siguiente paso implementaremos sus funciones y la conexión con la base de datos. Por ahora no se han guardado ni modificado datos.</div>
                <div style="margin-top:20px"><a class="button" href="index.php"><i class="fa-solid fa-arrow-left"></i> Volver al registro de asistencia</a></div>
            </div>
        </section>
    <?php endif; ?>
    <p class="bottom-note"><?php echo htmlspecialchars($nombre_institucion); ?><br>Sistema de Control de Asistencia</p>
</main>

<script>
    const fecha = new Date();
    document.getElementById("fechaActual").textContent = fecha.toLocaleDateString("es-CO", {
        weekday: "long", year: "numeric", month: "long", day: "numeric"
    });

    const seccionActual = <?php echo json_encode($seccion); ?>;
    if (seccionActual === "inicio") {
        const URL_MODELO = "https://teachablemachine.withgoogle.com/models/rUZfTf0Ph/";
        let modelo = null;
        let webcam = null;
        let camaraActiva = false;
        let iniciando = false;
        let prediccionEnCurso = false;

        const btnActivar = document.getElementById("btnActivarCamara");
        const btnDetener = document.getElementById("btnDetenerCamara");
        const btnCapturar = document.getElementById("btnCapturar");
        const btnOcultarCaptura = document.getElementById("btnOcultarCaptura");
        const resultadoCaptura = document.getElementById("resultadoCaptura");
        const canvasCaptura = document.getElementById("canvasCaptura");
        const contextoCaptura = canvasCaptura.getContext("2d");
        const claseCapturada = document.getElementById("claseCapturada");
        const confianzaCapturada = document.getElementById("confianzaCapturada");
        const mensajeCaptura = document.getElementById("mensajeCaptura");
        const contenedorCamara = document.getElementById("webcam-container");
        const contenedorEtiquetas = document.getElementById("label-container");
        const estadoCamara = document.getElementById("cameraStatus");
        const mensajeCamara = document.getElementById("cameraMessage");
        const tituloCamara = document.getElementById("cameraTitle");
        const statusBadge = document.getElementById("statusBadge");
        const statusDot = document.getElementById("statusDot");
        const cameraIcon = document.getElementById("cameraIcon");
        const panelRegistro = document.getElementById("panelRegistro");
        const estudianteNombre = document.getElementById("estudianteNombre");
        const estudianteDocumento = document.getElementById("estudianteDocumento");
        const estudianteGrupo = document.getElementById("estudianteGrupo");
        const selectClase = document.getElementById("selectClase");
        const mensajeRegistro = document.getElementById("mensajeRegistro");
        const btnRegistrarAsistencia = document.getElementById("btnRegistrarAsistencia");
        let ultimaClaseDetectada = "";
        let ultimaConfianza = null;
        let estudianteEncontrado = null;
        let etiquetaConsultada = "";
        let buscandoEstudiante = false;
        let tokenConsulta = 0;

        selectClase.addEventListener("change", () => {
            btnRegistrarAsistencia.disabled = !estudianteEncontrado || !selectClase.value;
        });
        btnRegistrarAsistencia.addEventListener("click", registrarAsistencia);

        async function enviarFormulario(datos) {
            const respuesta = await fetch("index.php?seccion=inicio", { method: "POST", body: datos });
            const texto = await respuesta.text();
            try { return JSON.parse(texto); }
            catch (_) { throw new Error("El servidor no devolvió JSON válido. Revisa los errores de PHP y la estructura de MySQL."); }
        }

        async function consultarEstudiante(etiqueta) {
            const token = ++tokenConsulta;
            buscandoEstudiante = true;
            etiquetaConsultada = etiqueta;
            estudianteEncontrado = null;
            btnRegistrarAsistencia.disabled = true;
            panelRegistro.style.display = "block";
            mensajeRegistro.style.color = "#cbd5e1";
            mensajeRegistro.textContent = "Buscando estudiante en la base de datos...";
            selectClase.replaceChildren(new Option("Consultando...", ""));
            try {
                const datos = new FormData();
                datos.append("accion", "buscar_estudiante");
                datos.append("etiqueta", etiqueta);
                const resultado = await enviarFormulario(datos);
                if (token !== tokenConsulta || !camaraActiva || ultimaClaseDetectada !== etiqueta || ultimaConfianza < 0.90) return;
                if (!resultado.ok) {
                    estudianteNombre.textContent = "No encontrado";
                    estudianteDocumento.textContent = "—";
                    estudianteGrupo.textContent = "—";
                    selectClase.replaceChildren(new Option("No hay clases disponibles", ""));
                    mensajeRegistro.textContent = resultado.mensaje;
                    return;
                }
                estudianteEncontrado = resultado.estudiante;
                estudianteNombre.textContent = estudianteEncontrado.nombre_completo || "—";
                estudianteDocumento.textContent = estudianteEncontrado.documento || "—";
                estudianteGrupo.textContent = estudianteEncontrado.nombre_grupo || "Sin grupo asignado";
                const formClases = new FormData();
                formClases.append("accion", "listar_clases");
                formClases.append("id_estudiante", estudianteEncontrado.id_estudiante);
                const respuestaClases = await enviarFormulario(formClases);
                if (token !== tokenConsulta || !camaraActiva || ultimaClaseDetectada !== etiqueta || ultimaConfianza < 0.90) return;
                selectClase.replaceChildren();
                if (!respuestaClases.ok || !Array.isArray(respuestaClases.clases) || respuestaClases.clases.length === 0) {
                    selectClase.add(new Option("No hay clases activas para este grupo", ""));
                    mensajeRegistro.textContent = respuestaClases.mensaje || "El estudiante no tiene clases activas registradas para su grupo.";
                    return;
                }
                selectClase.add(new Option("Selecciona una clase", ""));
                respuestaClases.clases.forEach(clase => {
                    const dia = clase.dia_semana || "Día sin especificar";
                    const inicio = clase.hora_inicio || "--:--";
                    const fin = clase.hora_fin || "--:--";
                    selectClase.add(new Option(`${dia} | ${inicio} - ${fin} | Clase #${clase.id_clase}`, clase.id_clase));
                });
                mensajeRegistro.textContent = "Verifica que el estudiante sea correcto y selecciona la clase antes de guardar.";
            } catch (error) {
                if (token === tokenConsulta) mensajeRegistro.textContent = error.message || "No se pudo consultar el estudiante.";
            } finally {
                if (token === tokenConsulta) buscandoEstudiante = false;
            }
        }

        async function registrarAsistencia() {
            if (!estudianteEncontrado || !selectClase.value) {
                mensajeRegistro.textContent = "Selecciona un estudiante y una clase válida.";
                return;
            }
            if (!camaraActiva || ultimaClaseDetectada !== etiquetaConsultada || ultimaConfianza === null || ultimaConfianza < 0.90) {
                mensajeRegistro.textContent = "La coincidencia ya no es suficientemente confiable. Verifica al estudiante y vuelve a intentarlo.";
                return;
            }
            btnRegistrarAsistencia.disabled = true;
            mensajeRegistro.style.color = "#cbd5e1";
            mensajeRegistro.textContent = "Guardando asistencia...";
            try {
                const datos = new FormData();
                datos.append("accion", "registrar_asistencia");
                datos.append("id_estudiante", estudianteEncontrado.id_estudiante);
                datos.append("id_clase", selectClase.value);
                const resultado = await enviarFormulario(datos);
                mensajeRegistro.textContent = resultado.mensaje;
                mensajeRegistro.style.color = resultado.ok ? "#34d399" : "#fbbf24";
                if (!resultado.ok) btnRegistrarAsistencia.disabled = false;
            } catch (error) {
                mensajeRegistro.style.color = "#f87171";
                mensajeRegistro.textContent = error.message || "No se pudo guardar la asistencia.";
                btnRegistrarAsistencia.disabled = false;
            }
        }

        function cambiarEstado(texto, activo = false) {
            estadoCamara.textContent = texto;
            statusBadge.textContent = activo ? "Cámara activa" : (iniciando ? "Preparando..." : "Cámara apagada");
            statusDot.style.background = activo ? "#34d399" : (iniciando ? "#fbbf24" : "#64748b");
        }

        btnActivar.addEventListener("click", iniciarCamara);
        btnDetener.addEventListener("click", detenerCamara);
        btnCapturar.addEventListener("click", capturarImagen);
        btnOcultarCaptura.addEventListener("click", () => { resultadoCaptura.style.display = "none"; });

        async function iniciarCamara() {
            if (iniciando || camaraActiva) return;
            iniciando = true;
            btnActivar.disabled = true;
            btnDetener.disabled = true;
            statusBadge.textContent = "Preparando...";
            statusDot.style.background = "#fbbf24";
            cambiarEstado("Cargando el modelo de reconocimiento...");

            try {
                if (!modelo) {
                    modelo = await tmImage.load(URL_MODELO + "model.json", URL_MODELO + "metadata.json");
                }

                cambiarEstado("Solicitando permiso para usar la cámara...");
                webcam = new tmImage.Webcam(320, 240, true);
                await webcam.setup();
                await webcam.play();

                contenedorCamara.replaceChildren(webcam.canvas);
                contenedorEtiquetas.replaceChildren();
                camaraActiva = true;
                btnDetener.disabled = false;
                btnCapturar.disabled = false;
                tituloCamara.textContent = "Reconocimiento activo";
                cameraIcon.style.display = "none";
                mensajeCamara.textContent = "Mira hacia la cámara. Revisa el resultado antes de registrar una asistencia.";
                cambiarEstado("Cámara activa. Analizando imagen...", true);
                requestAnimationFrame(actualizarPrediccion);
            } catch (error) {
                console.error("No se pudo iniciar el reconocimiento:", error);
                if (webcam) {
                    try { webcam.stop(); } catch (e) {}
                }
                webcam = null;
                camaraActiva = false;
                contenedorCamara.replaceChildren();
                tituloCamara.textContent = "Cámara de asistencia";
                cameraIcon.style.display = "";
                mensajeCamara.textContent = "Revisa los permisos de cámara y tu conexión a internet, y vuelve a intentarlo.";
                cambiarEstado("No se pudo iniciar la cámara. " + (error && error.message ? error.message : ""));
                btnActivar.disabled = false;
                btnDetener.disabled = true;
                btnCapturar.disabled = true;
            } finally {
                iniciando = false;
                if (!camaraActiva) statusBadge.textContent = "Cámara apagada";
            }
        }

        async function actualizarPrediccion() {
            if (!camaraActiva || !webcam || !modelo || prediccionEnCurso) return;
            prediccionEnCurso = true;
            try {
                webcam.update();
                const predicciones = await modelo.predict(webcam.canvas);
                if (!camaraActiva) return;
                predicciones.sort((a, b) => b.probability - a.probability);
                const mejor = predicciones[0];
                contenedorEtiquetas.replaceChildren();

                if (mejor) {
                    ultimaClaseDetectada = mejor.className;
                    ultimaConfianza = mejor.probability;
                    if (mejor.probability >= 0.90) {
                        if (etiquetaConsultada !== mejor.className && !buscandoEstudiante) {
                            consultarEstudiante(mejor.className);
                        }
                    } else {
                        tokenConsulta++;
                        estudianteEncontrado = null;
                        etiquetaConsultada = "";
                        btnRegistrarAsistencia.disabled = true;
                        panelRegistro.style.display = "none";
                    }
                    const resultado = document.createElement("p");
                    resultado.style.marginTop = "12px";
                    resultado.style.fontSize = "14px";
                    resultado.style.fontWeight = "bold";
                    resultado.textContent = "Clase detectada: " + mejor.className +
                        " (" + (mejor.probability * 100).toFixed(1) + "%)";
                    contenedorEtiquetas.appendChild(resultado);

                    const detalle = document.createElement("p");
                    detalle.style.marginTop = "6px";
                    detalle.textContent = mejor.probability >= 0.90
                        ? "Coincidencia probable: verifica la identidad manualmente."
                        : "Confianza baja: prueba con mejor iluminación y posición.";
                    contenedorEtiquetas.appendChild(detalle);
                    cambiarEstado(mejor.probability >= 0.90
                        ? "Resultado con alta confianza. Aún requiere verificación."
                        : "Analizando imagen; no hay coincidencia suficientemente confiable.", true);
                }
            } catch (error) {
                console.error("Error durante la predicción:", error);
                cambiarEstado("Ocurrió un error durante el reconocimiento.", true);
            } finally {
                prediccionEnCurso = false;
                if (camaraActiva) requestAnimationFrame(actualizarPrediccion);
            }
        }

        function capturarImagen() {
            if (!camaraActiva || !webcam || !webcam.canvas || !webcam.canvas.width || !webcam.canvas.height) {
                alert("Primero activa la cámara y espera a que esté lista.");
                return;
            }
            canvasCaptura.width = webcam.canvas.width;
            canvasCaptura.height = webcam.canvas.height;
            contextoCaptura.drawImage(webcam.canvas, 0, 0, canvasCaptura.width, canvasCaptura.height);
            claseCapturada.textContent = ultimaClaseDetectada || "Sin predicción disponible";
            confianzaCapturada.textContent = ultimaConfianza !== null ? (ultimaConfianza * 100).toFixed(1) + "%" : "No disponible";
            mensajeCaptura.textContent = "Vista previa temporal. La imagen no se ha guardado en el servidor ni en la base de datos.";
            resultadoCaptura.style.display = "block";
            resultadoCaptura.scrollIntoView({ behavior: "smooth", block: "nearest" });
        }

        function detenerCamara() {
            camaraActiva = false;
            if (webcam) {
                try { webcam.stop(); } catch (error) { console.warn(error); }
            }
            webcam = null;
            contenedorCamara.replaceChildren();
            contenedorEtiquetas.replaceChildren();
            tituloCamara.textContent = "Cámara de asistencia";
            cameraIcon.style.display = "";
            mensajeCamara.textContent = "Activa la cámara para iniciar el reconocimiento con tu modelo de Teachable Machine.";
            cambiarEstado("Cámara apagada.");
            statusBadge.textContent = "Cámara apagada";
            statusDot.style.background = "#64748b";
            btnActivar.disabled = false;
            btnDetener.disabled = true;
            btnCapturar.disabled = true;
            tokenConsulta++;
            estudianteEncontrado = null;
            etiquetaConsultada = "";
            ultimaClaseDetectada = "";
            ultimaConfianza = null;
            panelRegistro.style.display = "none";
            btnRegistrarAsistencia.disabled = true;
        }

        window.addEventListener("pagehide", detenerCamara);
    }
</script>
</body>
</html>
