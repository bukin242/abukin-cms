<?


/***
    Устанавливает кодировку сайта.
***/
function _charset($charset) {
    global $_;

    define('CHARSET', strtoupper($charset));
    setlocale(LC_ALL, 'ru_RU.'.CHARSET);
    mysql_query("SET NAMES cp1251");
    header('X-Powered-By: Aleksey Bukin [contacts@abukin.ru]');
    header('Server: my-best-super-puper-server');

    switch(CHARSET)
    {
        case 'CP1251':

            header("Content-Type: text/html; charset=windows-1251");
            $_['pages']['meta'] = '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />';

        break;

        case 'UTF-8':

            header('Content-Type: text/html; charset=UTF-8');
            $_['pages']['meta'] = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

        break;

    }

}


/***
    Ротация ошибок в массив $logerror.
    Вывод критических ошибок SQL если параметр $print установлен.
***/
$logerror_ = array();
function _logerror($print = 0) {
    global $logerror_;

    if($print)
    {
        if(count($logerror_))
        {
            print '<span style="color:red;">'.implode($logerror_, '<br />').'</span>';

        }

    } else {
        $error = @mysql_error();

        if(!count($logerror_) || !in_array($error, $logerror_))
        {
            $logerror_[] = $error;

        }

    }

}


/***
    Установка соединения с базой данных.
***/
function _connect($charset = '', $gmt = '', $time_zone = '') {

    if(!@mysql_connect(HOST, USER, PASS))
    {
        _logerror();
        return false;

    }

    if(!@mysql_select_db(BASE))
    {
        _logerror();
        return false;

    }

    @ob_start();
    @session_start();

    if($charset)
    {
        @_charset($charset);

    }

    if($gmt)
    {
        @_timezone($gmt);

    }

    if($time_zone)
    {
        @date_default_timezone_set($time_zone);

    }

    return true;

}


/***
    Закрытие соединения с базой данных.
***/
function _close($template) {

    if(!@mysql_close())
    {
        _logerror();
        return false;

    } else {

        print $template;
        @ob_end_flush();
        return true;

    }

}


/***
    Выполняет примитивный SQL запрос.
***/
function _query($query) {

    if(!$_ = @mysql_query($query))
    {
        if($query != '-1')
        {
            _logerror();

        }

    }

    return $_;

}


/***
    Вернет один ряд в массиве, из результирующего набора SQL.
***/
function _row($result) {

    $array = @mysql_fetch_assoc($result);

    if(_count($array))
    {
        return $array;

    } else {

        return array();

    }

}


/***
    Вернет один ряд в массиве, из строки запроса.
***/
function _array($query_or_table, $app = '') {

    if($app)
    {
        if(is_numeric($app))
        {
            $app = " WHERE id='".$app."'";

        } else {

            $app = ' '.$app;

        }

        return _row(_query("SELECT * FROM ".$query_or_table.$app));

    } else {

        return _row(_query($query_or_table));

    }

}


/***
    Вернет одно поле из запроса.
    Пример: _field("SELECT count(id) FROM articles");
***/
function _field($query) {

    return @mysql_result(_query($query), 0, false);

}


/***
    Проверит есть ли поле в таблице.
***/
function _is_field($field, $table) {

    $exist = mysql_query("SELECT ".$field." FROM ".$table." LIMIT 0, 1");
    return ($exist ? true : false);

}


/***
    Вытащит все существующие в таблице поля.
***/
function _is_fields($table, $field = 'Type') {

    $exist = _akval(_s("SHOW FULL FIELDS FROM ".$table), 'Field', $field);
    return ($exist ? $exist : false);

}


/***
    Вернёт последний вставленный id.
***/
function _id() {

    return @mysql_insert_id();

}


/***
    Проверяет и применяет правила обработки.
    К полям переданным в массиве. В соответствии с таблицей.
***/
function _fields_valide($table, $array) {

    $fields_ = _is_fields($table);
    $array_result = array();

    foreach($fields_ as $k => $v)
    {
        if(isset($array[$k]))
        {
            preg_match_all("/([a-z]+)(\(([0-9]+)\))?/", $v, $out);

            if($out[3][0])
            {
                switch($out[1][0])
                {
                    case 'int':

                        $array[$k] = intval($array[$k]);

                    break;

                }

                if(strlen($array[$k]) > $out[3][0])
                {
                    $array[$k] = substr($array[$k], $out[3][0]);

                }

            }

            $array_result[$k] = $array[$k];

        }

    }

    return $array_result;

}


/***
    Вернёт кол-во.
***/
function _count($result = '-1') {

    if(is_array($result))
    {
        return count($result);

    } elseif(is_string($result)) {

        return @mysql_num_rows(_query($result));

    } elseif(is_resource($result)) {

        return @mysql_num_rows($result);

    } elseif(is_object($result)) {

        return count($result);

    } elseif($result == '-1') {

        return mysql_affected_rows();

    }

}


