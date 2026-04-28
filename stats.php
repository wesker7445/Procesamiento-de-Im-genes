<div id="mundial-app">

    <h2>Mundial 2026 - Rankings</h2>

    <div class="menu">
        <button onclick="vista('ganados')">Más ganados</button>
        <button onclick="vista('goles')">Más goles</button>
    </div>

    <!-- VISTA GANADOS -->
    <div id="vista-ganados" class="vista">
        <table>
            <thead>
                <tr>
                    <th>Equipo</th>
                    <th>Ganados</th>
                </tr>
            </thead>
            <tbody id="tabla-ganados"></tbody>
        </table>
    </div>

    <!-- VISTA GOLES -->
    <div id="vista-goles" class="vista oculto">
        <table>
            <thead>
                <tr>
                    <th>Equipo</th>
                    <th>Goles</th>
                </tr>
            </thead>
            <tbody id="tabla-goles"></tbody>
        </table>
    </div>

</div>

<link rel="stylesheet" href="stats.css">
<script src="stats.js"></script>