<?


/***
    Проверка и установка пользователя.
    Пароль и логин установлен в [_setting.php]
***/
function _login() {
    global $_;

    if($_['url']['this_reverse'][0] == 'out')
    {
        unset($_SESSION['user_admin']);
        return false;

    }

    $user = trim($_POST['user']);
    $pass = trim($_POST['pass']);

    if($user && $pass)
    {
        sleep(2);

        if($user == $_['admin']['user']['name'] && $pass == $_['admin']['user']['pass'])
        {
            $_SESSION['user_admin']['name'] = md5($user);
            $_SESSION['user_admin']['pass'] = md5($pass);
            return true;

        } else {

            return false;

        }

    } else {

        if($_SESSION['user_admin']['name'] == md5($_['admin']['user']['name']) && $_SESSION['user_admin']['pass'] == md5($_['admin']['user']['pass']))
        {
            return true;

        } else {

            return false;

        }

    }

}


function _table($array = '', $order = array('id' => 'desc'), $page = 30) {
    global $_;

    $_['i']++ ;
    $hidden = '';
    $order = _order($order);
    $_['function'] = __FUNCTION__;

    if(!isset($_['submit']))
    {
        $_['submit'] = 'Сохранить';

    }

    foreach($array as $k => $v)
    {
        _fields_info($k, $v);

    }

    if($_['table'])
    {
        if(is_array($_['table']))
        {
            $a = $_['table'];

        } else {

            $fields = $fields_default = _is_fields($_['table']);
            $a = _s("SELECT * FROM ".$_['table'].$order);
            $is_comment = false;

            if(is_array($array))
            {
                foreach($array as $k => $v)
                {
                    if($fields[$k])
                    {
                        preg_match_all("/([a-z]+)(\(([0-9]+)\))?/", $fields[$k], $out);
                        $fields[$k] = $out[1][0];

                    }

                    if(is_numeric($k))
                    {
                        unset($array[$k]);
                        $array[$v] = false;
                        $is_comment = true;

                    }

                }

            }

            if($array == '' || $is_comment)
            {
                $comment = _is_fields($_['table'], 'Comment');
                $comment = _clear($comment);
                ksort($comment);

                if($comment)
                {
                    if(is_array($array))
                    {
                        $foreach = $array;

                        foreach($array as $k => $v)
                        {
                            $foreach[$k] = $comment[$k];

                        }

                    } else {

                        $foreach = $comment;

                    }

                    foreach($foreach as $k => $v)
                    {
                        if($fields[$k] == 'text' || $fields[$k] == 'longtext' || $fields[$k] == 'tinytext' || $fields[$k] == 'mediumtext')
                        {
                            unset($comment[$k]);

                        } else {

                            $array[$k] = $v;

                        }

                    }

                } elseif($array == '' && _count($fields)) {

                    foreach($fields as $k => $v)
                    {
                        $array[$k] = $k;

                    }

                }

                if(!isset($array['edit']))
                {
                    if($comment['name'] && isset($array['name']))
                    {
                        $array['name'] = array($comment['name'], _link('edit', array('id')));

                    } else {

                        $array['edit'] = 'Редактировать';

                    }

                }

                if(!isset($array['delete']))
                {
                    $array['delete'] = 'Удалить';

                }

            }

            if($page)
            {
                $a = _page($a, $page);

            }

        }

    }

    if(isset($_REQUEST['submit_'.$_['i']]))
    {
        $request = $_REQUEST;
        unset($request['field']);
        unset($request['submit_'.$_['i']]);

        foreach($_REQUEST['field'] as $k => $v)
        {
            if(!$fields[$k])
            {
                unset($_REQUEST['field'][$k]);

            }

        }

        if($_REQUEST['field'])
        {
            $k = key($_REQUEST['field']);

            if($k == 'id')
            {
                $ids = $_REQUEST['field'][$k];
                unset($_REQUEST['field'][$k]);
                $k = key($_REQUEST['field']);

            } else {

                $ids = '';

            }

            if($ids)
            {
                _update($_REQUEST['field'], $_['table'], " WHERE id "._in($ids, true));

            }

        }

        foreach($request as $k => $v)
        {
            $type_field = _get_type(_fields_info($k));

            foreach($v as $k2 => $v2)
            {
                if($k == 'delete')
                {
                    _delete($_['table'], $k2);

                } else {

                    if($type_field != 'wysiwyg' && $type_field != 'textarea')
                    {
                        $v2 = htmlspecialchars($v2, ENT_COMPAT, CHARSET);

                    }

                    if($k2)
                    {
                        _update(array($k => $v2), $_['table'], $k2);

                    }

                }

            }

        }

        if(count($_FILES) && $_['upload'])
        {
            if($_['table'])
            {
                $prefix = $_['table'];

            } else {

                $prefix = time();

            }

            foreach($_FILES as $k => $v)
            {
                $field_info = _fields_info($k);

                if (is_array($field_info[1]['access'])) {

                    $field_access = $field_info[1]['access'];

                } else {

                    $field_access[] = $field_info[1]['access'];

                }

                $field_type = $field_info[1]['type'];

                foreach ($v['name'] as $k2 => $v2)
                {
                    if(!$v2)
                    {
                        continue;

                    }

                    $_FILES[$k]['name'][$k2] = $prefix.'+'.$k.'+'.$k2.'.'._ext($v2);
                    _update(array($k => $_FILES[$k]['name'][$k2]), $_['table'], $k2);

                }

                if($fields[$k])
                {
                    _upload($k, $_['upload'], $field_type, 1, $field_access);

                }

            }

        }

        $a = _s("SELECT * FROM ".$_['table'].$order);

        if($page)
        {
            $a = _page($a, $page);

        }

    }

    if($array == '')
    {
        foreach($a[0] as $k => $v)
        {
            $array[$k] = $k;

        }

    }

    if(_count($array) && _count($a))
    {
        $new = array();

        foreach($array as $k => $v)
        {
            _default($k, $v, $fields_default, $array);

            if(is_array($v))
            {
                $new[$k]['name'] = $v[0];

            } else {

                $new[$k]['name'] = $v;

            }

            if($new[$k]['name'] == '')
            {
                $new[$k]['name'] = $k;

            }

        }

        ?>
        <form action="" method="post" enctype="multipart/form-data"><?=$hidden?>
        <table class="table <?=$_['table']?>" rel="1">
        <tr>
        <?

        foreach($new as $k => $v)
        {
            ?><th id="<?=$k?>" <?=(is_array($array[$k][1])? 'class="'.$array[$k][1]['type'].'"' : '')?> rel="1"><?=$v['name']?></th><?

        }

        ?></tr><?

        foreach($a as $r)
        {
            ?><tr><input type="hidden" name="field[id][<?=$r['id']?>]" value="1"/><?

            foreach($new as $k => $v)
            {
                _fields_info($k, $array[$k]);

                if(!isset($a[0][$k]))
                {
                    $r[$k] = $array[$k][2];

                }

                if(is_array($array[$k][1]))
                {
                    $field = $array[$k][1];
                    $type = $field['type'];
                    $name = $r[$k];

                    switch ($type)
                    {
                        case 'link':
                        case 'href':

                            if($r[$k] != '')
                            {
                                $r[$k] = $field['<'].(is_numeric($r[$k]) ? $r[$k] : _url($r[$k])).'">'.$r[$k].$field['>'];

                            } else {

                                if($fields[$k])
                                {
                                    $r[$k] = $field['<'].$r[$k].'">(пусто)'.$field['>'];

                                } else {

                                    $r[$k] = $field['<'].'">'.($field['value'] ? $field['value'] : $v['name']).$field['>'];

                                }

                            }

                        break;

                        case 'checkbox':

                            $r[$k] = $field['<'].' name="'.$k.'['.$r['id'].']" '.($r[$k] ? 'checked="checked"' : '').' '.$field['>'];
                            $r[$k] .= '<input type="hidden" name="field['.$k.']" value=""/>';

                        break;

                        case 'ord':

                            $r[$k] = $field['<'].' name="'.$k.'['.$r['id'].']" value="'.$r[$k].'"'.$field['>'];

                        break;

                        case 'text':

                            if($field['value'] != '')
                            {
                                if ($field['name'] == '')
                                {
                                    $field['name'] = ',';

                                }

                                $r[$k] = str_replace($field['name'], $field['value'], $r[$k]);

                            }

                        break;

                        case 'textedit':

                            $r[$k] = $field['<'].' name="'.$k.'['.$r['id'].']" value="'.$r[$k].'"'.$field['>'];

                        break;

                        case 'select':

                            $r[$k] = $field['value'][$r[$k]];

                        break;

                        case 'multiselect':

                            if ($r[$k] != '')
                            {
                                $explode = explode(',', $r[$k]);
                                $str = '';

                                foreach ($explode as $k2 => $v2)
                                {
                                    $str .= ($k2 ? ', ': '').$field['value'][$v2];

                                }

                                if ($str != '')
                                {
                                    $r[$k] = $str;

                                }

                            }

                        break;

                        case 'datetime':

                            if($field['format'])
                            {
                                $r[$k] = _date($r[$k], $field['format']);

                            } else {

                                switch($fields[$k])
                                {
                                    case 'timestamp':

                                        $r[$k] = _date($r[$k], "Y.m.d H:i:s");

                                    break;

                                    case 'datetime':

                                        $r[$k] = _date($r[$k], "Y.m.d H:i:s");

                                    break;

                                    case 'date':

                                        $r[$k] = _date($r[$k], "Y.m.d");

                                    break;

                                    case 'time':

                                        $r[$k] = _date($r[$k], "H:i:s");

                                    break;

                                    case 'int':

                                        $r[$k] = date("Y.m.d H:i:s", $r[$k]);

                                    break;

                                }

                            }

                        break;

                        case 'file':
                        case 'flash':
                        case 'image':
                        case 'doc':
                        case 'archive':

                            $r[$k] = $field['<'].' name="'.$k.'['.$r['id'].']" '.$field['>'];

                        break;

                    }

                } elseif(is_string($array[$k][1]) && !isset($fields[$k])) {

                    $r[$k] = $array[$k][1];

                }

                foreach($field['field'] as $k2 => $v2)
                {
                    if($v2 == 'field' || $v2 == $k)
                    {
                        $r[$k] = str_replace('{%'.$v2.'%}', $name, $r[$k]);

                    } else {

                        $r[$k] = str_replace('{%'.$v2.'%}', $r[$v2], $r[$k]);

                    }

                }

                ?><td class="<?=$k?> type_<?=$type?>" rel="1"><?=$r[$k]?></td><?

            }

            ?></tr><?

        }

        ?>
        </table>
        <? if($_['submit']) { ?>
        <input type="submit" value="<?=$_['submit']?>" class="submit" name="submit_<?=$_['i']?>"/>
        <span style="color:red;"><?=(isset($_REQUEST['submit_'.$_['i']]) ? 'сохранено...' : '')?></span>
        <?
            }

            print _pages_list();
        ?>
        </form>
        <?

    } else {

        ?><br />Данных нет.<?

    }

}