function _change($array, $table, $app = '', $act = 'insert') {
    global $_;

    if($array)
    {
        foreach($array as $k => $v)
        {
            if($act == 'insert')
            {
                if($v == '')
                {
                    continue;

                }

            }

            if(!isset($_['form']['submit'][$k]))
            {
                $str .= ($i?", ":" SET ").$k."='".$v."' ";
                $i++ ;
                $a['fields'][$k] = $v;

            }

        }

    }

    if($act == 'insert')
    {
        if($app)
        {
            if($str)
            {
                $app = ', '.$app;

            } else {

                $app = ' SET '.$app;

            }

        }

        if($str.$app)
        {
            _query("INSERT INTO ".$table.$str.$app);

        }

        return _id();

    } elseif($act == 'update') {

        if($app)
        {
            if(is_numeric($app))
            {
                $app = " WHERE id='".$app."'";

            } else {

                $app = ' '.$app;

            }

            $a['where'] = stristr($app, "WHERE");
            $a['app'] = str_replace($a['where'], '', $app);

            if($a['app'] && !$str)
            {
                $str = ' SET id = id ';

            }

            $a['where'] = ' '.$a['where'];

        }

        $return = _query("SELECT id FROM ".$table.$a['where']);

        if($return)
        {
            while ($w = _row($return))
            {
                $a['rows'][] = $w['id'];

            }

        }

        if($str)
        {
            $r = _query("UPDATE ".$table.$str.$app);

        }

        return $a;

    } elseif($act == 'delete') {

        if($app)
        {
            if(is_numeric($app))
            {
                $app = " WHERE id='".$app."'";

            } else {

                $app = ' '.$app;

            }

        } elseif($app == 0) {

            $app = " WHERE id='0'";

        }

        _query("DELETE FROM ".$table.$app);

        return _count();

    }

}


function _insert($array, $table, $app = '') {

    return _change($array, $table, $app);

}


function _update($array, $table, $app = '') {

    return _change($array, $table, $app, 'update');

}


function _delete($table, $app = '') {

    return _change(array(), $table, $app, 'delete');

}


function _row_copy($table, $to_table, $id, $replace = array()) {

    if($id)
    {
        if(is_numeric($id))
        {
            $id = " WHERE id='".$id."'";

        } else {

            $id = ' '.$id;

        }

    }

    $new = _array("SELECT * FROM ".$table.$id);

    if($new['id'])
    {
        unset($new['id']);

        if($replace)
        {
            foreach($replace as $k => $v)
            {
                $new[$k] = $v;

            }

        }

        $new = str_replace(array("\\", "'", '"'), array("\\\\", "\'", '\"'), $new);
        $insert = _insert($new, $to_table, $app);

        return $insert;

    } else {

        return false;

    }

}


function _row_move($table, $to_table, $id, $replace = array()) {

    if($id)
    {
        if(is_numeric($id))
        {
            $id = " WHERE id='".$id."'";

        } else {

            $id = ' '.$id;

        }

    }

    $new = _array("SELECT * FROM ".$table.$id);

    if($new['id'])
    {
        $delete = $new['id'];
        unset($new['id']);

        if($replace)
        {
            foreach($replace as $k => $v)
            {
                $new[$k] = $v;

            }

        }

        $new = str_replace(array("\\", "'", '"'), array("\\\\", "\'", '\"'), $new);
        $insert = _insert($new, $to_table, $app);

        if($insert)
        {
            _delete($table, $delete);

        }

        return $insert;

    } else {

        return false;

    }

}


function _row_count($table, $app = '') {

    return _field("SELECT count(id) FROM ".$table." ".$app);

}


function _d($table, $fields = '*') {

    return ($table ? _s("SELECT ".($fields == '*' ? $fields : (is_string($fields) ? $fields : implode(',', $fields)))." FROM ".$table) : false);

}


function _value($field, $table, $zero = false) {

    $array = array();

    if(_is_field($field, $table))
    {
        if($zero)
        {
            if (strstr($field, ','))
            {
                $explode = explode(', ',$field);

                foreach(_s("SELECT id, ".$field." FROM ".$table) as $k => $v)
                {
                    foreach ($explode as $k2 => $v2)
                    {
                        $array[$v['id']] .= ($k2 ? ' ' : '').$v[$v2];

                    }

                }

            } else {

                foreach(_s("SELECT id, ".$field." FROM ".$table) as $k => $v)
                {
                    $array[$v['id']] = $v[$field];

                }

            }

        } else {

            if (strstr($field, ','))
            {
                $explode = explode(', ',$field);

                foreach(_s("SELECT id, ".$field." FROM ".$table) as $k => $v)
                {
                    foreach ($explode as $k2 => $v2)
                    {
                        if($v[$v2] != '')
                        {
                            $array[$v['id']] .= ($k2 ? ' ' : '').$v[$v2];

                        }

                    }

                }

            } else {

                foreach(_s("SELECT id, ".$field." FROM ".$table) as $k => $v)
                {
                    if($v[$field] != '')
                    {
                        $array[$v['id']] = $v[$field];

                    }

                }

            }

        }

    }

    asort($array);

    return $array;

}


function _exist($table, $app = '') {

    if(is_numeric($app))
    {
        $app = " WHERE id='".$app."'";

    }

    return _field("SELECT count(id) FROM ".$table." ".$app);

}


/***
    Делает нормальную строку WHERE IN (...) для SQL запроса.
***/
function _in($array, $key = false) {

    if(_count($array) > 1)
    {
        $str = '';
        $i = 0;

        if($key)
        {
            foreach($array as $k => $v)
            {
                $str .= ($i < 1 ? "" : ",")."'".$k."'";
                $i++ ;

            }

        } else {

            $array = _clear($array);

            foreach($array as $k => $v)
            {
                $str .= ($i < 1 ? "" : ",")."'".$v."'";
                $i++ ;

            }

        }

        if($str)
        {
            $str = " IN (".$str.")";

        }

        return $str;

    } else {

        if($key)
        {
            return " = '".key($array)."'";

        } else {

            return " = '".$array[key($array)]."'";

        }

    }

}


function _truncate($table = '') {

    if($table)
    {
        return _query("TRUNCATE TABLE ".$table);

    }

}


?>
