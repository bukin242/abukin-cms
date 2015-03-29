<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru"><head xmlns="">
<?

$str = 'Вход';

if((isset($_POST['user']) && $_POST['user'] == '') && (isset($_POST['pass']) && $_POST['pass'] == ''))
{
    $str = 'Введите логин и пароль';

} elseif(isset($_POST['user']) && isset($_POST['pass'])) {

    $str = 'Неверный логин или пароль';

}

?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title><?=$_[$№]['name']?> - Вход</title>
<link type="text/css" rel="stylesheet" href="/modules/admin/styles/css/login.css" />
<script type="text/javascript" language="javascript" src="<?=str_replace(ROOT, '', implode('', glob(ROOT.'/modules/admin/styles/js/jquery/*.js')))?>"></script>
<script>


    i = 0;

    function request() {

        i = i + 1;
        var str = new Array(i).join('.');

        $('.str').html('Проверка имени и пароля.'+str);

    }

    $(function() {

        $('#log').focus();

    });


</script>
</head>
<body><table xmlns="" width="100%" height="100%" id="loginBox"><tbody><tr><td align="center"><div id="lbox" class="clearfx"><div class="lbox_bottom"><div class="lbox_top"><div class="lbox_cont clearfx">
<div class="headertwo">
<h2 class="str"><?=$str?></h2>
<h2 class="headertwo_dark str"><?=$str?></h2>
</div>
<form class="login" action="" method="post" id="form_login" onsubmit="setInterval('request()', 250);">
<table width="100%"><tbody>
<tr>
<td><label for="log">Логин</label></td>
<td width="100%"><div class="lb"><input type="text" name="user" id="log"></div></td>
</tr>
<tr>
<td><label for="pass">Пароль</label></td>
<td><div class="lb"><input type="password" name="pass" id="pass"></div></td>
</tr>
</tbody></table>
<table width="100%"><tbody><tr>
<td>&nbsp;</td>
<td><div class="profile_edit floatr clearfx"><div class="profile_edit_bg"><div class="profile_edit_left"><div class="profile_edit_right"><input type="submit" value="Войти" class="submit"></div></div></div></div></td>
</tr></tbody></table>

</form>
</div></div></div></div></td></tr></tbody></table>
</body>
</html>
