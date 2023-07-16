<div class="h3" >Seguimiento Recursos</div>
<?php
    global $db;
    $today = date("Ymd");
    $array_estados = $_REQUEST["cod_estado"];
    $array_areas = $_REQUEST["cod_area"];
    /*
    if( count( $array_estados ) == 0 ){
        if( $_SESSION["SES_SEG_ESTADOS"] == "" ){
        $array_estados = $db->selectArraysBySql("select id_estado_proyecto from estado_proyecto where abreviatura = 'C'");
        $array_estados = $array_estados[0];
        }else{
            $array_estados =$_SESSION["SES_SEG_ESTADOS"];
        }
    }*/    
    if( count( $array_areas ) == 0 ){
        $array_areas = array( "2" );        
    }
    $filtro_estado = implode( "," , $array_estados );
    $filtro_area = implode( "," , $array_areas );
    $mesant = (($_REQUEST["fecha_inicio"]=="" )? ( ( $_SESSION["SES_SEG_FECHA_INI"]=="" )?date("Y-m-d",strtotime($today."- 3 days")):$_SESSION["SES_SEG_FECHA_INI"]) :$_REQUEST["fecha_inicio"]);
    $mespos = (($_REQUEST["fecha_fin"]=="" )? ( ( $_SESSION["SES_SEG_FECHA_FIN"]=="" )?date("Y-m-d",strtotime($today."+ 2 weeks")): $_SESSION["SES_SEG_FECHA_FIN"]) :$_REQUEST["fecha_fin"]);    
    $filtro_pais = (($_REQUEST["cod_pais"]=="" )? ( ( $_SESSION["SES_SEG_PAIS"]=="" )?"": $_SESSION["SES_SEG_PAIS"]) :(($_REQUEST["cod_pais"]=="-")?"":$_REQUEST["cod_pais"]));
    $filtro_recurso = (($_REQUEST["cod_recurso"]=="" )? ( ( $_SESSION["SES_SEG_RECURSO"]=="" )?"": $_SESSION["SES_SEG_RECURSO"]) :(($_REQUEST["cod_recurso"]=="-")?"":$_REQUEST["cod_recurso"]));
    $_SESSION["SES_SEG_FECHA_INI"] = $mesant;
    $_SESSION["SES_SEG_FECHA_FIN"] = $mespos;
    $_SESSION["SES_SEG_ESTADOS"] = $array_estados;
    $_SESSION["SES_SEG_AREAS"] = $array_areas;
    $_SESSION["SES_SEG_PAIS"] = $filtro_pais;
    $_SESSION["SES_SEG_RECURSO"] = $filtro_recurso;
    /*echo "<pre>";
    print_r( $_SESSION );
    echo "</pre>";*/
    $recursos = $db->selectObjects("recurso","estado = 1 and cod_estado_recurso = 'A' ", "recurso");
?>


<form name="form1" id="form1" method="post"  action="index.php">
    <input type="hidden" name="view" id="view" value="seguimiento_recurso" />
    <div class="form-row">
        <div class="form-group col-md-2">
          <label for="fecha_inicio">Fecha Inicio</label>
          <input  autocomplete="off" type="text" placeholder="YYYY-MM-DD" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $mesant;?>">
        </div>
        <div class="form-group col-md-2">
          <label for="fecha_fin">Fecha Fin</label>
          <input autocomplete="off" type="text" placeholder="YYYY-MM-DD" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $mespos?>">
        </div>
        <div class="form-group col-md-1">
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
        <div class="form-group col-md-2">
        	<label for="cod_estado">Estado Proyectos</label><br/>
        	<select id="cod_estado" name="cod_estado[]" multiple="multiple">
        	<?php $estados =$db->selectObjects("estado_proyecto", "estado =1");
                foreach( $estados as $estado ){
                    echo '<option value="'.$estado->id_estado_proyecto.'" '.(( array_search( $estado->id_estado_proyecto , $array_estados ) !== false )?"selected":"").'>'.$estado->estado_proyecto.'</option>';
                }
            ?>
            </select>
        </div>
        <div class="form-group col-md-1">
        	<label for="cod_area">Área</label><br/>
        	<select id="cod_area" name="cod_area[]" multiple="multiple">
        	<?php $areas =$db->selectObjects("area", "estado =1");
        	   foreach( $areas as $area ){
        	       echo '<option value="'.$area->id_area.'" '.(( array_search( $area->id_area, $array_areas ) !== false )?"selected":"").'>'.$area->area.'</option>';
                }
            ?>
            </select>
        </div>
        <div class="form-group col-md-2">
          <label for="cod_recurso">Recursos</label>
          <select class="custom-select" id="cod_recurso" name="cod_recurso">
        		<option value='-' selected>Todos</option>
            <?php                
                foreach( $recursos as $recurso ){
                    echo "<option ".(( $recurso->id_recurso ==  $filtro_recurso )?"selected":"")." value='".$recurso->id_recurso."'>".$recurso->recurso."</option>";
                }
              	?>
        	</select>
        </div>
        <div class="form-group col-md-2">    	
        	<button id="save" name='save' type="submit" class="btn btn-success mt-4">Filtrar</button>
        	<button id="exportar" name='exportar' type="button" class="btn btn-secondary mt-4">Exportar</button>
        </div>
    </div>
