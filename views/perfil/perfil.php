<div class="h3" >Actualizar datos personales</div>
<?php
    global $db;
    $id = $_SESSION[ SES_USER ];
    $object = $db->selectObject("recurso", "id_recurso = '".$id."'" );    
    $ciudades =  $db->selectObjects("ciudad","estado = 1","ciudad asc");
    $form_name = 'form1';
    $file_name = 'file_foto';
    $file_target = 'foto';
?>
<!-- <form name="form1" id="form1" method="post" onsubmit="return false;">  -->
<form name='<?php echo $form_name;?>' id='<?php echo $form_name;?>' action='include/subirArchivo.php' enctype='multipart/form-data' target='puente' method='post' onsubmit='return false;'>
	<div class="row">		
    	<div class="col-10">
          	<div class="form-group">
            	<label for="recurso">Nombre</label>
            	<input type="text" class="form-control" id="recurso" name="recurso" value="<?php echo $object->recurso;?>">
          	</div>
          	<div class="row">		
    			<div class="col-6">
        			<div class="form-group">
        				<label for="correo">Correo</label>
          				<input type="text" class="form-control" id="correo" name="correo" value="<?php echo $object->correo;?>">
            		</div>
            	</div>
            	<div class="col-3">
        			<div class="form-group">
        				<label for="telefono">Teléfono</label>
          				<input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $object->telefono;?>">
          			</div>
          		</div>	
          		<div class="col-3">
        			<div class="form-group">
        				<label for="cod_ciudad">Ciudad</label>
          				<select class="custom-select" id="cod_ciudad" name="cod_ciudad">
                			<option value='0' selected>Seleccione una ciudad</option>
                        <?php
                            foreach( $ciudades as $ciudad ){
                                echo "<option ".(( $ciudad->id_ciudad ==  $object->cod_ciudad )?"selected":"")." value='".$ciudad->id_ciudad."'>".$ciudad->ciudad."</option>";
                            }
                          	?>
                		</select>
          			</div>
          		</div>	
            </div>
		</div>
		<div class="col-2">
			<label for="<?php echo $file_name;?>">Foto</label><br/>
    		<img name="img_<?php echo $file_target;?>" id="img_<?php echo $file_target;?>"  src="<?php echo (($object->foto != "")?$object->foto:IMG_PROFILE_256);?>" class="img-thumbnail text-center" style='height: 150px' />
    		<div class="input-group mb-3 ">
                <div class="custom-file">
                    <input type="file" name="<?php echo $file_name;?>" class="custom-file-input" id="<?php echo $file_name;?>">
                    <label class="custom-file-label" for="<?php echo $file_name;?>">Escoja un archivo</label>
                </div>
                <div class="input-group-append">
                	<!-- <span class="btn btn-info" id="btn_upload" >Subir</span> -->
                </div>
    		</div>
    	</div>
	</div>
    <div class="row">   		
		<div class="col-12 form-group">
    		<input type="hidden" id="id_recurso" name="id_recurso" value="<?php echo $object->id_recurso;?>">
    		<input type="hidden" id="<?php echo $file_target;?>" name="<?php echo $file_target;?>" value="<?php echo $object->foto;?>">
    		<input type="hidden" id="<?php echo $file_target;?>_new" name="<?php echo $file_target;?>_new" value="">
    		<input type="hidden" id="dir_target" name="dir_target" value="<?php echo TMP_DIR_PATH_PERFILES;?>">
    		<input type="hidden" id="form_name" name="form_name" value="<?php echo $form_name;?>">
    		<input type="hidden" id="file_name" name="file_name" value="<?php echo $file_name;?>">
    		<input type="hidden" id="fileIsImage" name="fileIsImage" value="1">
    		<input type="hidden" id="file_target" name="file_target" value="<?php echo $file_target;?>">
            <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>        
      		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  
      	</div>
	</div>  	
</form>
<iframe id='puente' name='puente' style='visibility:hidden; border:0px solid #000000; width: 1px; height:1px;'></iframe>
<script>
    $( "#save" ).click(function() {
      	xajax_guardarPerfil( xajax.getFormValues( this.form , true ) );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php';
    	return false;
    });
    $( "#<?php echo $file_name;?>" ).change(function() {
    	document.forms['<?php echo $form_name?>'].submit();	
    });
    /* 
    $( "#btn_upload" ).click(function() {
    	disableButton(this);
    	document.forms['<?php echo $form_name?>'].submit();
    	return false;    	
    });*/
</script>