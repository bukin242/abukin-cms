<?


require_once(dirname(__FILE__).'/../_.php');

_s(
    _files(ROOT.'/'.MOD, array(
        '_setting.php',
        '_functions.php')
    )
);


function get_url_code($url) {

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_exec($ch);

    return curl_getinfo($ch, CURLINFO_HTTP_CODE);

}


foreach(_s("SHOW TABLES") as $k => $v)
{
    $table = $v[key($v)];
    _query("OPTIMIZE TABLE ".$table);
    _query("CHECK TABLE ".$table);

    if(defined('CACHE') && intval(CACHE) && $table == 'cache')
    {
        $last_read = _s("SELECT DISTINCT(url) FROM ".$table);
        _clear($table);

    }

    $field[$table] = _is_fields($table);

}

$files_ = _files(ROOT.'/'.MOD, array('[!_]*.php'));

foreach($files_ as $k => $v)
{
    $files_[$k] = substr(_last_slash($v), strlen(ROOT.'/'.MOD));

    if($files_[$k][0] == '/')
    {
        $files_[$k] = substr($files_[$k], 1);

    }

    if($files_[$k][0].$files_[$k][1].$files_[$k][2].$files_[$k][3].$files_[$k][4] != 'admin')
    {
        if(!_row_count('modules', "WHERE modules = '".$files_[$k]."'"))
        {
            _insert(array('modules' => $files_[$k]), 'modules');

        }

    }

}

foreach(_s("SELECT * FROM modules") as $k => $v)
{
    if( is_file(ROOT.'/'.MOD.'/'.$v['modules']) ||
        is_file(ROOT.'/'.MOD.'/'.$v['modules'].'/index.php') ||
        is_file(ROOT.'/'.MOD.'/'.$v['modules'].'/index.htm') ||
        is_file(ROOT.'/'.MOD.'/'.$v['modules'].'/index.html')
    )
    {} else {

        _delete('modules', $v['id']);

    }

}

if(defined('CACHE') && intval(CACHE) && count($last_read))
{
    foreach($last_read as $k => $v)
    {
        $status = get_url_code('http://'.SITE.$v['url']);

        if($status == 404)
        {
            _delete('cache', " WHERE url='".$v['url']."'");

        }

    }

    _query("CHECK TABLE cache");

}

$find_upload_files = glob($_['upload'].'*+*+*.*');

foreach($find_upload_files as $k => $v)
{
    $file_upload = basename($v);
    $file_exp_ext = explode('.', $file_upload);
    $file_exp = explode('+', $file_exp_ext[0]);

    if($field[$file_exp[0]][$file_exp[1]])
    {
        $file_exp[2] = intval($file_exp[2]);

        if($file_exp[2])
        {
            $del_file = _field("SELECT ".$file_exp[1]." FROM ".$file_exp[0]." WHERE id = '".$file_exp[2]."'");

            if(!$del_file)
            {
                @unlink($v);

            }

        }

    }

}

foreach($field as $k => $v)
{
    if(isset($v['url']))
    {
        $r = _s("SELECT id, url FROM ".$k);
        $str = '';

        foreach($r as $k2 => $v2)
        {
            if($v2['url'])
            {
                $str = strrev($v2['url']);

                if($str[0] != '/')
                {
                    if(!_ext($v2['url']) && !strstr($v2['url'], '?'))
                    {
                        _update(array('url' => $v2['url'].'/'), $k, $v2['id']);

                    }

                }

            }

        }

    }

}

unset($_SESSION['glob']);


?>
