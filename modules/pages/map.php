<ul class="map">

    <?

    $i = 0;

    foreach(_s("SELECT name, url FROM pages WHERE public = 1 AND menu = 1 ORDER BY ord ASC") as $r)
    {
        ?><li><a href="<?=(!$r['url'] ? '/' : $r['url'])?>"><?=$r['name']?></a></li><?

        $i++ ;

    }

    ?>

</ul>
