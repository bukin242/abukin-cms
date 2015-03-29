<?


@require_once('_.php');

/***
    Инициализация модулей
***/
_s(
    _files(MOD, array(
    '_setting.php',
    '_functions.php',
    '_include.php')
    )
);

_s(_files(PLUGIN, array('_functions.php')));

/***
    Предопределенные страницы имеющие свой шаблон.
***/
_load(MOD.'/admin/index.php', '/admin');
_load('template.php');

_logerror(true);

_close($template_);


?>
