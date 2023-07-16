<?php
    global $db;
    
    $object = $db->selectObject("nivel", "id_nivel= '".$id."'" );
      
?>
<form name="form1" id="form1" method="post" onsubmit="return false;">
	
    <div class="form-group">
    	<label for="nivel">Nivel</label>
    	<input type="text" class="form-control" id="nivel" name="nivel" value="<?php echo $object->nivel;?>">
  	</div> 

	<div class="form-group">
		<input type="hidden" id="id_nivel" name="id_nivel" value="<?php echo $object->id_nivel;?>">
        <button id="save" name='save' type="submit" class="btn btn-primary">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  		
  	</div>
</form>
<script>
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'nivel' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    });
</script>