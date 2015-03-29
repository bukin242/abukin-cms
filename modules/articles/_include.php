<?


$_['page']['id'] = _chpu('/^page([0-9]+).html/');
$_[$¹]['id'] = _chpu('', 'articles');

$_['articles']['sections'] = _s("SELECT name, chpu, id FROM articles WHERE public = '1'");


if($_[$¹]['id'])
{
    $_[$¹]['this'] = _array("SELECT * FROM articles WHERE id='".intval($_[$¹]['id'])."'");

    if(!$_[$¹]['this']['id'])
    {
        _s('404');

    } else {

        if(isset($_[$¹]['this']['public']) && !$_[$¹]['this']['public'])
        {
            $_[$¹]['this']['text'] = _s('404');

        }

    }

    if(!$_[$¹]['this']['name'])
    {
        $_[$¹]['this']['name'] = $_[$¹]['name'];

    }

}


?>
