<?php
    global $db;
    
    $object = $db->selectObject("cliente", "id_cliente= '".$id."'" );
      
?>
<div class="p-3">
<div class="h3" ><?php echo (($id!="")?"Editar":"Crear");?> Cliente</div>
<div class="border border-secondary rounded px-3 pt-3">
<form name="form1" id="form1" method="post" onsubmit="return false;">
	<div class="row">
	<div class="form-group col-md-2">
		<label for="codigo_cliente">Código</label>
  		<input type="text" class="form-control" id="codigo_cliente" name="codigo_cliente" value="<?php echo $object->codigo_cliente;?>">
    </div>
	<div class="form-group col-md-2">
		<label for="abreviatura">Abreviatura</label>
  		<input type="text" class="form-control" id="abreviatura" name="abreviatura" value="<?php echo $object->abreviatura;?>">
    </div>
    <div class="form-group col-md-8">
    	<label for="cliente">Nombre</label>
    	<input type="text" class="form-control" id="cliente" name="cliente" value="<?php echo $object->cliente;?>">
  	</div> 
  	</div>
	<div class="form-group">
		<input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo $object->id_cliente;?>">
        <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  		
  	</div>
</form>
</div>
</div>
<script>
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'cliente' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    });
</script>