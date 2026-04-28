let equipos = {};
let equipoActual = null;
let modeloActual = null;

// 🎨 filtro de cámara
let filtroCamara = "none";

// 🔄 Cargar equipos desde PHP
fetch("equipos.php")
.then(res => res.json())
.then(data => {
    equipos = data;
    crearMarkers();
});

function crearMarkers() {
    const container = document.getElementById("markers-container");
    const modeloCentral = document.getElementById("modeloCentral");

    Object.keys(equipos).forEach(key => {
        let eq = equipos[key];

        let marker = document.createElement("a-marker");
        marker.setAttribute("type", "pattern");
        marker.setAttribute("url", eq.marker);

        // 👁️ DETECTA MARCADOR
        marker.addEventListener("markerFound", () => {
            equipoActual = eq;

            modeloCentral.setAttribute("visible", "true");
            modeloCentral.setAttribute("position", eq.posicion || "0 0 -3");
            modeloCentral.setAttribute("scale", eq.escala || "1 1 1");
            modeloCentral.setAttribute("rotation", eq.rotacion || "0 0 0");

            aplicarModeloNormal();

            mostrarBoton();
        });

        // ❌ CUANDO SE PIERDE
        marker.addEventListener("markerLost", () => {
            modeloCentral.setAttribute("visible", "false");
            limpiarModelo();

            ocultarBoton();
            equipoActual = null;
            modeloActual = null;
        });

        container.appendChild(marker);
    });
}

// 🔘 BOTÓN INFO
function mostrarBoton() {
    document.getElementById("btnInfo").style.display = "block";
}

function ocultarBoton() {
    document.getElementById("btnInfo").style.display = "none";
    document.getElementById("infoPanel").style.display = "none";
}

// 📄 VER MÁS
function verMas() {
    if (!equipoActual) return;

    let panel = document.getElementById("infoPanel");
    panel.style.display = "block";

    panel.innerHTML = `
        <h3>${equipoActual.nombre}</h3>
        <p>${equipoActual.descripcion}</p>
    `;
}

// 🔙 NAVEGACIÓN
function volver() {
    window.history.back();
}

function irTrivia() {
    window.location.href = "trivia.php";
}

// 🧹 LIMPIAR MODELO
function limpiarModelo() {
    const modeloCentral = document.getElementById("modeloCentral");

    while (modeloCentral.firstChild) {
        modeloCentral.removeChild(modeloCentral.firstChild);
    }
}

// 🎯 CREAR MODELO BASE
function crearModeloBase() {
    let modelo = document.createElement("a-entity");
    modelo.setAttribute("gltf-model", equipoActual.modelo);
    return modelo;
}

// 📦 MODELO NORMAL
function aplicarModeloNormal() {
    const contenedor = document.getElementById("modeloCentral");
    limpiarModelo();

    let modelo = crearModeloBase();

    aplicarTransformacionBase(modelo);

    contenedor.appendChild(modelo);

    // 🔥 guardar referencia para rotación
    modeloActual = modelo;
}

// 🔢 UTILS
function strToVec3(str, def = "0 0 0") {
    const v = (str || def).split(" ").map(Number);
    return { x: v[0] || 0, y: v[1] || 0, z: v[2] || 0 };
}

function vec3ToStr(v) {
    return `${v.x} ${v.y} ${v.z}`;
}

// 🔧 TRANSFORMACIONES
function aplicarTransformacionBase(modelo) {
    let posBase = strToVec3(equipoActual.posicion, "0 0 -3");
    let escalaBase = strToVec3(equipoActual.escala, "1 1 1");
    let rotBase = strToVec3(equipoActual.rotacion, "0 0 0");

    modelo.setAttribute("position", vec3ToStr(posBase));
    modelo.setAttribute("scale", vec3ToStr(escalaBase));
    modelo.setAttribute("rotation", vec3ToStr(rotBase));
}

//////////////////////////////////////////////////////
// 🖱️ ROTACIÓN CON CLICK (SOBRE SU PROPIO EJE)
//////////////////////////////////////////////////////

let rotando = false;
let ultimoX = 0;
let ultimoY = 0;

