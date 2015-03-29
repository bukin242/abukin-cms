<?


if(_login())
{
    print _s('admin/header.php');
    print _s('admin/module.php');
    print _s('admin/footer.php');

} else {

    print _s('admin/login.php');
    _redirect('/admin/');

}


?>
