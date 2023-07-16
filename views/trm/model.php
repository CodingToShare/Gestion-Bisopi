<?php
$model = array(    
    array_map(utf8_encode ,array('id' => 'trm',
        'name' => "TRM Proyectada",
        'required' => true
    )),
    array_map(utf8_encode ,array('id' => 'cod_moneda',
        'name' => "Moneda",
        'required' => true,
        'type' => "combo"
    )),
    array_map(utf8_encode ,array( 'id' => 'anio',
        'name' => "A�o",
        'required' => true
    ))
);

$json_fields = json_encode( $model );
?>