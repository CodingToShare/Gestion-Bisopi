<?php
$model = array(
    array_map(utf8_encode ,array('id' => 'propuesta',
        'name' => "Deal",
        'required' => true
    )),
    array_map(utf8_encode ,array('id' => 'cod_estado_propuesta',
        'name' => "Estado Deal",
        'required' => true,
        'type' => "combo"
    )),
    array_map(utf8_encode ,array('id' => 'cod_cliente',
        'name' => "Cliente",
        'required' => true,
        'type' => "combo"
    )),
    array_map(utf8_encode ,array('id' => 'responsable',
        'name' => "Responsable Cliente",
        'required' => false
    )),
    array_map(utf8_encode ,array('id' => 'cod_moneda',
        'name' => "Moneda",
        'required' => false,
        'type' => "combo"
    )),
    array_map(utf8_encode ,array('id' => 'valor',
        'name' => "Valor",
        'required' => false,
        'type' => "value"
    )),
    array_map(utf8_encode ,array('id' => 'comentario',
        'name' => "Comentarios",
        'required' => false
    ))
);
    
    $json_fields = json_encode( $model );
?>