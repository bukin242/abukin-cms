<?


$_['page']['id'] = _chpu('/^page([0-9]+).html/');
$_[$�]['id'] = _chpu('', 'articles');

$_['articles']['sections'] = _s("SELECT name, chpu, id FROM articles WHERE public = '1'");


if($_[$�]['id'])
{
    $_[$�]['this'] = _array("SELECT * FROM articles WHERE id='".intval($_[$�]['id'])."'");

    if(!$_[$�]['this']['id'])
    {
        _s('404');

    } else {

        if(isset($_[$�]['this']['public']) && !$_[$�]['this']['public'])
        {
            $_[$�]['this']['text'] = _s('404');

        }

    }

    if(!$_[$�]['this']['name'])
    {
        $_[$�]['this']['name'] = $_[$�]['name'];

    }

}


?>
