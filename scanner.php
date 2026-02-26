<?php
    session_start();
    // Necesitas estas variables para que la barra lateral no de error
    $isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    $userRole = $isLoggedIn && isset($_SESSION['rol']) ? $_SESSION['rol'] : null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modo Escaneo - Mundial</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="style.css">
        
</head>
<body class="scanner-layout"> <div class="layout">
    <main class="camera-container">
        <a href="Pagina.php" class="btn-back" title="Regresar al Feed">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <a href="Trivia.php" class="btn-action-right" title="Trivia">
               <i class="fa-solid fa-trophy"></i>   </a>
                
        <div class="camera-view"></div> 

        <div id="model-overlay" style="
            display: none; 
            position: fixed; 
            top: 0; 
            left: 250px; 
            right: 0; 
            bottom: 0; 
            z-index: 9999; 
            background: rgba(0, 0, 0, 0.4); 
            backdrop-filter: blur(8px); 
            -webkit-backdrop-filter: blur(8px);
            align-items: center; 
            justify-content: center;
        ">
            <div style="
                position: relative;
                width: 85%; 
                max-width: 450px; 
                height: 65vh; 
                background: #222; 
                border-radius: 25px; 
                display: flex;
                flex-direction: column;
                overflow: hidden;
                box-shadow: 0 25px 50px rgba(0,0,0,0.6);
                border: 1px solid rgba(255,255,255,0.1);
            ">
                <button id="close-3d" style="position: absolute; top: 15px; right: 15px; z-index: 10; background: rgba(0,0,0,0.5); border: none; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; color: white;">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <model-viewer 
                    id="viewer"
                    src="modelos/balon.glb" 
                    ar 
                    camera-controls 
                    auto-rotate 
                    rotation-speed="0.5"
                    shadow-intensity="1"
                    style="width: 100%; flex-grow: 1; background: radial-gradient(#444, #222);">
                </model-viewer>

                <div style="
                    height: 80px; 
                    background: rgba(0,0,0,0.3); 
                    display: flex; 
                    align-items: center; 
                    justify-content: center; 
                    gap: 20px;
                    border-top: 1px solid rgba(255,255,255,0.1);
                ">
                    <button onclick="changeModel('modelos/balon.glb')" style="background: #333; border: 2px solid #555; color: white; width: 50px; height: 50px; border-radius: 10px; cursor: pointer;" title="Balón">
                        <i class="fa-solid fa-soccer-ball"></i>
                    </button>
                    <button onclick="changeModel('modelos/Trofeo.glb')" style="background: #333; border: 2px solid #555; color: white; width: 50px; height: 50px; border-radius: 10px; cursor: pointer;" title="Trofeo">
                        <i class="fa-solid fa-trophy"></i>
                    </button>
                    <button onclick="changeModel('modelos/Sustitudor.glb')" style="background: #333; border: 2px solid #555; color: white; width: 50px; height: 50px; border-radius: 10px; cursor: pointer;" title="Aviso de Sustitucion">
                        <i class="fa-solid fa-arrows-turn-to-dots"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="scan-instructions">
            Apunta un escudo del mundial
        </div>

        <div class="scan-guide"></div>

        <div class="controls-row">
            <div class="side-control">
                <span class="control-label">Modelo 3D</span>
                <button class="btn-secondary" id="btn-show-3d">
                    <i class="fa-solid fa-cube"></i>
                </button>
            </div>

            <div class="split-scan-container">
                <button class="split-btn left" title="Tomar Foto">
                    <i class="fa-solid fa-camera"></i>
                    <span class="split-label">Foto</span>
                </button>
                <button class="split-btn right" title="Escanear QR">
                    <i class="fa-solid fa-qrcode"></i>
                    <span class="split-label">Scan</span>
                </button>
            </div>

            <div class="side-control">
                <span class="control-label">Efectos</span>
                <button class="btn-secondary">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </button>
            </div>
        </div>
    </main>
</div>

<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.3.0/model-viewer.min.js"></script>
<script>
    // Referencia al visor
    const modelViewer = document.getElementById('viewer');


    function changeModel(modelPath) {

        modelViewer.src = modelPath;
        

        modelViewer.style.opacity = '0.5';
        modelViewer.addEventListener('load', () => {
            modelViewer.style.opacity = '1';
        }, { once: true });
    }


    const btn3D = document.getElementById('btn-show-3d');
    const overlay = document.getElementById('model-overlay');
    const btnClose = document.getElementById('close-3d');

    btn3D.addEventListener('click', () => {
        overlay.style.display = 'flex';
    });

    btnClose.addEventListener('click', () => {
        overlay.style.display = 'none';
    });

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.style.display = 'none';
    });
</script>
</body>
</html>