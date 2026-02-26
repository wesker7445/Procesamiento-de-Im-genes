<?php

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trivia Mundialista</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="style.css">
</head>
<body class="full-screen-layout">
    
<header>
    <h1>Mundial <i class="fa-solid fa-futbol"></i></h1>
    <a href="javascript:history.back()" class="btn-regresar">
        <i class="fa-solid fa-chevron-left"></i> Volver
    </a>
</header>

    <div class="layout">
        <main>
            <div class="api-header">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <i class="fa-solid fa-lightbulb" style="color: #6cd085; font-size: 1.2rem;"></i>
                    <h3 id="trivia-title" style="margin: 0;">Pregunta 1 de 7</h3>
                </div>
                
                <div style="display: flex; align-items: center; gap: 10px; margin-left: auto; margin-right: 20px;">
                    <i class="fa-solid fa-clock" style="color: #f44336; font-size: 1.1rem;"></i>
                    <h3 id="timer-display" style="margin: 0; color: #f44336;">15s</h3>
                </div>

                <span class="api-badge" id="trivia-score">Puntos: 0</span>
            </div>
            <div class="trivia-container">
                <div class="api-header">
                    <i class="fa-solid fa-lightbulb" style="color: #6cd085; font-size: 1.2rem;"></i>
                    <h3 id="trivia-title">Trivia Mundial</h3>
                    <span class="api-badge" id="trivia-score">Puntos: 0</span>
                </div>

                <div class="trivia-content">
                    <div class="trivia-question-card">
                        <p class="question-text" id="question-text">Cargando pregunta...</p>
                        
                        <div class="trivia-options" id="options-container">
                            </div>
                    </div>

                    <div class="trivia-feedback" id="feedback" style="display: none;">
                        </div>
                    
                    <button id="next-btn" class="trivia-option" style="display: none; margin-top: 20px; background-color: #6cd085; color: black;">
                        Siguiente Pregunta <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </main>
    </div>