</form>
<?php
    $firstdate = 1;
    $lastdate = date("t");
    
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
                        left join recurso r
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
                        where p.estado = 1 ".( ($filtro_estado!= "")?" and p.cod_estado_proyecto in (".$filtro_estado.")":"")."";
    
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
                on r.id_recurso = p.cod_recurso and r.cod_estado_recurso ='A'
                inner join proyecto pr
                on pr.id_proyecto = p.cod_proyecto
                inner join estado_proyecto ep
                on ep.id_estado_proyecto = pr.cod_estado_proyecto
                inner join pais pa
                on pa.id_pais = pr.cod_pais
                where p.estado = 1 ".( ($filtro_estado!= "")?" and pr.cod_estado_proyecto in (".$filtro_estado.")":"")."
                ".(($filtro_pais!="")?" and pa.id_pais = '".$filtro_pais."'":"")."
                ".(($filtro_recurso!="")?" and r.id_recurso= '".$filtro_recurso."'":"")."
                ".( ($filtro_area!= "")?" and r.cod_area in (".$filtro_area.")":"")."
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
        $array_programacion_recursos[$programacion->cod_recurso][ $programacion->cod_proyecto][ $programacion->fecha ]->id= $programacion->id_programacion;
        //$array_recursos[$programacion->cod_recurso] = $programacion->recurso;
    }
    $recursos = $db->selectObjects("recurso","estado = 1 and cod_estado_recurso = 'A' ".(($filtro_recurso!="")?" and id_recurso= '".$filtro_recurso."'":"").( ($filtro_area!= "")?" and cod_area in (".$filtro_area.")":"") , "recurso");
    foreach( $recursos as $recurso ){
        $array_recursos[$recurso->id_recurso] = $recurso->recurso;
    }
    $array_recursos = array_unique($array_recursos);
    //sort( $array_recursos);
    /*
     echo "<pre>";
     echo print_r( $array_programacion );
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
        
        
        if( $m == date("m", strtotime( $mespos ) ) ){
            $lastdate = date( 'j' , strtotime(  $mespos ) );
        }else{
            $lastdate = date("t",mktime(0,0,0,$m,1,$año));//+7;
        }
        $td_mes.="<th style='border-left:1px solid #ccc' colspan='".($lastdate-$firstdate+1)."'>".$año." ".$meses[$m]."</th>";
        $fecha = $año*10000+$m*100+$firstdate;
        if( $cont_m == 0 ){
            $td_semana.="<th colspan='".$colsemana."' style='border-left:1px solid #ccc'>".date( 'Y-m-d' , strtotime( $fecha ) )."</th>";
        }
        $cont_m++;
        $colsemana = 7;
                    
        /*ciclo por Días*/
        for( $i = $firstdate ; $i<= $lastdate ; $i++ ){
            $fecha = $año*10000+$m*100+$i;
            $td_dias.="<th class='text-center ".( ( $fecha == $today )?"table-warning":"")."' data-toggle='tooltip' data-placement='top' title='".date("Y-m-d" , strtotime( $fecha ) )."'>".$i."</th>";
            //$td_ldias.="<th class='".(( ($diaSemana%7) == 0 || ( ($diaSemana%7) == 6) || (isFestivo( $fecha )) )?"table-active":"table-primary")."'>".$weekdays[ ($diaSemana%7) ]."</th>";
            $diaSemana=date("w",mktime(0,0,0,$m,$i,$año));//+7;
            $td_ldias.="<th class='text-center ".(( ($diaSemana%7) == 0 || ( ($diaSemana%7) == 6) || (isFestivo( $fecha )) || ( $fecha == $today ) )?(( $fecha == $today )?"table-warning":"table-active"):"table-primary")."' >".$weekdays[ $diaSemana ]."</th>";
            
            foreach( $array_recursos as $cod_recurso => $recurso ){
                if( count( $array_programacion[$cod_recurso] ) > 0 ){
                    if( count($array_programacion[$cod_recurso][$fecha] ) >0 ){
                        $color = (( $array_programacion[$cod_recurso][$fecha] == 100 )?"igual" : (($array_programacion[$cod_recurso][$fecha]<100)?"menor":"mayor") );
                        $array_td_recursos[$cod_recurso]->celda.="<td class='".$colores_recursos[ $color ]." h6 small' id='td_rec_fec_".$cod_recurso."_".$fecha."'>". $array_programacion[$cod_recurso][$fecha]."%</td>";
                    }else{
                        $array_td_recursos[$cod_recurso]->celda.="<td></td>";
                    }
                }
                foreach( $proyectos as $proyecto ){
                    if( count( $array_programacion_recursos[$cod_recurso][$proyecto->id_proyecto] ) > 0 ){
                        if( count($array_programacion_recursos[$cod_recurso][$proyecto->id_proyecto][$fecha]->asignacion ) >0 ){
                            $array_td_programacion_rec[$cod_recurso][ $proyecto->id_proyecto ]->celda.="<td id='td_ass_".$array_programacion_recursos[$cod_recurso][$proyecto->id_proyecto][$fecha]->id."' class='table-secondary'>";
                            // $array_td_programacion_rec[$cod_recurso][ $proyecto->id_proyecto ]->celda.=''.$array_programacion_recursos[$cod_recurso][$proyecto->id_proyecto][$fecha]->asignacion."%";
                            $array_td_programacion_rec[$cod_recurso][ $proyecto->id_proyecto ]->celda.=generaComboProgramacion($array_programacion_recursos[$cod_recurso][$proyecto->id_proyecto][$fecha]->id,$array_programacion_recursos[$cod_recurso][$proyecto->id_proyecto][$fecha]->asignacion , $cod_recurso , $fecha);
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
?>
<table data-toggle="table" id="table" >
<?php 
      echo "<thead>";
    //echo "<tr><th colspan='".($contador+1)."'>Asignación por Recurso</th></tr>";
    echo "<tr><th style='z-index:100;' class='align-middle' rowspan='3'>Asignación por Recurso</th>".$td_mes."</tr>";
    //echo "<tr><th></th>".$td_ldias."</tr>";
    //echo "<tr><th></th>".$td_dias."</tr>";
    echo "<tr>".$td_ldias."</tr>";
    echo "<tr>".$td_dias."</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach( $array_recursos as $cod_recurso => $val ){
        echo "<tr>";
        echo "<td style=' white-space: nowrap;' class='bg-secondary bg-gradient text-white' >";
        echo '<div id="div_recursos_prog" class="collapsed" data-toggle="collapse" data-target="#rec_'.$cod_recurso.'"><i class="bi bi-plus"></i>&nbsp;';
        echo $val;
        echo '</div>';
        //echo '<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#rec_'.$cod_recurso.'" aria-expanded="false" aria-controls="collapseExample">Mostrar Ocultar</button>';
        echo "</td>";
        echo $array_td_recursos[$cod_recurso]->celda;
        
        echo "</tr>";
        foreach( $proyectos as $proyecto){            
            if( $array_td_programacion_rec[$cod_recurso][$proyecto->id_proyecto]->celda !="" ){
                echo "<tr id='rec_".$cod_recurso."' class='collapse'>";
                //echo "<td style='white-space: nowrap;z-index:100' class='py-2 px-5' >".$proyecto->proyecto."</td>";
                echo "<td style='z-index:90' class='py-2 px-5 bg-light' >".$proyecto->proyecto."&nbsp;<a title='Programación' href='index.php?view=proyecto&action=programacion&id=".$proyecto->id_proyecto."'><i class='bi bi-calendar3'></i></a></td>";
                echo $array_td_programacion_rec[$cod_recurso][$proyecto->id_proyecto]->celda;
                echo "</tr>";
            }
        }
    }
    echo "</tbody>";
    /** Fin Zona Recursos > Proyecto ***/    
?>
</table>
<br /><br />   
<?php 

?>

    <script>
     $('#table').bootstrapTable('destroy').bootstrapTable({      
      showFullscreen: false,
      search: false,
      stickyHeader: true,      
      stickyHeaderOffsetLeft: parseInt($('body').css('padding-left'), 10),
      stickyHeaderOffsetRight: parseInt($('body').css('padding-right'), 10)
    });

	
	$(function(){
		$('#cod_estado').multiselect({
			buttonWidth: '100%',
			includeSelectAllOption: true
		});
	});
	
	$(function(){
		$('#cod_area').multiselect({
			buttonWidth: '100%',
			includeSelectAllOption: true
		});
	});
	
    $( "#exportar" ).click(function() {
      	window.open( 'exportar.php?view=<?php echo $_view;?>&fechaini=<?php echo $mesant;?>&fechafin=<?php echo $mespos;?>' );      	
    	return false;
    });
</script>
    
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