function _order($array = '') {
    global $_;

    if($array)
    {
        if($_['table'])
        {
            if(is_array($_['table']))
            {
                return '';

            }

            $fields = _is_fields($_['table']);
            $is_fields = true;

        } else {

            $is_fields = false;

        }

        $str = '';
        $i = 0;

        foreach($array as $k => $v)
        {
            $v = mb_strtolower($v);

            if(is_numeric($k) && is_string($v))
            {
                if($is_fields && $fields[$v])
                {
                    $str .= ($i ? ',' : '').' '.$v.' asc';
                    $i++ ;

                }

            } else {

                if($is_fields && $fields[$k])
                {
                    if($v == 'asc' || $v == 'desc')
                    {
                        $str .= ($i ? ',' : '').' '.$k.' '.$v;
                        $i++ ;

                    }

                }

            }

        }

        if($str)
        {
            $str = ' ORDER BY '.$str;

        }

        return $str;

    } else {

        return '';

    }

}


function _form($array = '', $func = '') {
    global $_;

    $_['i']++ ;
    $hidden = '';
    $_['function'] = __FUNCTION__;

    if($_['upload'])
    {
        $upload_dir = str_replace(ROOT, '', $_['upload']);
        $upload_dir = rtrim($upload_dir, '/\\').'/';

    }

    if($_['table'])
    {
        if(is_array($_['table']))
        {
            $a = $_['table'];

        } else {

            if($array == '')
            {
                $comment = _is_fields($_['table'], 'Comment');
                $comment = _clear($comment);

                if($comment)
                {
                    $array = $comment;

                }

            }

            $fields = _is_fields($_['table']);
            $a = _s("SELECT * FROM ".$_['table']);

        }

    }

    if($array == '' || !_count($array))
    {
        foreach($a[0] as $k => $v)
        {
            $array[$k] = $k;

        }

    }

    $new = array();

    foreach($array as $k => $v)
    {
        _default($k, $v, $fields, $array);

        if(is_array($v))
        {
            $new[$k]['name'] = $v[0];

        } else {

            $new[$k]['name'] = $v;

        }

        if($new[$k]['name'] == '')
        {
            $new[$k]['name'] = $k;

        }

    }

    ?>
    <form action="" method="post" enctype="multipart/form-data"><?=$hidden?>
    <table class="form <?=$_['table']?>" rel="1">
    <?

    foreach($new as $k => $v)
    {
        _fields_info($k, $array[$k]);

        if(is_array($array[$k][1]))
        {
            $class = 'class="'.$array[$k][1]['type'].' '.$k.'"';
            $field = $array[$k][1];
            $type = $field['type'];
            $value = ($func[$k] ? $func[$k] : $field['value']);
            $name = 'name="'.$k.'"';

            switch ($type)
            {
                case 'link':

                    if($value != '')
                    {
                        $r = $field['<'].$r.$field['>'];

                    }

                break;

                case 'checkbox':

                    $r = $field['<'].' '.$name.' '.($value  ? 'checked="checked"' : '').' '.$field['>'];
                    $r .= '<input type="hidden" name="field['.$k.']" value=""/>';

                break;

                case 'textarea':

                    $r = $field['<'].' '.$name.' >'.$value.'<'.$field['>'];

                break;

                case 'select':
                case 'multiselect':

                    if($type == 'multiselect')
                    {
                        $r = $field['<'].' name="'.$k.'[]">';

                        if($value != $field['value'])
                        {
                            $value = explode(',', $value);

                        }

                    } else {

                        $r = $field['<'].' name="'.$k.'">';

                    }

                    if($value && $value != $field['value'])
                    {
                        $_REQUEST[$k] = $value;
                        $value = $field['value'];

                    }

                    if(is_string($value) && $value != '')
                    {
                        $value = _value($value, $_['table']);

                    }

                    if(is_array($value))
                    {
                        if($field['name'])
                        {
                            $r .= '<option value="0">'.$field['name'].'</option>';

                        }

                        foreach($value as $k2 => $v2)
                        {
                            $r .= '<option value="'.$k2.'" '.($_REQUEST[$k] == $k2 || in_array($k2, $_REQUEST[$k]) ? 'selected="selected"' : '').'>'.$v2.'</option>';

                        }

                    }

                    $r .= '<'.$field['>'];
                    $r .= '<input type="hidden" name="field['.$k.']" value=""/>';

                break;

                case 'wysiwyg':

                    $r = _spaw($k, $value);

                break;

                case 'file':
                case 'flash':
                case 'image':
                case 'doc':
                case 'archive':

                    $r = $field['<'].' '.$name.' '.$field['>'].($value ? ' <input type="checkbox" name="del['.$k.']" class="checkbox"/> Удалить. <a href="'.$upload_dir.$value.'" target="_blank" title="Посмотреть '.$value.'">'.$value.'</a>' : '');

                break;

                default:

                    $r = $field['<'].' '.$name.' value="'.$value.'" '.$field['>'];

            }

        } elseif (is_string($array[$k][1]) && !isset($fields[$k])) {

            $class = '';
            $r = $array[$k][1];

        }

    ?>
    <tr>
        <th <?=$class?> rel="1"><?=$v['name']?></th>
        <td <?=$class?> rel="1"><?=$r?></td>
    </tr>
    <?

    }

    ?>
    </table>
    <input type="submit" value="Сохранить" class="submit" name="submit_<?=$_['i']?>"/>
    <span style="color:red;"><?=(isset($_REQUEST['submit_'.$_['i']]) ? 'сохранено...' : '')?></span>
    </form>
    <?

}


