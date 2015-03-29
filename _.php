<?


error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
$sql_ =  array();
$_ = $GLOBALS;

/***
    Настройки сайта.
***/
define('SITE', 'abukin.ru');
define('ROOT', dirname(__FILE__));
define('FUNC', ROOT.'/functions');
define('MOD', 'modules');
define('ADM', 'admin');
define('STYLE', 'styles/css');
define('SCRIPT', 'styles/js');
define('PLUGIN', 'styles/js');
define('IMG', 'styles/images');
// define('CACHE', 43210);


/***
    Настройки базы данных.
***/
define('HOST', 'localhost');
define('BASE', 'cms');
define('USER', 'root');
define('PASS', '123');


_s(glob(FUNC.'/*.php'));
_connect('CP1251');
_cache(array('/admin/'));
_on();


/***
    Командный модуль.
***/
function _s($run) {
    global $sql_, $module_;

    $_ = $GLOBALS;

    if($run == '')
    {
        return '';

    }

    if(is_array($run))
    {
        foreach($run as $k => $v)
        {
            if(is_file($v))
            {
                if(function_exists('module_name'))
                {
                    $№ = module_name($v);

                }

                @require_once($v);

            }

        }

    } else {

        $run = trim($run);
        $files = array();

        if($_['modules'][$run])
        {
            $files[] = $_['modules'][$run];

        } else {

            if($run[0].$run[1].$run[2].$run[3].$run[4].$run[5] != 'SELECT')
            {
                $files = glob(MOD.'/'.$run.'/index.php');

                if(!$files)
                {
                    $files = glob(MOD.'/'.$run);

                }

            }

        }

        if($_['modules'][$run] || count($files))
        {
            if(_field("SELECT id FROM modules_off WHERE pages='".$_['url']['path']."' AND modules='".$run."'"))
            {
                $module_[$run] = " ";
                return false;

            }

            if($module_[$run])
            {
                return $module_[$run];

            } else {

                foreach($files as $k => $v)
                {
                    if(is_file($v))
                    {
                        if(function_exists('module_name'))
                        {
                            $№ = module_name($v);

                        }

                        ob_start();

                        @require_once($v);
                        $module_[$run] .= ob_get_contents();

                        ob_end_clean();

                    }

                }

                return $module_[$run];

            }

        } else {

            $sql_[$run] = array();

            if(!@$sql_[$run])
            {
                $r = _query($run);

                while($v = _row($r))
                {
                    $sql_[$run][] = $v;

                }

            }

            return $sql_[$run];

        }

    }

}


?>
