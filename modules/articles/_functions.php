<?


function map_articles($id, $is, &$array, $i) {

    foreach($is as $k => $v)
    {
        if($v['id'] == $id)
        {
            $parent = $is[$k];
            break;

        }

    }

    if(!$i)
    {
        $array = array();

    }

    $i++ ;

    if($parent['parent'])
    {
        if($parent['chpu'])
        {
            $array[] = $parent['chpu'];

        }

        map_articles($parent['parent'], $is, $array, $i);

        return array_reverse($array);

    }

}



?>
