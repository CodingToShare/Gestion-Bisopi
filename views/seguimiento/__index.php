<div class="h3" >Seguimiento</div>
<?php
    global $db;
    $today = date("Ymd");
    $array_estados = $_REQUEST["cod_estado"];
    
    if( count( $array_estados ) == 0 ){
        if( $_SESSION["SES_SEG_ESTADOS"] == "" ){
        $array_estados = $db->selectArraysBySql("select id_estado_proyecto from estado_proyecto where abreviatura = 'C'");
        $array_estados = $array_estados[0];
        }else{
            $array_estados =$_SESSION["SES_SEG_ESTADOS"];
        }
    }
    $filtro_estado = implode( "," , $array_estados );
    $mesant = (($_REQUEST["fecha_inicio"]=="" )? ( ( $_SESSION["SES_SEG_FECHA_INI"]=="" )?date("Y-m-d",strtotime($today."- 3 days")):$_SESSION["SES_SEG_FECHA_INI"]) :$_REQUEST["fecha_inicio"]);
    $mespos = (($_REQUEST["fecha_fin"]=="" )? ( ( $_SESSION["SES_SEG_FECHA_FIN"]=="" )?date("Y-m-d",strtotime($today."+ 2 weeks")): $_SESSION["SES_SEG_FECHA_FIN"]) :$_REQUEST["fecha_fin"]);
    
    $filtro_pais = (($_REQUEST["cod_pais"]=="" )? ( ( $_SESSION["SES_SEG_PAIS"]=="" )?"": $_SESSION["SES_SEG_PAIS"]) :(($_REQUEST["cod_pais"]=="-")?"":$_REQUEST["cod_pais"]));    
    $_SESSION["SES_SEG_FECHA_INI"] = $mesant;
    $_SESSION["SES_SEG_FECHA_FIN"] = $mespos;
    $_SESSION["SES_SEG_ESTADOS"] = $array_estados;
    $_SESSION["SES_SEG_PAIS"] = $filtro_pais;
      
?>
<form name="form1" id="form1" method="get"  action="index.php">
<input type="hidden" name="view" id="view" value="seguimiento" />
<div class="form-row">
    <div class="form-group col-md-2">
      <label for="fecha_inicio">Fecha Inicio</label>
      <input  autocomplete="off" type="text" placeholder="YYYY-MM-DD" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $mesant;?>">
    </div>
    <div class="form-group col-md-2">
      <label for="fecha_fin">Fecha Fin</label>
      <input autocomplete="off" type="text" placeholder="YYYY-MM-DD" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $mespos?>">
    </div>
    <div class="form-group col-md-2">
      <label for="cod_pais">País</label>
      <select class="custom-select" id="cod_pais" name="cod_pais">
    		<option value='-' selected>Todos</option>
        <?php
            $paises = $db->selectObjects("pais","estado = 1","pais");
            foreach( $paises as $pais ){
                echo "<option ".(( $pais->id_pais ==  $filtro_pais)?"selected":"")." value='".$pais->id_pais."'>".$pais->pais." (".$pais->abreviatura.")</option>";
            }
          	?>
    	</select>
    </div>
    <div class="form-group col-md-5">
    	<label for="cod_estado">Estado Proyectos</label><br/>
  		<?php
  		    $estados =$db->selectObjects("estado_proyecto", "estado =1");
            foreach( $estados as $estado ){
                echo '<div class="form-check form-check-inline">';
                echo "<input class='form-check-input' name='cod_estado[]' id='cod_estado_".$estado->id_estado_proyecto."' type='checkbox' ".(( array_search( $estado->id_estado_proyecto , $array_estados ) !== false )?"checked='checked'":"")." value='".$estado->id_estado_proyecto."'/><label class='form-check-label' for='cod_estado_".$estado->id_estado_proyecto."'>".$estado->estado_proyecto."</label>";
                echo "</div>";
            }
          	?>
    </div>
    <div class="form-group col-md-1">    	
    	<button id="save" name='save' type="submit" class="btn btn-success mt-4">Filtrar</button>
    </div>
</div>
</form>

