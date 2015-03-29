<div class="art-box art-block">
    <div class="art-box-body art-block-body">
                <div class="art-bar art-blockheader">
                    <h3 class="t">Дизайны сайтов</h3>
                </div>
                <div class="art-box art-blockcontent">
                    <div class="art-box-body art-blockcontent-body">
<ul>
<?

    foreach (_s('SELECT * FROM templates ORDER BY name ASC') as $k => $v)
    {
        ?><li><span style="color: rgb(38, 41, 44); line-height: normal;"><a href="/templates/#<?=$v['name']?>" target="_blank"><?=$v['name']?></a></span></li><?

    }

?>
</ul>
    <div class="cleared"></div>

                    </div>
                </div>
        <div class="cleared"></div>
    </div>
</div>
