<?


$query = $_['url']['query_array'];

_form(array(
    'name' => '��������',
    'image' => array('��������', _image()),
),
    (isset($query['add']) ? _add() : _edit($query))
);


?>
