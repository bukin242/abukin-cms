<div id="main">
    <?

    $module = $_['table'] = $_['url']['this'][2];

    $files = _files(ROOT.'/'.MOD.'/*/admin/');

    unset($_['modules']);

    foreach($files as $k => $v)
    {
        $name = str_replace(ROOT.'/'.MOD.'/', '', $v);
        $name = dirname($name);
        $name = module_name($name);
        $_['modules'][$name] = ROOT.'/'.MOD.'/'.$name.'/';

    }

    $menu = array();

    foreach($_['url']['this'] as $k => $v)
    {
        if($v != 'admin' && $v != $module)
        {
            $menu[] = $v;

        }

    }

    $module_menu = implode('/', $menu);

    if(is_numeric($_['url']['this'][3]))
    {
        $module_menu = str_replace('/'.$_['url']['this'][3], '', $module_menu);
        define('PAGE', $_['url']['this'][3]);

        if(PAGE > 1)
        {
            $page_name = ' - страница '.PAGE;

        }

    }

    if($module_menu || $_['url']['query'])
    {
        $module_url = str_replace('/admin/'.$module.'/', '', $_['url']['path'].($_['url']['query'] ? '/'.$_['url']['query'] : ''));
        $module_head = $_[$module.'_admin']['menu'][$module_url];

        if($module_head)
        {
            $module_head = ' - '.$module_head;

        }

    }


    if(is_file(MOD.'/'.$module."/admin".($module_menu ? $module_menu.'.php' : '/index.php')))
    {
        $module_index = _s($module."/admin".($module_menu ? $module_menu.'.php' : ''));

    }

    if($module && $_['admin']['id'])
    {
        if(_is_field('name', $module))
        {
            $page_name = @mysql_result(mysql_query("SELECT name FROM ".$module." WHERE id='".$_['admin']['id']."'"), 0, false);

        }

        if(_is_field('url', $module))
        {
            $page_url = @mysql_result(mysql_query("SELECT url FROM ".$module." WHERE id='".$_['admin']['id']."'"), 0, false);

            if($page_url)
            {
                if($page_name == '')
                {
                    $page_name = $page_url;

                } else {

                    $page_name = '<a href="'.$page_url.'" target="_blank">'.$page_name.'</a>';

                }

            }

        } elseif(_is_field('chpu', $module)) {

            $page_url = @mysql_result(mysql_query("SELECT chpu FROM ".$module." WHERE id='".$_['admin']['id']."'"), 0, false);

            if($page_url)
            {
                if($page_name == '')
                {
                    $page_name = $page_url;

                } else {

                    $page_name = '<a href="'.rtrim($_['url'][$module], '/').'/'.$page_url.'" target="_blank">'.$page_name.'</a>';

                }

            }

        }

        $page_name = ($page_name ? ' - '.$page_name : '');

    }

    ?>
    <table id="admin">
        <tr>
            <td>
                <div class="menu">
                    <div class="bg left corner">
                        <h1>Модули</h1>

                        <?


                        foreach($_['modules'] as $k => $v)
                        {
                            if(!$_[$k]['name'] && $_[$k.'_admin'])
                            {
                                $_[$k]['name'] = $_[$k.'_admin']['name'];

                            }

                            if(!$_[$k]['name'])
                            {
                                $_[$k]['name'] = $k;

                            }

                            ?><a href="/admin/<?=$k?>/" class="modules"><?=$_[$k]['name']?></a><?

                        }

                        ?>

                        <a href="/admin/out/" class="modules">Выход</a>

                    </div>
                </div>
            </td>
            <td width="100%">
                <div class="bg content corner">
                    <h1><?=($_[$module]['name'] ? $_[$module]['name'] : $_[$№]['name']).$module_head.$page_name?></h1>

                    <?

                    if(count($_[$module.'_admin']['menu']))
                    {
                        ?><div class="admin_menu corner"><?

                        foreach($_[$module.'_admin']['menu'] as $k => $v)
                        {
                            ?><a href="/admin/<?=$module.'/'.$k?>" class="corner3"><?=$v?></a><?

                        }

                        ?></div><?

                    }

                    if(!is_array($module_index))
                    {
                        print $module_index;

                    }

                    ?>
                </div>
            </td>
        </tr>
    </table>
</div>
