<?


function _load($file, $path = '') {
    global $_;

    if(!$_['template'])
    {
        if($path)
        {
            if(is_array($path))
            {
                if(!in_array(strtolower($_['url']['path']), $path))
                {
                    return false;

                }

            } else {

                if(!expicmp($path, $_['url']['path']))
                {
                    return false;

                } else {

                    header($_SERVER['SERVER_PROTOCOL']." 200 OK");

                }

            }

        }

        if(!is_file($file))
        {
            return false;

        }

        ob_start();
        include_once($file);
        $_['template'] = $file;
        $string = ob_get_contents();
        @ob_end_clean();

        if(CHARSET == 'UTF-8' && extension_loaded('iconv'))
        {
            $string = iconv('CP1251', CHARSET, $string);

        }

        if($_['cache'] && !$path)
        {
            _insert(array('url' => substr($_SERVER['REQUEST_URI'], 0, 255), 'text' => mysql_escape_string($string)), 'cache');

        }

        print $string;

    }

}


function _dirs($dirname = null) {

    empty($dirname) ? $dirname = "*" : $dirname = $dirname."/*";

    $dir = _glob($dirname, GLOB_ONLYDIR);

    if($dir)
    {
        foreach($dir as $filename)
        {
            if(is_dir($filename))
            {
                $count = _dirs($filename, $file);

                if(count($count))
                {
                    $dir = array_merge($dir, $count);

                }

            }

        }

    }

    return $dir;
}


function _files($dirname, $filename = '') {

    $dirs = _dirs($dirname);
    $dirs[] = $dirname;
    $glob = array();
    $sort = array();

    foreach($dirs as $path)
    {
        if(is_array($filename))
        {
            foreach($filename as $file)
            {
                $glob = _glob($path.'/'.$file);
                $count_glob = count($glob);

                if($count_glob)
                {
                    if($count_glob == 1 && $glob[0] == 'modules/pages/_include.php')
                    {
                        $sort[][] = $glob[0];

                    } elseif($count_glob == 1) {

                        $key = array_search($file, $filename);
                        $sort[$key][] = $glob[0];

                    } else {

                        $key = array_search($file, $filename);

                        foreach($glob as $k => $v)
                        {
                            $sort[$key][] = $v;

                        }

                    }

                }

            }

        } else {

            $glob = _glob($path.'/'.$filename);

            if(count($glob))
            {
                $sort[] = $glob;

            }

        }

    }

    $files = array();

    foreach($sort as $k => $v)
    {
        $files = array_merge($files, $v);

    }

    return $files;

}


function _glob($file, $flag = 0) {

    if(!isset($_SESSION['glob'][$file]))
    {
        $_SESSION['glob'][$file] = glob($file, $flag);

    }

    return $_SESSION['glob'][$file];

}


function _is_file($filename = '') {

    if($filename == '' || $filename == '.' || $filename == '..')
    {
        return false;

    } else {

        if(stristr($filename, '.'))
        {
            return true;

        } else {

            return false;

        }

    }

}


function _filesize($val) {

    $ed = array('Áàéò', 'Êá', 'Ìá', 'Ãá', 'Òá');
    $zpz = 2;
    $i = 0;

    while(($val/1024) > 1)
    {
        $val = $val/1024;
        $i++;

    }

    return round($val, $zpz).' '.$ed[$i];

}


function _ext($file) {

    $pathinfo = pathinfo($file);
    return $pathinfo['extension'];

}


function _zip($source, $destination = '', $not_file = array())
{
    $is_array = is_array($source);

    if($destination == '')
    {
        $destination = $source.'.zip';

    }

    if(file_exists($destination))
    {
        unlink($destination);

    }

    if(!extension_loaded('zip') || (!$is_array && !file_exists($source)))
    {
        return false;

    }

    $zip = new ZipArchive();

    if(!$zip->open($destination, ZIPARCHIVE::CREATE))
    {
        return false;

    }

    if($is_array)
    {
        foreach($source as $file)
        {
            $zip->addFromString(basename($file), file_get_contents($file));

        }

    } else {

        $source = str_replace('\\', '/', realpath($source));

        if(is_dir($source) === true)
        {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

            foreach($files as $file)
            {
                $file = str_replace('\\', '/', realpath($file));

                if(is_dir($file) === true)
                {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));

                } elseif(is_file($file) === true) {

                    $file_name = str_replace($source . '/', '', $file);

                    if($not_file)
                    {
                        $ext_file = mb_strtolower(substr(strrchr($file_name, '.'), 1));

                        if($ext_file && in_array($ext_file, $not_file))
                        {
                            continue;

                        }

                    }

                    $zip->addFromString($file_name, file_get_contents($file));

                }

            }

        } elseif (is_file($source) === true) {

            $zip->addFromString(basename($source), file_get_contents($source));

        }

    }

    return $zip->close();

}


