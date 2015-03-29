<?


$a = _s("SELECT * FROM foto ".($where ? $where : " WHERE public = '1'")." ORDER BY id DESC ");

foreach($a as $k => $v)
{
    $modules = $k % 3;

    if(!$modules)
    {
        $width += $v['width'] + $a[$k + 1]['width'] + $a[$k + 2]['width'];
        $padding = intval((820 - $width) / 6);
        $padding += $padding / 2;
        $width = 0;

    }

    print '
        <div style="display:block; float:left; padding-top:10px; padding-bottom:10px; padding-left:'.(!$modules ? 0 : $padding).'px; padding-right:'.($modules == 2 ? 0 : $padding).'px;">
            <a href="/kvartira/foto/'.$v['url'].'" target="_blank" class="colorbox" rel="colorbox" title="'.$v['name'].'"><img src="/kvartira/foto_small/'.$v['url'].'" align="left" alt="'.$v['name'].'" title="'.$v['name'].'"/></a>
        </div>
    ';

}


?>
