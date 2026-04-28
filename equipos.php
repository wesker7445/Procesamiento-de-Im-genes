<?php
$equipos = [
    "equipo1" => [
        "nombre" => "México",
        "descripcion" => "- Rol: El anfitrión histórico. México se convierte en el primer país en organizar tres Mundiales (1970, 1986, 2026).<br>
                        - Sedes:  Estadio Azteca: El primero en tener tres inauguraciones.<br>
                        - Estadio Monterrey (BBVA): Ya que vives allá, este estadio tiene la tecnología de iluminación FIFA.<br>
                        - Jugador Promesa: Gilberto Mora, un mediocampista creativo joven.<br>
                        - Identidad Visual: El verde clásico regresando con fuerza, con patrones inspirados en las civilizaciones prehispánicas",
        "modelo" => "modelos/mexico_soccer_ball.glb",
        "marker" => "markers/pattern-mexico.patt",
        "posicion" => "0 0 0",
        "escala" => "0.5 0.5 0.5",
        "rotacion" => "0 0 0"
    ],
    "equipo2" => [
        "nombre" => "Sudafrica",
        "descripcion" => "- Apodo: Bafana Bafana.<br>
                    - Jugador Estrella: Mohau Nkota (extremo veloz, ideal si buscas animar jugadas de agilidad) y Teboho Mokoena.<br>
                    - Identidad Visual: Sus colores son el amarillo brillante y verde. El patrón de su uniforme suele incluir motivos tribales inspirados en la cultura sudafricana.<br>
                    - Dato Curioso para el Proyecto: Se concentrarán en Pachuca durante el Mundial. Su regreso tras 16 años (su último mundial fue el suyo en 2010) es un gran arco narrativo para un 
                    video de presentación.<br>
                    - Ambiente Sugerido: Puedes incluir elementos de la 'Nación Arcoíris' o sonidos de coros tradicionales como Ladysmith Black Mambazo en el diseño de audio de tus modelos.",
        "modelo" => "modelos/Trofeo.glb",
        "marker" => "markers/pattern-españa.patt",
        "posicion" => "0 -0.45 -2",
        "escala" => "0.04 0.04 0.04",
        "rotacion" => "0 0 0"
    ],
    "equipo3" => [
        "nombre" => "República Checa",
        "descripcion" => "- Historia: Vuelven al Mundial tras 20 años de ausencia. Tienen una herencia de orden táctico y sobriedad.<br>
                        - Jugador Estrella: Patrik Schick (delantero alto, gran referencia para modelos de complexión fuerte) y el arquero Matej Kovář.<br>
                        - Identidad Visual: Rojo, blanco y azul. Su estética es más clásica y europea, enfocada en la 'herencia competitiva'.<br>
                        - Dato de Proyecto: Clasificaron vía repechaje contra Dinamarca en un partido muy cerrado. Es el rival 'serio' y táctico del grupo.",
        "modelo" => "modelos/BanderaFrancia.glb",
        "marker" => "markers/pattern-francia.patt",
        "posicion" => "0 0 -2",
        "escala" => "0.7 0.7 0.7",
        "rotacion" => "0 0 0"
    ],
    "equipo4" => [
        "nombre" => "Corea del Sur",
        "descripcion" => "- Apodo: Tigers of Asia.<br>
                        - Jugador Estrella: Son Heung-min (Capitán). Es el referente absoluto; si vas a modelar un rostro, el suyo es el más icónico de Asia. 
                        Otros clave son Kim Min-jae (defensa central robusto) y Lee Kang-in.<br>
                        - Estilo de Juego: Transiciones rápidas y potencia. <br>
                        - Identidad Visual: Rojo vibrante con detalles en negro y azul.",
        "modelo" => "modelos/BanderaCorea.glb",
        "marker" => "markers/pattern-corea.patt",
        "posicion" => "0 -1 -5",
        "escala" => "0.5 0.5 0.5",
        "rotacion" => "0 0 0"
    ],

];

echo json_encode($equipos);
?>