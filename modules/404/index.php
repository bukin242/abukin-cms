<?


if($_['url']['this'][1] != 'admin')
{
    $error = false;

    if(!$_['error'])
    {
        foreach($_['url'] as $k => $v)
        {
            if($k != 'request' && $k != 'path' && ($_['url']['path'] == $v || $_['url']['path'].'/' == $v))
            {
                $error = $k;
                break;

            }

        }

    }

    if(!$error)
    {
        $_['pages']['error'] = 404;
        header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");

        $_['pages']['this']['name'] = 'Страница не найдена';
        ob_start();

        ?>
        <div class="<?=$№?>">
        404 : Данная страница готовится к публикации либо отсутствует на сервере! <br />
        Просим обратить ваше внимание на следующие разделы сайта: <?=_s('pages/map.php');?>
        </div>
        <?

        $_['module_'][$№] = ob_get_contents();
        ob_end_clean();

        $_['pages']['this']['text'] = $_['module_'][$№];

    } else {

        $explode_error = explode('/', $error);
        $error = $explode_error[0];

        if(!$_[$error]['name'])
        {
            _s(_files(ROOT.'/'.MOD.'/'.$error, '/admin/_setting.php'));

            if(!$_[$error]['name'])
            {
                $_[$error]['name'] = $_[str_replace('/', '_', dirname(ROOT)).'_'.MOD.'_'.$error.'__admin']['name'];

            }

        }

        $_['pages']['this']['name'] = ($_[$error]['name'] ? $_[$error]['name'] : $error);

    }

}


?>
