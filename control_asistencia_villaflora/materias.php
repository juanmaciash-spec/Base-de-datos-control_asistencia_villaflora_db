
<?php
require_once "conexion.php";

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");

    if ($nombre === "") {
        $error = "Escribe el nombre de la materia.";
    } else {
        $consulta = $conexion->prepare(
            "INSERT INTO materia (nombre) VALUES (?)"
        );

        $consulta->bind_param("s", $nombre);

        if ($consulta->execute()) {
            $mensaje = "Materia registrada correctamente.";
        } else {
            $error = "No se pudo registrar la materia.";
        }

        $consulta->close();
    }
}

$materias = $conexion->query(
    "SELECT id_materia, nombre
     FROM materia
     ORDER BY nombre"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias | Villa Flora</title>

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

    <h1>Gestión de materias</h1>
    <p>Administra las materias de la institución.</p>

    <section class="panel">
        <h2>Registrar materia</h2>

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
            <label for="nombre">Nombre de la materia</label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                maxlength="100"
                required
            >

            <button type="submit">Guardar materia</button>
        </form>
    </section>

    <section class="panel">
        <h2>Materias registradas</h2>

        <table>
            <thead>
                <tr>
                    <th>N.º</th>
                    <th>Nombre de la materia</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($materias && $materias->num_rows > 0): ?>
                <?php while ($materia = $materias->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$materia["id_materia"] ?></td>
                        <td><?= htmlspecialchars($materia["nombre"]) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">Todavía no hay materias registradas.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </section>
</div>
</body>
</html>
