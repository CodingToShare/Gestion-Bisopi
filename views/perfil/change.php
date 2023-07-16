<div class="h3" >Cambiar Contraseña</div>
<?php
    global $db;
    $id = $_SESSION[ SES_USER ];
    $object = $db->selectObject("recurso", "id_recurso = '".$id."'" );    
      
?>
<form name="form1" id="form1" method="post" onsubmit="return false;">
	<div class="row">		
    	<div class="col-9">
          	<div class="form-group">
            	<label for="recurso">Contraseña Actual</label>
            	<input type="password" class="form-control" id="pass" name="pass" value="">
          	</div>
        	<div class="form-group">
        		<label for="correo">Contraseña Nueva</label>
          		<input type="password" class="form-control" id="npass" name="npass" value="">
            </div>	
        	<div class="form-group">
        		<label for="cpass">Confirmar Contraseña</label>
          		<input type="password" class="form-control" id="cpass" name="cpass" value="">
            </div>	
          	<div class="form-group">
        		<input type="hidden" id="id_recurso" name="id_recurso" value="<?php echo $object->id_recurso;?>">		
                <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>        
          		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  
          	</div>
		</div>
  	</div>
 </form>
<script>
    $( "#save" ).click(function() {
      	xajax_cambiarContrasena( xajax.getFormValues( this.form , true ) );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php';
    	return false;
    });
</script>