function _edit($array = '') {
    global $_;

    if (is_numeric($array))
    {
        $where = " WHERE id = '".$array."'";

    } elseif (is_array($array)) {

        $i = 0;
        $str = '';

        foreach($array as $k => $v)
        {
            $str .= ($i ? " AND " : "").$k." = '".$v."'";
            $i++ ;

        }

        if($str)
        {
            $where = " WHERE ".$str;

        }

    }

    if(_count($_POST))
    {
        $fields = _is_fields($_['table']);
        $request = array();

        if($_['upload'])
        {
            $_['upload'] = rtrim($_['upload'], '/\\').'/';

        }

        foreach($fields as $k => $v)
        {
            if(isset($_POST[$k]))
            {
                if(is_array($_POST[$k]))
                {
                    $request[$k] = implode(',', $_POST[$k]);

                    if (in_array(0, $_POST[$k]))
                    {
                        $request[$k] = '';

                    }

                } else {

                    $request[$k] = $_POST[$k];
                    $request[$k] = mysql_escape_string($request[$k]);

                    $type_field = _get_type(_fields_info($k));

                    if($type_field != 'wysiwyg' && $type_field != 'textarea')
                    {
                        $request[$k] = htmlspecialchars($request[$k], ENT_COMPAT, CHARSET);

                    }

                }

            } else {

                if(isset($_POST['field'][$k]))
                {
                    $request[$k] = '';

                }

            }

            if($k == 'last_modified' && $v == 'timestamp')
            {
                $request[$k] = _now();

            }

        }

        if($request && $_['table'])
        {
            $update = _update($request, $_['table'], $where);

            if(count($_REQUEST['del']) && $_['upload'])
            {
                foreach($_REQUEST['del'] as $k => $v)
                {
                    if($v && $fields[$k] && $_FILES[$k]['error'])
                    {
                        $r = _field("SELECT ".$k." FROM ".$_['table']." ".$update['where']);

                        _update(array($k => ''), $_['table'], $update['where']);

                        @unlink($_['upload'].$r);

                    }

                }

            }

        }

        if(count($_FILES) && $_['upload'])
        {
            if($_['table'])
            {
                $prefix = $_['table'];

            } else {

                $prefix = time();

            }

            foreach($_FILES as $k => $v)
            {
                $field_info = _fields_info($k);

                if (is_array($field_info[1]['access'])) {

                    $field_access = $field_info[1]['access'];

                } else {

                    $field_access[] = $field_info[1]['access'];

                }

                $field_type = $field_info[1]['type'];

                $_FILES[$k]['name'] = $prefix.'+'.$k.'+'.$update['rows'][0].'.'._ext($_FILES[$k]['name']);

                if(_upload($k, $_['upload'], $field_type, 1, $field_access) && $fields[$k])
                {
                    $r = _field("SELECT ".$k." FROM ".$_['table']." ".$update['where']);

                    _update(array($k => $_FILES[$k]['name']), $_['table'], $update['where']);

                    if($r != $_FILES[$k]['name'])
                    {
                        @unlink($_['upload'].$r);

                    }

                }

            }

        }

    }

    $a = _array("SELECT * FROM ".$_['table'].$where);

    return $a;

}


