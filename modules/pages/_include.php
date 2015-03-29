<?


$array = array();
$str = '';

foreach($_['url']['this'] as $k => $v) {

    $f = $array[$k-1];
    $array[$k] = $f.'/'.$v;

    if(!$v)
    {
        unset($array[$k]);

    }

}

$array = array_reverse($array);
$last = '';

foreach($array as $k => $v)
{
    $str .= " || url='".$array[$k]."'"."\n";
    $last = $array[$k];

}

$str .= ($str && $last ? " || url='".$last."/'" : "")." ORDER BY url DESC";

$memory = array();

if(count($_[$¹]['this']))
{
    foreach($_[$¹]['this'] as $k => $v)
    {
        $memory[$k] = $v;

    }

}

$trim_path = rtrim($_['url']['path'], '/');

if(!$trim_path)
{
    $trim_path = '/';

}

$_[$¹]['this'] = _array("SELECT * FROM pages WHERE (url='".$_['url']['path']."' OR url='".$trim_path."') ".$str);

if(count($memory))
{
    foreach($memory as $k => $v)
    {
        $_[$¹]['this'][$k] = $v;

    }

}

if(isset($_[$¹]['this']['public']) && !$_[$¹]['this']['public'])
{
    $_['error'] = true;
    _s('404');

}

$flag = false;

foreach($_['url'] as $k => $v)
{
    if($_[$k]['id'])
    {
        $flag = true;
        break;

    }

}

if(!$flag && !$_[$¹]['this'])
{
    _s('404');

} elseif($_['url']['path'] != $_[$¹]['this']['url'] && $trim_path != $_[$¹]['this']['url']) {

    if(!$flag && $_['url']['path'])
    {
        _s('404');

    }

}

_last_modified($_[$¹]['this']['last_modified']);


?>
