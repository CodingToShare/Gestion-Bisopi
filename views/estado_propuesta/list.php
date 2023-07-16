<div class="p-3">
<div class="h3" >Listado Estado Deals</div>
<?php
    if( count( $objects ) > 0 ){
        echo listDefault( 'estado_propuesta' , $objects , $model ); 
    }
?>
</div>