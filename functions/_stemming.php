<?

global $STEMMING_RU_VOWELS;
$STEMMING_RU_VOWELS = "ÀÅÈÎÓÛÝÞß";
global $STEMMING_RU_PERFECTIVE_GERUND;
$STEMMING_RU_PERFECTIVE_GERUND = "/(ÛÂØÈÑÜ|ÈÂØÈÑÜ|ßÂØÈÑÜ|ÀÂØÈÑÜ|ÛÂØÈ|ÈÂØÈ|ßÂØÈ|ÀÂØÈ|ÛÂ|ÈÂ|ßÂ|ÀÂ)$/";
global $STEMMING_RU_REFLEXIVE;
$STEMMING_RU_REFLEXIVE=array("Ñß", "ÑÜ");
global $STEMMING_RU_ADJECTIVE;
$STEMMING_RU_ADJECTIVE=array("ÅÅ", "ÈÅ", "ÛÅ", "ÎÅ", "ÈÌÈ", "ÛÌÈ", "ÅÉ", "ÈÉ", "ÛÉ", "ÎÉ", "ÅÌ", "ÈÌ", "ÛÌ", "ÎÌ", "ÅÃÎ", "ÎÃÎ", "ÅÌÓ", "ÎÌÓ", "ÈÕ", "ÛÕ", "ÓÞ", "ÞÞ", "Àß", "ßß", "ÎÞ", "ÅÞ");
global $STEMMING_RU_PARTICIPLE_GR1;
$STEMMING_RU_PARTICIPLE_GR1=array("ÅÌ", "ÍÍ", "ÂØ", "ÞÙ", "Ù");
global $STEMMING_RU_PARTICIPLE_GR2;
$STEMMING_RU_PARTICIPLE_GR2=array("ÈÂØ", "ÛÂØ", "ÓÞÙ");
global $STEMMING_RU_ADJECTIVAL_GR1;
global $STEMMING_RU_ADJECTIVAL_GR2;
foreach($STEMMING_RU_ADJECTIVE as $i)
{
    foreach($STEMMING_RU_PARTICIPLE_GR1 as $j) $STEMMING_RU_ADJECTIVAL_GR1[]=$j.$i;
    foreach($STEMMING_RU_PARTICIPLE_GR2 as $j) $STEMMING_RU_ADJECTIVAL_GR2[]=$j.$i;
}
global $STEMMING_RU_ADJECTIVAL1;
usort($STEMMING_RU_ADJECTIVAL_GR1, "stemming_ru_sort");
$STEMMING_RU_ADJECTIVAL1="/([Àß])(".implode("|", $STEMMING_RU_ADJECTIVAL_GR1).")$/";

global $STEMMING_RU_ADJECTIVAL2;
foreach($STEMMING_RU_ADJECTIVE as $i)
    $STEMMING_RU_ADJECTIVAL_GR2[]=$i;
usort($STEMMING_RU_ADJECTIVAL_GR2, "stemming_ru_sort");
$STEMMING_RU_ADJECTIVAL2="/(".implode("|", $STEMMING_RU_ADJECTIVAL_GR2).")$/";

global $STEMMING_RU_VERB1;
$STEMMING_RU_VERB1="/([Àß])(ÍÍÎ|ÅÒÅ|ÉÒÅ|ÅØÜ|ËÀ|ÍÀ|ËÈ|ÅÌ|ËÎ|ÍÎ|ÅÒ|ÞÒ|ÍÛ|ÒÜ|É|Ë|Í)$/";

