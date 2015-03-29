<?


$_['form'] = $_['form_id'] = array('id' => 1);
function _forms_edit($table = '', $app = '') {
    global $_;

    foreach($_['forms'][$_['form']['id']] as $k => $v)
    {
        $_['forms'][$_['form']['id']][$k] = strip_tags(trim($v));

    }

    foreach($_['form']['sure'] as $k => $v)
    {
        if($_['forms'][$_['form']['id']][$k] == '')
        {
            return _error('form_sure', $v);

        }

    }

    foreach($_['form']['email'] as $k => $v)
    {
        if($_['forms'][$_['form']['id']][$k] != '' && !filter_var($_['forms'][$_['form']['id']][$k], FILTER_VALIDATE_EMAIL))
        {
            return _error('form_email');

        }

    }

    foreach($_['form']['url'] as $k => $v)
    {
        if($_['forms'][$_['form']['id']][$k] != '' && (!stristr($_['forms'][$_['form']['id']][$k], 'http://') && !stristr($_['forms'][$_['form']['id']][$k], 'https://')))
        {
            $_['forms'][$_['form']['id']][$k] = 'http://'.$_['forms'][$_['form']['id']][$k];

        }

    }

    if(isset($_REQUEST['captcha']))
    {
        if(!_captcha())
        {
            return _error('form_captcha');

        }

    }

    if($table)
    {
        foreach($_['form']['textarea'] as $k => $v)
        {
            $_['forms'][$_['form']['id']][$k] = nl2br($_['forms'][$_['form']['id']][$k]);

        }

        return _update(_fields_valide($table, $_['forms'][$_['form']['id']]), $table, $app);

    } else {

        return $_['forms'][$_['form']['id']];

    }

}


function _forms_add($table = '', $app = '') {
    global $_;

    foreach($_['forms'][$_['form']['id']] as $k => $v)
    {
        $_['forms'][$_['form']['id']][$k] = strip_tags(trim($v));

    }

    foreach($_['form']['sure'] as $k => $v)
    {
        if ($_['forms'][$_['form']['id']][$k] == '')
        {
            return _error('form_sure', $v);

        }

    }

    foreach($_['form']['email'] as $k => $v)
    {
        if($_['forms'][$_['form']['id']][$k] != '' && !filter_var($_['forms'][$_['form']['id']][$k], FILTER_VALIDATE_EMAIL))
        {
            return _error('form_email');

        }

    }

    foreach($_['form']['url'] as $k => $v)
    {
        if($_['forms'][$_['form']['id']][$k] != '' && (!stristr($_['forms'][$_['form']['id']][$k], 'http://') && !stristr($_['forms'][$_['form']['id']][$k], 'https://')))
        {
            $_['forms'][$_['form']['id']][$k] = 'http://'.$_['forms'][$_['form']['id']][$k];

        }

    }

    if(isset($_REQUEST['captcha']))
    {
        if(!_captcha())
        {
            return _error('form_captcha');

        }

    }

    if($table)
    {
        foreach($_['form']['textarea'] as $k => $v)
        {
            $_['forms'][$_['form']['id']][$k] = nl2br($_['forms'][$_['form']['id']][$k]);

        }

        _forms_reset('form_'.$_['form']['id']);

        $id = _insert(_fields_valide($table, $_['forms'][$_['form']['id']]), $table, $app);
        return _array($table, $id);

    } else {

        return $_['forms'][$_['form']['id']];

    }

}


function _forms_reset($form_id) {

    $str = '<script type="text/javascript">';
    $str .= '
        $(function(){

            $("form#'.$form_id.' :input").not(":button, :submit, :reset, :hidden").each( function() {
                this.value = "";
            });

        })
        ';
    $str .= '</script>';

    print $str;

}


