<div class="p-3">
<div class="h3" >Listado Solicitudes abrir semana</div>
<?php
    if( count( $objects ) > 0 ){
        echo listDefault( 'cierre_permiso' , $objects , $model , 'left' ); 
    }
?>
</div>