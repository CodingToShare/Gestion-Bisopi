<div class="p-3">
<div class="h3" >Listado Estado Leads</div>
<?php
    if( count( $objects ) > 0 ){
        echo listDefault( 'estado_cotizacion' , $objects , $model ); 
    }
?>
</div>