<?php 
    $firstdate = 1;
    $lastdate = date("t");   
    $colores_recursos = array( "igual" => "bg-success" , "mayor"=> "bg-danger" , "menor" => "bg-warning" );
    
    
    $sql ="
                    SELECT
                    	id_proyecto
                    	, proyecto
                    	, date_format( fecha_inicio , '%Y%m%d') fecha_inicio
                    	, date_format( fecha_fin, '%Y%m%d') fecha_fin
                    	, pais
                    	, pa.abreviatura
                    	, p.cod_lider
                    	, r.recurso lider
                    	,ep.abreviatura
                    FROM proyecto p
                    inner join recurso r
                    on r.id_recurso = p.cod_lider
                    inner join pais pa
                    on pa.id_pais = p.cod_pais
                    inner join estado_proyecto ep
                    on ep.id_estado_proyecto = p.cod_estado_proyecto
                    where p.estado = 1 ".( ($filtro_estado!= "")?" and p.cod_estado_proyecto in (".$filtro_estado.")":"")."
                    ".(($filtro_pais!="")?" and pa.id_pais = '".$filtro_pais."'":"")."
                    order by pais, estado_proyecto, proyecto
                    
                   ";   
    $proyectos = $db->selectObjectsBySql($sql) ;
    
    $sql = "select min( fecha_inicio ) fecha_inicio , max( fecha_fin ) fecha_fin
                    from proyecto p
                    inner join estado_proyecto e
                    on e.id_estado_proyecto = p.cod_estado_proyecto
                    where p.estado = 1 and e.abreviatura not in ('T','S')";
    
    $fechas = $db->selectObjectsBySql($sql);
    
    $sql = "select
            	id_programacion
            	,p.cod_proyecto
                ,proyecto
            	,date_format( fecha_inicio , '%Y%m%d') fecha_inicio
            	,date_format( fecha_fin, '%Y%m%d') fecha_fin
            	,p.cod_recurso
                ,concat( p.cod_proyecto ,'_', p.cod_recurso) llave
            	,r.recurso
            	,date_format( p.fecha , '%Y%m%d') fecha
            	,p.asignacion asignacion
            from programacion p
            inner join recurso r
            on r.id_recurso = p.cod_recurso
            inner join proyecto pr
            on pr.id_proyecto = p.cod_proyecto
            inner join estado_proyecto ep
            on ep.id_estado_proyecto = pr.cod_estado_proyecto
            inner join pais pa
            on pa.id_pais = pr.cod_pais
            where p.estado = 1 ".( ($filtro_estado!= "")?" and pr.cod_estado_proyecto in (".$filtro_estado.")":"")."
            ".(($filtro_pais!="")?" and pa.id_pais = '".$filtro_pais."'":"")."
            and date_format( p.fecha , '%Y-%m-%d') between '".$mesant."' and '".$mespos."'              
            order by recurso";
    $programaciones = $db->selectObjectsBySql($sql);
    //echo $sql;
    /***** Inicio Asignar valores a los Arrays *****/
    $array_programacion = array();
    $array_programacion_recursos = array();
    $array_recursos  = array();
    foreach( $programaciones as $programacion ){        
        $array_programacion[ $programacion->cod_proyecto][$programacion->cod_recurso][ $programacion->fecha ]->asignacion= $programacion->asignacion;        
        $array_programacion[ $programacion->cod_proyecto][$programacion->cod_recurso][ $programacion->fecha ]->id= $programacion->id_programacion;
        $array_programacion[$programacion->cod_recurso][ $programacion->fecha ]+= $programacion->asignacion;
        $array_programacion_recursos[$programacion->cod_recurso][ $programacion->cod_proyecto][ $programacion->fecha ]->asignacion= $programacion->asignacion;
        $array_recursos[$programacion->cod_recurso] = $programacion->recurso;
    }
    $array_recursos = array_unique($array_recursos);
    //sort( $array_recursos);
    /*
    echo "<pre>";
    echo print_r( $array_programacion_recursos );
    echo "</pre>";
    */
    /***** Fin Asignar valores a los Arrays *****/
    
    $añoini = date( 'Y' , strtotime(  $fechas[0]->fecha_inicio ) );
    $mesini = date( 'n' , strtotime(  $fechas[0]->fecha_inicio ) );
    $mesfin = date( 'n' , strtotime(  $fechas[0]->fecha_fin ) );
    $firstdate = date( 'j' , strtotime(  $fechas[0]->fecha_inicio ) );
   
    $añoini = date( 'Y' , strtotime(  $mesant ) );
    $añofin = date( 'Y' , strtotime(  $mespos ) );
    
    $añomesini = date( 'Y' , strtotime(  $mesant ) );
    $añomesfin = date( 'Y' , strtotime(  $mespos ) );
    
    $mesini = date( 'n' , strtotime(  $mesant ) );
    $mesfin = date( 'n' , strtotime(  $mespos ) );
    $firstdate = date( 'j' , strtotime(  $mesant ) );
    
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
    $array_td_recursos = array();
    $array_td_programacion_rec = array();
    $contador = 0;
    
    $mesact = $mesant;
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
            $td_ldias.="<td class='text-center ".(( ($diaSemana%7) == 0 || ( ($diaSemana%7) == 6) || (isFestivo( $fecha )) || ( $fecha == $today ) )?(( $fecha == $today )?"table-warning":"table-active"):"table-primary")."' >".$weekdays[ $diaSemana ]."</td>";
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
                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='<a class="dropdown-item small" href="#" onclick="xajax_confirmarEliminar(\'programacion\',\''.$array_programacion[$proyecto->id_proyecto][$cod_recurso][$fecha]->id.'\')" >Eliminar</a>';
                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='</div>';
                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.='</div>';                                
                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.="</td>";
                        }else{
                            $array_td_programacion[ $proyecto->id_proyecto ][$cod_recurso]->celda.="<td class='p-1'></td>";
                        }
                    }
                }
            }
            /*************/
            foreach( $array_recursos as $cod_recurso => $recurso ){
                if( count( $array_programacion[$cod_recurso] ) > 0 ){
                    if( count($array_programacion[$cod_recurso][$fecha] ) >0 ){
                        $color = (( $array_programacion[$cod_recurso][$fecha] == 100 )?"igual" : (($array_programacion[$cod_recurso][$fecha]<100)?"menor":"mayor") );
                        $array_td_recursos[$cod_recurso]->celda.="<td class='".$colores_recursos[ $color ]." h6 small'>". $array_programacion[$cod_recurso][$fecha]."%</td>";
                    }else{                            
                        $array_td_recursos[$cod_recurso]->celda.="<td></td>";
                    }
                }
                foreach( $proyectos as $proyecto ){
                    if( count( $array_programacion_recursos[$cod_recurso][$proyecto->id_proyecto] ) > 0 ){
                        if( count($array_programacion_recursos[$cod_recurso][$proyecto->id_proyecto][$fecha]->asignacion ) >0 ){
                            $array_td_programacion_rec[$cod_recurso][ $proyecto->id_proyecto ]->celda.="<td class='table-secondary'>";                            
                            $array_td_programacion_rec[$cod_recurso][ $proyecto->id_proyecto ]->celda.=''.$array_programacion_recursos[$cod_recurso][$proyecto->id_proyecto][$fecha]->asignacion."%";
                            $array_td_programacion_rec[$cod_recurso][ $proyecto->id_proyecto ]->celda.="</td>";
                        }else{
                            $array_td_programacion_rec[$cod_recurso][ $proyecto->id_proyecto ]->celda.="<td class='p-1'></td>";
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
    
        
    echo '<div class="table-fixeder border border-secondary rounded px-3 pt-3">';
    /** Inicio Tabla ***/
    echo '<table class="table table_seg table-responsive h6 small table-hover">';
    /** Inicio Cabeceras de la Tabla ***/    
    echo "<thead>";
    echo "<tr><td class='bg-dark text-white font-weight-bold' colspan='".($contador+1)."'>Asignación por proyecto</td></tr>";
    echo "<tr><td></td>".$td_mes."</tr>";
    echo "<tr><td></td>".$td_semana."</tr>";
    echo "<tr><td></td>".$td_ldias."</tr>";
    echo "<tr><td></td>".$td_dias."</tr>";
    echo "</thead>";    
    /** Fin Cabeceras de la Tabla ***/
    echo "<tbody>";
    /** Inicio Zona Proyectos > Recurso ***/    
    foreach( $proyectos as $proyecto ){
        echo "<tr>";
        echo "<td class='text-nowrap p-2' >";        
        echo "<a href='index.php?view=proyecto&action=programacion&id=".$proyecto->id_proyecto."'>".$proyecto->proyecto."</a>";
        echo "<a href='index.php?view=proyecto&action=agregar&id=".$proyecto->id_proyecto."'><img src='".IMG_EDIT."'  style='width:16px' title='Editar' /></a>";
        echo "</td>";
        echo $array_td_proyecto[$proyecto->id_proyecto]->celda;
        echo "</tr>";
        foreach( $array_recursos as $cod_recurso => $val ){
            if( $array_td_programacion[$proyecto->id_proyecto][$cod_recurso]->celda !="" ){
                echo "<tr>";
                echo "<td style='white-space: nowrap;z-index:100' class='py-2 px-5' >".$val."</td>";
                echo $array_td_programacion[$proyecto->id_proyecto][$cod_recurso]->celda;
                echo "</tr>";
            }
        }
    }
    /** Fin Zona Proyectos > Recurso ***/
    echo '</table>';    
    /** Inicio Zona Recursos > Proyecto ***/
    /** Inicio Tabla ***/
    echo '<table class="table table_seg table-responsive h6 small table-hover">';
    /** Inicio Cabeceras de la Tabla ***/    
    echo "<tr><td class='bg-dark text-white font-weight-bold' colspan='".($contador+1)."'>Asignación por Recurso</td></tr>";
    echo "<tr><td></td>".$td_mes."</tr>";
    echo "<tr><td></td>".$td_ldias."</tr>";
    echo "<tr><td></td>".$td_dias."</tr>";
    foreach( $array_recursos as $cod_recurso => $val ){
        echo "<tr>";
        echo "<td style='white-space: nowrap;' class='' >".$val."</td>";
        echo $array_td_recursos[$cod_recurso]->celda;
        echo "</tr>";
        foreach( $proyectos as $proyecto){
            echo "<tr>";
            if( $array_td_programacion_rec[$cod_recurso][$proyecto->id_proyecto]->celda !="" ){                
                echo "<td style='white-space: nowrap;z-index:100' class='py-2 px-5' >".$proyecto->proyecto."</td>";
                echo $array_td_programacion_rec[$cod_recurso][$proyecto->id_proyecto]->celda;
                echo "</tr>";
            }
        }
    }
    /** Fin Zona Recursos > Proyecto ***/
    echo '</table>';
      
    /** Inicio Zona Total Recursos ***/
    /** Inicio Tabla ***/
    /*echo '<table class="table table_seg table-responsive h6 small table-hover">';
    echo "<tr><td class='bg-dark text-white font-weight-bold'>Todos los recursos</td><td class='bg-dark' colspan='".$contador."'></td></tr>";
    echo "<tr><td></td>".$td_mes."</tr>";
    echo "<tr><td></td>".$td_ldias."</tr>";
    echo "<tr><td></td>".$td_dias."</tr>";
    foreach( $array_recursos as $cod_recurso => $val ){        
        echo "<tr>";
        echo "<td style='white-space: nowrap;' class='' >".$val."</td>";
        echo $array_td_recursos[$cod_recurso]->celda;
        echo "</tr>";
    }    
    echo "</tbody>";
    echo "</table>";*/
    /** Fin Tabla ***/
    echo "</div>";
?>
<script>
    $( function() {
    	$( "#fecha_inicio" ).datepicker({
    		dateFormat: "yy-mm-dd",
    		monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],    		
    		firstDay: 1,    		  	
    		dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"]
    	});
    } );
    $( function() {
    	$( "#fecha_fin" ).datepicker({
    		dateFormat: "yy-mm-dd",
    		monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],    		
    		firstDay: 1,    		
    		dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"]
    	});
    } );
</script>