<div class="art-box art-block">
    <div class="art-box-body art-block-body">
                <div class="art-bar art-blockheader">
                    <h3 class="t">Мои сайты</h3>
                </div>
                <div class="art-box art-blockcontent">
                    <div class="art-box-body art-blockcontent-body">
<?
$r = glob('/home/site/*/_.php');
$array = array();

foreach ($r as $k => $v)
{
    $v = str_replace(array('/home/site/', '/_.php'), '', $v);
    $array[$v] = $v;

}

unset($array[SITE]);
unset($array['mama']);

if (count($array))
{
    ?><ul><?

    foreach ($array as $k => $v)
    {
        ?><li><span style="color: rgb(38, 41, 44); line-height: normal;"><a href="http://<?=$k?>" target="_blank"><?=$v?></a></span></li><?

    }

    ?>
    </ul><?

}
?>
    <div class="cleared"></div>

                    </div>
                </div>
        <div class="cleared"></div>
    </div>
</div>
