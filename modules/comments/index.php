<div class="art-postcontent">
<?


function _comment_add() {
    global $_;

    $_['forms'][$_['form']['id']]['text'] = substr($_['forms'][$_['form']['id']]['text'], 0, 1500);

    $r = _forms_add("comments");

    if($r)
    {
        $mail = new Mail;
        $mail->From(($r['email'] ? $r['email'] : $_['settings']['email']));
        $mail->To($_['settings']['email']);

        if($_['settings']['bcc_email'])
        {
            $mail->Bcc(array_map('trim', explode(',', $_['settings']['bcc_email'])));

        }

        $mail->Subject('Новый отзыв на сайте Parkovyi.ru');
        $mail->Body("Дата: "._now()."\n"."Имя: ".$r['name']."\n"."E-mail: ".$r['email']."\n"."Отзыв: ".$r['text']);
        $mail->Send();

        _redirect("?add");

    }

}

$a = _s("SELECT * FROM comments WHERE public = '1' ORDER BY id DESC");

if(_count($a))
{
    foreach($a as $k => $v)
    {
        ?>
        <div class="comment <?=($k? 'hr' : '')?>"><? if($v['email']) { ?><a rel="nofollow" href="mailto:<?=$v['email']?>" target="_blank"><? } ?><strong><?=$v['name']?><? if($v['email']) { ?></a><? } ?></strong><br />
        <?=$v['text']?>
        </div>
        <?

    }

} else {

    ?>Напишите первый отзыв.<?

}

if(isset($_['url']['query_array']['add']) && !isset($_REQUEST['form_'.$_['form']['id']]))
{
    $referer = parse_url($_SERVER['HTTP_REFERER']);

    if($referer['path'] == $_['url']['path'].'/')
    {
        _highlight("Ваше сообщение опубликовано!");

    } else {

        _redirect($_['url']['path']);

    }

}

?>
</div>
<h2>Оставить свой отзыв</h2>
<?

_forms(
    _input('Имя', '*', 'name', 'text').
    _input('E-mail', '', 'email', 'email').
    _input('Отзыв', '*', 'text', 'textarea').
    _input('Перетащите или кликните на правильную фигуру', '*', 'captcha', 'captcha').
    _input('', '', 'submit', 'submit', 'Отправить', '_comment_add();')
);


?>
