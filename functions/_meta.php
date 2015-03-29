<?


function _title($title = '') {
    global $_;

    if(!$_['title'])
    {
        if($_['pages']['this']['title'])
        {
            $_['title'] = $_['pages']['this']['title'];

        } else {

            $_['title'] = $_['pages']['this']['name'];

        }

    }

    if($title != '')
    {
        $_['title'] = $title;

    }

    return $_['title'];

}


function _description($description = '') {
    global $_;

    if(!$_['description'])
    {
        if($_['pages']['this']['description'])
        {
            $_['description'] = $_['pages']['this']['description'];

        } else {

            $_['description'] = $_['pages']['this']['name'];

        }

    }

    if($description != '')
    {
        $_['description'] = $description;

    }

    $_['description'] = _symbols_trim($_['description']);

    return $_['description'];

}


function _keywords($keywords = '') {
    global $_;

    if(!$_['keywords'])
    {
        if($_['pages']['this']['keywords'])
        {
            $_['keywords'] = $_['pages']['this']['keywords'];

        } else {

            $_['keywords'] = $_['pages']['this']['name'];

        }

    }

    if($keywords != '')
    {
        $_['keywords'] = $keywords;

    }

    $_['keywords'] = _symbols_trim($_['keywords']);
    $_['keywords'] = mb_strtolower($_['keywords']);

    return $_['keywords'];

}


function _meta() {
    global $_;

    $_['pages']['meta'] .= '
    <meta name="description" content="'._description().'" />
    <meta name="keywords" content="'._keywords().'" />
    <link type="image/vnd.microsoft.icon" rel="icon" href="http://'.SITE.'/favicon.ico" />
    <link type="image/vnd.microsoft.icon" rel="shortcut icon" href="http://'.SITE.'/favicon.ico" />'."\n";

    return $_['pages']['meta'];

}


function _style($path = '') {

    $all_style = '';
    $styles = glob(STYLE.'/'.($path ? $path.'/' : '').'[!@]*.css');

    foreach($styles as $k    => $v)
    {
        $all_style .= ($k ? "\t" : "").'<link type="text/css" rel="stylesheet" href="/'.$v.'" />'."\n";

    }

    return $all_style;

}


function _script($path = '') {

    $all_script = '';
    $script = glob(SCRIPT.'/'.($path ? $path.'/' : '').'[!@]*.js');

    foreach($script as $k => $v)
    {
        $all_script .= ($k ? "\t" : "").'<script type="text/javascript" language="javascript" src="/'.$v.'"></script>'."\n";

    }

    return $all_script;

}


function _plugin($path = '') {

    $css = _files(PLUGIN.($path ? '/'.$path : ''), array('[!@]*.css'));
    $js = _files(PLUGIN.($path ? '/'.$path : ''), array('[!@]*.js'));

    $all = '';

    foreach($css as $k => $v)
    {
        $all .= '<link type="text/css" rel="stylesheet" href="/'.$v.'" />'."\n"."\t";

    }

    foreach($js as $k => $v)
    {
        $all .= ($k ? "\t" : "").'<script type="text/javascript" language="javascript" src="/'.$v.'"></script>'."\n";

    }

    return $all;

}


function _jquery($plugin_name = '') {

    if(is_array($plugin_name))
    {
        $str = '';

        foreach($plugin_name as $k => $v)
        {
            if($v != '')
            {
                if($v == 'jquery')
                {
                    $v = '';

                } else {

                    $v = '.'.$v;

                }

            }

            $str .= _plugin('jquery'.$v);

        }

        return $str;

    } else {

        if($plugin_name != '')
        {
            if($plugin_name == 'jquery')
            {
                $plugin_name = '';

            } else {

                $plugin_name = '.'.$plugin_name;

            }

        }

        return _plugin('jquery'.$plugin_name);

    }

}


?>
