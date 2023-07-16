<?php

// ej: http://localhost/webservice/webservice.php?nombre=Pepe&peso=75&estatura=183


//definimos con header el tipo del documento (JSON)
header("Content-Type:application/json");
global $db;
$sql = "SELECT
            	id_cliente codigo_cliente
                , cliente
            FROM cliente
            where estado = 1
            order by cliente asc
    "; //where id_recurso = 1
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