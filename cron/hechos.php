<?php
    global $db;
    
    $carga = $db->selectObject("ws_carga","carga='hechos'");
    // $sql = "truncate table ws_hechos;";
    // $db->sql( $sql );
    $filtro = "";
    if( $carga->fecha_carga != "" ){
        $filtro =" and ptr.fecha_creacion > '".$carga->fecha_carga."' ";
    }
    $sql = "INSERT INTO ws_hechos 
            SELECT            	
            	id_proyecto_tarea_registro id_registro 
                ,cod_proyecto_tarea codigo_tarea
                ,cod_responsable                
                ,ptr.tiempo_ejecutado segundos 
                ,ptr.comentario
                ,fecha_registro
                , case when mca_historico = 1 then fecha_registro else DATE_FORMAT( ptr.fecha_creacion , '%Y-%m-%d') end fecha_creacion_hito
                , DATEDIFF( case when mca_historico = 1 then fecha_registro else DATE_FORMAT( ptr.fecha_creacion , '%Y-%m-%d') end  , fecha_registro ) diferencia_dias
                ,rc.valor_hora
                ,CURRENT_TIMESTAMP fecha_creacion
          from proyecto_tarea_registro ptr
          inner join proyecto_tarea pt on pt.id_proyecto_tarea = ptr.cod_proyecto_tarea    
          left join recurso_costo rc on pt.cod_responsable = rc.cod_recurso
          and ptr.fecha_registro between rc.fecha_desde and coalesce( rc.fecha_hasta , ptr.fecha_registro )
          where ptr.estado = 1 and ptr.tiempo_ejecutado >0 ".$filtro."
          order by fecha_registro";
    $db->sql( $sql );
    
    $fecha = $db->max("proyecto_tarea_registro","fecha_creacion",null , "ptr.estado = 1 and ptr.tiempo_ejecutado >0");
    $sql = "update ws_carga set fecha_carga=CURRENT_TIMESTAMP where carga = 'hechos'";
    $db->sql( $sql );
    
?>