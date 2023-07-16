<?php
    global $db;
    
    $object = $db->selectObject("estudio", "id_estudio = '".$id."'" );
      
?>
<form name="form1" id="form1" method="post" onsubmit="return false;">
	
    <div class="form-group">
    	<label for="pais">Nombre</label>
    	<input type="text" class="form-control" id="estudio" name="estudio" value="<?php echo $object->estudio;?>">
  	</div> 

	<div class="form-group">
		<input type="hidden" id="id_estudio" name="id_estudio" value="<?php echo $object->id_estudio;?>">
        <button id="save" name='save' type="submit" class="btn btn-primary">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  		
  	</div>
</form>
<script>
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'estudio' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    });
</script>