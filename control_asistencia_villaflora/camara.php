
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reconocimiento - Villa Flora</title>

    <!-- Librerías para Teachable Machine -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@1.7.4/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@0.8/dist/teachablemachine-image.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #173b57;
        }

        main {
            max-width: 700px;
            margin: 25px auto;
            padding: 25px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px #00000012;
            text-align: center;
        }

        h1 {
            margin-bottom: 5px;
        }

        .subtitulo {
            color: #64748b;
            margin-bottom: 25px;
        }

        button {
            padding: 12px 20px;
            margin: 6px;
            border: none;
            border-radius: 8px;
            background: #173b57;
            color: white;
            font-size: 15px;
            cursor: pointer;
        }

        button:hover {
            background: #245779;
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        #camara {
            margin: 20px auto;
            width: fit-content;
            max-width: 100%;
        }

        #camara canvas {
            display: block;
            max-width: 100%;
            height: auto;
            border-radius: 12px;
        }

        #estado {
            padding: 12px;
            background: #f1f5f9;
            border-radius: 8px;
            color: #334155;
            overflow-wrap: anywhere;
        }

        #resultado {
            font-size: 22px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        #confianza {
            color: #475569;
        }

        .nota {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
        }
    </style>
</head>

<body>
<main>
    <h1>Control de Asistencia</h1>
    <h2>Institución Educativa Villa Flora</h2>

    <p class="subtitulo">
        Reconocimiento mediante Teachable Machine
    </p>

    <button id="btnIniciar" onclick="iniciarCamara()">
        Activar cámara
    </button>

    <button id="btnDetener" onclick="detenerCamara()" disabled>
        Detener cámara
    </button>

    <div id="camara"></div>

    <p id="estado">Preparado para iniciar.</p>

    <p id="resultado">Esperando reconocimiento...</p>

    <p id="confianza"></p>

    <p class="nota">
        Esta página realiza una prueba del modelo.
        Todavía no registra asistencias en la base de datos.
    </p>
</main>

<script>
    // Enlace de tu modelo entrenado
    const URL_MODELO =
        "https://teachablemachine.withgoogle.com/models/rUZfTf0Ph/";

    let modelo = null;
    let webcam = null;
    let activa = false;
    let iniciando = false;
    let framePendiente = null;

    const estado = document.getElementById("estado");
    const resultado = document.getElementById("resultado");
    const confianza = document.getElementById("confianza");
    const btnIniciar = document.getElementById("btnIniciar");
    const btnDetener = document.getElementById("btnDetener");
    const contenedorCamara = document.getElementById("camara");

    async function iniciarCamara() {
        if (activa || iniciando) return;

        iniciando = true;
        btnIniciar.disabled = true;

        try {
            estado.textContent = "Comprobando las librerías...";

            if (typeof tmImage === "undefined") {
                throw new Error(
                    "No cargó Teachable Machine. Revisa tu conexión a internet."
                );
            }

            estado.textContent = "Cargando tu modelo...";

            modelo = await tmImage.load(
                URL_MODELO + "model.json",
                URL_MODELO + "metadata.json"
            );

            estado.textContent = "Solicitando permiso para la cámara...";

            webcam = new tmImage.Webcam(320, 240, true);

            await webcam.setup();
            await webcam.play();

            contenedorCamara.replaceChildren(webcam.canvas);

            activa = true;
            btnDetener.disabled = false;

            estado.textContent = "Cámara activa. Analizando imagen...";

            actualizarPrediccion();

        } catch (error) {
            console.error("Error al iniciar la cámara:", error);

            estado.textContent =
                "No se pudo iniciar: " +
                (error.message || String(error));

            if (webcam) {
                try {
                    webcam.stop();
                } catch (e) {
                    console.warn(e);
                }
            }

            webcam = null;
            activa = false;
            btnDetener.disabled = true;

        } finally {
            iniciando = false;
            btnIniciar.disabled = false;
        }
    }

    async function actualizarPrediccion() {
        if (!activa || !webcam || !modelo) return;

        try {
            webcam.update();

            const predicciones = await modelo.predict(webcam.canvas);

            if (!activa) return;

            predicciones.sort(
                (a, b) => b.probability - a.probability
            );

            const mejor = predicciones[0];

            if (!mejor) {
                resultado.textContent = "No hay resultados.";
                return;
            }

            const porcentaje =
                (mejor.probability * 100).toFixed(1);

            confianza.textContent =
                "Confianza del modelo: " + porcentaje + "%";

            if (mejor.probability >= 0.90) {
                resultado.textContent =
                    "Clase reconocida: " + mejor.className;
            } else {
                resultado.textContent =
                    "No hay una coincidencia suficientemente alta.";
            }

        } catch (error) {
            console.error("Error durante el reconocimiento:", error);

            if (activa) {
                estado.textContent =
                    "Error al analizar la imagen: " +
                    (error.message || String(error));
            }
        }

        if (activa) {
            framePendiente = requestAnimationFrame(
                actualizarPrediccion
            );
        }
    }

    function detenerCamara() {
        activa = false;

        if (framePendiente !== null) {
            cancelAnimationFrame(framePendiente);
            framePendiente = null;
        }

        if (webcam) {
            try {
                webcam.stop();
            } catch (error) {
                console.warn("No se pudo detener la cámara:", error);
            }
        }

        webcam = null;

        contenedorCamara.replaceChildren();

        estado.textContent = "Cámara apagada.";
        resultado.textContent = "Esperando reconocimiento...";
        confianza.textContent = "";

        btnDetener.disabled = true;
        btnIniciar.disabled = false;
    }
</script>
</body>
</html>
