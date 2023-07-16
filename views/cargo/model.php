<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'cargo',
            'name' => "Cargo",
            'required' => true
        ))
    );
    
    $json_fields = json_encode( $model );
?>