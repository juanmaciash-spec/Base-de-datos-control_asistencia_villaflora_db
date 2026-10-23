
<?php
require_once "conexion.php";

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $documento = trim($_POST["documento"] ?? "");

    if ($nombre === "" || $documento === "") {
        $error = "Completa todos los campos.";
    } else {
        $consulta = $conexion->prepare(
            "INSERT INTO docente (nombre_completo, documento, activo)
             VALUES (?, ?, 1)"
        );

        $consulta->bind_param("ss", $nombre, $documento);

        if ($consulta->execute()) {
            $mensaje = "Docente registrado correctamente.";
        } else {
            $error = "No se pudo registrar. Verifica que el documento no esté repetido.";
        }

        $consulta->close();
    }
}

$docentes = $conexion->query(
    "SELECT id_docente, nombre_completo, documento
     FROM docente
     ORDER BY nombre_completo"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docentes | Villa Flora</title>

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

        input, button {
            width: 100%;
            padding: 12px;
            border-radius: 7px;
            border: 1px solid #475569;
            font-size: 15px;
        }

        input {
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

        .mensaje, .error {
            padding: 12px;
            border-radius: 7px;
            margin-top: 15px;
        }

        .mensaje { background: #14532d; }
        .error { background: #7f1d1d; }

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
    </style>
</head>

<body>
<div class="contenedor">
    <a href="index.php">← Volver al inicio</a>

    <h1>Gestión de docentes</h1>
    <p>Registra y consulta los docentes de la institución.</p>

    <section class="panel">
        <h2>Registrar docente</h2>

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

            <button type="submit">Guardar docente</button>
        </form>
    </section>

    <section class="panel">
        <h2>Docentes registrados</h2>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Nombre completo</th>
                        <th>Documento</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($docentes && $docentes->num_rows > 0): ?>
                    <?php while ($docente = $docentes->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($docente["nombre_completo"]) ?></td>
                            <td><?= htmlspecialchars($docente["documento"]) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">Todavía no hay docentes registrados.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
</body>
</html>
