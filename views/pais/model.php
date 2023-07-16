<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'pais',
            'name' => "País",
            'required' => true
        )),
        array_map(utf8_encode ,array('id' => 'abreviatura',
            'name' => "Abreviatura",
            'required' => true
        ))
    );
    
    $json_fields = json_encode( $model );
?>