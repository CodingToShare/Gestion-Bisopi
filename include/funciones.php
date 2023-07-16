<?php
    /***
     * 
     */
    function isFestivo( $dia ){
        global $db;
        $festivo = $db->countObjects("festivo", "festivo='".$dia."'" );
        if( $festivo ==0 )
            return false;
            return true;
    }
    function isFeriado( $fecha ){
        global $db;
        $festivo = $db->countObjects("festivo", "festivo='".$fecha."'" );
        if( $festivo > 0 )
            return true;
        $dia = date( 'w' , strtotime(  $fecha ) );
        if( $dia == 0  || $dia == 6 )
            return true;
        return false;
    }
    
    function retornaExt( $cadena ){
        $salida = substr( $cadena , strrpos( $cadena , "." )+1 );
        return $salida;
    }
    
    function listDefault( $table , $objects , $fields , $position = 'right'){
        global $_view;
        //return print_r( $objects , true );
        /*$sal = '<div class="row my-1">
        	<div class="col-sm-4"> 
    		<input class="form-control" id="search" type="text" placeholder="Buscar..." autocomplete="off" >
    		</div>
    	</div>';
    	*/
        $sal = '<table class="table table-striped table-hover">';
        $sal.= '<thead class="thead-dark">';
        $sal.= '<tr>';
        $sal.= '<th class="p-1" scope="col" style="width:35px"></th>';        
        if( $position == 'left'){
            $sal.= '<th class="p-1" scope="col" style="width:35px"></th>';
            $sal.= '<th class="p-1" scope="col" style="width:35px"></th>';
        }
        foreach( $fields as $field ){
            $sal.= '<th class="p-1" scope="col">'.utf8_decode( $field["name"] ).'</th>';
        }
        if( $position == 'right'){
            $sal.= '<th class="p-1" scope="col" style="width:35px"></th>';
            $sal.= '<th class="p-1" scope="col" style="width:35px"></th>';
        }
        $sal.= '<tr>';
        $sal.= '</thead>';
        $sal.= '<tbody id="tb_search" class=" text-nowrap">';
        $contador = 1;
        $val_id =  'id_'.$table;
        $valor = "";
        foreach ( $objects as $object ){
            $sal.='<tr>';
            $sal.='<th class="p-1" scope="row">'.($contador++).'</th>';
            if( $position == 'left'){
                $sal.='<td class="p-1"><a href="index.php?view='.$_view.'&action=agregar&id='.$object->$val_id.'" class=""><img src="'.IMG_EDIT.'" style="width:16px" title="Editar" /></a></td>';
                //$sal.='<td class="p-1"><a href="#" onclick="xajax_confirmarEliminar(\'pais\',\''.$object->$val_id.'\')" class=""><img src="'.IMG_DELETE.'" style="width:16px"  title="Eliminar" /></a></td>';
                $sal.="<td class='p-1'><a href='#' onclick=\"crearDialogo('confirmacion','¿Está seguro de eliminar el registro seleccionado?','xajax_eliminar','\'".$table."\'','\'".$object->$val_id."\'');\">";
                $sal.='<img src="'.IMG_DELETE.'" style="width:16px"  title="Eliminar" /></a></td>';
            }
            foreach( $fields as $field ){
                $valor = $field["id"];
                if( $field["type"] == "color"){
                    $sal.='<td class="p-1"><div style="background-color:#'.$object->$valor.';padding:2px;font-size:11px;width:100px;text-align:center;">#'.$object->$valor.'</div></td>';
                }else{
                    $sal.='<td class="p-1">'.$object->$valor.'</td>';
                }
            }
            if( $position == 'right'){
                $sal.='<td class="p-1"><a href="index.php?view='.$_view.'&action=agregar&id='.$object->$val_id.'" class=""><img src="'.IMG_EDIT.'" style="width:16px" title="Editar" /></a></td>';
                //$sal.='<td class="p-1"><a href="#" onclick="xajax_confirmarEliminar(\'pais\',\''.$object->$val_id.'\')" class=""><img src="'.IMG_DELETE.'" style="width:16px"  title="Eliminar" /></a></td>';
                $sal.="<td class='p-1'><a href='#' onclick=\"crearDialogo('confirmacion','¿Está seguro de eliminar el registro seleccionado?','xajax_eliminar','\'".$table."\'','\'".$object->$val_id."\'');\">";
                $sal.='<img src="'.IMG_DELETE.'" style="width:16px"  title="Eliminar" /></a></td>';
            }
            $sal.='</tr>';
        }
        
        $sal.= '</tbody></table>';
        
        $sal.= '<script>
                $(document).ready(function(){
                  $("#search").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#tb_search tr").filter(function() {
                      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                  });
                });
                </script>';
        return $sal;
    }
    
    function printProgramacion( $id ){
        global $meses;
        global $weekdays;
        global $porcentajes;
        $today = date("Ymd");
        $firstdate = 1;
        $lastdate = date("t");
        $sal = "";
        global $db;
        $sql ="SELECT
            	id_proyecto
            	,proyecto
            	,date_format( fecha_inicio , '%Y-%m-%d') fecha_inicio
            	,date_format( fecha_fin, '%Y-%m-%d') fecha_fin
            	,pais
            	,pa.abreviatura
            	,p.cod_lider
            	,r.recurso lider
            	,ep.abreviatura
            FROM proyecto p
            left join recurso r
            on r.id_recurso = p.cod_lider
            inner join pais pa
            on pa.id_pais = p.cod_pais
            inner join estado_proyecto ep
            on ep.id_estado_proyecto = p.cod_estado_proyecto
            where p.id_proyecto='".$id."'
                   ";
        //echo $sql;
        $proyectos = $db->selectObjectsBySql($sql) ;
        $proyecto = $proyectos[0];
        $sql = "select min( fecha_inicio )  fecha_inicio , max( fecha_fin)  fecha_fin
                    from proyecto p                    
                    where p.id_proyecto='".$id."' ";
        $fechas = $db->selectObjectsBySql($sql);
        
        $sql = "select min( fecha ) fecha_inicio , max( fecha ) fecha_fin
                    from programacion p
                    where p.cod_proyecto='".$id."' ";
        $fechas_prog = $db->selectObjectsBySql($sql);
        
        $sql = "select
                        id_programacion
                    	,p.cod_proyecto
                        , proyecto
                    	, date_format( fecha_inicio , '%Y%m%d') fecha_inicio
                    	, date_format( fecha_fin, '%Y%m%d') fecha_fin
                    	, p.cod_recurso
                        , concat( p.cod_proyecto ,'_', p.cod_recurso) llave
                    	, r.recurso
                    	,date_format( p.fecha , '%Y%m%d') fecha
                    	,p.asignacion
                    from programacion p
                    inner join recurso r
                    on r.id_recurso = p.cod_recurso
                    inner join proyecto pr
                    on pr.id_proyecto = p.cod_proyecto
                    where p.cod_proyecto='".$id."'
                    order by recurso
                ";
        $programaciones = $db->selectObjectsBySql($sql);
        
        
        $array_programacion = array();
        $array_recursos  = array();
        
        foreach( $programaciones as $programacion ){
            $array_programacion[ $programacion->cod_proyecto][$programacion->cod_recurso][ $programacion->fecha ]->asignacion= $programacion->asignacion;
            $array_programacion[ $programacion->cod_proyecto][$programacion->cod_recurso][ $programacion->fecha ]->id= $programacion->id_programacion;
            $array_programacion[$programacion->cod_recurso][ $programacion->fecha ]+= $programacion->asignacion;
            $array_recursos[$programacion->cod_recurso] = $programacion->recurso;
        }
        $array_recursos = array_unique($array_recursos);
        if( $fechas_prog[0]->fecha_inicio != "0000-00-00" ||  $fechas_prog[0]->fecha_inicio != ""){
            if( $fechas[0]->fecha_inicio != "0000-00-00" &&  $fechas[0]->fecha_inicio != "" ){
                $fecha_inicio = (( $fechas[0]->fecha_inicio < $fechas_prog[0]->fecha_inicio )?$fechas[0]->fecha_inicio: $fechas_prog[0]->fecha_inicio);
                $fecha_fin = (( $fechas[0]->fecha_fin > $fechas_prog[0]->fecha_fin )?$fechas[0]->fecha_fin: $fechas_prog[0]->fecha_fin);
                $test = "1 - ";
            }else{
                $fecha_inicio = $fechas_prog[0]->fecha_inicio;
                $fecha_fin = $fechas_prog[0]->fecha_fin;
                $test = "2 - ";
            }
        }else{
            $fecha_inicio = $fechas[0]->fecha_inicio;
            $fecha_fin = $fechas[0]->fecha_fin;
            $test = "3 - ";
        }
        
        //return $test.$fecha_inicio.$fechas_prog[0]->fecha_inicio." - ".$fechas[0]->fecha_inicio;
        if( $fecha_inicio == "" )
            return "Aun no se ha definido programación";
        
        $añoini = date( 'Y' , strtotime(  $fecha_inicio ) );
        $añofin = date( 'Y' , strtotime(  $fecha_inicio ) );
        $mesini = date( 'n' , strtotime(  $fecha_inicio ) );
        $mesfin = date( 'n' , strtotime(  $fecha_fin ) );
        $firstdate = date( 'j' , strtotime(  $fecha_inicio ) );       
                
        
        $td_mes = "";
        $td_semana = "";
        $td_dias = "";
        $td_ldias = "";
        
        $diaSemana=date("w",mktime(0,0,0,$mesini,$firstdate,$añoini));//+7;
        $colsemana = 8-$diaSemana-(($diaSemana==0)?7:0);
        /*Ciclo por meses*/
        $cont_m= 0;
        $array_td_proyecto = array();
        $array_td_programacion = array();
        
        $contador = 0;
        $mesact = $fecha_inicio;
        $mespos = $fecha_fin;
        $firstdate = date( 'j' , strtotime(  $fecha_inicio ) );
        //$sal = $mesact." - ".$mespos;
        //$sal = "<pre>".print_r( $fechas_prog , true )."</pre>";
        //return $sal;
        $sal = "";
        while( strtotime( $mesact ) <= strtotime( $mespos ) ){
            //for( $m = $mesini ; $m<=$mesfin ; $m++ ){
            //date("Y-m-d",strtotime($today."+ 1 month"))
            $m = date("n", strtotime( $mesact ) );
            
            $año = date("Y", strtotime( $mesact ) );
            # Obtenemos el dia de la semana del primer dia
            # Devuelve 0 para domingo, 6 para sabado
            $diaSemana=date("w",mktime(0,0,0,$m,1,$año));//+7;
            
            
            if( $m == date("m", strtotime( $mespos ) ) )
                $lastdate = date( 'j' , strtotime(  $mespos ) );
                else
                    $lastdate = date("t",mktime(0,0,0,$m,1,$año));//+7;
                    
                    
                    
                    $td_mes.="<td style='border-left:1px solid #ccc' colspan='".($lastdate-$firstdate+1)."'>".$año." ".$meses[$m]."</td>";
                    $fecha = $año*10000+$m*100+$firstdate;
                    if( $cont_m == 0 )
                        $td_semana.="<td colspan='".$colsemana."' style='border-left:1px solid #ccc'>".date( 'Y-m-d' , strtotime( $fecha ) )."</td>";
                        $cont_m++;
                        $colsemana = 7;
                        
                        /*ciclo por Días*/
                        for( $i = $firstdate ; $i<= $lastdate ; $i++ ){
                            $fecha = $año*10000+$m*100+$i;
                            $td_dias.="<td class='text-center ".( ( $fecha == $today )?"table-warning":"")."' data-toggle='tooltip' data-placement='top' title='".date("Y-m-d" , strtotime( $fecha ) )."'>".$i."</td>";
                            //$td_ldias.="<th class='".(( ($diaSemana%7) == 0 || ( ($diaSemana%7) == 6) || (isFestivo( $fecha )) )?"table-active":"table-primary")."'>".$weekdays[ ($diaSemana%7) ]."</th>";
                            $diaSemana=date("w",mktime(0,0,0,$m,$i,$año));//+7;
                            $td_ldias.="<td class='text-center ".(( ($diaSemana%7) == 0 || ( ($diaSemana%7) == 6) || (isFestivo( $fecha )) || ( $fecha == $today ) )?(( $fecha == $today )?"table-warning":"table-active"):"table-primary")."'  >".(( $fecha == $today )?"<input type='text' name='focus' id='focus' readonly autofocus value='".$weekdays[ $diaSemana ]."' style='width:15px; background-color: transparent; border:0px none #fff' />":$weekdays[ $diaSemana ])."</td>";
                            /***********/
                            foreach( $proyectos as $proyecto ){
                                if( $proyecto->fecha_fin >= $fecha && $proyecto->fecha_inicio <= $fecha ){
                                    $array_td_proyecto[ $proyecto->id_proyecto ]->celda.="<td class='bg-danger'></td>";
                                }else{
                                    $array_td_proyecto[ $proyecto->id_proyecto ]->celda.="<td></td>";
                                }
                                foreach( $array_recursos as $cod_recurso => $recurso ){
                                    if( count( $array_programacion[$proyecto->id_proyecto][$cod_recurso] ) > 0 ){
                                        if( count($array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->asignacion ) >0 ){
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.="<td class='table-success p-1'>";
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.=generaComboProgramacion($array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->id, $array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->asignacion );
                                            /*
                                            
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<div class="dropdown">';
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<button class="btn btn-outline-dark p-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<small>'.$array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->asignacion."%</small>";
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='</button>';
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                                            foreach( $porcentajes as $porcentaje ){
                                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<a class="dropdown-item small '.(($array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->asignacion == $porcentaje )?"active":"").'" href="#" onclick="xajax_actualizaValor(\'programacion\',\''.$array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->id.'\',\'asignacion\',\''.$porcentaje.'\')">'.$porcentaje.'%</a>';
                                            }
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<div class="dropdown-divider"></div>';
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<a class="dropdown-item small" href="#" ><input type="number" onchange="xajax_actualizaValor(\'programacion\',\''.$array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->id.'\',\'asignacion\',this.value)" style="width:50px" value="'.$array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->asignacion.'"/>&nbsp;%</a>';
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<div class="dropdown-divider"></div>';
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.="<a class='dropdown-item small' href='#' onclick=\"crearDialogo('confirmacion','¿Está seguro de eliminar el registro seleccionado?','xajax_eliminar','\'programacion\'','\'".$array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->id."\'');\" >Eliminar</a>";
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='</div>';
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='</div>';
                                            */
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.="</td>";
                                        }else{
                                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.="<td class='p-1'></td>";
                                        }
                                    }
                                }
                            }
                            
                            
                            if( ($diaSemana%7) == 0 ){
                                $td_semana.="<td colspan='".$colsemana."' style='border-left:1px solid #ccc'>".date( "Y-m-d" , strtotime($fecha." +1 day"))."</td>";
                            }
                            $diaSemana++;
                            $contador++;
                        }
                        $firstdate = 1;
                        $mesact =date("Y-m-d",mktime(0,0,0,($m+1),1,$año));//+7;
        }
        
/*
        $añoini = date( 'Y' , strtotime(  $fechas[0]->fecha_inicio ) );
        $mesini = date( 'n' , strtotime(  $fechas[0]->fecha_inicio ) );
        $mesfin = date( 'n' , strtotime(  $fechas[0]->fecha_fin ) );
        $firstdate = date( 'j' , strtotime(  $fechas[0]->fecha_inicio ) );
        $lastdatepr = date( 'j' , strtotime(  $fechas[0]->fecha_fin ) );        
        
        $td_mes = "";
        $td_semana = "";
        $td_dias = "";
        $td_ldias = "";
        
        $diaSemana=date("w",mktime(0,0,0,$mesini,$firstdate,$añoini));//+7;
        $colsemana = 8-$diaSemana-(($diaSemana==0)?7:0);
        //Ciclo por meses
        $cont_m= 0;
        $array_td_proyecto = array();
        $array_td_programacion = array();
        for( $m = $mesini ; $m<=$mesfin ; $m++ ){
            $año = $añoini;
            # Obtenemos el dia de la semana del primer dia
            # Devuelve 0 para domingo, 6 para sabado
            $diaSemana=date("w",mktime(0,0,0,$m,1,$año));//+7;
            $lastdate = date("t",mktime(0,0,0,$m,1,$año));//+7;
            if( $m == $mesfin )
                $lastdate = $lastdatepr;
            $td_mes.="<td style='border-left:1px solid #ccc' colspan='".($lastdate-$firstdate+1)."'>".$meses[$m]."</td>";
            $fecha = $año*10000+$m*100+$firstdate;
            if( $cont_m == 0 )
                $td_semana.="<td colspan='".$colsemana."' style='border-left:1px solid #ccc'>".date( 'Y-m-d' , strtotime( $fecha ) )."</td>";
                $cont_m++;
                $colsemana = 7;
                
                //ciclo por Días
                for( $i = $firstdate ; $i<= $lastdate ; $i++ ){
                    $fecha = $año*10000+$m*100+$i;
                    $td_dias.="<td ".( ( $fecha == $today )?"class='table-warning'":"")." data-toggle='tooltip' data-placement='top' title='".date("Y-m-d" , strtotime( $fecha ) )."'>".$i."</td>";
                    //$td_ldias.="<th class='".(( ($diaSemana%7) == 0 || ( ($diaSemana%7) == 6) || (isFestivo( $fecha )) )?"table-active":"table-primary")."'>".$weekdays[ ($diaSemana%7) ]."</th>";
                    $diaSemana=date("w",mktime(0,0,0,$m,$i,$año));//+7;
                    $td_ldias.="<td class='".(( ($diaSemana%7) == 0 || ( ($diaSemana%7) == 6) || (isFestivo( $fecha )) || ( $fecha == $today ) )?(( $fecha == $today )?"table-warning":"table-active"):"table-primary")."' >".$weekdays[ $diaSemana ]."</td>";
                    
                    
                    if( $proyecto->fecha_fin >= $fecha && $proyecto->fecha_inicio <= $fecha ){
                        $array_td_proyecto[ $proyecto->id_proyecto ]->celda.="<td class='bg-danger'></td>";
                    }else{
                        $array_td_proyecto[ $proyecto->id_proyecto ]->celda.="<td></td>";
                    }
                    foreach( $array_recursos as $cod_recurso => $recurso ){
                        if( count( $array_programacion[$proyecto->id_proyecto][$cod_recurso] ) > 0 ){
                            if( count($array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->fecha ) >0 ){
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.="<td class='table-success p-1'>";
                                
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<div class="dropdown">';
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<button class="btn btn-outline-dark p-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.="<small>".$array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->fecha."%</small>";
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='</button>';
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                                foreach( $porcentajes as $porcentaje ){
                                    $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<a class="dropdown-item small '.(($array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->fecha == $porcentaje )?"active":"").'" href="#" onclick="xajax_actualizaValor(\'programacion\',\''.$array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->id.'\',\'asignacion\',\''.$porcentaje.'\')">'.$porcentaje.'%</a>';
                                }
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<div class="dropdown-divider"></div>';
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<a class="dropdown-item small" href="#" ><input type="number" onchange="xajax_actualizaValor(\'programacion\',\''.$array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->id.'\',\'asignacion\',this.value)" style="width:50px" value="'.$array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->fecha.'"/>&nbsp;%</a>';
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<div class="dropdown-divider"></div>';
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<a class="dropdown-item small" href="#" onclick="xajax_confirmarEliminar(\'programacion\',\''.$array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->id.'\')" >Eliminar</a>';
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='</div>';
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='</div>';
                                
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.=""; 
                                
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.="</td>";
                            }else{
                                $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.="<td></td>";
                            }
                        }
                    }
                    
                    if( ($diaSemana%7) == 0 ){
                        $td_semana.="<td colspan='".$colsemana."' style='border-left:1px solid #ccc'>".date( "Y-m-d" , strtotime($fecha." +1 day"))."</td>";
                    }
                    $diaSemana++;
                     
                }
                $firstdate = 1;
        }
        */
        
        $sal.='<div class="table-fixeder ">';
        $sal.='<table class="table table_seg table-responsive h6 small table-hover">';
        $sal.="<thead>";
        $sal.="<tr><td></td>".$td_mes."</tr>";
        $sal.="<tr><td></td>".$td_semana."</tr>";
        $sal.="<tr><td></td>".$td_ldias."</tr>";
        $sal.="<tr><td></td>".$td_dias."</tr>";
        $sal.="</thead>";
        $sal.="<tbody>";        
            
        foreach( $array_recursos as $cod_recurso => $val ){
            if( $array_td_programacion[$proyecto->id_proyecto][$cod_recurso]->celda !="" ){
                $sal.="<tr>";
                //Imprimer el nombre del recurso
                $sal.="<td style='white-space: nowrap; z-index:100' class='py-2' >".$val."</td>";
                $sal.=$array_td_programacion[$proyecto->id_proyecto][$cod_recurso]->celda;
                $sal.="</tr>";
            }
        }        
        $sal.="</tbody>";
        $sal.="</table>";
        $sal.="</div>";
        return $sal;
    }
    
    function generaComboProgramacion( $id , $asignacion , $cod_recurso = 0 , $fecha = 0 ){
        global $db;
        
        $programacion = $db->selectObject("programacion", "id_programacion=".$id );
        $tipo_actividad = $db->selectValue("tipo_actividad", "tipo_actividad","id_tipo_actividad=".$programacion->cod_tipo_actividad );
        $celda ='<div class="dropdown">';
        $celda.='<button class="btn btn-outline-dark p-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $celda.='<small title="'.$tipo_actividad." ".$programacion->comentario.'">'.$asignacion."%</small>";
        $celda.='</button>';
        $celda.='<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
        
        $celda.='<p class="dropdown-item small "><span style="font-weight:bold">'.$tipo_actividad.'</span></p>';
        $celda.='<div class="dropdown-divider"></div>';
        if( $programacion->comentario != "" ){
            $celda.='<p class="dropdown-item small ">'.wordwrap( $programacion->comentario , 30 , "<br/>" ).'</p>';        
            $celda.='<div class="dropdown-divider"></div>';
        }
                
        global $porcentajes;
        foreach( $porcentajes as $porcentaje ){
            $celda.='<a class="dropdown-item small '.(($asignacion == $porcentaje )?"active":"").'" href="javascript:void(0);" onclick="xajax_actualizaValorProgramacion(\'programacion\',\''.$id.'\',\'asignacion\',\''.$porcentaje.'\',\''.$cod_recurso.'\',\''.$fecha.'\')">'.$porcentaje.'%</a>';
        }
        $celda.='<div class="dropdown-divider"></div>';
        $celda.='<a class="dropdown-item small" href="javascript:void(0);" ><input type="number" onchange="xajax_actualizaValorProgramacion(\'programacion\',\''.$id.'\',\'asignacion\',this.value,\''.$cod_recurso.'\',\''.$fecha.'\')" style="width:50px" value="'.$asignacion.'"/>&nbsp;%</a>';
        $celda.='<div class="dropdown-divider"></div>';
        $celda.="<a class='dropdown-item small' href='javascript:void(0);' onclick=\"crearDialogo('confirmacion','¿Está seguro de eliminar el registro seleccionado?','xajax_eliminar','\'programacion\'','\'".$id."\'');\" >Eliminar</a>";
        $celda.='</div>';
        $celda.='</div>';
        return $celda;
    }
    
    function getCorreos( $from , $id="" ){
        global $db;
        $correos = array();
        if( $from == "proyecto" ){
            $proyecto = $db->selectObject("proyecto", "id_proyecto='".$id."'");
            $cor[] = $proyecto->cod_gerente;
            $cor[] = $proyecto->cod_lider;
            $recursos = $db->selectObjects("recurso", "id_recurso in ( ".implode( array_filter( $cor ),"," ).")");
            // return "id_recurso in ( ".$proyecto->cod_gerente.",".$proyecto->cod_lider.")";
        }elseif( $from == "administracion" ){
            $recursos = $db->selectObjects("recurso", "cod_area ='".AREA_ADMON."'");
        }elseif( $from == "comercial" ){
            $recursos = $db->selectObjects("recurso", "cod_area ='".AREA_COMERCIAL."'");
        }
        if( count( $recursos ) > 0 ){
            foreach ( $recursos as $recurso ){
                $correos[] = $recurso->correo;
            }
        }
        return $correos;
    }
    function accionesHito( $cod_estado , $id , $id_proyecto , $gerente ){
        global $estados_hito;
        global $user;
        $isOwner = 0;
        if( $gerente == $user->id_recurso || $user->cod_area == AREA_COMERCIAL || $user->cod_cargo == CARGOS_GO ){
            $isOwner = 1;
        }
        $isAdministrative = 0;
        if( $user->cod_area == AREA_ADMON ){
            $isAdministrative = 1;
        }
       
        $array_acciones = 
            array( "1"  => 
                array( 
                    "cod_estado_sgte" => "2",
                    "mensaje" =>"¿Está seguro de aprobar para facturar el hito seleccionado?",
                    "img" => IMG_PATH."approved.png",                    
                    "responsable" => ( $isOwner == 1 ),
                    "correo" =>"administracion"
                ),"2"  =>
                array(
                    "cod_estado_sgte" => "3",
                    "img" => IMG_PATH."invoice.png",
                    "mensaje" =>"¿Está seguro de cambiar el estado a facturado?",                    
                    "responsable" => ( $isAdministrative == 1 ),
                    "correo" => "proyecto"
                ),"-1"  =>
                array(
                    "cod_estado_sgte" => "4",
                    "img" => IMG_PATH."paid.png",
                    "mensaje" =>"¿Está seguro de cambiar a el estado a pagado?",                    
                    "responsable" => 1 , ////( $isAdministrative == 1 ),
                    "correo" => "proyecto"
                )
        );
        $salida ="";
        if( $array_acciones[ $cod_estado ]["responsable"] ){
            $salida ="<button type='button' class='btn p-1 btn-outline-secondary'>
                <img style='cursor:pointer;width:24px' src='".$array_acciones[ $cod_estado ]["img"]."'
                 title='".$estados_hito[  $array_acciones[ $cod_estado ]["cod_estado_sgte"] ]."'
            onclick=\"crearDialogo('confirmacion','".$array_acciones[ $cod_estado ]["mensaje"]."','xajax_cambiarEstadoHito','".$id."','".$array_acciones[ $cod_estado ]["cod_estado_sgte"]."','".$id_proyecto."','\'".$array_acciones[ $cod_estado ]["correo"]."\'');\" 
            /></button>";
        }        
        return $salida;
    }
    
    function segundos_tiempo($segundos , $pos = true) {
        $minutos = $segundos / 60;
        $horas = floor($minutos / 60);
        $minutos2 = $minutos % 60;
        $segundos_2 = $segundos % 60 % 60 % 60;
        if ($minutos2 < 10){
            $minutos2 = '0'.$minutos2;
        }
        if ($segundos_2 < 10){
            $segundos_2 = '0'.$segundos_2;
        }
        if ($segundos < 60) { /* segundos */
            $resultado = round($segundos).(($pos)?' Seg':'');
        }elseif($segundos > 60 && $segundos < 3600) { /* minutos */
            $resultado = '0:'.$minutos2.':' .$segundos_2.(($pos)?' Min':'');//
        } else { /* horas */
            $resultado = $horas . ':' . $minutos2. ':' . $segundos_2. (($pos)?' Horas':'');//  
        }
        return $resultado;
    }
    
    
    function extrae_tiempo($segundos, $extrae) {
        $minutos = $segundos / 60;
        $horas = floor($minutos / 60);
        $minutos2 = $minutos % 60;
        $segundos_2 = $segundos % 60 % 60 % 60;
        if ($minutos2 < 10){
            $minutos2 = '0'.$minutos2;
        }
        if ($segundos_2 < 10){
            $segundos_2 = '0'.$segundos_2;
        }
        
        if( $extrae == "hora" ){
            return $horas;
        }elseif( $extrae == "minutos"){
            return $minutos2;
        }else{
            if ($segundos < 60) {
                return $segundos;
            }else{
                return $segundos_2;
            }
        }        
    }
    function validaCierre( $fecha ){
        global $db;
        global $user;
        $cierre = $db->selectObject("cierre", "'".$fecha."' between semana_inicio and semana_fin");
        $date1 = new DateTime("now");
        $date2 = new DateTime( $cierre->fecha_cierre );
        if( $date2 < $date1 ){
            $permiso = $db->selectObject("cierre_permiso", "cod_responsable='".$user->id_recurso."' and estado_permiso = 1 and cod_cierre = '".$cierre->id_cierre."' and fecha >='".date('Y-m-d H:i:s')."'");            
            if( count( $permiso ) == 0){
                return false;
            }
        }
        return true;
    }
    function writeLog( $content ){
        global $user;
        $dir = dirname(__FILE__);
        $date = date("Ymd");
        $datetime = date("Ymd h:i:s a");
        $filename = $dir."/../logs/sql_".$date.".log";
        if ($fp = fopen($filename, 'a')) {
            fwrite($fp, $datetime." - ".$user->id_recurso."_".$user->abreviatura." - ".$content."\n" );
            fclose( $fp);
        }
    }
?>