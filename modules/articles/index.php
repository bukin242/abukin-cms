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
            foreach(_s("SELECT * FROM articles_picture WHERE articles = '".$_[$¹]['id']."'") as $r)
            {
                ?>
                <div style="position:relative;">
                <img src="<?=$r['picture']?>" alt="<?=$_[$¹]['this']['name']?>" title="<?=$_[$¹]['this']['name']?>"/>
                <div style="position:absolute; top:0px; left:0px; width:100%; height:100%; background-image:url('/styles/images/pro.png');"></div>
                        <div style="position:absolute; left:<?=$r['square_left']?>px; top:<?=$r['square_top']?>px; width:<?=$r['square_width']?>px; height:<?=$r['square_height']?>px; border:1px solid red; background-position:-<?=$r['square_left']+1?>px -<?=$r['square_top']+1?>px; background-image:url('<?=$r['picture']?>');"></div>
                        <div style="position:absolute; left:<?=$r['price_left']?>px; top:<?=$r['price_top']?>px; width:<?=$r['price_width']?>px; height:<?=$r['price_height']?>px; background-position:-<?=$r['price_left']?>px -<?=$r['price_top']?>px; background-image:url('<?=$r['picture']?>');"></div>
                </div>
                <?

            }

            $_['pages']['this']['name'] = $_[$¹]['this']['name'];
            $_['pages']['this']['text'] = $_[$¹]['this']['text'];

        }

    }

}


?>
