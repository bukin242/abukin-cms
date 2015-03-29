<?


function _key($array, $shift = 1) {

    for($i = 1; $i < $shift; $i++)
    {
        next($array);

    }

    return @$array[key($array)];

}


/***
    Очищает массив от пустых значений или таблицу, если параметр - строка.
***/
function _clear($array_or_table) {

    if($array_or_table)
    {
        if(is_string($array_or_table))
        {
            return _truncate($array_or_table);

        } else {

            $array_or_table = array_diff($array_or_table, array(false));

        }

    }

    return @$array_or_table;

}


function _nkey($array) {

    $array_result = array();

    foreach($array as $v)
    {
        $array_result[] = $v;

    }

    return $array_result;

}


function _aval($array, $key_name = 'id') {

    $array_result = array();

    foreach($array as $k => $v)
    {
        $array_result[$k] = $v[$key_name];

    }

    return $array_result;

}


function _akval($array, $key_name = 'id', $val_name = 'id') {

    $array_result = array();

    foreach($array as $k => $v)
    {
        $array_result[$v[$key_name]] = $v[$val_name];

    }

    return $array_result;

}


function _aimp($array, $sep1 = '', $sep2 = '') {

    $str = '';
    $c = _count($array);

    if($c)
    {
        foreach($array as $k => $v)
        {
            $i++ ;
            $str .= $k.$sep1.$v.($c != $i ? $sep2 : '');

        }

    }

    return $str;

}


function _kimp($array, $sep = '') {

    $str = '';
    $c = _count($array);

    if($c)
    {
        foreach($array as $k => $v)
        {
            $str .= ($i ? $sep : '').$k;
            $i++ ;

        }

    }

    return $str;

}


/***
    Усовершенствованная функция, умеет превращать массивы в html мнемонику.
***/
function _htmlspecialchars($array = '') {

    if(is_array($array))
    {
        $r = array();

        while(list($k, $v) = each($array))
        {
            if(is_array($v))
            {
                $r[$k] = htmlspecialchars_array($v, ENT_COMPAT, CHARSET);

            } else {

                $r[$k] = htmlspecialchars($v, ENT_QUOTES, CHARSET);

            }

        }

    } else {

        $r = htmlspecialchars($array, ENT_COMPAT, CHARSET);
        $r = str_replace("'", "&apos;", $r);

    }

    return $r;

}


?>
