<?php
header('Content-Type: application/json');

$data = file_get_contents("resultados.json");
echo $data;