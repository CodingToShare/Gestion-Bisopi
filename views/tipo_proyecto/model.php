<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'tipo_proyecto',
            'name' => "Tipo Proyecto",
            'required' => true
        ))
    );
    
    $json_fields = json_encode( $model );
?>