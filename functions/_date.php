<?


$_['date']['month'] = array(
    '01' => '������',
    '02' => '�������',
    '03' => '�����',
    '04' => '������',
    '05' => '���',
    '06' => '����',
    '07' => '����',
    '08' => '�������',
    '09' => '��������',

    '1' => '������',
    '2' => '�������',
    '3' => '�����',
    '4' => '������',
    '5' => '���',
    '6' => '����',
    '7' => '����',
    '8' => '�������',
    '9' => '��������',
    '10' => '�������',
    '11' => '������',
    '12' => '�������',

    'Jan' => '������',
    'Feb' => '�������',
    'Mar' => '�����',
    'Apr' => '������',
    'May' => '���',
    'Jun' => '����',
    'Jul' => '����',
    'Aug' => '�������',
    'Sep' => '��������',
    'Oct' => '�������',
    'Nov' => '������',
    'Dec' => '�������',

    'January' => '������',
    'February' => '�������',
    'March' => '�����',
    'April' => '������',
    'May' => '���',
    'June' => '����',
    'July' => '����',
    'August' => '�������',
    'September' => '��������',
    'October' => '�������',
    'November' => '������',
    'December' => '�������'
);


$_['date']['week'] = array(
    '0' => '�����������',
    '1' => '�����������',
    '2' => '�������',
    '3' => '�����',
    '4' => '�������',
    '5' => '�������',
    '6' => '�������',

    'Monday' => '�����������',
    'Tuesday' => '�������',
    'Wednesday' => '�����',
    'Thursday' => '�������',
    'Friday' => '�������',
    'Saturday' => '�������',
    'Sunday' => '�����������'
);


$_['date']['day'] = array(
    '01' => '������',
    '02' => '������',
    '03' => '������',
    '04' => '���������',
    '05' => '�����',
    '06' => '������',
    '07' => '�������',
    '08' => '�������',
    '09' => '�������',

    '1' => '������',
    '2' => '������',
    '3' => '������',
    '4' => '���������',
    '5' => '�����',
    '6' => '������',
    '7' => '�������',
    '8' => '�������',
    '9' => '�������',
    '10' => '�������',
    '11' => '������������',
    '12' => '�����������',
    '13' => '�����������',
    '14' => '�������������',
    '15' => '�����������',
    '16' => '������������',
    '17' => '�����������',
    '18' => '�������������',
    '19' => '�������������',
    '20' => '���������',
    '21' => '�������� ������',
    '22' => '�������� ������',
    '23' => '�������� ������',
    '24' => '�������� ���������',
    '25' => '�������� �����',
    '26' => '�������� ������',
    '27' => '�������� �������',
    '28' => '�������� �������',
    '29' => '�������� �������',
    '30' => '���������',
    '31' => '�������� ������'
);


/***
    ������� ���� � �������� ��� �������� �������.
***/
function _date($date, $format = "d.m.Y H:i:s") {
    global $_;

    if(strstr($format, '%F'))
    {
        $format = str_replace('%F', $_['date']['month'][date("F", strtotime($date))], $format);

    }

    if(strstr($format, '%M'))
    {
        $format = str_replace('%M', $_['date']['month'][date("M", strtotime($date))], $format);

    }

    if(strstr($format, '%m'))
    {
        $format = str_replace('%m', $_['date']['month'][date("m", strtotime($date))], $format);

    }

    if(strstr($format, '%n'))
    {
        $format = str_replace('%n', $_['date']['month'][date("n", strtotime($date))], $format);

    }

    if(strstr($format, '%w'))
    {
        $format = str_replace('%w', $_['date']['week'][date("w", strtotime($date))], $format);

    }

    if(strstr($format, '%l'))
    {
        $format = str_replace('%l', $_['date']['week'][date("l", strtotime($date))], $format);

    }

    if(strstr($format, '%d'))
    {
        $format = str_replace('%d', $_['date']['day'][date("d", strtotime($date))], $format);

    }

    if(strstr($format, '%j'))
    {
        $format = str_replace('%j', $_['date']['day'][date("j", strtotime($date))], $format);

    }

    $newdate = date($format, strtotime($date));

    return $newdate;

}


/***
    ������� ���� � ������� ���������� ��� ������� � SQL ������.
***/
function _sqldate($date, $format = "Y-m-d H:i:s") {

    return date($format, strtotime($date));

}


/***
    ������� ������� ���� � �������� �������, �������� ��� ������� � ���� ������.
***/
function _now($format = "Y-m-d H:i:s") {

    return date($format);

}


/***
    ������������� ������� ������� ���� ��� �����.
***/
function _timezone($gmt) {

    _query("SET TIME_ZONE='".$gmt."';");

}


function _last_modified($date, $gmt = '+0000') {

    if(isset($date) && $date != '0000-00-00 00:00:00')
    {
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', strtotime($date.' '.$gmt)).' GMT');

    }

}


?>
