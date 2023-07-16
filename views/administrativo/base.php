<?php
global $db;
global $estados_hito;
$today = date("Ymd");
$array_estados = $_REQUEST["cod_estado"];
$filtro_estado = implode( "," , $array_estados );
$filtro_t_fecha = (($_REQUEST["tfecha"]=="")?"hito":$_REQUEST["tfecha"]);
$array_estados_hito = $_REQUEST["cod_estado_hito"];
$filtro_estado_hito = implode( "," , $array_estados_hito );
$mesant = (($_REQUEST["fecha_inicio"]=="" )? date("Y-m-d",strtotime($today."- 3 days")):$_REQUEST["fecha_inicio"]);
$mespos = (($_REQUEST["fecha_fin"]=="" )? date("Y-m-d",strtotime($today."+ 1 weeks")):$_REQUEST["fecha_fin"]);
$filtro_pais = (($_REQUEST["cod_pais"]=="-" )? "" :$_REQUEST["cod_pais"]);
$filtro_cliente = (($_REQUEST["cod_cliente"]=="-" )? "" :$_REQUEST["cod_cliente"]);
$array_filtros_fecha = array( "hito" => "ph.fecha_hito" ,"aprobado" => "phe2.fecha_creacion" ,"facturado" => "phe3.fecha_creacion" ,"pagado" => "phe4.fecha_creacion");
$array_txt_filtros_fecha = array( "hito" => "Hito" ,"aprobado" => "Aprobado Facturar" ,"facturado" => "Facturado" ,"pagado" => "Pagado");

if( $_REQUEST["output"] == "xls"){
    $filtro_estado = $_REQUEST["f_estado"];
    $filtro_estado_hito = $_REQUEST["f_estado_hito"];
}
$sql ="
                            codigo_proyecto
                            , proyecto
                            ,case when mca_control_cambio = 1 then 'Si' else 'No' end control_cambio
                            , concat ( nro_hito , ' de ', nh.total_hitos) nro_hito
                        	, date_format( ph.fecha_hito, '%Y-%m-%d') fecha_hito
                            , case when ph.fecha_hito < CURRENT_DATE then DATEDIFF( CURRENT_DATE , ph.fecha_hito ) else 0 end nro_dias
                        	, pais
                            , cliente
                        	-- , pa.abreviatura
                        	-- , coalesce( p.cod_lider , -1 )  cod_lider
                        	-- , coalesce( r.recurso ,'Sin Lider' ) lider
                            ,ep.estado_proyecto
                            ,case ph.cod_estado_hito when 1 then 'Pendiente facturar' when 2 then 'Aprobado para facturar' when 3 then 'Facturado' when 4 then 'Pagado' end estado_hito
                            ,t.trm trm_proyectada
                            ,round( ph.valor ,3 ) valor_hito                            
                            ,round( round( ph.valor ,3 ) * t.trm ,3 ) valor_hito_pesos                             
                            , ( ( ph.valor * re.retencion / 100 ) * t.trm ) valor_retencion_pesos
                            ,replace(  case when m.abreviatura = 'COP' then round( (porcentaje_iva/100) * t.trm * round( ph.valor ,3) ,3) else 0 end , '.',',' ) iva
                            ,replace( case when m.abreviatura = 'COP' then round( (porcentaje_iva/100) * t.trm * round( ph.valor ,3 ) * ( porcentaje_rete_iva /100) ,3) else 0 end , '.',',' ) rete_iva
                            ,replace( ( t.trm * ph.valor ) - ( ( ph.valor * re.retencion / 100 ) * t.trm ) + case when m.abreviatura = 'COP' then round( (porcentaje_iva/100) * t.trm * round( ph.valor ,3) ,3) else 0 end - case when m.abreviatura = 'COP' then round( (porcentaje_iva/100) * t.trm * round( ph.valor ,3 ) * ( porcentaje_rete_iva /100) ,3) else 0 end, '.',',' ) total_pesos
                            ,m.abreviatura moneda
                            ,date_format( phe2.fecha_creacion , '%Y-%m-%d' ) fecha_aprobado_facturar
                            ,date_format( phe3.fecha_creacion , '%Y-%m-%d' ) fecha_facturado
                            ,date_format( phe4.fecha_creacion , '%Y-%m-%d' ) fecha_pagado
                        FROM proyecto p
                        left join proyecto_hito ph on ph.cod_proyecto = p.id_proyecto
                        left join cliente c on c.id_cliente = p.cod_cliente
                        left join recurso r
                        on r.id_recurso = p.cod_lider
                        inner join pais pa
                        on pa.id_pais = p.cod_pais
                        inner join estado_proyecto ep
                        on ep.id_estado_proyecto = p.cod_estado_proyecto
                        left join moneda m on m.id_moneda = ph.cod_moneda
                        left join proyecto_hito_estado phe2
                        on ph.id_proyecto_hito = phe2.cod_proyecto_hito and phe2.cod_estado_hito = 2
                        left join proyecto_hito_estado phe3
                        on ph.id_proyecto_hito = phe3.cod_proyecto_hito and phe3.cod_estado_hito = 3
                        left join proyecto_hito_estado phe4
                        on ph.id_proyecto_hito = phe4.cod_proyecto_hito and phe4.cod_estado_hito = 4
                        left join retencion re on re.cod_pais = p.cod_pais and date_format( coalesce( phe3.fecha_creacion , CURRENT_DATE ) , '%Y-%m-%d' ) between re.fecha_inicio and coalesce( re.fecha_fin , ADDDATE( CURRENT_DATE , INTERVAL 10 YEAR ) )
                        left join (SELECT
                          count(1) total_hitos, cod_proyecto
                        FROM  proyecto_hito ph
                        group by cod_proyecto) nh on nh.cod_proyecto = p.id_proyecto
                        left join trm t
                        on t.cod_moneda = ph.cod_moneda and date_format( fecha_hito , '%Y' )  = t.anio
                        where p.estado = 1 and coalesce( p.mca_facturable , 0 ) = 1 ".( ($filtro_estado!= "")?" and p.cod_estado_proyecto in (".$filtro_estado.")":"")."                        
                        ".(($filtro_pais!="")?" and pa.id_pais = '".$filtro_pais."'":"")."
                        ".(($filtro_cliente!="")?" and c.id_cliente = '".$filtro_cliente."'":"")."
                        and (  
                            ( date_format( ".$array_filtros_fecha[ $filtro_t_fecha ]." , '%Y-%m-%d') BETWEEN '".$mesant."' and '".$mespos."'
                             ".( ($filtro_estado_hito!= "")?" and ph.cod_estado_hito in (".$filtro_estado_hito.")":"")." ) 
                             or ph.fecha_hito is null 
                        )
                        order by pais, proyecto, codigo_proyecto , ph.nro_hito , ph.cod_estado_hito
                       ";

?>