<?


$query = $_['url']['query_array'];

if(_is_field('ord', $_['table']))
{
    $array = array(
        'ord' => _field("SELECT max(ord)+1 FROM ".$_['table'])
    );

} else {

    $array = array();

}

_form(array(
    'name' => '��������',
    'url' => array('URL', _text('/')),
    'text' => '�����',
    'title' => 'Title (���������)',
    'keywords' => 'Keywords (����. ��.)',
    'description' => 'Description (��������)',
),
    (isset($query['add']) ? _add($array) : _edit($query))
);


?>
