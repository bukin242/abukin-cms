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
    'name' => '��������',
    'chpu' => array('���', _text()),
    'text' => '�����',
    'title' => 'Title (���������)',
    'keywords' => 'Keywords (����. ��.)',
    'description' => 'Description (��������)',
),
    (isset($query['add']) ? _add($chpu) : _edit($query))
);


?>
