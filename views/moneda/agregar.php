<?php
    global $db;
    
    $object = $db->selectObject("moneda", "id_moneda= '".$id."'" );
      
?>
<form name="form1" id="form1" method="post" onsubmit="return false;">
	<div class="row">
	<div class="form-group col-md-3">
		<label for="abreviatura">Abreviatura</label>
  		<input type="text" class="form-control" id="abreviatura" name="abreviatura" value="<?php echo $object->abreviatura;?>">
    </div>
    <div class="form-group col-md-9">
    	<label for="pais">Nombre</label>
    	<input type="text" class="form-control" id="moneda" name="moneda" value="<?php echo $object->moneda;?>">
  	</div> 
  	</div>
	<div class="form-group">
		<input type="hidden" id="id_ciudad" name="id_moneda" value="<?php echo $object->id_moneda;?>">
        <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  		
  	</div>
</form>
<script>
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'moneda' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    });
</script>