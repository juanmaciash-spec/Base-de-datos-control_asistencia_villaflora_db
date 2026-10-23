<?php
require_once "conexion.php";

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $documento = trim($_POST["documento"] ?? "");
    $id_grupo = filter_input(INPUT_POST, "id_grupo", FILTER_VALIDATE_INT);

    if ($nombre === "" || $documento === "" || !$id_grupo) {
        $error = "Completa todos los campos.";
    } else {
        $consulta = $conexion->prepare(
            "INSERT INTO estudiante
            (nombre_completo, documento, clase_teachable, activo, id_grupo)
            VALUES (?, ?, NULL, 1, ?)"
        );

        $consulta->bind_param("ssi", $nombre, $documento, $id_grupo);

        if ($consulta->execute()) {
            $mensaje = "Estudiante registrado correctamente.";
        } else {
            $error = "No se pudo registrar. Verifica que el documento no esté repetido y que el grupo exista.";
        }

        $consulta->close();
    }
}

$grupos = $conexion->query(
    "SELECT g.id_grupo, g.nombre_grupo, gr.nombre AS grado
     FROM grupo g
     INNER JOIN grado gr ON g.id_grado = gr.id_grado
     ORDER BY gr.nombre, g.nombre_grupo"
);

$estudiantes = $conexion->query(
    "SELECT e.id_estudiante, e.nombre_completo, e.documento,
            g.nombre_grupo, gr.nombre AS grado
     FROM estudiante e
     INNER JOIN grupo g ON e.id_grupo = g.id_grupo
     INNER JOIN grado gr ON g.id_grado = gr.id_grado
     ORDER BY e.nombre_completo"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes | Villa Flora</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
        }

        .contenedor { max-width: 1000px; margin: auto; }

        a { color: #93c5fd; text-decoration: none; }

        .panel {
            background: #1e293b;
            padding: 24px;
            border-radius: 12px;
            margin-top: 20px;
        }

        label {
            display: block;
            margin: 14px 0 7px;
        }

        input, select, button {
            width: 100%;
            padding: 12px;
            border-radius: 7px;
            border: 1px solid #475569;
            font-size: 15px;
        }

        input, select {
            background: #0f172a;
            color: white;
        }

        button {
            margin-top: 18px;
            background: #3b82f6;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .mensaje {
            padding: 12px;
            border-radius: 7px;
            background: #14532d;
            margin-top: 15px;
        }

        .error {
            padding: 12px;
            border-radius: 7px;
            background: #7f1d1d;
            margin-top: 15px;
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
        }

        th { color: #93c5fd; }

        .volver { display: inline-block; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="contenedor">
    <a class="volver" href="index.php?seccion=estudiantes">
        ← Volver al menú
    </a>

    <h1>Gestión de estudiantes</h1>
    <p>Registra estudiantes y consulta los que ya están guardados.</p>

    <section class="panel">
        <h2>Registrar estudiante</h2>

        <?php if ($mensaje !== ""): ?>
            <div class="mensaje">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <?php if ($error !== ""): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label for="nombre">Nombre completo</label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                maxlength="150"
                required
            >

            <label for="documento">Documento</label>
            <input
                type="text"
                id="documento"
                name="documento"
                maxlength="30"
                required
            >

            <label for="id_grupo">Grupo</label>
            <select id="id_grupo" name="id_grupo" required>
                <option value="">Selecciona un grupo</option>

                <?php if ($grupos): ?>
                    <?php while ($grupo = $grupos->fetch_assoc()): ?>
                        <option value="<?= (int)$grupo["id_grupo"] ?>">
                            <?= htmlspecialchars(
                                $grupo["grado"] . " - " .
                                $grupo["nombre_grupo"]
                            ) ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>

            <button type="submit">Guardar estudiante</button>
        </form>
    </section>

    <section class="panel">
        <h2>Estudiantes registrados</h2>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Grado</th>
                        <th>Grupo</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($estudiantes && $estudiantes->num_rows > 0): ?>
                    <?php while ($estudiante = $estudiantes->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($estudiante["nombre_completo"]) ?></td>
                            <td><?= htmlspecialchars($estudiante["documento"]) ?></td>
                            <td><?= htmlspecialchars($estudiante["grado"]) ?></td>
                            <td><?= htmlspecialchars($estudiante["nombre_grupo"]) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Todavía no hay estudiantes registrados.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
</body>
</html>
