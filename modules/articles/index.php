<?


prev($_['chpu']);

if($_['pages']['error'] != 404)
{

    if($_[$�]['id'] || $_['chpu'][$_[$�]['id']] && $_[$�]['this']['public'] == 1)
    {
        _title($_[$�]['this']['name']);
        _keywords($_[$�]['this']['keywords']);
        _description($_[$�]['this']['description']);

        if($_[$�]['this']['id'])
        {
            $_['pages']['this']['name'] = $_[$�]['this']['name'];
            $_['pages']['this']['text'] = $_[$�]['this']['text'];

            ?>
            <br /><br />
            <p><a href="<?=$_['url'][$�]?>">Return back</a></p>
            <?

        }

    } else {

            foreach(_s("SELECT * FROM articles WHERE public = 1") as $r)
            {
                ?>
                <a href="<?=$_['url'][$�]?><?=($r['chpu'] ? $r['chpu'] : '?id='.$r['id'])?>"><?=$r['name']?></a>
                <br />
                <?

            }

    }

}


?>
