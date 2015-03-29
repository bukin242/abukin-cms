<?


$mod = '';
foreach(_s("SELECT modules FROM modules WHERE pages != ''") as $k => $v)
{
    $mod .= _s(_on($v['modules']));

}

echo _s('header'), _s('pages'), $mod, _s('footer');


?>