function _forms($input, $param = array('action' => '', 'method' => 'post', 'enctype' => 'multipart/form-data')) {
    global $_;

    $id = 'form_'.$_['form_id']['id'];
    $str = '';
    $str .= '<span id="steps"><form ';

    foreach($param as $k => $v)
    {
        $str .= $k.'="'.str_replace('"', "'", $v).'" ';

    }

    $str .= ($id?'id="'.$id.'"':'').'>';
    $str .= '<input type="hidden" value="'.$_['form_id']['id'].'" class="'.$id.'" name="'.$id.'" />';
    $str .= $input;

    $_['form_id']['id']++ ;

    if($_['forms'])
    {
        $_['form']['id'] = key($_['forms']);

    } else {

        $_['form']['id']++ ;

    }

    $str .= '</form></span>';

    print $str;

    $GLOBALS['_'.$param['method']] = array_merge($GLOBALS['_'.$param['method']], $_FILES);

    return $GLOBALS['_'.$param['method']];

}


function _input($label, $sure = false, $name, $type = 'text', $value = '', $request = '', $attribute = array()) {
    global $_;

    $_['form'][$type][$name] = $request;

    if(($type['checkbox'] || $type['radio']) && is_array($request) && is_array($value) && count($value))
    {
        $_['forms'][$_['form']['id']][$name] = $_REQUEST[$name];
        unset($_['forms'][$_['form']['id']][$name]['form_'.$_['form']['id']]);
        unset($_['forms'][$_['form']['id']][$name]['PHPSESSID']);
        unset($_['form'][$type][$name]['form_'.$_['form']['id']]);
        unset($_['form'][$type][$name]['PHPSESSID']);
        $_['form']['unset'][$name] = $type;

    } else {

        if(isset($_REQUEST[$name]))
        {
            $_['forms'][$_['form']['id']][$name] = $_REQUEST[$name];

        }

        if($_['form']['unset'])
        {
            foreach($_['form']['unset'] as $k => $v)
            {
                unset($_['forms'][$_['form']['id']][$k][$name]);
                unset($_['form'][$v][$k][$name]);

            }

        }

    }

    if($type == 'submit' && $_['forms'][$_['form']['id']][$name] != '')
    {
        $exp = explode(':', $request);

        eval('$res  = '.$exp[0]);

        if($res)
        {
            unset($exp[0]);
            eval(implode(' ', $exp));

        }

    }

    $attr = '';

    if($attribute)
    {
        if(is_array($attribute))
        {
            foreach($attribute as $k => $v)
            {
                $attr .= $k.'="'.str_replace('"', "'", $v).'" ';

            }

        } else {

            $attr .= $attribute;

        }

    }

    if($type == 'email' || $type == 'url')
    {
        $type = 'text';

    }

    switch($type)
    {
        case 'text':

            $attr .= ' maxlength="255"';

        break;

        case 'textarea':

            $attr .= ' rows="6" cols="50"';
            $value = br2nl($value);
            $value = stripslashes($value);
            $value = htmlspecialchars($value, ENT_COMPAT, CHARSET);

        break;

    }

    $str = '';

    if($type != 'hidden')
    {
        $str .= '<fieldset class="form_'.$name.'"><p>';

    }

    $class = $type;

    if($label)
    {
        $str .= '<span>'.$label.':';

            if($sure)
            {
                $_['form']['sure'][$name] = $label;
                $str .= '<font class="sure" color="red">'.$sure.'</font>';
                $class .= ' required';

            }

        $str .= ' </span>';

    }

    switch($type)
    {
        case 'textarea':

            $str .= '<textarea name="'.$name.'" class="'.$name.' '.$class.'" '.$attr.'>'.$value.'</textarea>';

        break;

        case 'select':

            $str .= '<select name="'.$name.'" class="'.$name.' '.$class.'" '.$attr.'>';

            if(count($value))
            {
                foreach($value as $k => $v)
                {
                    $str .= '<option value="'.$k.'" '.($k == $request?'selected="selected"':'').'>'.htmlspecialchars(stripslashes($v), ENT_COMPAT, CHARSET).'</option>';

                }

            }

            $str .= '</select>';

        break;

        case 'date':

            $str .= '<input readonly="readonly" name="'.$name.'" class="'.$name.' '.$class.'" type="'.$type.'" value="'.htmlspecialchars(stripslashes($value), ENT_COMPAT, CHARSET).'" '.$attr.'/>';
            _ui_datepicker($name);

        break;

        case 'time':

            $str .= '<input readonly="readonly" name="'.$name.'" class="'.$name.' '.$class.'" type="'.$type.'" value="'.htmlspecialchars(stripslashes($value), ENT_COMPAT, CHARSET).'" '.$attr.'/>';
            _ui_timepicker($name);

        break;

        case 'datetime':

            $str .= '<input readonly="readonly" name="'.$name.'" class="'.$name.' '.$class.'" type="'.$type.'" value="'.htmlspecialchars(stripslashes($value), ENT_COMPAT, CHARSET).'" '.$attr.'/>';
            _ui_datetimepicker($name);

        break;

        case 'hour':

            $str .= '<input readonly="readonly" name="'.$name.'" class="'.$name.' '.$class.'" type="'.$type.'" value="'.htmlspecialchars(stripslashes($value), ENT_COMPAT, CHARSET).'" '.$attr.'/>';
            _ui_timepicker($name, array("showButtonPanel" => "false", "timeFormat" => "'hh:mm'", "showSecond" => "false", "showMinute" => "false"));

        break;

        case 'captcha':

            $str .= '<span class="captcha"></span>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $(".captcha").sexyCaptcha("/styles/js/jquery.sexy-captcha/captcha.process.php");
                    });
                </script>';

        break;

        case 'slider':

            $str .= '<input name="'.$name.'" class="'.$name.' '.$class.'" type="'.$type.'" value="'.htmlspecialchars(stripslashes($value), ENT_COMPAT, CHARSET).'" '.$attr.'/>';
            $str .= _ui_slider($name);

        break;

        case 'checkbox':

            $str .= '<i id="checkbox_'.$name.'">';

            if(is_array($value) && count($value))
            {
                foreach($value as $k => $v)
                {
                    $str .= '<i id="'.$k.'"><input name="'.$name.'['.$k.']" class="checkbox" type="checkbox" value="1" '.($request[$k]?'checked="checked"':'').' '.$attr.'/> '.htmlspecialchars(stripslashes($v), ENT_COMPAT, CHARSET).'<br /></i>';

                }

            } else {

                $str .= '<input name="'.$name.'" class="checkbox" type="checkbox" value="1" '.($request[$name]?'checked="checked"':'').' '.$attr.'/>';

            }

            $str .= '</i>';

        break;

        case 'radio':

            if(is_array($value) && count($value))
            {
                foreach($value as $k => $v)
                {
                    $str .= '<input name="'.$name.'" class="'.$name.'" type="'.$type.'" value="'.$k.'" '.(($request[$name] == $k)?'checked="checked"':'').' '.$attr.'/> '.htmlspecialchars(stripslashes($v), ENT_COMPAT, CHARSET).'<br />';

                }

            } else {

                $str .= '<input name="'.$name.'" class="'.$name.'" type="'.$type.'" value="0" '.(!isset($request[$name]) || ($request[$name] == 0)?'checked="checked"':'').' '.$attr.'/>';
                $str .= '<input name="'.$name.'" class="'.$name.'" type="'.$type.'" value="1" '.($request[$name] == 1?'checked="checked"':'').' '.$attr.'/>';

            }

        break;

        case 'image':

            $str .= '<input name="'.$name.'" class="'.$name.' '.$class.'" type="'.$type.'" src="'.htmlspecialchars(stripslashes($value), ENT_COMPAT, CHARSET).'" '.$attr.'/>';

        break;

        default:

            $str .= '<input name="'.$name.'" class="'.$name.' '.$class.'" type="'.$type.'" value="'.htmlspecialchars(stripslashes($value), ENT_COMPAT, CHARSET).'" '.$attr.'/>';

    }

    if($type != 'hidden')
    {
        $str .= '</p></fieldset>';

    }

    return $str;

}


?>
