<?php
	session_start();
	$dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	if (isset($_POST['app']) && isset($_POST['cmd'])) { $has_data = true; $app = $_POST['app']; $cmd = $_POST['cmd']; $attr = $_POST['attr']; }
	else if (isset($_GET['app']) && isset($_GET['cmd'])) { $has_data = true; $app = $_GET['app']; $cmd = $_GET['cmd']; $attr = $_GET['attr']; }
	else $has_data = false; if ($has_data) {
		require_once($dirPWroot."resource/php/core/config.php"); require_once($dirPWroot."resource/php/lib/TianTcl.php");
        if ($app == "account") {
            if ($cmd == "login") {
                $_SESSION['evt2'] = $attr;
				$_SESSION['evt2']["force_pwd_change"] = $_SESSION['evt2']["force_pwd_change"] == "true";
				echo '{"success": true}';
            } else if ($cmd == "new-pswd") {
				$_SESSION['evt2']["force_pwd_change"] = false;
				echo '{"success": true}';
            }
        }
	}
?>