global $STEMMING_RU_VERB_GR2;
$STEMMING_RU_VERB_GR2=array("ÈËÀ", "ÛËÀ", "ÅÍÀ", "ÅÉÒÅ", "ÓÉÒÅ", "ÈÒÅ", "ÈËÈ", "ÛËÈ", "ÅÉ", "ÓÉ", "ÈË", "ÛË", "ÈÌ", "ÛÌ", "ÅÍ", "ÈËÎ", "ÛËÎ", "ÅÍÎ", "ßÒ", "ÓÅÒ", "ÓÞÒ", "ÈÒ", "ÛÒ", "ÅÍÛ", "ÈÒÜ", "ÛÒÜ", "ÈØÜ", "ÓÞ", "Þ");
usort($STEMMING_RU_VERB_GR2, "stemming_ru_sort");
global $STEMMING_RU_VERB2;
$STEMMING_RU_VERB2="/(".implode("|", $STEMMING_RU_VERB_GR2).")$/";
global $STEMMING_RU_NOUN;
$STEMMING_RU_NOUN="/(ÈßÌÈ|ÈßÕ|ÈÅÌ|ÈßÌ|ÀÌÈ|ßÌÈ|Üß|Èß|ÜÞ|ÈÞ|ßÕ|ÀÕ|ÎÌ|ÀÌ|ÅÌ|ßÌ|ÈÉ|ÎÉ|ÅÉ|ÈÅÉ|ÈÈ|ÅÈ|ÜÅ|ÈÅ|ÎÂ|ÅÂ|Þ|Ü|Û|Ó|Î|É|È|Å|ß|À)$/";
function stemming_letter_ru()
{
    return "¸éöóêåíãøùçõúôûâàïðîëäæýÿ÷ñìèòüáþ¨ÉÖÓÊÅÍÃØÙÇÕÚÔÛÂÀÏÐÎËÄÆÝß×ÑÌÈÒÜÁÞ";
}
function stemming_ru_sort($a, $b)
{
    $al = strlen($a);
    $bl = strlen($b);
    if($al == $bl)
        return 0;
    elseif($al < $bl)
        return 1;
    else
        return -1;
}
function stemming_stop_ru($sWord)
{
    if(strlen($sWord) < 2)
        return false;
    static $stop_list = false;
    if(!$stop_list)
    {
        $stop_list = array (
            "QUOTE"=>0,"HTTP"=>0,"WWW"=>0,"RU"=>0,"IMG"=>0,"GIF"=>0,"ÁÅÇ"=>0,"ÁÛ"=>0,"ÁÛË"=>0,
            "ÁÛÒ"=>0,"ÂÀÌ"=>0,"ÂÀØ"=>0,"ÂÎ"=>0,"ÂÎÒ"=>0,"ÂÑÅ"=>0,"ÂÛ"=>0,"ÃÄÅ"=>0,"ÄÀ"=>0,
            "ÄÀÆ"=>0,"ÄËß"=>0,"ÄÎ"=>0,"ÅÃ"=>0,"ÅÑË"=>0,"ÅÑÒ"=>0,"ÅÙ"=>0,"ÆÅ"=>0,"ÇÀ"=>0,
            "ÈÇ"=>0,"ÈË"=>0,"ÈÌ"=>0,"ÈÕ"=>0,"ÊÀÊ"=>0,"ÊÎÃÄ"=>0,"ÊÒÎ"=>0,"ËÈ"=>0,"ËÈÁ"=>0,
            "ÌÅÍ"=>0,"ÌÍÅ"=>0,"ÌÎ"=>0,"ÌÛ"=>0,"ÍÀ"=>0,"ÍÀÄ"=>0,"ÍÅ"=>0,"ÍÅÒ"=>0,"ÍÈ"=>0,
            "ÍÎ"=>0,"ÍÓ"=>0,"ÎÁ"=>0,"ÎÍ"=>0,"ÎÒ"=>0,"Î×ÅÍ"=>0,"ÏÎ"=>0,"ÏÎÄ"=>0,"ÏÐÈ"=>0,
            "ÏÐÎ"=>0,"ÑÀÌ"=>0,"ÑÅÁ"=>0,"ÑÂÎ"=>0,"ÒÀÊ"=>0,"ÒÀÌ"=>0,"ÒÅÁ"=>0,"ÒÎ"=>0,"ÒÎÆ"=>0,
            "ÒÎËÜÊ"=>0,"ÒÓÒ"=>0,"ÒÛ"=>0,"ÓÆ"=>0,"ÕÎÒ"=>0,"×ÅÃ"=>0,"×ÅÌ"=>0,"×ÒÎ"=>0,"×ÒÎÁ"=>0,
            "ÝÒ"=>0,"ÝÒÎÒ"=>0, "ÈÏ"=>0, "×Ï"=>0, "ÏÁÎÞË"=>0, "ÎÎ"=>0, "ÇÀÎ"=>0, "ÎÀ"=>0, "ÒÊ"=>0, "ÃÊ"=>0, "ÐÊ"=>0, "ÀÍ"=>0
        );
        if(defined("STEMMING_STOP_RU"))
        {
            foreach(explode(",", STEMMING_STOP_RU) as $word)
            {
                $word = trim($word);
                if(strlen($word)>0)
                    $stop_list[$word]=0;
            }
        }
    }
    return !array_key_exists($sWord, $stop_list);
}

