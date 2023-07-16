<?php
    global $db;
    
    $object = $db->selectObject("tipo_proyecto", "id_tipo_proyecto = '".$id."'" );
      
?>
<form name="form1" id="form1" method="post" onsubmit="return false;">
	
    <div class="form-group">
    	<label for="pais">Nombre</label>
    	<input type="text" class="form-control" id="tipo_proyecto" name="tipo_proyecto" value="<?php echo $object->tipo_proyecto;?>">
  	</div> 

	<div class="form-group">
		<input type="hidden" id="id_tipo_proyecto" name="id_tipo_proyecto" value="<?php echo $object->id_tipo_proyecto;?>">
        <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  		
  	</div>
  	
</form>
<script>
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'tipo_proyecto' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    })
</script>