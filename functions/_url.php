<?


$_['url']['request']      = $_SERVER['REQUEST_URI'];
$_['url']['referer']      = parse_url($_SERVER['HTTP_REFERER']);
$_['url']['referer']['url'] = (!$_['url']['referer']['path'] ? '/' : $_SERVER['HTTP_REFERER']);
$parse_url                = parse_url($_['url']['request']);
parse_str($parse_url['query'], $query_array);
$_['url']['query']        = (strlen($parse_url['query']) ? '?'.$parse_url['query'] : '');
$_['url']['query_array']  = $query_array;

$_['url']['path']         = $parse_url['path'];
$_['url']['path']         = _last_slash($_['url']['path']);

$_['url']['this']         = explode('/', $_['url']['path']);
$_['url']['this_reverse'] = array_reverse($_['url']['this']);

$_['url']['this']         = _nkey($_['url']['this']);
$_['url']['this_reverse'] = _nkey($_['url']['this_reverse']);

if($_['url']['this_reverse'][0])
{
    if(!strstr($_['url']['this_reverse'][0], '.'))
    {
        $_['url']['path'] = $_['url']['path'].'/';

    }

}

function _last_slash($path) {

    $path = preg_replace('/\/\/+/', '/', $path);
    $path = rawurldecode($path);
    $path = iconv("UTF-8", "CP1251", $path);
    $path = str_replace(array('\'', '"', 'index.html', 'index.htm', 'index.php'), '', $path);

    $path = rtrim($path, '/');

    return $path;

}


function _on($module_name = false, $path_include = false) {
    global $_;

    if($module_name)
    {
        if(isset($_[$module_name]['path']))
        {
            return ;

        }

        if(!$path_include)
        {
            $query = _query("SELECT pages FROM modules WHERE modules='".$module_name."'");

            while($r = _row($query))
            {
                $path_include[] = $r['pages'];

            }

        }

        if(is_array($path_include))
        {
            foreach($path_include as $v)
            {
                $last_slash = _last_slash($v);

                if(expicmp($last_slash, $_['url']['path']))
                {
                    $_[$module_name]['path'] = $last_slash;
                    $_['pages']['this']['url'] = $last_slash;

                }

            }

            if(!isset($_[$module_name]['path']) || (isset($_['pages']['this']['public']) && !$_['pages']['this']['public']))
            {
                $_['module_'][$module_name] = ' ';
                return ;

            }

        } else {

            $last_slash = _last_slash($path_include);

            if(!expicmp($last_slash, $_['url']['path']) || (isset($_['pages']['this']['public']) && !$_['pages']['this']['public']))
            {
                $_['module_'][$module_name] = ' ';
                return ;

            }

            $_[$module_name]['path'] = $last_slash;
            $_['pages']['this']['url'] = $last_slash;

        }

    } else {

        $query = _query("SELECT pages, modules FROM modules");

        while($r = _row($query))
        {
            if(is_file(ROOT.'/'.MOD.'/'.$r['modules'].'/index.php'))
            {
                $_['modules'][$r['modules']] = MOD.'/'.$r['modules'].'/index.php';

            } elseif (is_file(ROOT.'/'.MOD.'/'.$r['modules'])) {

                $_['modules'][$r['modules']] = MOD.'/'.$r['modules'];

            } else {

                $_['modules'][$r['modules']] = false;

            }

            if($r['pages'])
            {
                if(!strstr($r['pages'], '.'))
                {
                    $reverse = strrev($r['pages']);

                    if($reverse[0] != '/')
                    {
                        $r['pages'] = $r['pages'].'/';

                    }

                }

                $_['url'][$r['modules']] = $r['pages'];

            }

        }

    }

    return $module_name;

}


function _pages($array, $count = 0) {
    global $_;

    if(is_array($array))
    {
        if($count)
        {
            $_['pages']['count'] = $count;
            $_['pages']['max'] = count($array);

            if((($_['pages']['pages'] * $count) - $count) < 1)
            {
                $_['pages']['pages'] = 1;

            }

            $array = array_slice($array, ($_['pages']['pages'] * $count) - $count, $count);

        }

    }

    return $array;

}


function _chpu($pattern = '', $table = '') {
    global $_;

    $preg = preg_match($pattern, $_['url']['this_reverse'][0], $matches);

    if(isset($matches[1]))
    {
        $id = intval($matches[1]);

        if(!$table)
        {
            $_['pages']['pages'] = $id;

        } else {

            $id = _field("SELECT id FROM ".$table." WHERE id='".$id."'");

            if(!$id)
            {
                _s('404');

            } else {

                if(!$_['url']['this'][0] && count($_['url']['this']) <= 2)
                {
                    $_['url']['path'] = '';

                }

            }

        }

    } else {

        if($table)
        {
            if($_['url']['this_reverse'][0])
            {
                if(_is_field('chpu', $table))
                {
                    if(_is_field('parent', $table))
                    {
                        $pages = _s("SELECT id, parent, chpu FROM ".$table." WHERE chpu "._in($_['url']['this']));
                        $count_pages = _count($pages);
                        $count_url = _count($_['url']['this'])-1;

                        if($count_pages < $count_url)
                        {
                            return false;

                        }

                        if($count_pages <= 1)
                        {
                            return $pages[0]['id'];

                        } else {

                            $array = array();

                            foreach($pages as $key => $val)
                            {
                                $array[$val['id']] = $val;

                            }

                            $array = array_reverse($array, true);
                            $i = 0;

                            foreach($array as $key => $val)
                            {
                                if($array[$val['parent']] && $array[$val['parent']]['chpu'] == $_['url']['this_reverse'][1])
                                {
                                    return $val['id'];

                                } else {

                                    $i++ ;

                                }

                            }

                            if($i == $count_url)
                            {
                                return false;

                            }

                            $count = _count("SELECT id FROM ".$table." WHERE chpu = '".$_['url']['this_reverse'][0]."'");

                            if($count > 1)
                            {
                                foreach($array as $key => $val)
                                {
                                    $field = _field("SELECT parent FROM ".$table." WHERE id = '".$val['parent']."'");

                                    if($field == 0)
                                    {
                                        return $val['id'];

                                    }

                                }

                            } else {

                                return _field("SELECT id FROM ".$table." WHERE chpu = '".$_['url']['this_reverse'][0]."'");

                            }

                        }

                    } else {

                        $id = _field("SELECT id FROM ".$table." WHERE chpu = '".$_['url']['this_reverse'][0]."'");

                    }

                }

            }

        }

    }

    if($id)
    {
        return $id;

    } else {

        return false;

    }

}


function _redirect($url, $type = '301 Moved Permanently') {

    if($url != $_SERVER['REQUEST_URI'])
    {
        if(!$url)
        {
            $url = '/';

        }

        if($type)
        {
            header($_SERVER['SERVER_PROTOCOL']." ".$type);

        }

        header("Location: ".$url);

    }

}


function _url($str) {

    if($str[0].$str[1].$str[2].$str[3] == 'http')
    {
        return $str;

    } elseif($str[0].$str[1].$str[2].$str[3].$str[4] == 'https') {

        return $str;

    }

    return '/'.$str;

}


?>
