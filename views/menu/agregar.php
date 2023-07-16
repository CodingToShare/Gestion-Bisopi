<?php
    $object = $db->selectObject("menu", "id_menu= '".$id."'" );
?>
<div class="p-3">
<div class="h3" ><?php echo (($id!="")?"Editar":"Crear");?> Menú</div>
<div class="border border-secondary rounded px-3 pt-3">
<form name="form1" id="form1" method="post" onsubmit="return false;">
	<div class="row">
    	<div class="form-group col-md-3">
    		<label for="nombre">Nombre</label>
      		<input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $object->nombre;?>">
        </div>
        <div class="form-group col-md-3">
        	<label for="tipo">Tipo</label>
        	<select class="custom-select" id="tipo" name="tipo">
    			<option value='-' selected>Seleccione un Estado</option>
            <?php
                $tipos = array( "texto"=>"Texto" , "modulo" => "Modulo");
                foreach( $tipos as $postipo=> $tipo){
                    echo "<option ".(( $postipo ==  $object->tipo )?"selected":"")." value='".$postipo."'>".$tipo."</option>";
                }
              	?>
    		</select>
      	</div>
    	<div class="form-group col-md-2">
    		<label for="cod_menu">Menú Padre</label>      		
      		<select class="custom-select" id="cod_menu" name="cod_menu">
        		<option value='0' selected>Sin Padre</option>
            <?php
                $menus_padre = $db->selectObjects("menu","estado = 1 and cod_menu= 0","nombre");
                foreach( $menus_padre as $menu_padre ){
                    echo "<option ".(( $menu_padre->id_menu ==  $object->cod_menu)?"selected":"")." value='".$menu_padre->id_menu."'>".$menu_padre->nombre."</option>";
                }
              	?>
        	</select>
        </div>
    	<div class="form-group col-md-2">
    		<label for="posicion">Posición</label>
      		<input type="text" class="form-control" id="posicion" name="posicion" value="<?php echo $object->posicion;?>">
        </div>
    	<div class="form-group col-md-2">
    		<label for="admin">Admin</label>
      		<input type="text" class="form-control" id="admin" name="admin" value="<?php echo $object->admin;?>">
        </div>
  	</div>
	<div class="row">
    	<div class="form-group col-md-3">
    		<label for="accion">Acción</label>
      		<input type="text" class="form-control" id="accion" name="accion" value="<?php echo $object->accion;?>" />
        </div>
    	<div class="form-group col-md-3">
    		<label for="opcion">Opción</label>
      		<input type="text" class="form-control" id="opcion" name="opcion" value="<?php echo $object->opcion;?>" />
        </div>
    	<div class="form-group col-md-3">
    		<label for="cod_area">Áreas</label>
      		<input type="text" class="form-control" id="cod_area" name="cod_area" value="<?php echo $object->cod_area;?>" />
        </div>
    	<div class="form-group col-md-3">
    		<label for="cod_cargo">Cargos</label>
      		<input type="text" class="form-control" id="cod_cargo" name="cod_cargo" value="<?php echo $object->cod_cargo;?>" />
        </div>
    </div>
	<div class="form-group">
		<input type="hidden" id="id_menu" name="id_menu" value="<?php echo $object->id_menu;?>">
        <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  		
  	</div>
</form>
</div>
</div>
<script>
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'menu' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    });
</script>