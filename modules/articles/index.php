<?


prev($_['chpu']);

if($_['pages']['error'] != 404)
{

    if($_[$¹]['id'] || $_['chpu'][$_[$¹]['id']] && $_[$¹]['this']['public'] == 1)
    {
        _title($_[$¹]['this']['name']);
        _keywords($_[$¹]['this']['keywords']);
        _description($_[$¹]['this']['description']);

        if($_[$¹]['this']['id'])
        {
            $_['pages']['this']['name'] = $_[$¹]['this']['name'];
            $_['pages']['this']['text'] = $_[$¹]['this']['text'];

            ?>
            <br /><br />
            <p><a href="<?=$_['url'][$¹]?>">Return back</a></p>
            <?

        }

    } else {

            foreach(_s("SELECT * FROM articles WHERE public = 1") as $r)
            {
                ?>
                <a href="<?=$_['url'][$¹]?><?=($r['chpu'] ? $r['chpu'] : '?id='.$r['id'])?>"><?=$r['name']?></a>
                <br />
                <?

            }

    }

}


?>
