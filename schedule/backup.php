<?


require_once(dirname(__FILE__).'/../_.php');

_s(
    _files(ROOT.'/'.MOD, array(
        '_setting.php',
        '_functions.php')
    )
);

$date = _now("Y.m.d");
$dump_file = ROOT.'/'.BASE.'_dump.sql';
$path = '/tmp/'.SITE.'.zip';
$email = 'backup@'.SITE;

exec("mysqldump -u".USER." -p".PASS." ".BASE." > ".$dump_file);

if(_zip(ROOT, $path, array('jpg', 'jpeg', 'png', 'gif')))
{
    $mail = new Mail;
    $mail->From($email);
    $mail->To($email);
    $mail->Attach($path, SITE.'_'.$date.'.zip', 'application/x-zip-compressed');
    $mail->Subject(SITE.' '.$date);
    $mail->Body("Дата: ".$date."\n");
    $mail->Send();

    unlink($path);
    unlink($dump_file);

}


?>
