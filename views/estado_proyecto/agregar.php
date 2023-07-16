<?php
    global $db;
    
    $object = $db->selectObject("estado_proyecto", "id_estado_proyecto = '".$id."'" );
?>
<div class="p-3">
<div class="h3" ><?php echo (($id!="")?"Editar":"Crear");?> Estado Proyecto</div>
<div class="border border-secondary rounded px-3 pt-3">
<form name="form1" id="form1" method="post" onsubmit="return false;">
	<div class="row">
	<div class="form-group col-md-3">
		<label for="abreviatura">Abreviatura</label>
  		<input type="text" class="form-control" id="abreviatura" name="abreviatura" value="<?php echo $object->abreviatura;?>">
    </div>
    <div class="form-group col-md-9">
    	<label for="pais">Nombre</label>
    	<input type="text" class="form-control" id="pais" name="estado_proyecto" value="<?php echo $object->estado_proyecto;?>">
  	</div>
    <div class="form-group col-md-9">
    	<label for="filtra_tarea">Filtra tareas</label>
    	<select class="custom-select" id="filtra_tarea" name="filtra_tarea">
			<option value='0' <?php echo (( $object->filtra_tarea == 0 )?"selected":"");?>>No</option>
			<option value='1' <?php echo (( $object->filtra_tarea == 1 )?"selected":"");?>>Si</option>			
		</select>
  	</div> 
  	</div>
  	
  	
      		
	<div class="form-group">
		<input type="hidden" id="id_estado_proyecto" name="id_estado_proyecto" value="<?php echo $object->id_estado_proyecto;?>">
        <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  		
  	</div>
</form>
</div>
</div>
<script>
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'estado_proyecto' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    });
</script>