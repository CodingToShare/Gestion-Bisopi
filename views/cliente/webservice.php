<?php

// ej: http://localhost/webservice/webservice.php?nombre=Pepe&peso=75&estatura=183


    //definimos con header el tipo del documento (JSON)
    header("Content-Type:application/json");
    global $db;
    $sql = "SELECT            	
            	codigo_cliente
                , cliente
            	, abreviatura            	
            FROM cliente c
            where c.estado = 1
            order by codigo_cliente asc
    "; //where id_recurso = 1
    $objects = $db->selectArraysBySql($sql);
    // $recurso = array_map(utf8_encode ,$recurso );
    $respuesta_json = "";
    foreach( $objects as $object ){
        $object = array_map(utf8_encode , $object );
        $respuesta_json.= json_encode( $object )."\n";
    }
    //print_r( $recurso );
    //codificamos el json
    //$respuesta_json = json_encode($recurso);
    //pintamos el contenido del json
    echo $respuesta_json;
?>