<script>
    const preguntas = [
        {
            pregunta: "¿Quién fue el máximo goleador del Mundial de Qatar 2022?",
            opciones: ["Lionel Messi", "Kylian Mbappé", "Julián Álvarez", "Olivier Giroud"],
            correcta: 1 
        },
        {
            pregunta: "¿En qué país se celebró el primer Mundial en 1930?",
            opciones: ["Brasil", "Italia", "Uruguay", "Argentina"],
            correcta: 2
        },
        {
            pregunta: "¿Qué selección tiene más títulos mundiales?",
            opciones: ["Alemania", "Italia", "Brasil", "Francia"],
            correcta: 2
        },
        {
            pregunta: "¿Quién ganó el Mundial de Sudáfrica 2010?",
            opciones: ["Países Bajos", "España", "Alemania", "Uruguay"],
            correcta: 1
        },
        {
            pregunta: "¿En qué años ha sido México sede de la Copa del Mundo?",
            opciones: ["1970 y 1986", "1966 y 1994", "1970 y 1990", "1982 y 2010"],
            correcta: 0
        },
        {
            pregunta: "¿Quién es el máximo goleador histórico de los mundiales?",
            opciones: ["Pelé", "Ronaldo Nazário", "Miroslav Klose", "Lionel Messi"],
            correcta: 2
        },
        {
            pregunta: "¿Qué selección ganó el Mundial de 1950 (El Maracanazo)?",
            opciones: ["Brasil", "Uruguay", "Italia", "Argentina"],
            correcta: 1
        },

        {
            pregunta: "¿Qué jugador ha ganado más Copas del Mundo en la historia?",
            opciones: ["Diego Maradona", "Pelé", "Cafú", "Ronaldo Nazário"],
            correcta: 1 // Pelé (ganó 3: 1958, 1962 y 1970)
        },
        {
            pregunta: "¿Quién es el jugador con más partidos jugados en la historia de los mundiales?",
            opciones: ["Lothar Matthäus", "Miroslav Klose", "Lionel Messi", "Cristiano Ronaldo"],
            correcta: 2 // Lionel Messi (llegó a 26 partidos en Qatar 2022)
        },
        {
            pregunta: "¿Quién anotó el gol más rápido en la historia de los mundiales (a los 11 segundos)?",
            opciones: ["Hakan Şükür", "Clint Dempsey", "Bryan Robson", "Bernard Lacombe"],
            correcta: 0 // Hakan Şükür (Turquía vs Corea del Sur en 2002)
        }
    ];

    let indiceActual = 0;
    let puntaje = 0;
    let tiempoRestante = 15;
    let timer;

    function iniciarTemporizador() {
        tiempoRestante = 15;
        document.getElementById('timer-display').innerText = `Tiempo: ${tiempoRestante}s`;
        
        timer = setInterval(() => {
            tiempoRestante--;
            document.getElementById('timer-display').innerText = `Tiempo: ${tiempoRestante}s`;
            
            if (tiempoRestante <= 0) {
                clearInterval(timer);
                verificarRespuesta(-1); // -1 indica que se acabó el tiempo
            }
        }, 1000);
    }

    function cargarPregunta() {
        const p = preguntas[indiceActual];
        document.getElementById('trivia-title').innerText = `Pregunta ${indiceActual + 1} de ${preguntas.length}`;
        document.getElementById('question-text').innerText = p.pregunta;
        document.getElementById('feedback').style.display = 'none';
        document.getElementById('next-btn').style.display = 'none';
        
        const container = document.getElementById('options-container');
        container.innerHTML = '';

        p.opciones.forEach((opcion, index) => {
            const btn = document.createElement('button');
            btn.classList.add('trivia-option');
            btn.innerText = opcion;
            btn.onclick = () => verificarRespuesta(index);
            container.appendChild(btn);
        });

        iniciarTemporizador();
    }

    function verificarRespuesta(seleccionado) {
        clearInterval(timer);
        const p = preguntas[indiceActual];
        const feedback = document.getElementById('feedback');
        // IMPORTANTE: Solo seleccionamos los botones de opciones para no deshabilitar el de "Siguiente"
        const buttons = document.getElementById('options-container').querySelectorAll('.trivia-option');

        buttons.forEach(b => b.disabled = true);

        if (seleccionado === p.correcta) {
            puntaje++;
            feedback.innerHTML = `<p><i class="fa-solid fa-check-circle"></i> ¡Correcto! ${p.opciones[p.correcta]}</p>`;
            feedback.style.backgroundColor = "rgba(108, 208, 133, 0.2)";
            feedback.style.color = "#6cd085";
        } else if (seleccionado === -1) {
            feedback.innerHTML = `<p><i class="fa-solid fa-clock"></i> ¡Se acabó el tiempo! La respuesta era ${p.opciones[p.correcta]}</p>`;
            feedback.style.backgroundColor = "rgba(255, 152, 0, 0.2)";
            feedback.style.color = "#ff9800";
        } else {
            feedback.innerHTML = `<p><i class="fa-solid fa-times-circle"></i> Incorrecto. La respuesta era ${p.opciones[p.correcta]}</p>`;
            feedback.style.backgroundColor = "rgba(244, 67, 54, 0.2)";
            feedback.style.color = "#f44336";
        }

        feedback.style.display = 'block';
        document.getElementById('trivia-score').innerText = `Puntos: ${puntaje}`;
        document.getElementById('next-btn').style.display = 'block';
    }

    document.getElementById('next-btn').onclick = () => {
        indiceActual++;
        const cardContent = document.querySelector('.trivia-question-card');
        
        if (indiceActual < preguntas.length) {
            cargarPregunta();
        } else {
            cardContent.innerHTML = `
                <div style="text-align: center; padding: 20px;">
                    <i class="fa-solid fa-trophy" style="font-size: 3rem; color: #6cd085; margin-bottom: 15px;"></i>
                    <h2 style="color: white;">¡Trivia Finalizada!</h2>
                    <p style="font-size: 1.5rem; color: #6cd085;">Puntaje Final: ${puntaje} / ${preguntas.length}</p>
                    <button onclick="location.reload()" class="trivia-option" style="width: 100%; margin-top: 20px;">
                        <i class="fa-solid fa-rotate-right"></i> Volver a intentar
                    </button>
                </div>
            `;
            document.getElementById('next-btn').style.display = 'none';
            document.getElementById('feedback').style.display = 'none';
            document.getElementById('timer-display').innerText = "Fin";
        }
    };

    cargarPregunta();
</script>
    <footer>
        <p class="Resaltado">© 2025 Mi Pagina de Mundiales | MiPaginadeMundiales@gmail.com</p>
    </footer>
</body>
</html>