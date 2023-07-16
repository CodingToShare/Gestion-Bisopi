<div class="h3" >Reporte Horas</div>
<?php

$today = date("Ymd");
$mesant = (($_REQUEST["fecha_inicio"]=="" )? date("Y-m-d",strtotime($today."last week")):$_REQUEST["fecha_inicio"]);
$mespos = (($_REQUEST["fecha_fin"]=="" )? date("Y-m-d",strtotime($today."today")):$_REQUEST["fecha_fin"]);

$d = date("d", strtotime( $mesant ) );
$año = date("Y", strtotime( $mesant ) );
$m = date("n", strtotime( $mesant ) );
//echo $meses_abr[ $m ]." ".$d;
$mespos =date("Ymd",mktime(0,0,0,$m,($d+6),$año));


$filtro_recurso = (($_REQUEST["cod_responsable"] != "" )? $_REQUEST["cod_responsable"]:$user->id_recurso );
//echo $filtro_recurso;
$sql = "select id_proyecto_tarea
                , pais
                , id_proyecto
                , proyecto                
                , gt.grupo_tarea
                ,proyecto_tarea
                ,pt.cod_responsable
                ,r.recurso
                , pt.tiempo_estimado
                , pt.tiempo_ejecutado tiempo_ejecutado_total
                , sum( ptr.tiempo_ejecutado ) tiempo_ejecutado
                , ptr.fecha_registro
                , ptr.comentario
                , et.estado_tarea
                , DATE_FORMAT( ptr.fecha_registro , '%Y%m%d' ) fecha                
            from proyecto_tarea pt
            inner join proyecto_tarea_registro ptr on ptr.cod_proyecto_tarea = pt.id_proyecto_tarea
            inner join proyecto p on p.id_proyecto =  pt.cod_proyecto
            inner join pais pa on pa.id_pais = p.cod_pais
            inner join estado_tarea et on et.id_estado_tarea = pt.cod_estado_tarea
            inner join grupo_tarea gt on gt.id_grupo_tarea = pt.cod_grupo_tarea
            inner join recurso r on r.id_recurso =  pt.cod_responsable
            where pt.estado = 1 ".(($filtro_recurso=="-")?"": "and pt.cod_responsable = ".$filtro_recurso)." -- and p.cod_estado_proyecto <> 3
            and ptr.fecha_registro  between '".$mesant."' and '".$mespos."'
            group by 
                id_proyecto_tarea
                , pais
                , id_proyecto
                , proyecto                
                , gt.grupo_tarea
                ,proyecto_tarea
                ,r.recurso
                , pt.tiempo_estimado
                , pt.tiempo_ejecutado                 
                , ptr.fecha_registro
                , ptr.comentario
                , et.estado_tarea
            order by responsable ,proyecto_tarea
        ";
    //echo $sql;
    $proyectos = $db->selectObjectsBySql($sql) ;
    //echo $db->error();
?>
<form name="form1" id="form1" method="post"  action="index.php?view=tarea&action=reporte_fecha">
    <input type="hidden" name="view" id="view" value="tarea" />    
    <input type="hidden" name="action" id="action" value="reporte_fecha" />
    <div class="form-row">
        <div class="form-group col-md-2">
          <label for="fecha_inicio">Fecha Inicio</label>
          <input  autocomplete="off" type="text" placeholder="YYYY-MM-DD" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $mesant;?>">
        </div>
        <?php if( array_search( $user->cod_cargo , explode( ',','1,3,5,6,11' ) ) !== false ){?>
        <div class="form-group col-md-5">
        	<label for="cod_responsable">Responsable</label><br/>
        	<select  class="custom-select"  id="cod_responsable" name="cod_responsable" >
        		<option value="-">Todos</option>
        	<?php $recursos =$db->selectObjects("recurso", "estado =1 and cod_estado_recurso = 'A'","recurso");
        	   foreach( $recursos as $recurso ){
        	       echo '<option value="'.$recurso->id_recurso.'" '.(( $recurso->id_recurso == $filtro_recurso )?"selected":"").'>'.$recurso->recurso.'</option>';
                }
            ?>
            </select>
        </div>
        <?php } ?>
        <div class="form-group col-md-1">    	
        	<button id="save" name='save' type="submit" class="btn btn-success mt-4">Filtrar</button>
        </div>
    </div>
