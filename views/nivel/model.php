<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'nivel',
            'name' => "Nivel",
            'required' => true
        ))
    );
    
    $json_fields = json_encode( $model );
?>