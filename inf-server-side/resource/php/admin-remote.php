<?php
	session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");
    if (isset($_REQUEST['user']) && $_REQUEST['user']<>"") {
        $user = trim($_REQUEST['user']);
        if (isset($_REQUEST['do']) && $_REQUEST['do']<>"") {
            $action = trim($_REQUEST['do']);
            require($dirPWroot."e/resource/db_connect.php"); # require($dirPWroot."/resource/php/lib/TianTcl.php");
            if ($action == "login") {
                if (isset($_POST['user']) && isset($_POST['pass']) && !empty(trim($_POST['user'])) && !empty(trim($_POST['pass']))) {
                    $user = $db -> real_escape_string(trim($_POST['user']));
                    $pass = $db -> real_escape_string(trim($_POST['pass']));
                    $success = $db -> query("SELECT user_id,username,name,role,pswd FROM PathwaySCon_organizer WHERE username='$user' AND password='".md5($pass)."'");
                    if ($success) {
                        if ($success -> num_rows == 1) {
                            $uifo = $success -> fetch_array(MYSQLI_ASSOC);
                            $role = array_map("trim", explode(",", $uifo['role']));
                            $info = array(
                                "user" => $uifo['user_id'],
                                "usrn" => $uifo['username'],
                                "name" => $uifo['name'],
                                "role" => $role,
                                "force_pwd_change" => $uifo['pswd']=="N",
                            ); slog($uifo['user_id'], "PathwaySCon", "account", "login", "team", "pass", "remote");
                            echo '{"success": true, "info": '.json_encode($info).'}';
                        } else { echo '{"success": false, "reason": [3, "Incorrect username or password."]}'; slog("webForm", "PathwaySCon", "account", "login", "team:$user", "fail", "remote", "Incorrect"); }
                    } else { echo '{"success": false, "reason": [3, "Unable to authenticate. Please try again."]}'; slog("webForm", "PathwaySCon", "account", "login", "team:$user", "fail", "remote", "InvalidQuery"); }
                } else { echo '{"success": false, "reason": [1, "Missing parameter or parameter empty."]}'; slog("webForm", "PathwaySCon", "account", "login", "team:$user", "fail", "remote", "Empty"); }
            } else if ($action == "logout") {
                if ($user<>"") slog($user, "PathwaySCon", "account", "logout", "team", "pass", "remote");
                else slog($user, "PathwaySCon", "account", "logout", "team", "fail", "remote", "NotExisted");
                echo '{"success": true}';
            } else if ($action == "new-pswd") {
                if (isset($_POST['pwd-old']) && isset($_POST['pwd-new']) && isset($_POST['pwd-cnf']) && !empty(trim($_POST['pwd-old'])) && !empty(trim($_POST['pwd-new'])) && !empty(trim($_POST['pwd-cnf']))) {
                    $pwd_old = trim($_POST['pwd-old']);
                    $pwd_new = trim($_POST['pwd-new']);
                    $pwd_cnf = trim($_POST['pwd-cnf']);
                    if ($pwd_old == $pwd_new) $notify = '2, "Old password and new password can\'t be the same."';
                    else if ($pwd_new == $user) $notify = '2, "New password can\'t be your username."';
                    else if ($pwd_new <> $pwd_cnf) $notify = '2, "New password does not match."';
                    else {
                        $success = $db -> query("UPDATE PathwaySCon_organizer SET password='".md5($pwd_new)."',pswd='Y' WHERE user_id='$user' AND password='".md5($pwd_old)."'");
                        if ($success) { echo '{"success": true}'; slog($user, "PathwaySCon", "password", "new", "", "pass", "remote"); }
                        else { echo '{"success": false, "reason": [3, "Incorrect username or password."]}'; slog($user, "PathwaySCon", "password", "new", "", "fail", "remote", "Incorrect"); }
                    } if (isset($notify)) { echo '{"success": false, "reason": ['.$notify.']}'; slog($user, "PathwaySCon", "password", "new", "", "fail", "remote", "NotEligible"); }
                } else { echo '{"success": false, "reason": [1, "Missing parameter or parameter empty."]}'; slog($user, "PathwaySCon", "password", "new", "", "fail", "remote", "Empty"); }
            } else if ($action == "showTeam") {
                $teams = array("lead" => array(), "rest" => array());
                $sqlfront = "SELECT name,role,avatar,display FROM PathwaySCon_organizer";
                $getlead = $db -> query("$sqlfront WHERE role LIKE '%lead%' ORDER BY sequence,role,name");
                while ($readlead = $getlead -> fetch_assoc()) array_push($teams['lead'], $readlead);
                $getrest = $db -> query("$sqlfront WHERE NOT role LIKE '%lead%' ORDER BY sequence,role,name");
                while ($readrest = $getrest -> fetch_assoc()) array_push($teams['rest'], $readrest);
                echo json_encode($teams);
            } else if ($action == "getAdminProfile") {
                $myinfo = $db -> query("SELECT avatar,display FROM PathwaySCon_organizer WHERE user_id=$user");
                echo json_encode($myinfo -> fetch_array(MYSQLI_ASSOC));
            }
            $db -> close();
        }
    }
?>