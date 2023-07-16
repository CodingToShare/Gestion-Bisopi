<?php
    global $db;
    
    $object = $db->selectObject("tipo_actividad", "id_tipo_actividad = '".$id."'" );
      
?>
<form name="form1" id="form1" method="post" onsubmit="return false;">
	
    <div class="form-group">
    	<label for="tipo_actividad">Tipo Actividad</label>
    	<input type="text" class="form-control" id="tipo_actividad" name="tipo_actividad" value="<?php echo $object->tipo_actividad;?>">
  	</div> 

	<div class="form-group">
		<input type="hidden" id="id_tipo_actividad" name="id_tipo_actividad" value="<?php echo $object->id_tipo_actividad;?>">
        <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  		
  	</div>
  	
</form>
<script>
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'tipo_actividad' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    })
</script>