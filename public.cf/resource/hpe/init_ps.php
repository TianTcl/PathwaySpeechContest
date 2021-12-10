<?php
    session_start();
    $my_url = ($_SERVER['REQUEST_URI']=="/organize/home")?"":"?return_url=".urlencode(substr(ltrim($_SERVER['REQUEST_URI'], "/"), 9)); // str_replace("#", "%23", "");
    if (!isset($dirPWroot)) $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	
    // App management
    $exptimeout = strval(time()+31536000);
    if (!isset($_COOKIE['set_theme'])) setcookie("set_theme", "light", $exptimeout, "/", $_SERVER['HTTP_HOST']);
    if (!isset($_COOKIE['set_lang'])) setcookie("set_lang", "en", $exptimeout, "/", $_SERVER['HTTP_HOST']);

    // PSC custom
    /* if (!isset($_SESSION['event'])) */ $_SESSION['event'] = array(
        "round" => "1"
    );
?>