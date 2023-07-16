<?php
    global $db;
    
    $object = $db->selectObject("cargo", "id_cargo = '".$id."'" );
      
?>
<form name="form1" id="form1" method="post" onsubmit="return false;">
	
    <div class="form-group">
    	<label for="pais">Nombre</label>
    	<input type="text" class="form-control" id="cargo" name="cargo" value="<?php echo $object->cargo;?>">
  	</div> 

	<div class="form-group">
		<input type="hidden" id="id_cargo" name="id_cargo" value="<?php echo $object->id_cargo;?>">
        <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  		
  	</div>
</form>
<script>
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'cargo' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    });
</script>