function _add($array = '') {
    global $_;

    if(_count($_POST))
    {
        $fields = _is_fields($_['table']);
        $request = array();

        foreach($fields as $k => $v)
        {
            if(isset($array[$k]))
            {
                $request[$k] = $array[$k];

            } else {

                $request[$k] = $_POST[$k];

            }

            if(isset($_POST[$k]))
            {
                if(is_array($request[$k]))
                {
                    $request[$k] = implode(',', $request[$k]);

                    if (in_array(0, $_POST[$k]))
                    {
                        $request[$k] = '';

                    }

                } else {

                    $request[$k] = mysql_escape_string($request[$k]);

                    $type_field = _get_type(_fields_info($k));

                    if($type_field != 'wysiwyg' && $type_field != 'textarea')
                    {
                        $request[$k] = htmlspecialchars($request[$k], ENT_COMPAT, CHARSET);

                    }

                }

                unset($_REQUEST[$k]);

            } else {

                if(isset($_POST['field'][$k]))
                {
                    $request[$k] = '';

                }

            }

            if($k == 'last_modified' && $v == 'timestamp')
            {
                $request[$k] = _now();

            }

        }

        if(!_count(_clear($request)))
        {
            if(count($_FILES))
            {
                $insert = _insert(array('id' => ' '), $_['table']);

            } else {

                unset($_REQUEST);
                return false;

            }

        }

        if($request && $_['table'])
        {
            if(isset($request['url']) && _row_count($_['table'], "WHERE url='".($request['url'] == '' ? '/' : $request['url'])."'"))
            {
                if($request['name'])
                {
                    $translit_name = '/'.mb_strtolower(_translit($request['name']));

                } else {

                    $translit_name = '/'.$_['table'];

                }

                if(_row_count($_['table'], "WHERE url='".$translit_name.".html'"))
                {
                    $translit_name = $translit_name.'_'._now("Y-m-d_H-i-s");

                }

                $request['url'] = $translit_name.'.html';

            }

            $insert = _insert($request, $_['table']);

        }

        if(count($_FILES))
        {
            if($_['table'])
            {
                $prefix = $_['table'];

            } else {

                $prefix = time();

            }

            foreach($_FILES as $k => $v)
            {
                $field_info = _fields_info($k);

                if (is_array($field_info[1]['access'])) {

                    $field_access = $field_info[1]['access'];

                } else {

                    $field_access[] = $field_info[1]['access'];

                }

                $field_type = $field_info[1]['type'];

                $_FILES[$k]['name'] = $prefix.'+'.$k.'+'.$insert.'.'._ext($_FILES[$k]['name']);

                if(_upload($k, $_['upload'], $field_type, 1, $field_access) && $fields[$k])
                {
                    _update(array($k => $_FILES[$k]['name']), $_['table'], $insert);

                }

            }

        }

    }

    return $insert;

}