function stemming_upper_ru($sText)
{
    return str_replace(array("¨"), array("Å"), strtoupper($sText));
}

function stemming_ru($word)
{
    global $STEMMING_RU_VOWELS;
    global $STEMMING_RU_PERFECTIVE_GERUND;
    global $STEMMING_RU_REFLEXIVE;
    global $STEMMING_RU_ADJECTIVE;
    global $STEMMING_RU_PARTICIPLE_GR2;
    global $STEMMING_RU_ADJECTIVAL1;
    global $STEMMING_RU_ADJECTIVAL2;
    global $STEMMING_RU_VERB1;
    global $STEMMING_RU_VERB_GR2;
    global $STEMMING_RU_VERB2;
    global $STEMMING_RU_NOUN;
    //There is a 33rd letter, ¸ (?), but it is rarely used, and we assume it is mapped into å (e).
    $word=str_replace("¨", "Å", $word);
    //Exceptions
    static $STEMMING_RU_EX = array("ÁÅÇÅ"=>true,"ÁÛËÜ"=>true,"ÌÅÍÞ"=>true,"ÃÐÀÍÀÒ"=>true,"ÃÐÀÍÈÒ"=>true);
    if(isset($STEMMING_RU_EX[$word]))
        return $word;
    //In any word, RV is the region after the first vowel, or the end of the word if it contains no vowel.
    //All tests take place in the the RV part of the word.
    $found=array();
    if(preg_match("/^(.*?[$STEMMING_RU_VOWELS])(.+)$/", $word, $found))
    {
        $rv = $found[2];
        $word = $found[1];
    }
    else
    {
        return $word;
    }

    //Do each of steps 1, 2, 3 and 4.
    //Step 1: Search for a PERFECTIVE GERUND ending. If one is found remove it, and that is then the end of step 1.


    if(preg_match($STEMMING_RU_PERFECTIVE_GERUND, $rv, $found))
    {
        switch($found[0]) {
            case "ÀÂ":
            case "ÀÂØÈ":
            case "ÀÂØÈÑÜ":
            case "ßÂ":
            case "ßÂØÈ":
            case "ßÂØÈÑÜ":
                $rv = substr($rv, 0, 1-strlen($found[0]));
                break;
            default:
                $rv = substr($rv, 0, -strlen($found[0]));
        }
    }
    //Otherwise try and remove a REFLEXIVE ending, and then search in turn for
    // (1) an ADJECTIVE,
    // (2) a VERB or (3)
    // a NOUN ending.
    // As soon as one of the endings (1) to (3) is found remove it, and terminate step 1.
    else
    {
        $rv = preg_replace("/(Ñß|ÑÜ)$/", "", $rv);
        //ADJECTIVAL
        if(preg_match($STEMMING_RU_ADJECTIVAL1, $rv, $found))
            $rv = substr($rv, 0, -strlen($found[2]));
        elseif(preg_match($STEMMING_RU_ADJECTIVAL2, $rv, $found))
            $rv = substr($rv, 0, -strlen($found[0]));
        elseif(preg_match($STEMMING_RU_VERB1, $rv, $found))
            $rv = substr($rv, 0, -strlen($found[2]));
        elseif(preg_match($STEMMING_RU_VERB2, $rv, $found))
            $rv = substr($rv, 0, -strlen($found[0]));
        else
            $rv = preg_replace($STEMMING_RU_NOUN, "", $rv);
    }

    //Step 2: If the word ends with è (i), remove it.
    if(substr($rv, -1) == "È")
        $rv = substr($rv, 0, -1);
    //Step 3: Search for a DERIVATIONAL ending in R2 (i.e. the entire ending must lie in R2), and if one is found, remove it.
    //R1 is the region after the first non-vowel following a vowel, or the end of the word if there is no such non-vowel.
    if(preg_match("/(ÎÑÒÜ|ÎÑÒ)$/", $rv))
    {
        $R1=0;
        $rv_len = strlen($rv);
        while( ($R1<$rv_len) && (strpos($STEMMING_RU_VOWELS, substr($rv,$R1,1))!==false) )
            $R1++;
        if($R1 < $rv_len)
            $R1++;
        //R2 is the region after the first non-vowel following a vowel in R1, or the end of the word if there is no such non-vowel.
        $R2 = $R1;
        while( ($R2<$rv_len) && (strpos($STEMMING_RU_VOWELS, substr($rv,$R2,1))===false) )
            $R2++;
        while( ($R2<$rv_len) && (strpos($STEMMING_RU_VOWELS, substr($rv,$R2,1))!==false) )
            $R2++;
        if($R2 < $rv_len)
            $R2++;
        //"ÎÑÒÜ", "ÎÑÒ"
        if((substr($rv, -4) == "ÎÑÒÜ") && ($rv_len >= ($R2+4)))
            $rv = substr($rv, 0, $rv_len - 4);
        elseif((substr($rv, -3) == "ÎÑÒ") && ($rv_len >= ($R2+3)))
            $rv = substr($rv, 0, $rv_len - 3);
    }
    //Step 4: (1) Undouble í (n), or, (2) if the word ends with a SUPERLATIVE ending, remove it and undouble í (n), or (3) if the word ends ü (') (soft sign) remove it.
    $rv = preg_replace("/(ÅÉØÅ|ÅÉØ)$/", "", $rv);
    $r = preg_replace("/ÍÍ$/", "Í", $rv);
    if($r == $rv)
        $rv = preg_replace("/Ü$/", "", $rv);
    else
        $rv = $r;

    return $word.$rv;
}

