<?php
if( count( $objects ) > 0 ){
    echo '<div class="row">';
    $contador = 0;
    foreach ( $objects as $object ){
        if( ($contador%3) == 0 && $contador!=0 )
            echo '</div><div class="row">';
            
            echo '<div class="col-sm-4">
                    <div class="card mb-3 shadow bg-white rounded">
                         <h5 class="card-header bg-dark text-white">'.$object->recurso.'</h5>
                        <div class="card-body">
                            <h5 class="card-title">Cargo '.$object->cargo.'</h5>
                            <p class="card-text">
                                <span class="font-weight-bold">Correo:</span> <span class="small">'.$object->correo.'</span>
                            </p>
                            <p class="text-right">
                                <a href="index.php?view='.$_view.'&action=agregar&id='.$object->id_recurso.'" class="btn btn-primary">Editar</a>
                                <a href="#" onclick="xajax_confirmarEliminar(\'recurso\',\''.$object->id_recurso.'\')" class="btn btn-dark">Eliminar</a>
                            </p>
                        </div>
                     </div>
                    </div>';
            $contador++;
    }
    
    echo '</div>';
}
?>