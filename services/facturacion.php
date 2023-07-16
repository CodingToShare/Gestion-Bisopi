<?php

// ej: http://localhost/webservice/webservice.php?nombre=Pepe&peso=75&estatura=183


//definimos con header el tipo del documento (JSON)
header("Content-Type:application/json");
global $db;
include_once 'views/proyecto/base.php';
$datos = $db->selectArraysBySql($sql);
// $recurso = array_map(utf8_encode ,$recurso );
$respuesta_json = "";
foreach( $datos as $dato ){
    $dato = array_map(utf8_encode , $dato );
    $respuesta_json.= json_encode( $dato)."\n";
}
//print_r( $recurso );
//codificamos el json
//$respuesta_json = json_encode($recurso);
//pintamos el contenido del json
echo $respuesta_json;
?>