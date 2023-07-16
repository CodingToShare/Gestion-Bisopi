<?php
    global $db;
    
    $object = $db->selectObject("recurso", "id_recurso = '".$id."'" );
    $cargos =  $db->selectObjects("cargo","estado = 1","cargo asc");
    $areas =  $db->selectObjects("area","estado = 1","area asc");
    $ciudades =  $db->selectObjects("ciudad","estado = 1","ciudad asc");
      
?>
<div class="p-3">
<div class="h3" ><?php echo (($id!="")?"Editar":"Crear");?> Recurso</div>
<div class="border border-secondary rounded px-3 pt-3">
    <form name="form1" id="form1" method="post" onsubmit="return false;">
      	<div class="form-row">
      		<!-- <div class="form-group col-md-2">
        		<label for="codigo_recurso">Código</label>
        		<input type="text" class="form-control" id="codigo_recurso" name="codigo_recurso" value="<?php echo $object->codigo_recurso;?>">
        	</div>-->
      		<div class="form-group col-md-3">
        		<label for="abreviatura">Abreviatura</label>
        		<input type="text" class="form-control" id="abreviatura" name="abreviatura" value="<?php echo $object->abreviatura;?>">
        	</div>
        	<div class="form-group col-md-9">
        		<label for="recurso">Nombre</label>
        		<input type="text" class="form-control" id="recurso" name="recurso" value="<?php echo $object->recurso;?>">
        	</div>
      	</div>  
    	<div class="form-row">
    		<div class="form-group col-md-4">
        		<label for="cod_cargo">Área</label>
          		<select class="custom-select" id="cod_area" name="cod_area">
        			<option value='-' selected>Seleccione un área</option>
                <?php
                    foreach( $areas as $area ){
                        echo "<option ".(( $area->id_area ==  $object->cod_area )?"selected":"")." value='".$area->id_area."'>".$area->area."</option>";
                    }
                  	?>
        		</select>
            </div>
    		<div class="form-group col-md-4">
        		<label for="cod_cargo">Cargo</label>
          		<select class="custom-select" id="cod_cargo" name="cod_cargo">
        			<option value='-' selected>Seleccione un cargo</option>
                <?php
                    foreach( $cargos as $cargo ){
                        echo "<option ".(( $cargo->id_cargo ==  $object->cod_cargo )?"selected":"")." value='".$cargo->id_cargo."'>".$cargo->cargo."</option>";
                    }
                  	?>
        		</select>
            </div>
            <div class="form-group col-md-4">
        		<label for="cod_cargo">Estado</label>
          		<select class="custom-select" id="cod_estado_recurso" name="cod_estado_recurso">
        			<option value='-' selected>Seleccione un Estado</option>
                <?php
                    $estados = array( "A"=>"Activo" , "I" => "Inactivo");
                    foreach( $estados as $posestado => $estado){
                        echo "<option ".(( $posestado ==  $object->cod_estado_recurso )?"selected":"")." value='".$posestado."'>".$estado."</option>";
                    }
                  	?>
        		</select>
            </div>
    	</div>	  
    	<div class="form-row">
    		<div class="form-group col-md-6">
        		<label for="telefono">Teléfono</label>
          		<input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $object->telefono;?>">
            </div>
            <div class="form-group col-md-6">
        		<label for="cod_ciudad">Ciudad</label>
          		<select class="custom-select" id="cod_ciudad" name="cod_ciudad">
        			<option value='0' selected>Seleccione una ciudad</option>
                <?php
                    foreach( $ciudades as $ciudad ){
                        echo "<option ".(( $ciudad->id_ciudad ==  $object->cod_ciudad )?"selected":"")." value='".$ciudad->id_ciudad."'>".$ciudad->ciudad."</option>";
                    }
                  	?>
        		</select>
            </div>
    	</div>	
    	<div class="form-group">
    		<label for="correo">Correo</label>
      		<input type="text" class="form-control" id="correo" name="correo" value="<?php echo $object->correo;?>">
        </div>
    	<div class="form-group">
    		<label for="descripcion">Descripción</label>
    		<textarea class="form-control" id="descripcion" name="descripcion" rows="6" ><?php echo $object->descripcion;?></textarea>
    	</div>
    	<div class="form-group">
    		<input type="hidden" id="id_recurso" name="id_recurso" value="<?php echo $object->id_recurso;?>">		
            <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>        
      		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  
      				
      	</div>        
	</form>
