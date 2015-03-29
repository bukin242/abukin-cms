<?


$_['errors'] = array(
    'form_sure' => 'Не заполнено поле: ',
    'form_captcha' => 'Фигура не указана либо указана неверно.',
    'form_email' => 'Неправильно указан email.',
    'form_url' => 'Неправильно указан URL.',
);


function _error($str, $mess = '') {
    global $_;

    print _ui_error($_['errors'][$str], $mess);

}


function _highlight($str, $mess = '') {
    global $_;

    print _ui_highlight($str, $mess);

}


?>