function _default($k, $v, $fields, &$array) {
    global $_;

    if($fields[$k])
    {
        preg_match_all("/([a-z]+)(\(([0-9]+)\))?/", $fields[$k], $out);
        $type = $out[1][0];
        $len = $out[3][0];

    }

    if(is_array($v[1]))
    {
        if($v[1]['type'] == 'hidden')
        {
            $hidden .= $v[1]['<'].' '.($v[1]['name'] == '' ? 'name="'.$k.'"' : 'name="'.$v[1]['name'].'"').' value="'.$v[1]['value'].'" '.$v[1]['>'];
            continue;

        }

    } else {

        if(($type == 'int' || $type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' || $type == 'boolean') && $len == 1)
        {
            $array[$k] = array(0 => $array[$k], 1 => _check());

        } elseif($type == 'text'|| $type == 'tinytext') {

            $array[$k] = array(0 => $array[$k], 1 => _textarea());

        } elseif($type == 'longtext' || $type == 'mediumtext') {

            $array[$k] = array(0 => $array[$k], 1 => _wysiwyg());

        } elseif($type == 'int' && $len == 4) {

            $array[$k] = array(0 => $array[$k], 1 => _ord());

        } else {

            $array[$k] = array(0 => $array[$k], 1 => _text());

        }

        switch ($k)
        {
            case 'public':

                if(_is_field($k, $_['table']))
                {
                    $array[$k] = array(0 => $array[$k], 1 => _check());

                }

            break;

            case 'date':

                if(_is_field($k, $_['table']))
                {
                    $array[$k] = array(0 => $array[$k], 1 => _text());

                }

            break;

            case 'url':

                if(_is_field($k, $_['table']))
                {
                    $array[$k] = array(0 => $array[$k], 1 => _link());

                }

            break;

            case 'ord':

                if(_is_field($k, $_['table']))
                {
                    $array[$k] = array(0 => $array[$k], 1 => _ord());

                }

            break;

            case 'datetime':

                if(_is_field($k, $_['table']))
                {
                    $array[$k] = array(0 => $array[$k], 1 => _text());

                }

            break;

            case 'delete':

                if(!_is_field($k, $_['table']))
                {
                    $array[$k] = array(0 => $array[$k], 1 => _check());

                }

            break;

            default:

                if(!_is_field($k, $_['table']))
                {
                    if($_['function'] == '_table')
                    {
                        $array[$k] = array(0 => $v, 1 => _link($k, array('id')));

                    } else {

                        $array[$k] = array(0 => $array[$k], 1 => _text());

                    }

                }

        }

    }

}


function _link($pref = '', $field = '') {
    global $_;
    $str = '';

    if(is_array($field))
    {
        foreach($field as $k => $v)
        {
            $str .= ($k ? '&' : '').$v.'={%'.$v.'%}';

        }

        $str = '?'.$str;

    } else {

        if($field == '')
        {
            if($pref == '')
            {
                $str .= '{%field%}';
                $field = array(0 => 'field');

            }

        } else {

            $str .= '?'.$field.'={%'.$field.'%}';
            $field = array($field);

        }

    }

    return array(
        'type' => 'link',
        'field' => $field,
        '<' => '<a href="'.$pref.$str.'" '.($str == '{%field%}' ? 'target="_blank"' : ''),
        '>' => '</a>'
    );

}


function _href($link = '', $value = '') {
    global $_;

    $module_url = rtrim($_['url'][$_['url']['this'][2]], '/');

    return array(
        'type' => 'href',
        'value' => $value,
        '<' => '<a target="_blank" href="'.($link ? $link : $module_url),
        '>' => '</a>'
    );

}


function _check($value = '', $name = '') {
    global $_;

    return array(
        'value' => $value,
        'name' => $name,
        'type' => 'checkbox',
        '<' => '<input type="checkbox" value="1"',
        '>' => '/>'
    );

}


function _checkbox($value = '', $name = '') {

    return _check($value, $name);

}


function _hidden($value = '', $name = '') {
    global $_;

    return array(
        'value' => $value,
        'name' => $name,
        'type' => 'hidden',
        '<' => '<input type="hidden"',
        '>' => '/>'
    );

}


function _datetime($format = '') {
    global $_;

    return array(
        'format' => $format,
        'type' => 'datetime'
    );

}


function _text($value = '', $name = '') {
    global $_;

    return array(
        'value' => $value,
        'name' => $name,
        'type' => 'text',
        '<' => '<input type="text"',
        '>' => '/>'
    );

}


function _textarea($value = '', $name = '') {
    global $_;

    return array(
        'value' => $value,
        'name' => $name,
        'type' => 'textarea',
        '<' => '<textarea',
        '>' => '/textarea>'
    );

}


function _select($value = '', $name = '(пусто)') {
    global $_;

    return array(
        'value' => $value,
        'name' => $name,
        'type' => 'select',
        '<' => '<select',
        '>' => '/select>'
    );

}


function _multiselect($value = '', $name = '(пусто)', $size = '') {
    global $_;

    $minsize = 10;

    if(!$size)
    {
        $size = intval(count($value) / ($minsize / 2));

        if($size <= $minsize)
        {
            $size = $minsize;

        }

    }

    return array(
        'value' => $value,
        'name' => $name,
        'type' => 'multiselect',
        '<' => '<select size="'.$size.'" multiple="multiple"',
        '>' => '/select>'
    );

}


function _wysiwyg($value = '', $name = '') {
    global $_;

    return array(
        'value' => $value,
        'name' => $name,
        'type' => 'wysiwyg',
    );

}


function _wysi($value = '', $name = '') {
    global $_;

    return _wysiwyg($value, $name);

}


$_['spaw'] = false;
function _spaw($name = 'wysiwyg', $value = '') {
    global $_;

    if(!$_['spaw'])
    {
        require_once(MOD."/admin/styles/spaw2/spaw.inc.php");
        $_['spaw'] = true;

    }

    $spaw = new SpawEditor($name, $value);

    return $spaw->getHtml();

}


function _ord($value = '', $name = '') {
    global $_;

    return array(
        'value' => $value,
        'name' => $name,
        'type' => 'ord',
        '<' => '<input type="text" maxlength="4"',
        '>' => '/>'
    );

}


function _textedit() {
    global $_;

    return array(
        'type' => 'textedit',
        '<' => '<input type="text"',
        '>' => '/>'
    );

}


function _image($access = '') {
    global $_;

    return array(
        'type' => 'image',
        'access' => $access,
        '<' => '<input type="file"',
        '>' => '/>'
    );

}


function _doc($access = '') {
    global $_;

    return array(
        'type' => 'doc',
        'access' => $access,
        '<' => '<input type="file"',
        '>' => '/>'
    );

}


function _swf($access = '') {
    global $_;

    return array(
        'type' => 'flash',
        'access' => $access,
        '<' => '<input type="file"',
        '>' => '/>'
    );

}


function _archive($access = '') {
    global $_;

    return array(
        'type' => 'archive',
        'access' => $access,
        '<' => '<input type="file"',
        '>' => '/>'
    );

}


function _file($access = '') {
    global $_;

    return array(
        'type' => 'file',
        'access' => $access,
        '<' => '<input type="file"',
        '>' => '/>'
    );

}


function _page($array = array(), $count_pages = 0, $page = PAGE) {

    define('PAGES_COUNT', $count_pages);
    define('PAGES_ROWS', count($array));

    if((PAGES_ROWS && PAGES_COUNT) && (PAGES_ROWS > PAGES_COUNT))
    {
        if((($page * $count_pages) - $count_pages) < 1)
        {
            $page = 1;

        }

        define('PAGES', true);
        define('PAGES_SLICE', ($page * $count_pages) - $count_pages);

        $array = array_slice($array, PAGES_SLICE, $count_pages);

    } else {

        define('PAGES', false);

    }

    return $array;

}


function _pages_list($href = '', $list_count = 9) {
    global $_;

    $str = '';

    if(defined('PAGES') AND PAGES)
    {
        $list_pages = PAGES_ROWS / PAGES_COUNT;
        $list_pages = (is_float($list_pages) ? intval($list_pages) + 1 : $list_pages);
        $list_pages_last = $list_pages;
        $list_pages++ ;

        if(!$href)
        {
            $rev = $_['url']['this_reverse'];

            if($rev[0] == PAGE)
            {
                unset($rev[0]);

            }

            $rev = array_reverse($rev);
            $href = implode('/', $rev);
            $href = $href.'/';

        }

        if($list_count)
        {
            $left_right = intval($list_count / 2);
            $left = PAGE - $left_right;
            $right = PAGE + $left_right;
            $right++ ;

            if($left < 1)
            {
                $right = (($right - $left) + 1);
                $left = 1;

            }

            $i = ($left - 1);

            if($i && $list_pages_last > $list_count)
            {
                $str .= '<a href="'.$href.'" class="corner3 nav" title="Первая">&lt;&lt;&lt;</a> ';
                $str .= '<a href="'.$href.$i.'" class="corner3 nav" title="Назад на ('.$i.') страницу.">&lt;</a> ';

            }

            if($right < $list_pages)
            {
                $list_pages_right = $list_pages;
                $list_pages = $right;

            }

            $left = $left - ($right - $list_pages);

            $right++ ;

            if($left < 0)
            {
                $left = 1;

            }

        } else {

            $left = 1;

        }

        if(!defined('PAGE') && $list_pages_last)
        {
            define('PAGE', 1);

        }

        if(!$left)
        {
            $left = 1;

        }

        for($i = $left; $i < $list_pages; $i++)
        {
            $str .= '<a href="'.$href.$i.'" class="corner3 '.($i == PAGE ? 'active' : '').'">'.$i.'</a> ';

        }

        if($list_count && $list_pages_right && $list_pages_last > $list_count)
        {
            $str .= '<a href="'.$href.$i.'" class="corner3 nav" title="Вперёд на ('.$i.') страницу.">&gt;</a> ';
            $str .= '<a href="'.$href.$list_pages_last.'" class="corner3 nav" title="Последняя ('.$list_pages_last.')">&gt;&gt;&gt;</a> ';

        }

        $str = '<br /><br /><br /><div class="admin_menu pages_list corner">'.$str.'</div>';

    }

    return $str;

}


function _fields_info($k = '', $set = false) {
    global $_;

    if($k)
    {
        $modules_table = ($_['table'] ? $_['table'] : $_['url']['this'][2]);
        $url_path = $_['url']['path'];

        if ($set !== false)
        {
            if ($set == '')
            {
                unset($_SESSION[$modules_table][$url_path]['fields_info'][$k]);

            } else {

                $_SESSION[$modules_table][$url_path]['fields_info'][$k] = $set;

            }

        }

        return $_SESSION[$modules_table][$url_path]['fields_info'][$k];

    }

}


function _get_type($array) {

    if(isset($array[1]['type']) && $array[1]['type'] != '')
    {
        return $array[1]['type'];

    } else {

        return false;

    }

}


?>
