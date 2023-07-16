<?php

// ej: http://localhost/webservice/webservice.php?nombre=Pepe&peso=75&estatura=183


    //definimos con header el tipo del documento (JSON)
    header("Content-Type:application/json");
    global $db;
    $sql = "SELECT            	
            	id_recurso codigo_recurso
                , r.abreviatura
            	, recurso
            	,c.cargo
                ,case r.cod_estado_recurso when 'A' then 'Activo' else 'Inactivo' end estado_recurso                
                ,ci.ciudad
                ,r.telefono
                ,a.area
            FROM recurso r
            inner join cargo c
            on c.id_cargo = r.cod_cargo
            left join ciudad ci
            on ci.id_ciudad = r.cod_ciudad
            left join area a
            on a.id_area = r.cod_area
            where c.estado = 1
            order by recurso asc
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