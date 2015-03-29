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
    'name' => 'Название',
    'url' => array('URL', _text('/')),
    'text' => 'Текст',
    'title' => 'Title (заголовок)',
    'keywords' => 'Keywords (ключ. сл.)',
    'description' => 'Description (описание)',
),
    (isset($query['add']) ? _add($array) : _edit($query))
);


?>
