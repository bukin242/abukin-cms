<?


function _string_count($string, $symbol=false) {

    if($symbol)
    {
        $string = str_replace(" ", "", $string);
        $string = str_replace("\n", "", $string);
        $string = str_replace("\r", "", $string);
        $string = str_replace("\t", "", $string);

    }

    $string = strip_tags($string);

    return strlen($string);

}


function expicmp($str1, $str2) {

    $str1 = strtolower($str1);
    $str2 = strtolower($str2);
    $e1 = explode('/', $str1);
    $e2 = explode('/', $str2);

    foreach($e1 as $k => $v)
    {
        if($v!='')
        {
            if($v != $e2[$k])
            {
                return false;

            }

        }

    }

    return true;

}


function module_name($string, $prefix = MOD) {
    global $_;

    $explode = explode('/', $string);

    if($explode[0] == $prefix)
    {
        unset($explode[0]);

    }

    foreach($explode as $k => $v)
    {
        $pathinfo = pathinfo($v);

        if(isset($pathinfo['extension']))
        {
            unset($explode[$k]);

        }

    }

    $module_name  = implode('_', $explode);

    if(!isset($_[$module_name]))
    {
        $_[$module_name] = array();

    }

    return $module_name;

}


$_['symbols'] = array(
    '~'=>'', '`'=>'', '!'=>'', '@'=>'', '#'=>'', ';'=>'', '$'=>'',
    '^'=>'', ':'=>'', '&'=>'', '?'=>'', '*'=>'', '('=>'', ')'=>'',
    '_'=>'', '='=>'', '+'=>'', '/'=>'', '\\'=>'', '|'=>'', '['=>'',
    ']'=>'', '\''=>'', '"'=>'', '<'=>'', '>'=>'', '%'=>'', '.'=>''
);

function _symbols_trim($string) {
    global $_;

    if($string)
    {
        return strtr(trim($string), $_['symbols']);

    } else {

        return ;

    }

}


function br2nl($string) {

    return  preg_replace('/<br\\s*?\/??>/i', '', $string);

}


function _translit($str) {

    $str = trim($str);

    $tr = array(
        "À"=>"A","Á"=>"B","Â"=>"V","Ã"=>"G",
        "Ä"=>"D","Å"=>"E","¨"=>"E","Æ"=>"J","Ç"=>"Z","È"=>"I",
        "É"=>"Y","Ê"=>"K","Ë"=>"L","Ì"=>"M","Í"=>"N",
        "Î"=>"O","Ï"=>"P","Ğ"=>"R","Ñ"=>"S","Ò"=>"T",
        "Ó"=>"U","Ô"=>"F","Õ"=>"H","Ö"=>"C","×"=>"Ch",
        "Ø"=>"Sh","Ù"=>"Sh","Ú"=>"","Û"=>"Y","Ü"=>"",
        "İ"=>"E","Ş"=>"Yu","ß"=>"Ya","à"=>"a","á"=>"b",
        "â"=>"v","ã"=>"g","ä"=>"d","å"=>"e","¸"=>"e","æ"=>"j",
        "ç"=>"z","è"=>"i","é"=>"y","ê"=>"k","ë"=>"l",
        "ì"=>"m","í"=>"n","î"=>"o","ï"=>"p","ğ"=>"r",
        "ñ"=>"s","ò"=>"t","ó"=>"u","ô"=>"f","õ"=>"h",
        "ö"=>"c","÷"=>"ch","ø"=>"sh","ù"=>"sh","ú"=>"",
        "û"=>"y","ü"=>"","ı"=>"e","ş"=>"yu","ÿ"=>"ya",
        ","=>"", " "=>"_", "*"=>"", "%"=>"", "$"=>"", "`"=>"",
        "@"=>"", "!"=>"", "&"=>"", "?"=>"", ":"=>"", "¹"=>"",
        ";"=>"", "."=>"", "\'"=>"", "\""=>"", "("=>"", ")"=>"", "«"=>"", "»"=>"",
    );

    return strtr($str, $tr);

}


function _password($number = 5) {

    $array = array(
        'a','b','c','d','e','f',
        'g','h','i','j','k','l',
        'm','n','o','p','r','s',
        't','u','v','x','y','z',
        'A','B','C','D','E','F',
        'G','H','I','J','K','L',
        'M','N','O','P','R','S',
        'T','U','V','X','Y','Z',
        '1','2','3','4','5','6',
        '7','8','9','0'
    );

    $password = "";
    $count_array = count($array);

    for($i = 0; $i < $number; $i++)
    {
      $index = rand(0, $count_array - 1);
      $password .= $array[$index];

    }

    return $password;

}


function _is_utf($string)
{
    return preg_match('%(?:
        [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |\xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
        |\xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |\xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        |[\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
        |\xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        )+%xs',
    $string);
}


?>
