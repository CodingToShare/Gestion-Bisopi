<?php
    global $db;
    
    $object = $db->selectObject("herramienta", "id_herramienta = '".$id."'" );
      
?>
<form name="form1" id="form1" method="post" onsubmit="return false;">
	
    <div class="form-group">
    	<label for="pais">Nombre</label>
    	<input type="text" class="form-control" id="herramienta" name="herramienta" value="<?php echo $object->herramienta;?>">
  	</div> 

	<div class="form-group">
		<input type="hidden" id="id_herramienta" name="id_herramienta" value="<?php echo $object->id_herramienta;?>">
        <button id="save" name='save' type="submit" class="btn btn-primary">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  		
  	</div>
</form>
<script>
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'herramienta' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    });
</script>