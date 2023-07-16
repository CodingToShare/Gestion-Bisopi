<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'cliente',
            'name' => "Cliente",
            'required' => true
        )),
        array_map(utf8_encode ,array('id' => 'abreviatura',
            'name' => "Abreviatura",
            'required' => true
        )),
        array_map(utf8_encode ,array('id' => 'codigo_cliente',
            'name' => "Código Cliente",
            'required' => true
        ))
    );
    
    $json_fields = json_encode( $model );
?>