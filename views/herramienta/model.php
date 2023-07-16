<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'herramienta',
            'name' => "herramienta",
            'required' => true
        ))
    );
    
    $json_fields = json_encode( $model );
?>