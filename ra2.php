<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>RA Mundial</title>

        <link rel="stylesheet" href="estilos2.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>

        <script src="js/aframe.min.js"></script>
        <script src="js/aframe-ar.js"></script>
        <script src="app.js" defer></script>
    </head>

    <body>

        <!-- 🔝 HEADER -->
        <div class="header">
            <a href="Pagina.php" class="btn-back" title="Regresar al Feed">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2>Apunta un escudo del mundial</h2>
            <a href="Trivia.php" class="btn-action-right" title="Trivia">
               <i class="fa-solid fa-trophy"></i>   
            </a>
        </div>

        <!-- 📱 ESCENA AR -->
       <a-scene id="escenaAR" embedded arjs="sourceType: webcam" style="opacity: 1;">

            <!-- Cámara -->
            <a-camera>
                <!-- MODELO FIJO EN CENTRO -->
                <a-entity id="modeloCentral"
                    position="0 0 -3"
                    scale="0.8 0.8 0.8"
                    visible="false"
                    gesture-handler>
                </a-entity>
            </a-camera>

            <!--  LUCES  -->
            <a-entity light="type: ambient; intensity: 1"></a-entity>
            <a-entity light="type: directional; intensity: 1" position="1 1 1"></a-entity>

            <!-- SOLO MARKERS (sin modelo dentro) -->
            <a-entity id="markers-container"></a-entity>

        </a-scene>

        <!-- 🔘 BOTÓN VER MÁS -->
        <button id="btnInfo" onclick="verMas()">Ver más</button>

        <!-- 📄 PANEL INFO -->
        <div id="infoPanel"></div>

        <!-- 🔽 FOOTER -->
        <div class="footer">
            <div class="side-control">
                <span class="control-label">Efectos</span>
                <button class="btn-secondary" onclick="toggleFiltros()">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </button>
            </div>
        </div>

        <!-- 🎛️ FILTROS -->
        <div id="filtros"></div>

    </body>
</html>