<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'estudio',
            'name' => "Estudio",
            'required' => true
        ))
    );
    
    $json_fields = json_encode( $model );
?>