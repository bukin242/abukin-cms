<?


$_['errors'] = array(
    'form_sure' => '�� ��������� ����: ',
    'form_captcha' => '������ �� ������� ���� ������� �������.',
    'form_email' => '����������� ������ email.',
    'form_url' => '����������� ������ URL.',
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
