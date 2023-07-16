<?php
global $db;
global $user;
?>
<div class="p-3" style='font-size:13px'>
<div class="h3" >Listado de Tareas</div>
<?php
    $cierre = $db->selectObjects("cierre", "fecha_cierre > '".date('Y-m-d H:i:s')."' " , "fecha_cierre");
    //$resp->alert( date('Y-m-d H:i:s') );
    //$resp->alert(print_r( $cierre , true ) );
    
    $date1 = new DateTime("now");
    $date2 = new DateTime( $cierre[0]->fecha_cierre );
    $diff = $date1->diff($date2);    
//    echo $date1."<br/>";
    //echo $date2."<br/>";
    //echo "La semana ".$cierre[0]->semana_inicio." - ".$cierre[0]->semana_fin." se cierra en ".$diff->format('%a días %h horas %I minutos')
    if( $diff->days <1  ){
        $alert = "danger";
    }elseif( $diff->days < 3 ){
        $alert = "warning";
    }else{
        $alert = "secondary";
    }
    if( $diff->days <5  ){
?>
<div class="alert alert-<?php echo $alert;?> alert-dismissible fade show" role="alert">
  La semana <strong><?php echo $cierre[0]->semana_inicio." - ".$cierre[0]->semana_fin;?></strong> se cierra en <?php echo $diff->format('%a días %h horas %I minutos');?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php }
    $estados_tarea = $db->selectObjects("estado_tarea","estado = 1");
    //$fil_estados_tarea = (($_REQUEST["est_tarea"]=="")?"1":$_REQUEST["est_tarea"]);
    $array_estados = (( $_REQUEST["est_tarea"]=="")?array(1):$_REQUEST["est_tarea"] ) ;
    //print_r( $array_estados );
    $fil_estados_tarea = implode( "," , $array_estados );
    $fil_estados_tarea = (($fil_estados_tarea=="")?array(1):$fil_estados_tarea);
?>
<div class="p-3">
<form name="form1" id="form1" method="post"  action="index.php">
    <input type="hidden" name="view" id="view" value="tarea" />
    <div class="form-row">
	<div class="form-group col-md-2">
        	<label for="est_tarea">Filtro Estado Tareas</label><br/>
        	<select id="est_tarea" name="est_tarea[]" multiple="multiple">
        	<?php 
        	   foreach( $estados_tarea as $estado ){
                    echo '<option value="'.$estado->id_estado_tarea.'" '.(( array_search( $estado->id_estado_tarea , $array_estados ) !== false )?"selected":"").'>'.$estado->estado_tarea.'</option>';
                }
            ?>
            </select>
        </div>
        <div class="form-group col-md-1">    	
        	<button id="save" name='save' type="submit" class="btn btn-success mt-4">Filtrar</button>
        </div>
    </div>
