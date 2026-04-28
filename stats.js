let equipos = [];
let partidos = [];

fetch("datos.php")
.then(res => res.json())
.then(data => {

    partidos = data.partidos;

    generarEquipos();

    cargarGanados();
    cargarGoles();
});


// CAMBIO DE VISTA
function vista(tipo){
    document.querySelectorAll(".vista").forEach(v => v.classList.add("oculto"));
    document.getElementById("vista-" + tipo).classList.remove("oculto");
}


// GENERAR ESTADÍSTICAS DESDE PARTIDOS
function generarEquipos(){
    let mapa = {};

    partidos.forEach(p => {

        if(!mapa[p.local]){
            mapa[p.local] = {nombre:p.local, goles:0, ganados:0};
        }
        if(!mapa[p.visitante]){
            mapa[p.visitante] = {nombre:p.visitante, goles:0, ganados:0};
        }

        mapa[p.local].goles += p.goles_local;
        mapa[p.visitante].goles += p.goles_visitante;

        if(p.goles_local > p.goles_visitante){
            mapa[p.local].ganados++;
        } else if(p.goles_visitante > p.goles_local){
            mapa[p.visitante].ganados++;
        }
    });

    equipos = Object.values(mapa);
}


// LOGO
function logo(nombre){
    return "img/" + nombre.toLowerCase().replace(/ /g,"") + ".png";
}


// VISTA GANADOS
function cargarGanados(){
    let ordenados = [...equipos].sort((a,b)=> b.ganados - a.ganados);

    let html = "";
    ordenados.forEach(e => {
        html += `
        <tr>
            <td><img src="${logo(e.nombre)}" class="logo"> ${e.nombre}</td>
            <td>${e.ganados}</td>
        </tr>
        `;
    });

    document.getElementById("tabla-ganados").innerHTML = html;
}


// VISTA GOLES
function cargarGoles(){
    let ordenados = [...equipos].sort((a,b)=> b.goles - a.goles);

    let html = "";
    ordenados.forEach(e => {
        html += `
        <tr>
            <td><img src="${logo(e.nombre)}" class="logo"> ${e.nombre}</td>
            <td>${e.goles}</td>
        </tr>
        `;
    });

    document.getElementById("tabla-goles").innerHTML = html;
}