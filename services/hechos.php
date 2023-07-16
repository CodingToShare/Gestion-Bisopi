<?php

// ej: http://localhost/webservice/webservice.php?nombre=Pepe&peso=75&estatura=183


    //definimos con header el tipo del documento (JSON)
    header("Content-Type:application/json");
    global $db;
    $sql = "SELECT            	
            	id_proyecto_tarea_registro id_registro 
                ,cod_proyecto_tarea codigo_tarea
                ,cod_responsable                
                ,ptr.tiempo_ejecutado segundos 
                ,ptr.comentario
                ,fecha_registro
                , case when mca_historico = 1 then fecha_registro else DATE_FORMAT( ptr.fecha_creacion , '%Y-%m-%d') end fecha_creacion
                , DATEDIFF( case when mca_historico = 1 then fecha_registro else DATE_FORMAT( ptr.fecha_creacion , '%Y-%m-%d') end  , fecha_registro ) diferencia_dias
                ,rc.valor_hora
          from proyecto_tarea_registro ptr
          inner join proyecto_tarea pt on pt.id_proyecto_tarea = ptr.cod_proyecto_tarea    
          left join recurso_costo rc on pt.cod_responsable = rc.cod_recurso
          and ptr.fecha_registro between rc.fecha_desde and coalesce( rc.fecha_hasta , ptr.fecha_registro )
          where ptr.estado = 1 and ptr.tiempo_ejecutado >0 
          order by fecha_registro
    "; 
    //echo $sql;
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