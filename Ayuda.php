<?php
    session_start();
    $isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    $userRole = $isLoggedIn && isset($_SESSION['rol']) ? $_SESSION['rol'] : null;
    $username_session = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ayuda - Mi Página de Mundiales</title>
    <link rel="stylesheet" href="style.css"> <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
</head>
<body>
<header>
    <h1>Ayuda <i class="fa-solid fa-circle-question"></i></h1>
    <?php if ($isLoggedIn): ?>
    <div class="user">
        <img src="MostrarImagen.php" alt="Perfil" height="50" width="50">
        <h3><?php echo htmlspecialchars($username_session); ?></h3>
    </div>
    <?php endif; ?>
</header>

<div class="layout">
    <nav class="sidebar">
        <ul>
            <li><a href="Pagina.php"><i class="fas fa-home"></i><span>Inicio</span></a></li>
            <li><a href="javascript:history.back()"><i class="fas fa-undo"></i><span>Volver</span></a></li>
            </ul>
    </nav>

    <main>
    <div class="publicacion">
        <h1>Guía de Ayuda y Soporte</h1>
        <p class="info">Todo lo que necesitas saber para navegar en la plataforma.</p>

        <section>
            <h2>1. Navegación General (Acceso Libre)</h2>
            <p>Si eres un visitante nuevo, puedes explorar el contenido principal utilizando el <strong>menú lateral</strong>:</p>
            <ul>
                <li><strong>VR (Realidad Virtual):</strong> Disponible en la sección de escaneo para una experiencia inmersiva.</li>
                <li><strong>Trivia:</strong> Pon a prueba tus conocimientos en la página de escaneo.</li>
                <li><strong>Publicaciones:</strong> Visualiza los aportes de la comunidad.</li>
                <li><strong>Historia de los Mundiales:</strong> Consulta información detallada de cada edición.</li>
            </ul>
        </section>

        <section>
            <h2>2. Funciones para Usuarios Registrados</h2>
            <p>Al crear una cuenta y acceder a tu perfil, desbloqueas las herramientas interactivas:</p>
            <ul>
                <li><strong>Perfil Personalizado:</strong> Gestiona tu identidad en la plataforma.</li>
                <li><strong>Participación Activa:</strong> Capacidad para crear publicaciones en los apartados de los mundiales y comentar en los posts de otros usuarios.</li>
            </ul>
        </section>

        <section>
            <h2>3. Tutoriales Paso a Paso</h2>
            
            <h3>¿Cómo crear una publicación?</h3>
            <p>Para compartir contenido, sigue esta ruta:</p>
            <ol>
                <li><strong>Registro:</strong> Crea tu cuenta o inicia sesión.</li>
                <li><strong>Selección:</strong> Dirígete al Mundial específico sobre el que quieras publicar.</li>
                <li><strong>Menú Lateral:</strong> Abre el menú izquierdo y selecciona <em>"Crear publicación"</em>.</li>
                <li><strong>Formulario:</strong> Completa los datos requeridos por el sistema.</li>
                <li><strong>Validación:</strong> Tu post pasará a revisión por un administrador. Una vez aceptado, aparecerá públicamente.</li>
            </ol>

            <h3>¿Cómo comentar una publicación?</h3>
            <ol>
                <li>Selecciona la publicación de tu interés haciendo clic en ella.</li>
                <li>Desliza hacia la parte inferior del post.</li>
                <li>Escribe tu mensaje en la <strong>Sección de Comentarios</strong> y presiona enviar.</li>
            </ol>
        </section>
    </div>
</main>
</div>

<footer>
    <p class="Resaltado">© 2025 Mi Pagina de Mundiales | Soporte</p>
</footer>
</body>
</html>