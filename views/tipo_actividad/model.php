<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'tipo_actividad',
            'name' => "Tipo Actividad",
            'required' => true
        ))
    );
    
    $json_fields = json_encode( $model );
?>