<?php
    session_start();
    require_once "Connection.php";

    // --- 1. VERIFICACIÓN DE SESIÓN ---
    $isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    $userRole = $isLoggedIn && isset($_SESSION['rol']) ? $_SESSION['rol'] : null;
    $username_session = $isLoggedIn ? $_SESSION['username'] : 'Invitado';
    $fk_usuario = $_SESSION['usuario_id'] ?? 0; // Obtener el ID del usuario

    // Redirigir si no está logueado
    if (!$isLoggedIn) {
        header("Location: InicioSesion.php");
        exit();
    }

    $errores = [];
    $error = null; // Para SweetAlert

    // --- Obtener el ID del mundial seleccionado de GET (carga inicial) o POST (envío) ---
    $id_mundial_seleccionado = 0; // Inicializar a 0

    // 1. Si es POST, obtenemos el ID directamente del campo oculto.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['Mundial_Publicacion']) && is_numeric($_POST['Mundial_Publicacion'])) {
            $id_mundial_seleccionado = (int)$_POST['Mundial_Publicacion'];
        }

    // 2. Si es GET, leemos el TOKEN codificado ('data') y lo decodificamos.
    } elseif (isset($_GET['data'])) {
        $decodificado = base64_decode($_GET['data']);
        
        // Validamos que el resultado de la decodificación sea un número
        if (is_numeric($decodificado)) {
            $id_mundial_seleccionado = (int)$decodificado;
        }
    }

    // Si no se encontró el ID, redirigimos.
    if ($id_mundial_seleccionado === 0) {
        $_SESSION['error'] = "No has seleccionado un mundial válido para publicar.";
        header("Location: Pagina.php"); 
        exit();
    }

    // Ahora, el ID de mundial se usa en la variable de formulario (línea 40)
    // Usamos el ID descifrado en el campo oculto si no es POST.
    $id_mundial = $_POST['Mundial_Publicacion'] ?? $id_mundial_seleccionado;

    // --- 2. PROCESAMIENTO DEL FORMULARIO (SOLO SI ES POST) ---
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener datos del formulario
    $titulo = $_POST['Titulo_Publicacion'] ?? '';
    $descripcion = $_POST['Descripcion_Publicacion'] ?? '';
    $id_categoria = $_POST['Categoria_Publicacion'] ?? 0;
    $id_mundial = $_POST['Mundial_Publicacion'] ?? 0;

    // Validación básica
    if (empty($titulo)) $errores[] = "El título es obligatorio.";
    if (empty($id_categoria)) $errores[] = "Debes seleccionar una categoría.";
    if (empty($id_mundial)) $errores[] = "Debes seleccionar un mundial.";

    // --- 3. LÓGICA DE SUBIDA DE ARCHIVO ---
    $multimedia_url = null; // Por defecto
    
    if (isset($_FILES['Multimedia_Publicacion']) && $_FILES['Multimedia_Publicacion']['error'] === UPLOAD_ERR_OK) {
        
    $file_tmp = $_FILES['Multimedia_Publicacion']['tmp_name'];
    $file_name = basename($_FILES['Multimedia_Publicacion']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // 📢 Array de extensiones permitidas
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'webm', 'ogg']; 
    
    // 📢 VERIFICACIÓN DE LA EXTENSIÓN
    if (!in_array($file_ext, $allowed_extensions)) {
        $errores[] = "❌ Error: El tipo de archivo no es una imagen o un video válido. Extensiones permitidas: " . implode(', ', $allowed_extensions);
        goto end_upload_process; // Salta el proceso de subida
    }
    
    // Directorio de subida (relativo a ESTE script)
    $upload_dir = __DIR__ . "/uploads/publicaciones/"; // Asegúrate que esta carpeta exista y tenga permisos
    
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            $errores[] = "❌ Error: No se pudo crear el directorio de subida.";
            goto end_upload_process; // Saltar al final del bloque de subida
        }
    }
    
    // Nombre único para el archivo
    $unique_name = uniqid('post_', true) . "." . $file_ext;
    $upload_path = $upload_dir . $unique_name;
    
    if (move_uploaded_file($file_tmp, $upload_path)) {
        // URL que se guardará en la BD (relativa a la raíz del sitio)
        $multimedia_url = "/Pagina/uploads/publicaciones/" . $unique_name;
    } else {
        $errores[] = "⚠️ Error al mover el archivo subido.";
    }

    } elseif (isset($_FILES['Multimedia_Publicacion']) && $_FILES['Multimedia_Publicacion']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errores[] = "⚠️ Error en la subida del archivo: " . $_FILES['Multimedia_Publicacion']['error'];
    }
    
    end_upload_process: // Etiqueta para el 'goto'

    // --- 4. EJECUCIÓN DEL INSERT (SOLO SI NO HAY ERRORES) ---
    if (empty($errores)) {
        
        $sql_insert = "INSERT INTO publicacion (Usuario_Publicacion, Mundial_Publicacion, Categorias_Publicacion, Titulo_Publicacion, Descripcion_Publicacion, Multimedia_Publicacion) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql_insert);
        
        // Tipos de datos:
        // i: Usuario_Publicacion (int)
        // i: Mundial_Publicacion (int)
        // i: Categorias_Publicacion (int)
        // s: Titulo_Publicacion (string)
        // s: Descripcion_Publicacion (string)
        // s: Multimedia_Publicacion (string/url)
        
        if ($stmt->bind_param("iiisss", $fk_usuario, $id_mundial, $id_categoria, $titulo, $descripcion, $multimedia_url)) {
            if ($stmt->execute()) {
                $_SESSION['success'] = "Publicacion creada exitosamente.";
                
                $id_encriptado = base64_encode($id_mundial_seleccionado);
                
                // CORRECCIÓN: Usamos 'data' y la variable '$id_encriptado'
                // Y redirigimos a Publicaciones.php (la lista), no a PHP_SELF (el formulario)
                header("Location: Publicaciones.php?data=" . $id_encriptado . "&creado=exitoso");
                exit();
            } else {
                $errores[] = "Error al insertar en la base de datos: " . $stmt->error;
            }
        } else {
            $errores[] = "Error al preparar los parámetros de inserción: " . $stmt->error;
        }
        $stmt->close(); 
    }

    // Si hubo errores, los preparamos para SweetAlert
    if (!empty($errores)) {
        $error = implode("<br>", $errores);
    }
    } // --- FIN DEL BLOQUE `if ($_SERVER["REQUEST_METHOD"] == "POST")` ---


    // --- 5. CARGAR DATOS PARA EL FORMULARIO (SE EJECUTA SIEMPRE) ---

    // Cargar Categorías
    $categorias = [];
    $sql_categorias = "SELECT ID_Categorias, Nombre_Categoria FROM categorias ORDER BY Nombre_Categoria ASC";
    $resultado_categorias = $conexion->query($sql_categorias);
    if ($resultado_categorias && $resultado_categorias->num_rows > 0) { 
        while ($fila = $resultado_categorias->fetch_assoc()) {
            $categorias[] = $fila;
        }
        $resultado_categorias->free();
    }

    $conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Publicación</title>
    <link rel="stylesheet" href="style.css"> <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header>
    <h1><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#6cd085" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-ball-football"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 7l4.76 3.45l-1.76 5.55h-6l-1.76 -5.55z" /><path d="M12 7v-4m3 13l2.5 3m-.74 -8.55l3.74 -1.45m-11.44 7.05l-2.56 2.95m.74 -8.55l-3.74 -1.45" /></svg></h1>
    <?php if ($isLoggedIn): ?>
    <div class="user">
        <img src="MostrarImagen.php" alt="Foto de perfil" height="50" width="50">
        <h3><?php echo htmlspecialchars($username_session); ?></h3>
    </div>
    <?php endif; ?>
    </header>