function _cache($no_path = '/admin/') {
    global $_;

    if(defined('CACHE') && intval(CACHE) && $_SERVER['SERVER_ADDR'])
    {
        if(!$_REQUEST && !$_['url']['query'])
        {
            if(isset($no_path))
            {
                $no_path = str_replace('/', '\/', $no_path);

                if(is_array($no_path))
                {
                    foreach($no_path as $k => $v)
                    {
                        if($v == '')
                        {
                            if($_['url']['path'] == $v)
                            {
                                $_['cache'] = false;
                                return false;

                            }

                        } else {

                            if(preg_match("/^".$v.".*$/", $_['url']['path']) || preg_match("/^.*\..*\/$/", $_SERVER['REQUEST_URI']))
                            {
                                $_['cache'] = false;
                                return false;

                            }

                        }

                    }

                } else {

                    if($no_path == '')
                    {
                        if($_['url']['path'] == $no_path)
                        {
                            $_['cache'] = false;
                            return false;

                        }

                    } else {

                        if(preg_match("/^".$no_path.".*$/", $_['url']['path']) || preg_match("/^.*\..*\/$/", $_SERVER['REQUEST_URI']))
                        {
                            $_['cache'] = false;
                            return false;

                        }

                    }

                }

            }

            $r = _array("SELECT id, text FROM cache WHERE url = '".$_SERVER['REQUEST_URI']."' AND UNIX_TIMESTAMP(last_modified)+".CACHE." >= UNIX_TIMESTAMP(NOW()) ORDER BY id DESC LIMIT 0, 1");

            if($r['id'])
            {
                if($r['text'] != '')
                {
                    echo $r['text'];
                    exit();

                }

            } else {

                _delete("cache", "WHERE url = '".$_['url']['path']."'");

            }

            $_['cache'] = true;

        } else {

            if($_POST)
            {
                if(isset($no_path))
                {
                    $no_path = str_replace('/', '\/', $no_path);

                    if(is_array($no_path))
                    {
                        foreach($no_path as $k => $v)
                        {
                            if($v == '')
                            {
                                if($_['url']['path'] == $v)
                                {
                                    _clear('cache');
                                    $_['cache'] = false;
                                    return false;

                                }

                            } else {

                                if(preg_match("/^".$v.".*$/", $_['url']['path']))
                                {
                                    _clear('cache');
                                    $_['cache'] = false;
                                    return false;

                                }

                            }

                        }

                    } else {

                        if($no_path == '')
                        {
                            if($_['url']['path'] == $no_path)
                            {
                                _clear('cache');
                                $_['cache'] = false;
                                return false;

                            }

                        } else {

                            if(preg_match("/^".$no_path.".*$/", $_['url']['path']))
                            {
                                _clear('cache');
                                $_['cache'] = false;
                                return false;

                            }

                        }

                    }

                }

            }

            _delete("cache", "WHERE url = '".$_['url']['path']."'");

            $_['cache'] = false;

        }

    } else {

        $_['cache'] = false;

    }

}


$_['upload'] = ROOT.'/styles/upload/';

function _upload($files_name = 'file', $to_dir = '', $extensions = 'file', $extensions_mode = 2, $access = '') {
    global $_;

    if(count($_FILES) > 0)
    {
        $u = new file_upload();

        if($to_dir == '')
        {
            if($_['upload'] == '')
            {
                return false;

            }

            $to_dir = $_['upload'];

        }

        if(is_string($extensions))
        {
            switch($extensions)
            {
                case 'file':

                    $extensions = array(
                        'exe', 'php', 'php3', 'php4', 'phtml', 'shtml', 'html', 'cgi', 'pl'
                    );

                    $extensions_mode = 1;

                break;

                case 'image':

                    $extensions = array(
                        'wbmp', 'aiff', 'tiff', 'jpeg', 'jfif', 'gif', 'jpg', 'png', 'psd', 'bmp', 'tif', 'jpc', 'jp2', 'jpf', 'jb2', 'swc', 'ico', 'cod', 'ief', 'ras', 'cmx', 'pnm', 'pbm', 'pgm', 'ppm', 'rgb', 'xpm', 'xwd', 'jps', 'xbm', 'fh'
                    );

                    $extensions_mode = 2;

                break;

                case 'flash':

                    $extensions = array(
                        'swf'
                    );

                    $extensions_mode = 2;

                break;

                case 'doc':

                    $extensions = array(
                        'pdf', 'doc', 'xls', 'ppt', 'rtf', 'txt', 'vsd', 'docx', 'xlsx', 'pptx'
                    );

                    $extensions_mode = 2;

                break;

                case 'archive':

                    $extensions = array(
                        '7z', 'zip', 'gzip', 'bzip2', 'tar', 'arj', 'cab', 'chm', 'cpio', 'deb', 'dmg', 'hfs', 'iso', 'lzh', 'lzma', 'msi', 'nsis', 'rar', 'rpm', 'udf', 'wim', 'xar', 'z'
                    );

                    $extensions_mode = 2;

                break;

            }

        }

        if(is_array($access) && count(_clear($access)))
        {
            if($extensions_mode == 1)
            {
                $extensions = $access;
                $extensions_mode  = 2;

            } else {

                $extensions = array_merge($extensions, $access);

            }

            $extensions = array_unique($extensions);

        }

        if(is_array($_FILES[$files_name]['name']))
        {
            $array = array();

            foreach($_FILES[$files_name] as $k => $v)
            {
                foreach($v as $k2 => $v2)
                {
                    $array[$k2][$k] = $v2;

                }

            }

            $upload = false;

            foreach($array as $k => $v)
            {
                if(!$v['error'])
                {
                    if(!$u->upload($v, $to_dir, 2, $extensions, $extensions_mode))
                    {
                        print '<br />Îøèáêà: '.$u->error;

                    } else {

                        $upload = true;

                    }

                }

            }

            return $upload;

        } else {

            if(!$_FILES[$files_name]['error'])
            {
                if(!$u->upload($_FILES[$files_name], $to_dir, 2, $extensions, $extensions_mode))
                {
                    print '<br />Îøèáêà: '.$u->error;

                } else {

                    return true;

                }

            }

        }

    }

}


?>
