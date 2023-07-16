<div class="h3" >Reporte Horas</div>
<?php

$today = date("Ymd");
$mesant = (($_REQUEST["fecha_inicio"]=="" )? date("Y-m-d",strtotime($today."last week")):$_REQUEST["fecha_inicio"]);
$mespos = (($_REQUEST["fecha_fin"]=="" )? date("Y-m-d",strtotime($today."today")):$_REQUEST["fecha_fin"]);
    
$filtro_recurso = (($_REQUEST["cod_responsable"] != "" )? $_REQUEST["cod_responsable"]:$user->id_recurso );
$filtro_proyecto = (($_REQUEST["cod_proyecto"] != "" )? $_REQUEST["cod_proyecto"]:"" );
//echo $filtro_recurso;
$sql = "select id_proyecto_tarea
                , pais
                , id_proyecto
                , proyecto                
                , gt.grupo_tarea
                ,proyecto_tarea
                ,r.recurso
                , pt.tiempo_estimado
                , pt.tiempo_ejecutado tiempo_ejecutado_total
                , sum( ptr.tiempo_ejecutado ) tiempo_ejecutado
                , ptr.fecha_registro
                , ptr.comentario
                , et.estado_tarea                
            from proyecto_tarea pt
            inner join proyecto_tarea_registro ptr on ptr.cod_proyecto_tarea = pt.id_proyecto_tarea
            inner join proyecto p on p.id_proyecto =  pt.cod_proyecto
            inner join pais pa on pa.id_pais = p.cod_pais
            inner join estado_tarea et on et.id_estado_tarea = pt.cod_estado_tarea
            inner join grupo_tarea gt on gt.id_grupo_tarea = pt.cod_grupo_tarea
            inner join recurso r on r.id_recurso =  pt.cod_responsable
            where pt.estado = 1 ".(($filtro_recurso=="-")?"": "and pt.cod_responsable = ".$filtro_recurso)." -- and p.cod_estado_proyecto <> 3
            and ptr.fecha_registro  between '".$mesant."' and '".$mespos."'
            ".(($filtro_proyecto!="")?" and p.id_proyecto = '".$filtro_proyecto."'":"")."
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
            order by pais , p.proyecto , gt.orden , ptr.fecha_registro
        ";
    //echo $sql;
    $proyectos = $db->selectObjectsBySql($sql) ;
    //echo $db->error();
?>
<form name="form1" id="form1" method="post"  action="index.php?view=tarea&action=reporte">
    <input type="hidden" name="view" id="view" value="tarea" />    
    <input type="hidden" name="action" id="action" value="reporte" />
    <div class="form-row">
        <div class="form-group col-md-2">
          <label for="fecha_inicio">Fecha Inicio</label>
          <input  autocomplete="off" type="text" placeholder="YYYY-MM-DD" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $mesant;?>">
        </div>
        <div class="form-group col-md-2">
          <label for="fecha_fin">Fecha Fin</label>
          <input autocomplete="off" type="text" placeholder="YYYY-MM-DD" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $mespos?>">
        </div>
        <?php if( array_search( $user->cod_cargo , explode( ',','1,3,5,6,11' ) ) !== false ){?>
        <div class="form-group col-md-3">
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
         <?php
                $proyectos2 = $db->selectObjects("proyecto", "estado=1","cod_pais , proyecto");
        ?>
        <div class="form-group col-md-3">
          <label for="cod_proyecto">Proyectos</label>
          <select class="custom-select" id="cod_proyecto" name="cod_proyecto">
        		<option value='' selected>Todos</option>
            <?php
                foreach( $proyectos2 as $proyecto2 ){
                    echo "<option ".(( $proyecto2->id_proyecto ==  $filtro_proyecto )?"selected":"")." value='".$proyecto2->id_proyecto."'>".$proyecto2->proyecto."</option>";
                }
              	?>
        	</select>
        </div>
        <?php } ?>
        <div class="form-group col-md-1">    	
        	<button id="filtrar" name='filtrar' type="submit" class="btn btn-success mt-4">Filtrar</button>
        </div>
    </div>
</form>    
<?php if( count( $proyectos ) > 0 ){ ?>
    <table class="table table-striped table-hover" style="font-size:13px">
        <thead class="thead-dark">
        <tr>
            <th class="p-1" scope="col"></th>
            <th class="p-1" scope="col">País</th>
            <th class="p-1" scope="col">Proyecto</th>            
            <th class="p-1" scope="col">Grupo</th>
            <th class="p-1" scope="col">Tarea</th>
            <th class="p-1" scope="col">Responsable</th>
            <th class="p-1" scope="col">Tiempo Estimado</th>
            <th class="p-1" scope="col">Tiempo Reportado</th>
            <th class="p-1" scope="col">Fecha Registro</th>
        </tr>
        </thead>
        <tbody id="tb_search" class=" text-nowrap">
        <?php
            $contador= 1;
            $total_estado = 0;
            $estado_txt = "";
            foreach( $proyectos as $proyecto ){ 
        ?>
        	<tr>
        		<th class="p-1" scope="row"><?php echo ( $contador++);?></th>
				<!--  <td class="p-1"><a href="index.php?view=<?php echo 'proyecto&action=tarea&id='.$proyecto->id_proyecto;?>"><img src="<?php echo IMG_TASK;?>" style="width:24px" title="Tareas" /></a></td> -->        		
        		<td class="p-1"><?php echo $proyecto->pais;?></td>
        		<td class="p-1"><?php echo $proyecto->proyecto;?></td>        		        		
        		<td class="p-1"><?php echo $proyecto->grupo_tarea;?></td>
        		<td class="p-1"><?php echo $proyecto->proyecto_tarea;?></td>
        		<td class="p-1"><?php echo $proyecto->recurso;?></td>
        		<td class="p-1"><?php echo segundos_tiempo( $proyecto->tiempo_estimado );?></td>        		
        		<td class="p-1"  style='text-align:center;'><?php echo segundos_tiempo( $proyecto->tiempo_ejecutado);?></td>
        		<td class="p-1"><?php echo $proyecto->fecha_registro;?></td>
        	</tr>
        <?php
        $total_estado+= $proyecto->tiempo_ejecutado;
            }            
        ?>
        	<tr  class="thead-dark">        		
        		<th colspan='7'>Total</th><th class="p-1" style='text-align:center;'><?php echo segundos_tiempo( $total_estado );?></th>
        		<th colspan='2'></th>        	
        	</tr>
        </tbody>
    </table>
<?php } ?>
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