</div>
<?php
	if( $object ){
	    $costos = $db->selectObjects("recurso_costo","cod_recurso ='".$object->id_recurso."' " , "fecha_desde desc" );
	    $sql="  select RH.id_recurso_herramienta, RH.cod_herramienta, RH.cod_nivel, H.herramienta, N.nivel
                            from recurso_herramienta as RH
                            left join herramienta H
                            on RH.cod_herramienta = H.id_herramienta
                            left join nivel N
                            on RH.cod_nivel = N.id_nivel
                            where cod_recurso ='".$object->id_recurso."'" ;
	    $recurso_herramientas =  $db->selectObjectsBySql($sql);
	    $sql="  select RE.id_recurso_estudio, RE.cod_estudio, E.estudio, E.descripcion
                            from recurso_estudio as RE
                            left join estudio E
                            on RE.cod_estudio = E.id_estudio
                            where cod_recurso ='".$object->id_recurso."'" ;
	    $recurso_estudio =  $db->selectObjectsBySql($sql);	    
?>
<div class="px-3 pt-3">
	<div class="form-row">		
		<div class="col-md-4 p-2"><button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#ModalHerramientas">Agregar Conocimientos</button></div>
		<div class="col-md-4 p-2"><button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#ModalEstudios">Agregar Estudios</button></div>
		<div class="col-md-4 p-2">
		<?php if( $user->cod_area == AREA_ADMON || $user->cod_cargo == CARGOS_GG || $user->cod_cargo == CARGOS_GO ){?>
			<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#ModalCostos">Agregar Costos</button>
		<?php }?>
		</div>
	</div>
	
	<div class="form-row">
		<div class="col-md-4 p-2">
		<?php if( count($recurso_herramientas)>0 ){ ?>			
			<table class="table table-striped table-hover">
        		<thead class="thead-dark">
          		<tr>
              		<th class="p-1" scope="col"></th>
              		<th class="p-1" scope="col">Herramienta</th>
              		<th class="p-1" scope="col">Nivel</th>
              		<th class="p-1" scope="col"></th>    
          		</tr>
          		</thead>
          		<?php
          		   $contador=1;
          			foreach( $recurso_herramientas as $recurso_herramienta ){ ?>
          		<tr>
          			<th class="p-1" scope="row"><?php echo ($contador++);?></th>
    	  			<td class="p-1" scope="row"><?php echo $recurso_herramienta->herramienta;?></td>
    				<td class="p-1" scope="row"><?php echo $recurso_herramienta->nivel;?></td>		
        			        <?php 
        			        echo "<td class='p-1'><a href='#' onclick=\"crearDialogo('confirmacion','¿Está seguro de eliminar el registro seleccionado?','xajax_eliminar','\'recurso_herramienta\'','\'".$recurso_herramienta->id_recurso_herramienta."\'');\">";
                    		echo '<img src="'.IMG_DELETE.'" style="width:16px"  title="Eliminar" />
                                </a></td>';?>
          		</tr>
          	
          		<?php }?>
          	</table>
      	<?php }else{ echo "No tiene registrada información";} ?>
		</div>
		<div class="col-md-4 p-2">
			<?php if( count( $recurso_estudio ) > 0 ){ ?>
			<table class="table table-striped table-hover">
        		<thead class="thead-dark">
          		<tr>
              		<th class="p-1" scope="col"></th>
              		<th class="p-1" scope="col">Estudios</th>
              		<th class="p-1" scope="col"></th>    
          		</tr>
          		</thead>
          		<?php
          		   $contador=1;
          			foreach( $recurso_estudio as $recurso_estudio ){ ?>
          		<tr>
          			<th class="p-1" scope="row"><?php echo ($contador++);?></th>
    	  			<td class="p-1" scope="row"><?php echo $recurso_estudio->estudio;?></td>
        			        <?php 
        			        echo "<td class='p-1'><a href='#' onclick=\"crearDialogo('confirmacion','¿Está seguro de eliminar el registro seleccionado?','xajax_eliminar','\'recurso_estudio\'','\'".$recurso_estudio->id_recurso_estudio."\'');\">";
                    		echo '<img src="'.IMG_DELETE.'" style="width:16px"  title="Eliminar" />
                                </a></td>';?>
          		</tr>
          	
          		<?php }?>
          	</table>
			<?php }else{ echo "No tiene registrada información";} ?>
		</div>
		<div class="col-md-4 p-2">
		<?php if( count($costos)>0 && $user->cod_area == AREA_ADMON  || $user->cod_cargo == CARGOS_GG || $user->cod_cargo == CARGOS_GO){ ?>
    		<table class="table table-striped table-hover">
    		<thead class="thead-dark">
      		<tr>
          		<th class="p-1" scope="col"></th>
          		<th class="p-1" scope="col">Fecha Desde</th>
          		<th class="p-1" scope="col">Fecha Hasta</th>
          		<th class="p-1" scope="col">Valor Hora</th>
          		<th class="p-1" scope="col"></th>    
      		</tr>
      		</thead>
      		<?php
      		   $contador=1;
      		   foreach( $costos as $costo ){ ?>
      		<tr>
      			<th class="p-1" scope="row"><?php echo ($contador++);?></th>
	  			<td class="p-1" scope="row"><?php echo $costo->fecha_desde;?></td>
	  			<td class="p-1" scope="row"><?php echo $costo->fecha_hasta;?></td>
				<td class="p-1" scope="row" style='text-align:right'><?php echo number_format( $costo->valor_hora , 0 , ',','.' );?></td>		
    			<?php 
    			echo "<td class='p-1'><a href='#' onclick=\"crearDialogo('confirmacion','¿Está seguro de eliminar el registro seleccionado?','xajax_eliminar','\'recurso_costo\'','\'".$costo->id_recurso_costo."\'');\">";
                	 echo '<img src="'.IMG_DELETE.'" style="width:16px"  title="Eliminar" /></a></td>';
                ?>
      		</tr>      	
      		<?php }?>
      	</table>
		<?php }else{ echo (($user->cod_area == AREA_ADMON || $user->cod_cargo == CARGOS_GG || $user->cod_cargo == CARGOS_GO)?"No tiene registrada información":"");} ?>
		</div>
	</div>
</div>
<?php } ?>

  		<form name="form1" id="form1" method="post" onsubmit="return false;">
            <!-- Modal -->
            <div class="modal fade" id="ModalHerramientas" tabindex="-1"  data-backdrop="static" data-keyboard="true" role="dialog" aria-labelledby="ModalProgramacion" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Conocimientos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <?php
                        $herramientas =  $db->selectObjects("herramienta","estado = 1");
                        $nivel =  $db->selectObjects("nivel","estado = 1");
                    ?>        
                    	<div class="form-row">
                    		<div class="form-group col-md-6">
                        		<label for="cod_herramienta">Herramienta</label>
                          		<select class="custom-select" id="cod_herramienta" name="cod_herramienta">
                        			<option value='-' selected>Seleccione una herramienta</option>
                                <?php
                                foreach( $herramientas as $herramienta ){
                                    echo "<option  value='".$herramienta->id_herramienta."'>".$herramienta->herramienta."</option>";
                                    }
                                  	?>
                        		</select>
                            </div>
                            <div class="form-group col-md-6">
                        		<label for="cod_nivel">Nivel</label>
                          		<select class="custom-select" id="cod_nivel" name="cod_nivel">
                        			<option value='-' selected>Seleccione un Nivel</option>
                                  	 <?php
                                  	 foreach( $nivel as $nivel ){
                                  	     echo "<option  value='".$nivel->id_nivel."'>".$nivel->nivel."</option>";
                                        }
                      	             ?>
                        		</select>
                            </div>
                    	</div>
                  
                  </div>
                  <div class="modal-footer">   
                  	<input type="hidden" id="cod_recurso" name="cod_recurso" value="<?php echo $object->id_recurso;?>">		  
                    <button id="saveherramienta" name='saveherramienta' type="button" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                  </div>
                </div>
              </div>
            </div>
            </form>
            
            <form name="form1" id="form1" method="post" onsubmit="return false;">
            <!-- Modal -->
            <div class="modal fade" id="ModalEstudios" tabindex="-1"  data-backdrop="static" data-keyboard="true" role="dialog" aria-labelledby="ModalProgramacion" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Estudios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <?php
                        $estudios =  $db->selectObjects("estudio","estado = 1");
                    ?>        
                    	<div class="form-row">
                    		<div class="form-group col-md-6">
                        		<label for="cod_estudio">Estudio</label>
                          		<select class="custom-select" id="cod_estudio" name="cod_estudio">
                        			<option value='-' selected>Seleccione un Estudio</option>
                                <?php
                                foreach( $estudios as $estudio ){
                                    echo "<option  value='".$estudio->id_estudio."'>".$estudio->estudio."</option>";
                                    }
                                  	?>
                        		</select>                        		
                            </div>
                    	</div>
                  
                  </div>
                  <div class="modal-footer">     
                  	<input type="hidden" id="cod_recurso" name="cod_recurso" value="<?php echo $object->id_recurso;?>">		
                    <button id="saveestudio" name='saveestudio' type="button" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                  </div>
                </div>
              </div>
            </div>
    	</form>
    	
		<form name="form3" id="form3" method="post" onsubmit="return false;">
            <!-- Modal -->
            <div class="modal fade" id="ModalCostos" tabindex="-1"  data-backdrop="static" data-keyboard="true" role="dialog" aria-labelledby="ModalProgramacion" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Costos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">                    
                    	<div class="form-row">
                    		<div class="form-group col-md-4">
                        		<label for="fecha_desde">Fecha Inicio</label>
                          		<input type="text" autocomplete="off" class="form-control" id="fecha_desde" name="fecha_desde" value="" />
                            </div>
                    		<div class="form-group col-md-4">
                        		<label for="valor_hora">Valor Hora</label>
                          		<input type="text" class="form-control" id="valor_hora" name="valor_hora" value="" />
                            </div>
                    	</div>
                  
                  </div>
                  <div class="modal-footer">     
                  	<input type="hidden" id="cod_recurso" name="cod_recurso" value="<?php echo $object->id_recurso;?>">		
                    <button id="savecosto" name='savecosto' type="button" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
                  </div>
                </div>
              </div>
            </div>
    	</form>
	</div>
</div>
<script>
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'recurso' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    });
    ;
    $( "#saveherramienta" ).click(function() {
      	xajax_guardarHerramientaRecurso( xajax.getFormValues( this.form , true ) );
    });
    $( "#saveestudio" ).click(function() {
      	xajax_guardarEstudioRecurso( xajax.getFormValues( this.form , true ) );
    });
    $( "#savecosto" ).click(function() {
      	xajax_guardarCostoRecurso( xajax.getFormValues( this.form , true ) );
    });
    
    $( function() {
    	$( "#fecha_desde" ).datepicker({
    		dateFormat: "yy-mm-dd",
    		monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],    		
    		firstDay: 1,
    		dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"]
    	});
    } );
</script>