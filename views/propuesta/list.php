<div class="p-3">
<div class="h3" >Listado Deals</div>
<?php
    if( count( $objects ) > 0 ){
        echo listDefault( 'propuesta' , $objects , $model ); 
    }
?>
</div>