document.addEventListener("mousedown", (e) => {
    rotando = true;
    ultimoX = e.clientX;
    ultimoY = e.clientY;
});

document.addEventListener("mouseup", () => {
    rotando = false;
});

document.addEventListener("mousemove", (e) => {
    if (!rotando || !modeloActual) return;

    let deltaX = e.clientX - ultimoX;
    let deltaY = e.clientY - ultimoY;

    ultimoX = e.clientX;
    ultimoY = e.clientY;

    let rot = modeloActual.getAttribute("rotation");

    rot.y += deltaX * 0.5;
    rot.x += deltaY * 0.5;

    modeloActual.setAttribute("rotation", rot);
});

//////////////////////////////////////////////////////
// 🎛️ FILTROS PARA CÁMARA
//////////////////////////////////////////////////////

function toggleFiltros() {
    let f = document.getElementById("filtros");

    if (f.style.display === "block") {
        f.style.display = "none";
    } else {
        f.style.display = "block";
        f.innerHTML = `
            <button onclick="setFiltro('infrared')">Infrarrojo</button>
            <button onclick="setFiltro('duotone')">Duotono</button>
            <button onclick="setFiltro('solar')">Solarización</button>
            <button onclick="setFiltro('glitch')">Glitch</button>
            <button onclick="setFiltro('kaleidoscope')">Prisma</button>
            <button onclick="setFiltro('fisheye')">Ojo de pez</button>
            <button onclick="setFiltro('tilt')">Tilt-Shift</button>
            <button onclick="setFiltro('cinematic')">Cinemático</button>
            <button onclick="setFiltro('lightleak')">Light Leak</button>
            <button onclick="setFiltro('thermal')">Térmico</button>
            <button onclick="setFiltro('none')">Normal</button>
        `;
    }
}

function setFiltro(tipo) {
    const video = document.querySelector("video");
    if (!video) return;

    filtroCamara = "none";

    switch (tipo) {

        case "infrared":
            filtroCamara = "hue-rotate(180deg) saturate(2) contrast(1.2)";
            break;

        case "duotone":
            filtroCamara = "grayscale(1) contrast(2) sepia(1)";
            break;

        case "solar":
            filtroCamara = "invert(0.7) contrast(1.5)";
            break;

        case "glitch":
            filtroCamara = "contrast(2) saturate(3) hue-rotate(20deg)";
            video.style.transform = "translateX(2px)";
            setTimeout(() => video.style.transform = "translateX(-2px)", 100);
            break;

        case "kaleidoscope":
            filtroCamara = "contrast(1.5) saturate(2)";
            video.style.transform = "scaleX(-1)";
            break;

        case "fisheye":
            filtroCamara = "none";
            video.style.borderRadius = "50%";
            video.style.transform = "scale(1.2)";
            break;

        case "tilt":
            filtroCamara = "blur(2px) contrast(1.2)";
            break;

        case "cinematic":
            filtroCamara = "contrast(1.3) saturate(1.4) hue-rotate(-10deg)";
            break;

        case "lightleak":
            filtroCamara = "brightness(1.2) contrast(1.1) saturate(1.5)";
            video.style.boxShadow = "0 0 100px rgba(255,100,50,0.5) inset";
            break;

        case "thermal":
            filtroCamara = "hue-rotate(90deg) saturate(3) contrast(2)";
            break;

        default:
            filtroCamara = "none";
            break;
    }

    // Reset estilos antes de aplicar
    video.style.filter = filtroCamara;

    if (tipo === "none") {
        video.style.transform = "none";
        video.style.borderRadius = "0";
        video.style.boxShadow = "none";
    }
}
//////////////////////////////////////////////////////
// 📸 TOMAR FOTO
//////////////////////////////////////////////////////

function tomarFoto() {
    const video = document.querySelector("video");

    if (!video) {
        alert("No se detecta la cámara");
        return;
    }

    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    ctx.filter = filtroCamara;
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    const link = document.createElement("a");
    link.download = "foto_ar.png";
    link.href = canvas.toDataURL("image/png");

    link.click();
}