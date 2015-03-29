<?


$query = $_['url']['query_array'];

_form(array(
    'name' => 'Название',
    'image' => array('Картинка', _image()),
),
    (isset($query['add']) ? _add() : _edit($query))
);


?>