</form>    
<?php
    $cabecera = '<thead class="thead-dark">
        <tr style="text-align:center;">
        <th class="p-1" scope="col"></th>'
        .(( $filtro_recurso == "-")?'<th class="p-1" scope="col">Responsable</th>':"")
        .'<th class="p-1" scope="col">Tarea</th>';
    $mesact = date( 'Ymd' , strtotime( $mesant ) );
    $array_days = array();
    for( $i = 1 ; $i<=7; $i++ ){
        $d = date("d", strtotime( $mesact ) );
        $año = date("Y", strtotime( $mesact ) );
        $m = date("n", strtotime( $mesact ) );        
        //echo $meses_abr[ $m ]." ".$d;
        $cabecera.='<th class="p-1" scope="col" style="text-align:right;">'.$meses_abr[ $m ]." ".$d.'</th>'; 
        $array_days[$mesact]= "dia_".$i;
        $mesact =date("Ymd",mktime(0,0,0,$m,($d+1),$año));
    }
    $cabecera.='<th class="p-1" scope="col">Total</th>';
    $cabecera.="</tr></thead>";
    /*
    echo "<pre>";
    print_r( $proyectos );
    echo "</pre>";*/
    
    $array_data = array();
    $array_total = array();
    foreach( $proyectos as $proyecto ){
        //$array_data[ $proyecto->recurso ]
        $txt_fecha =$array_days[$proyecto->fecha];        
        $array_data[$proyecto->id_proyecto_tarea]->fecha = $proyecto->fecha;
        $array_data[$proyecto->id_proyecto_tarea]->$txt_fecha+=$proyecto->tiempo_ejecutado;
        $array_data[$proyecto->id_proyecto_tarea]->total+=$proyecto->tiempo_ejecutado;
        $array_data[$proyecto->id_proyecto_tarea]->tarea = $proyecto->proyecto_tarea;
        $array_data[$proyecto->id_proyecto_tarea]->cod_responsable = $proyecto->cod_responsable;
        $array_data[$proyecto->id_proyecto_tarea]->responsable = $proyecto->recurso;
        $array_data[$proyecto->id_proyecto_tarea]->proyecto = $proyecto->proyecto;
        $array_total[$proyecto->cod_responsable]->responsable=$proyecto->recurso;
        $array_total[$proyecto->cod_responsable]->$txt_fecha+=$proyecto->tiempo_ejecutado;;
        $array_total[$proyecto->cod_responsable]->total+=$proyecto->tiempo_ejecutado;;
    }
    usort($array_data,function($first,$second){
        return strtolower($first->responsable) > strtolower($second->responsable);
    });
    /*
    echo "<pre>";
    print_r( $array_total); 
    echo "</pre>";
    */
    /*echo date("Y-m-d",strtotime($today."this week"));
    echo date("Y-m-d",strtotime($today."this week + 7"));
    
    $mon = $fri = new DateTime( $today );
    $mon->modify('Last Monday');
    var_dump($mon);
    $fri->modify('Next Sunday');*/
    
    //echo $mesact;
    $d = date("d", strtotime( $mesact ) );
    $año = date("Y", strtotime( $mesact ) );
    $m = date("n", strtotime( $mesact ) );
    ?>
    <table class="table table-striped table-hover" style="font-size:13px;width:800px">
    <?php 
        echo $cabecera;
        $contador = 1;
        $txt_responsable =0;
    foreach( $array_data as $data  ){  
        if( $txt_responsable != $data->cod_responsable && $txt_responsable != 0 ){?>
    	<tr  class="thead-dark">
    		<th class="p-1" scope="row" colspan="<?php echo (( $filtro_recurso == "-")?"3":"1");?>">Total</th>
    		<th class="p-1" style='text-align:right;width:60px;'><?php echo (( $array_total[$txt_responsable]->dia_1 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_1 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;width:60px;'><?php echo (( $array_total[$txt_responsable]->dia_2 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_2 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;width:60px;'><?php echo (( $array_total[$txt_responsable]->dia_3 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_3 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;width:60px;'><?php echo (( $array_total[$txt_responsable]->dia_4 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_4 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;width:60px;'><?php echo (( $array_total[$txt_responsable]->dia_5 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_5 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;width:60px;'><?php echo (( $array_total[$txt_responsable]->dia_6 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_6 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;width:60px;'><?php echo (( $array_total[$txt_responsable]->dia_7 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_7 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;width:60px;'><?php echo (( $array_total[$txt_responsable]->total != 0) ? segundos_tiempo( $array_total[$txt_responsable]->total , false ):'');?></th>
		</tr>
    <?php
    echo "<tr><th colspan='11'>&nbsp;</th></tr>";
    echo $cabecera;
    $contador=1;
    		  }
    ?>
           <tr>
        		<th class="p-1" scope="row"><?php echo ( $contador++);?></th>
        		<?php if( $filtro_recurso == "-"){?>
        		<td class="p-1"><?php echo $data->responsable;?></td>
        		<?php }?>
        		<td class="p-1"><span title='Proyecto: <?php echo $data->proyecto;?>'><?php echo $data->tarea;?></span></td>
        		<td class="p-1" style='text-align:right;'><?php echo (( $data->dia_1 != 0) ? segundos_tiempo( $data->dia_1 , false ):'');?></td>
        		<td class="p-1" style='text-align:right;'><?php echo (( $data->dia_2 != 0) ? segundos_tiempo( $data->dia_2 , false ):'');?></td>
        		<td class="p-1" style='text-align:right;'><?php echo (( $data->dia_3 != 0) ? segundos_tiempo( $data->dia_3 , false ):'');?></td>
        		<td class="p-1" style='text-align:right;'><?php echo (( $data->dia_4 != 0) ? segundos_tiempo( $data->dia_4 , false ):'');?></td>
        		<td class="p-1" style='text-align:right;'><?php echo (( $data->dia_5 != 0) ? segundos_tiempo( $data->dia_5 , false ):'');?></td>
        		<td class="p-1" style='text-align:right;'><?php echo (( $data->dia_6 != 0) ? segundos_tiempo( $data->dia_6 , false ):'');?></td>
        		<td class="p-1" style='text-align:right;'><?php echo (( $data->dia_7 != 0) ? segundos_tiempo( $data->dia_7 , false ):'');?></td>
        		<td class="p-1" style='text-align:right;'><?php echo (( $data->total != 0) ? segundos_tiempo( $data->total , false ):'');?></td>
    		</tr>
    		<?php 
            $txt_responsable= $data->cod_responsable;
        } 
    ?>
   	 	<tr  class="thead-dark">
    		<th class="p-1" scope="row" colspan="<?php echo (( $filtro_recurso == "-")?"3":"2");?>">Total</th>
    		<th class="p-1" style='text-align:right;' ><?php echo (( $array_total[$txt_responsable]->dia_1 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_1 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;' ><?php echo (( $array_total[$txt_responsable]->dia_2 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_2 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;'><?php echo (( $array_total[$txt_responsable]->dia_3 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_3 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;' ><?php echo (( $array_total[$txt_responsable]->dia_4 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_4 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;' ><?php echo (( $array_total[$txt_responsable]->dia_5 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_5 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;' ><?php echo (( $array_total[$txt_responsable]->dia_6 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_6 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;' ><?php echo (( $array_total[$txt_responsable]->dia_7 != 0) ? segundos_tiempo( $array_total[$txt_responsable]->dia_7 , false ):'');?></th>
    		<th class="p-1" style='text-align:right;' ><?php echo (( $array_total[$txt_responsable]->total != 0) ? segundos_tiempo( $array_total[$txt_responsable]->total , false ):'');?></th>
		</tr>
    </table>
    <?php /*
    $mesact = $mesant;
    $firstdate = date( 'j' , strtotime(  $mesant ) );
    while( strtotime( $mesact ) <= strtotime( $mespos ) ){
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
        //echo $mesact;
        
        for( $i = $firstdate ; $i<= $lastdate ; $i++ ){
            $fecha = $año*10000+$m*100+$i;
            $diaSemana=date("w",mktime(0,0,0,$m,$i,$año));//+7;
            echo "*".$i."*";
            $diaSemana++;
            
        }
        echo $mesact;
        $firstdate = 1;
        $mesact =date("Y-m-d",mktime(0,0,0,($m+1),1,$año));//+7;
    }
*/
 ?>
<script>
	$(function(){
		$('#cod_estado').multiselect({
			buttonWidth: '100%',
			includeSelectAllOption: true
		});
	});
	$(function(){
		$('#cod_estado_hito').multiselect({
			buttonWidth: '100%',
			includeSelectAllOption: true
		});
	});

    $( function() {
    	$( "#fecha_inicio" ).datepicker({
    		dateFormat: "yy-mm-dd",
    		monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
    		monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],    		
    		firstDay: 1,
    		changeMonth: false,
    		changeYear: false,    		
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
    
$( "#exportar" ).click(function() {
	// crearDialogo('confirmacion','¿Está seguro de exportar los datos de facturación?','window.location.href=\'exportar.php?view=<?php echo $_view;?>"\';location.reload();');
  	window.open( "exportar.php?view=<?php echo $_view."&output=xls&fecha_inicio=".$_REQUEST["fecha_inicio"]."&fecha_fin=".$_REQUEST["fecha_fin"].( ($_REQUEST["cod_pais"]!= "")?"&cod_pais=".$_REQUEST["cod_pais"]:"").( ($_REQUEST["cod_estado"]!= "")?"&f_estado=".$filtro_estado:"").( ($_REQUEST["cod_estado_hito"]!= "")?"&f_estado_hito=".$filtro_estado_hito:"");?>" );      	
	return false;
});
</script>