function stemming_init($sLang="ru")
{
    static $arStemFunc=array();

    if(!isset($arStemFunc[$sLang]))
    {
        $stemming_function_suf = $sLang;

        $stemming_stop_function = "stemming_stop_".$sLang;
        if(!function_exists($stemming_stop_function))
            $stemming_stop_function = "stemming_stop_default";

        $stemming_upper_function = "stemming_upper_".$sLang;
        if(!function_exists($stemming_upper_function))
            $stemming_upper_function = "stemming_upper_default";

        $letters = stemming_letter_default();
        $stemming_letter_function = "stemming_letter_".$sLang;
        if(function_exists($stemming_letter_function))
            $letters .= $stemming_letter_function();
        $letters .= '-';

        $arStemFunc[$sLang] = array(
            "stem" => "stemming_".$stemming_function_suf,
            "stop" => $stemming_stop_function,
            "upper" => $stemming_upper_function,
            "letters" => $letters,
            "pcre_letters" => str_replace(
                array("\\"  , "-"  , "^"  , "]"  ),
                array("\\\\", " ", "\\^", "\\]"),
                $letters
            ),
        );
    }

    return $arStemFunc[$sLang];
}

function stemming_upper($sText, $sLang="ru")
{
    $arStemFunc = stemming_init($sLang);
    $upper_function = $arStemFunc["upper"];
    return $upper_function($sText);
}

function _stemming($sText, $sLang="ru")
{
    static $WORD_CACHE=array();
    static $STOP_CACHE=array();

    $arStemFunc = stemming_init($sLang);
    $stem_function = $arStemFunc["stem"];
    $stop_function = $arStemFunc["stop"];

    $word_cache = &$WORD_CACHE[$sLang];
    $stop_cache = &$STOP_CACHE[$sLang];
    //uppercase and remove punctuation marks

    $stems = array();

    $tok = " ";
    $sText = preg_replace("/[^".$arStemFunc["pcre_letters"]."]+/", $tok, strtoupper($sText));

    $word = strtok($sText, $tok);
    while($word !== false)
    {
        $word = substr($word, 0, 50);
        if(isset($word_cache[$word]))
            $stem = $word_cache[$word];
        else
            $stem = $word_cache[$word] = $stem_function($word);
        if(!isset($stop_cache[$stem]))
            $stop_cache[$stem] = $stop_function($stem);
        if($stop_cache[$stem])
            $stems[$stem]++;
        $word = strtok($tok);
    }

    return $stems;
}

function stemming_upper_default($sText)
{
    return strtoupper($sText);
}

function stemming_stop_default($sWord)
{
    if(strlen($sWord) < 2)
        return false;
    else
        return true;
}
function stemming_letter_default()
{
    return "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789";
}

function _stemming_find($array, $table = '') {

    $count = count($array);

    if ($count) {

        $i = 0;

        $str = " stemming_count = '".$count."' AND ";

        foreach ($array as $k => $v) {

            $i++ ;

            $str .= " FIND_IN_SET('".$k."', stemming) != 0 ".($count != $i ? "AND" : "")." ";

        }

        if ($table) {

            return @mysql_result(mysql_query("SELECT id FROM ".$table." WHERE ".$str), 0, false);

        } else {

            return $str;

        }

    } else {
        return false;
    }
}

?>
