<?


$query = $_['url']['query_array'];

_form('',
    (isset($query['add']) ? _add() : _edit($query))
);



?>
