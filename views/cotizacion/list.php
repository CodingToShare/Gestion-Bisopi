<div class="p-3">
<div class="h3" >Listado Leads</div>
<?php
    if( count( $objects ) > 0 ){
        echo listDefault( 'cotizacion' , $objects , $model ); 
    }
?>
</div>