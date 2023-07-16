<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'area',
            'name' => "Area",
            'required' => true
        ))
    );
    
    $json_fields = json_encode( $model );
?>