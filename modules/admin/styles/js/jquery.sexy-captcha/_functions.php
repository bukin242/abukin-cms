<?


function _captcha() {

    $requestVars = isset($_REQUEST) ? $_REQUEST : array();

    if(substr($requestVars['captcha'], 10) == $_SESSION['captchaCodes'][$_SESSION['captchaAnswer']])
    {
        return true;

    } else {

        return false;

    }

}


?>