</form>
<?php
    $sql = "select id_proyecto_tarea
                , pais                
                , proyecto
                ,proyecto_tarea  
                , pt.comentario                 
                , pt.fecha_inicio
                , pt.fecha_fin 
                , pt.tiempo_estimado
                , pt.tiempo_ejecutado
                , pt.cod_estado_tarea
                , et.estado_tarea
                , gt.grupo_tarea
                , cgt.nro_tareas
                ,ptr.fecha_registro_ejecucion
                ,ptr.id_proyecto_tarea_registro
            from proyecto_tarea pt
            inner join proyecto p on p.id_proyecto =  pt.cod_proyecto 
            inner join pais pa on pa.id_pais = p.cod_pais 
            inner join estado_tarea et on et.id_estado_tarea = pt.cod_estado_tarea
            inner join grupo_tarea gt on gt.id_grupo_tarea = pt.cod_grupo_tarea
            inner join ( 
                select count(1) nro_tareas
                , cod_grupo_tarea
                , cod_proyecto   
                from proyecto_tarea pt
                where pt.estado = 1 
                and pt.cod_responsable = ".$user->id_recurso." 
                and pt.cod_estado_tarea in (".$fil_estados_tarea.")
                group by cod_grupo_tarea,cod_proyecto 
            ) cgt on cgt.cod_grupo_tarea = pt.cod_grupo_tarea and pt.cod_proyecto =cgt.cod_proyecto
            left join proyecto_tarea_registro ptr on ptr.cod_proyecto_tarea = pt.id_proyecto_tarea and ptr.mca_ejecucion = 1
            where pt.estado = 1 and pt.cod_responsable = ".$user->id_recurso." and p.cod_estado_proyecto not in ( select id_estado_proyecto from estado_proyecto where filtra_tarea = 1 ) -- ( 3 , 7 )
            and pt.cod_estado_tarea in (".$fil_estados_tarea.")
            order by pais , p.proyecto , gt.orden , pt.fecha_inicio, pt.fecha_fin, proyecto_tarea
        ";
    //echo $sql;
    $tareas = $db->selectObjectsBySql($sql);
    if( count( $tareas) > 0 ){
?>

<div>			
<?php $cabecera = '<table class="table table-striped table-hover">
        <thead class="thead-dark">
        	<tr style="text-align:center;">
                <th class="p-1" scope="col" style="width:130px">Grupo</th>
                <th class="p-1" scope="col">Tarea</th>                
                <th class="p-1" scope="col" style="width:100px">Fecha Inicio</th>
                <th class="p-1" scope="col" style="width:100px">Fecha Fin</th>
                <th class="p-1" scope="col" style="width:100px">Estimado</th>
                <th class="p-1" scope="col" style="width:100px">Reportado</th>
                <th class="p-1" scope="col"  style="width:100px">Estado</th>';       
            $cabecera.= '<th class="p-1" scope="col"  style="width:80px">Play</th>';                
        $cabecera.= '<th class="p-1" scope="col" style="width:30px">Reportar</th>
                <th class="p-1" scope="col" style="width:30px">Historial</th>
			</tr>
		</thead><tbody>';?>
		
<?php  
    $group_proyecto = "";
    $txt_grupo = ""; 
    $estados_tarea = $db->selectObjects("estado_tarea","estado = 1");
        foreach( $tareas as $tarea  ){ 
            if( $group_proyecto == "" ||  $group_proyecto != $tarea->proyecto ){                
                if( $group_proyecto != $tarea->proyecto ){ 
                    echo "</tbody></table>"; $txt_grupo =""; 
                }
                echo '<div class="row" ><div class="col-12"><h5>'.$tarea->proyecto.' ('.$tarea->pais.')</h5></div></div>';                
                echo $cabecera;
            }
            ?>        	    
            <tr style='text-align:left;'>
            <?php if( $txt_grupo == "" || $txt_grupo != $tarea->grupo_tarea ){?>
            		<th class="p-1" scope="row" rowspan="<?php echo $tarea->nro_tareas;?>"><?php echo $tarea->grupo_tarea;?></th>
            	<?php } ?>
            	<th class="p-1" scope="row"><span title="<?php echo $tarea->comentario;?>"><?php echo $tarea->proyecto_tarea;?></span></th>      			
      			<td class="p-1" style='text-align:center;' scope="row"><?php echo $tarea->fecha_inicio;?></td>
      			<td class="p-1" style='text-align:center;' scope="row"><?php echo $tarea->fecha_fin;?></td>
      			<td class="p-1" style='text-align:center;' scope="row"><?php echo segundos_tiempo( $tarea->tiempo_estimado) ;?></td>
      			<td class="p-1" style='text-align:center;' scope="row" id="div_crono_1_<?php echo $tarea->id_proyecto_tarea;?>">
      				<?php echo segundos_tiempo( $tarea->tiempo_ejecutado + (($tarea->id_proyecto_tarea_registro != "")?( time()-strtotime($tarea->fecha_registro_ejecucion ) ):0) );?>
      			</td>
      			<td class="p-1" style='text-align:center;' scope="row"> 
      			<select class="custom-select" id="cod_estado_tarea" name="cod_estado_tarea" style="font-size:11px;cursor:pointer;" onchange="if( this.value != 0 ){xajax_actualizaValor('proyecto_tarea','<?php echo $tarea->id_proyecto_tarea;?>','cod_estado_tarea', this.value, 0 );}">
            			<option value='0' selected>Seleccione</option>
                    <?php                        
                        foreach( $estados_tarea as $estado_tarea ){
                            echo "<option  ".(( $estado_tarea->id_estado_tarea==$tarea->cod_estado_tarea)?"selected":"")."  value='".$estado_tarea->id_estado_tarea."'>".$estado_tarea->estado_tarea."</option>";
                        }
                      	?>
            		</select>
            	</td>
      			<td class="p-1" style='text-align:center;' scope="row">
      			<?php if( $tarea->id_proyecto_tarea_registro !="" &&  $tarea->id_proyecto_tarea == $_SESSION["SES_TAREA"] ){?>
      				<img onclick="xajax_playTarea('<?php echo $tarea->id_proyecto_tarea;?>' ,'<?php echo $tarea->id_proyecto_tarea_registro;?>');" src="<?php echo IMG_STOPTIME;?>" style='width:24px;cursor:pointer;' title='Detener Tarea'/>
      			<?php }elseif( $_SESSION["SES_TAREA"] == "" ){?>
      				<img onclick="xajax_playTarea('<?php echo $tarea->id_proyecto_tarea;?>');" src="<?php echo IMG_PLAYTIME;?>" style='width:24px;cursor:pointer;' title='Iniciar Tarea'/>
      			</td>
      			<?php } ?>
      			<td class="p-1" style='text-align:center;' scope="row"><img onclick="xajax_registrarTarea('<?php echo $tarea->id_proyecto_tarea;?>');" src="<?php echo IMG_ADDTIME;?>" style='width:24px;cursor:pointer;' title='Registrar Hora'/></td>
      			<td class="p-1" style='text-align:center;' scope="row"><img onclick="xajax_historialTarea('<?php echo $tarea->id_proyecto_tarea;?>');" src="<?php echo IMG_HISTORY;?>" style='width:24px;cursor:pointer;' title='Historial'/></td>
  			</tr>        
		<?php 
		  $group_proyecto = $tarea->proyecto;
		  $txt_grupo = $tarea->grupo_tarea;
        }
        ?>
		</tbody>
	</table>
	</div>
<?php } ?>
</div>
</div>
<?php include_once 'views/tarea/view_registro.php';?>
<?php include_once 'views/tarea/view_historia.php';?>
<script>
$(function(){
		$('#est_tarea').multiselect({
			buttonWidth: '100%',
			includeSelectAllOption: true
		});
	});
</script>