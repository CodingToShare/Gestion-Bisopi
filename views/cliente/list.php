<div class="p-3">
<div class="h3" >Listado Clientes</div>
<?php
    if( count( $objects ) > 0 ){
        echo listDefault( 'cliente' , $objects , $model ); 
    }
?>
</div>