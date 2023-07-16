<?php

// ej: http://localhost/webservice/webservice.php?nombre=Pepe&peso=75&estatura=183


    //definimos con header el tipo del documento (JSON)
    header("Content-Type:application/json");
    global $db;
    $sql = "SELECT            	
            	id_proyecto_tarea codigo_tarea
                ,proyecto_tarea tarea 
                ,cod_proyecto codigo_proyecto
                ,cod_responsable
                ,estado_tarea
                , cargo rol
                , tiempo_ejecutado segundos 
          from proyecto_tarea pt 
          left join estado_tarea et on et.id_estado_tarea = pt.cod_estado_tarea
            left join cargo c on c.id_cargo = pt.cod_cargo
          where pt.estado = 1 and tiempo_ejecutado >0
          order by proyecto_tarea
    "; 
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