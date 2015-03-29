<?


$_['date']['month'] = array(
    '01' => 'Января',
    '02' => 'Февраля',
    '03' => 'Марта',
    '04' => 'Апреля',
    '05' => 'Мая',
    '06' => 'Июня',
    '07' => 'Июля',
    '08' => 'Августа',
    '09' => 'Сентября',

    '1' => 'Января',
    '2' => 'Февраля',
    '3' => 'Марта',
    '4' => 'Апреля',
    '5' => 'Мая',
    '6' => 'Июня',
    '7' => 'Июля',
    '8' => 'Августа',
    '9' => 'Сентября',
    '10' => 'Октября',
    '11' => 'Ноября',
    '12' => 'Декабря',

    'Jan' => 'Января',
    'Feb' => 'Февраля',
    'Mar' => 'Марта',
    'Apr' => 'Апреля',
    'May' => 'Мая',
    'Jun' => 'Июня',
    'Jul' => 'Июля',
    'Aug' => 'Августа',
    'Sep' => 'Сентября',
    'Oct' => 'Октября',
    'Nov' => 'Ноября',
    'Dec' => 'Декабря',

    'January' => 'Января',
    'February' => 'Февраля',
    'March' => 'Марта',
    'April' => 'Апреля',
    'May' => 'Мая',
    'June' => 'Июня',
    'July' => 'Июля',
    'August' => 'Августа',
    'September' => 'Сентября',
    'October' => 'Октября',
    'November' => 'Ноября',
    'December' => 'Декабря'
);


$_['date']['week'] = array(
    '0' => 'Воскресенье',
    '1' => 'Понедельник',
    '2' => 'Вторник',
    '3' => 'Среда',
    '4' => 'Четверг',
    '5' => 'Пятница',
    '6' => 'Суббота',

    'Monday' => 'Понедельник',
    'Tuesday' => 'Вторник',
    'Wednesday' => 'Среда',
    'Thursday' => 'Четверг',
    'Friday' => 'Пятница',
    'Saturday' => 'Суббота',
    'Sunday' => 'Воскресенье'
);


$_['date']['day'] = array(
    '01' => 'Первое',
    '02' => 'Второе',
    '03' => 'Третье',
    '04' => 'Четвертое',
    '05' => 'Пятое',
    '06' => 'Шестое',
    '07' => 'Седьмое',
    '08' => 'Восьмое',
    '09' => 'Девятое',

    '1' => 'Первое',
    '2' => 'Второе',
    '3' => 'Третье',
    '4' => 'Четвертое',
    '5' => 'Пятое',
    '6' => 'Шестое',
    '7' => 'Седьмое',
    '8' => 'Восьмое',
    '9' => 'Девятое',
    '10' => 'Десятое',
    '11' => 'Одиннадцатое',
    '12' => 'Двенадцатое',
    '13' => 'Тринадцатое',
    '14' => 'Четырнадцатое',
    '15' => 'Пятнадцатое',
    '16' => 'Шестнадцатое',
    '17' => 'Семнадцатое',
    '18' => 'Восемнадцатое',
    '19' => 'Девятнадцатое',
    '20' => 'Двадцатое',
    '21' => 'Двадцать первое',
    '22' => 'Двадцать второе',
    '23' => 'Двадцать третье',
    '24' => 'Двадцать четвертое',
    '25' => 'Двадцать пятое',
    '26' => 'Двадцать шестое',
    '27' => 'Двадцать седьмое',
    '28' => 'Двадцать восьмое',
    '29' => 'Двадцать девятое',
    '30' => 'Тридцатое',
    '31' => 'Тридцать первое'
);


/***
    Выводит дату в понятном для человека формате.
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
    Выводит дату в формате подходящем для вставки в SQL запрос.
***/
function _sqldate($date, $format = "Y-m-d H:i:s") {

    return date($format, strtotime($date));

}


/***
    Выводит текущую дату в заданном формате, подходит для вставки в базу данных.
***/
function _now($format = "Y-m-d H:i:s") {

    return date($format);

}


/***
    Устанавливает текущую часовую зону для сайта.
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
