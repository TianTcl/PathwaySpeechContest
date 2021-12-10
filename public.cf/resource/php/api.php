<?php
	session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	if (isset($_POST['app']) && isset($_POST['cmd'])) { $has_data = true; $app = $_POST['app']; $cmd = $_POST['cmd']; $attr = $_POST['attr']; }
	else if (isset($_GET['app']) && isset($_GET['cmd'])) { $has_data = true; $app = $_GET['app']; $cmd = $_GET['cmd']; $attr = $_GET['attr']; }
	else $has_data = false; if ($has_data) {
		require($dirPWroot."e/resource/db_connect.php"); require_once($dirPWroot."resource/php/core/config.php");
		if ($app == "register") {
            if ($cmd == "chkEml") {
                $attr = $db -> real_escape_string(trim($attr));
                $success = $db -> query("SELECT ptpid FROM PathwaySCon_attendees WHERE email='$attr'");
                if ($success) echo '{"success": true, "isTaken": '.json_encode($success -> num_rows > 0).'}';
                else echo '{"success": false, "reason": [3, "Unable to check E-mail address duplicates"]}';
            } else if ($cmd == "addNew") {
                foreach ($attr as $ef => $ev) $attr[$ef] = $db -> real_escape_string($ev);
                require($dirPWroot."resource/php/core/getip.php");
                $success = $db -> query("INSERT INTO PathwaySCon_attendees (email,namef,namel,namen,phone,school,grade,line,ip) VALUES ('".strtolower($attr['email'])."','".$attr['namef']."','".$attr['namel']."','".$attr['namen']."','".$attr['phone']."','".$attr['school']."',".$attr['grade'].",'".$attr['line']."','$ip')");
                if ($success) { echo '{"success": true}'; slog("webForm", "PathwaySCon", "register", "new", strtolower($attr['email']), "pass"); }
                else { echo '{"success": false, "reason": [3, "Unable to register. Please try again."]}'; slog("webForm", "PathwaySCon", "register", "new", strtolower($attr['email']), "fail", "", "InvalidQuery"); }
            }
        } else if ($app == "account") {
            if ($cmd == "login") {
                $attr['user'] = $db -> real_escape_string(trim($attr['user']));
                $attr['pass'] = $db -> real_escape_string(trim($attr['pass']));
                $success = $db -> query("SELECT ptpid,namef,namel,namen FROM PathwaySCon_attendees WHERE email='".$attr['user']."' AND phone='".$attr['pass']."'");
                if ($success) {
                    if ($success -> num_rows == 1) {
                        $result = $success -> fetch_array(MYSQLI_ASSOC);
                        $_SESSION['evt'] = array(
                            "user" => $result['ptpid'],
                            "namea" => $result['namef']." ".$result['namel'],
                            "namen" => $result['namen']
                        ); echo '{"success": true}';
                        slog($_SESSION['evt']['user'], "PathwaySCon", "account", "login", "", "pass");
                    } else { echo '{"success": false, "reason": [3, "Incorrect E-mail address or phone number"]}'; slog("webForm", "PathwaySCon", "account", "login", $attr['user'].",".$attr['pass'], "fail", "", "Incorrect"); }
                } else { echo '{"success": false, "reason": [3, "Unable to sign you in. Please try again."]}'; slog("webForm", "PathwaySCon", "account", "login", $attr['user'].",".$attr['pass'], "fail", "", "InvalidQuery"); }
            } else if ($cmd == "logout") {
                if (isset($_SESSION['evt'])) {
                    slog($_SESSION['evt']['user'], "PathwaySCon", "account", "logout", "", "pass");
                    unset($_SESSION['evt']);
                } else slog("", "PathwaySCon", "account", "logout", "", "fail", "", "NotExisted");
                header("Location: /e/Pathway-Speech-Contest/");
            }
        } else if ($app == "stat") {
            if ($cmd == "fetch") {
                $getappall = $db -> query("SELECT COUNT(ptpid)-1 AS amt FROM PathwaySCon_attendees");
                $db = create_database_connection("tiantcl_inf");
                $getpageview = $db -> query("SELECT COUNT(logid) AS amt FROM log_pageview WHERE url LIKE '%e/Pathway-Speech-Contest/%' AND NOT url LIKE '%e/Pathway-Speech-Contest/organize/%'");
                if ($getappall || $getpageview) {
                    $result = array(
                        "ptp-all" => ($getappall ? ($getappall -> fetch_array(MYSQLI_ASSOC))['amt'] : "-"),
                        "ptp-att" => "0",
                        "vdo-mark" => "0",
                        "vdo-clip" => "0",
                        "pageview" => ($getpageview ? ($getpageview -> fetch_array(MYSQLI_ASSOC))['amt'] : "-")
                    ); echo '{"success": true, "info": '.json_encode($result).'}';
                } else echo '{"success": false, "reason": [3, "Unable to fetch statics."], "err": "'.($db -> error).'"}';
            }
        }
		$db -> close();
	}
?>