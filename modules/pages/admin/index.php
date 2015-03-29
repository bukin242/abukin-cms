<?


_table(array(
        'ord' => array('Порядок', _ord()),
        'name' => array('Название', _link('edit', array('id'))),
        'url' => array('URL', _link()),
        'public' => array('Включено?', _check()),
        'menu' => array('Меню', _check()),
        'delete' => 'Удалить'
    ),
    array('url' => 'asc', 'name' => 'asc')
);


?>
