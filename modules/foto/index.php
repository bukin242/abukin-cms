<?

$_['pages']['this']['name'] = $_['foto_admin']['name'];

foreach(_s("SELECT * FROM foto WHERE public = 1") as $r)
{
    ?>
    <a href="/styles/upload/<?=$r['image']?>" target="_blank">
        <?=$r['name']?><br />
        <img src="/styles/upload/<?=$r['image']?>"/>
    </a>
    <br />
    <?

}


?>
