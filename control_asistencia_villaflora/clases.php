<?php
require_once "conexion.php";

$sql = "
    SELECT
        c.id_clase,
        c.dia_semana,
        c.hora_inicio,
        c.hora_fin,
        c.activo,
        g.nombre_grupo,
        gr.nombre AS grado,
        m.nombre AS materia,
        d.nombre_completo AS docente
    FROM clase c
    INNER JOIN grupo g ON c.id_grupo = g.id_grupo
    INNER JOIN grado gr ON g.id_grado = gr.id_grado
    INNER JOIN materia m ON c.id_materia = m.id_materia
    INNER JOIN docente d ON c.id_docente = d.id_docente
    ORDER BY
        FIELD(
            c.dia_semana,
            'Lunes', 'Martes', 'Miércoles',
            'Jueves', 'Viernes', 'Sábado', 'Domingo'
        ),
        c.hora_inicio
";

$clases = $conexion->query($sql);

if (!$clases) {
    die("Error al consultar los horarios: " .
        htmlspecialchars($conexion->error));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horarios | Villa Flora</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
        }

        .contenedor {
            max-width: 1100px;
            margin: auto;
        }

        a {
            color: #93c5fd;
            text-decoration: none;
        }

        .panel {
            background: #1e293b;
            padding: 24px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .tabla-contenedor { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #334155;
            white-space: nowrap;
        }

        th { color: #93c5fd; }

        .estado {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 6px;
            background: #14532d;
            color: #bbf7d0;
        }

        .inactivo {
            background: #7f1d1d;
            color: #fecaca;
        }

        .aviso {
            padding: 14px;
            border-radius: 8px;
            background: #334155;
            margin-top: 16px;
        }
    </style>
</head>

<body>
<div class="contenedor">
    <a href="index.php">← Volver al inicio</a>

    <h1>Gestión de clases y horarios</h1>
    <p>
        Consulta las clases programadas por día, grupo, materia
        y docente.
    </p>

    <section class="panel">
        <h2>Horarios semanales</h2>

        <?php if ($clases->num_rows > 0): ?>
            <div class="tabla-contenedor">
                <table>
                    <thead>
                        <tr>
                            <th>Día</th>
                            <th>Hora de inicio</th>
                            <th>Hora de fin</th>
                            <th>Grado</th>
                            <th>Grupo</th>
                            <th>Materia</th>
                            <th>Docente</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php while ($clase = $clases->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($clase["dia_semana"]) ?></td>
                            <td><?= htmlspecialchars(substr($clase["hora_inicio"], 0, 5)) ?></td>
                            <td><?= htmlspecialchars(substr($clase["hora_fin"], 0, 5)) ?></td>
                            <td><?= htmlspecialchars($clase["grado"]) ?></td>
                            <td><?= htmlspecialchars($clase["nombre_grupo"]) ?></td>
                            <td><?= htmlspecialchars($clase["materia"]) ?></td>
                            <td><?= htmlspecialchars($clase["docente"]) ?></td>
                            <td>
                                <?php if ((int)$clase["activo"] === 1): ?>
                                    <span class="estado">Activa</span>
                                <?php else: ?>
                                    <span class="estado inactivo">Inactiva</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="aviso">
                Todavía no hay clases registradas. Cuando tengamos
                los datos de docentes y horarios, podremos agregarlas.
            </div>
        <?php endif; ?>
    </section>
</div>
</body>
</html>
