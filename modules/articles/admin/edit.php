<?


$query = $_['url']['query_array'];

if(trim($_POST['chpu']) == '')
{
    $chpu = array(
        'chpu' => _translit(mb_strtolower($_POST['name'])).'.html'
    );

} else {

    $chpu = '';

}

_form(array(
    'name' => 'Название',
    'chpu' => array('ЧПУ', _text()),
    'text' => 'Текст',
    'title' => 'Title (заголовок)',
    'keywords' => 'Keywords (ключ. сл.)',
    'description' => 'Description (описание)',
),
    (isset($query['add']) ? _add($chpu) : _edit($query))
);


?>
