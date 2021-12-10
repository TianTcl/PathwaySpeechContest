<?php
	session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	if (isset($_POST['app']) && isset($_POST['cmd'])) { $has_data = true; $app = $_POST['app']; $cmd = $_POST['cmd']; $attr = $_POST['attr']; }
	else if (isset($_GET['app']) && isset($_GET['cmd'])) { $has_data = true; $app = $_GET['app']; $cmd = $_GET['cmd']; $attr = $_GET['attr']; }
	else $has_data = false; if ($has_data) {
		require_once($dirPWroot."resource/php/core/config.php"); require_once($dirPWroot."resource/php/lib/TianTcl.php");
        if ($app == "account") {
            if ($cmd == "login") {
                $id = strval(intval($tcl -> decode(str_replace("-", "", $attr[0])."5d3"))/138-138);
                $_SESSION['evt'] = array(
					"user" => $id, "encid" => $attr[0],
					"namea" => $attr[1],
					"namen" => $attr[2]
				); echo '{"success": true}';
            } else if ($cmd == "logout") {
				$user = $_SESSION['evt']['encid'] ?? "";
                if (isset($_SESSION['evt'])) {
                    unset($_SESSION['evt']);
                } // else slog("", "PathwaySCon", "account", "logout", "", "fail", $remote, "NotExisted");
                header("Location: https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/api?app=account&cmd=logout&attr[user]=$user&attr[remote]=true");
            }
        }
	}
?>