<div class= "layout">
    <nav class="sidebar">
        <ul>
            <?php if ($isLoggedIn): ?>
            <li>
                <a href="logout.php"> <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
            </li>
            <?php else: ?>
            <li>
                <a href="InicioSesion.php">Iniciar Sesión</a>
            </li>
            <?php endif; ?>
            
            <li><a href="javascript:history.back()" onclick="return true;"><i class="fas fa-undo"></i><span>Volver Atrás</span></a></li>

            <?php if ($userRole == 1): ?>
            <li class="admin-dropdown"> <a href="#" class="dropdown-toggle" id="adminToggle"> <i class="fa-solid fa-user-tie"></i> Modo Admin </a>
                <ul>
                    <li><a href="Admin/AprobarP.php"> <i class="fa-solid fa-thumbs-up"></i> Aprobar Publicacion</a></li>
                    <li><a href="Admin/EliminarC.php"> <i class="fa-solid fa-trash"></i>Eliminar Comentario</a></li>
                    <li><a href="Admin/GestionarM.php"> <i class="fa-solid fa-list-check"></i> Gestionar Mundial</a></li>
                    <li><a href="Admin/GestionarAdmin.php?tipo=categoria"><i class="fa-solid fa-tag"></i> Gestionar Categorías</a></li>
                    <li><a href="Admin/GestionarAdmin.php?tipo=pais"><i class="fa-solid fa-flag"></i> Gestionar Países</a></li>
                    <li><a href="Admin/Mundial.php"> <i class="fa-solid fa-globe"></i> Crear Mundial</a></li>
                    <li><a href="Admin/Categoria.php?tipo=categoria"><i class="fa-solid fa-tag"></i>Crear Categoría</a></li>
                    <li><a href="Admin/Categoria.php?tipo=pais"><i class="fa-solid fa-flag"></i>Crear País</a></li>
                </ul>
            </li> 
            <?php endif; ?>
            <li class="push-bottom"> 
                <li><a href="scanner.php"><i class="fa-solid fa-expand"></i> Modo escaneo</a></li>
                <li><a href="Ayuda.php"><i class="fa-solid fa-question"></i> Ayuda </a></li>
                <a href="MisPosts.php"><i class="fas fa-cog"></i> Perfil</a>
            </li>
        </ul>
    </nav>

    <main>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" class="Formulario">
            <h2>Crear Publicación</h2>
            
            <input type="text" name="Titulo_Publicacion" id="Titulo_Publicacion" placeholder="Ingresa el titulo..." required>
            
            <textarea name="Descripcion_Publicacion" id="Descripcion_Publicacion" placeholder="Ingresa la descripcion..."></textarea>
            
            <input type="hidden" name="Mundial_Publicacion" value="<?= $id_mundial_seleccionado ?>">


            <select name="Categoria_Publicacion" id="Categoria_Publicacion" required>
                <option value="">-- Selecciona la categoría --</option>
                <?php if (empty($categorias)): ?>
                    <option value="" disabled>No hay categorías disponibles</option>
                <?php else: ?>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo htmlspecialchars($categoria['ID_Categorias']); ?>">
                            <?php echo htmlspecialchars($categoria['Nombre_Categoria']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            
            <label for="Multimedia_Publicacion">Subir imagen o video (opcional):</label>
            <input type="file" name="Multimedia_Publicacion" id="Multimedia_Publicacion" accept="image/jpeg, image/png, image/gif, video/mp4, video/webm, video/ogg">
            
            <button type="submit"> Crear Publicación </button>
        </form>
    </main>
</div>

<footer>
<p class="Resaltado">© 2025 Mi Pagina de Mundiales | MiPaginadeMundiales@gmail.com | 815678921</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($error)) : ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops... Tienes errores:',
    html: <?= json_encode($error) ?>, // Muestra los errores de validación
    confirmButtonColor: '#d33'
});
</script>
<?php endif; ?>

<?php if (isset($_GET['creado']) && $_GET['creado'] === 'exitoso') : ?>
<script>
Swal.fire({
    icon: 'success',
    title: '¡Publicación Creada!',
    text: 'Tu publicación se ha guardado exitosamente.',
    confirmButtonColor: '#3085d6'
});
</script>
<?php endif; ?>

</body>
</html>