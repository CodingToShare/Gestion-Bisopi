<div class="p-3">
<div class="h3" >Listado Recursos</div>
<?php
    if( count( $objects ) > 0 ){
        echo '<table class="table table-striped table-hover">
                  <thead class="thead-dark">
                    <tr>
                        <th class="p-1" scope="col"></th>
                        <th class="p-1" scope="col"></th>
                        <th class="p-1" scope="col"></th>                        
                        <th class="p-1" scope="col">Abreviatura</th>
                        <th class="p-1" scope="col">Nombre</th>
                        <th class="p-1" scope="col">Correo</th>
                        <th class="p-1" scope="col">Teléfono</th>
                        <th class="p-1" scope="col">Área</th>
                        <th class="p-1" scope="col">Cargo</th>
                        <th class="p-1" scope="col">Estado</th>
                    </tr>
                  </thead>
                    <tbody id="tb_search" class=" text-nowrap">
                    ';
        $contador = 1;
        $estados = array( "A"=>"Activo" , "I" => "Inactivo");
        foreach ( $objects as $object ){
            echo '<tr>';
            echo '<th class="p-1" scope="row">'.($contador++).'</th>';
            echo '<td class="p-1"><a href="index.php?view='.$_view.'&action=agregar&id='.$object->id_recurso.'" class=""><img src="'.IMG_EDIT.'" style="width:24px" title="Editar" /></a></td>';
            echo "<td class='p-1'><a href='#' onclick=\"crearDialogo('confirmacion','¿Está seguro de eliminar el registro seleccionado?','xajax_eliminar','\'recurso\'','\'".$object->id_recurso."\'');\">";
            echo '<img src="'.IMG_DELETE.'" style="width:24px"  title="Eliminar" /></a></td>';
            echo '<td class="p-1">'.$object->abreviatura.'</td>';
            echo '<td class="p-1">'.$object->recurso.'</td>';
            echo '<td class="p-1">'.$object->correo.'</td>';
            echo '<td class="p-1">'.$object->telefono.'</td>';
            echo '<td class="p-1">'.$object->area.'</td>';
            echo '<td class="p-1">'.$object->cargo.'</td>';
            echo '<td class="p-1"><span class="badge badge-'.(($object->cod_estado_recurso == 'A')?"success":"danger").'">'.$estados[$object->cod_estado_recurso].'</span></td>';
            
           echo '</tr>';            
        }        
        echo '</tbody></table>';
        echo '<script>
            $(document).ready(function(){
              $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#tb_search tr").filter(function() {
                  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
              });
            });
            $( "#exportar" ).click(function() {
              	window.open( "exportar.php?view='.$_view.'" );      	
            	return false;
            });
            </script>';
    }
?>
</div>