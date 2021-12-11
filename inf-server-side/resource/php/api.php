<?php
	session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");
	if (isset($_POST['app']) && isset($_POST['cmd'])) { $has_data = true; $app = $_POST['app']; $cmd = $_POST['cmd']; $attr = $_POST['attr']; }
	else if (isset($_GET['app']) && isset($_GET['cmd'])) { $has_data = true; $app = $_GET['app']; $cmd = $_GET['cmd']; $attr = $_GET['attr']; }
	else $has_data = false; if ($has_data) {
		require($dirPWroot."e/resource/db_connect.php"); require_once($dirPWroot."resource/php/core/config.php");
        require_once($dirPWroot."resource/php/lib/TianTcl.php"); require($dirPWroot."resource/php/core/getip.php");
        $rmte = (isset($attr['remote']) && $attr['remote']); $remote = $rmte ? "remote" : "";
		if ($app == "register") {
            if ($cmd == "chkEml") {
                $attr = $db -> real_escape_string(trim($attr));
                $success = $db -> query("SELECT ptpid FROM PathwaySCon_attendees WHERE email='$attr'");
                if ($success) echo '{"success": true, "isTaken": '.json_encode($success -> num_rows > 0).'}';
                else echo '{"success": false, "reason": [3, "Unable to check E-mail address duplicates"]}';
            } else if ($cmd == "addNew") {
                foreach ($attr as $ef => $ev) $attr[$ef] = $db -> real_escape_string(trim($ev));
                $success = $db -> query("INSERT INTO PathwaySCon_attendees (email,namef,namel,namen,phone,school,grade,line,ip) VALUES ('".strtolower($attr['email'])."','".$attr['namef']."','".$attr['namel']."','".$attr['namen']."','".$attr['phone']."','".$attr['school']."',".$attr['grade'].",'".$attr['line']."','$ip')");
                if ($success) {
                    $newid = $db -> insert_id;
                    echo '{"success": true}'; slog("webForm", "PathwaySCon", "register", "new", $newid, "pass", $remote);
                    // Notify team via LINE application
                    $num2grade = array("ป.3", "ป.4", "ป.5", "ป.6", "ม.1", "ม.2", "ม.3", "ม.4", "ม.5", "ม.6");
                    require($dirPWroot."resource/php/lib/LINE.php");
                    $LINE -> setToken("970F4tFzYzTrBZ4ayvrhqKihmGFCrvPsM11sKrNhPPU");
                    $LINE -> notify("มีผู้สมัครใหม่ → ".$attr['namen']."\r\n".$num2grade[intval($attr['grade'])]." โรงเรียน".$attr['school']."\r\nจำนวนผู้สมัครทั้งหมด ".strval(intval($newid)-1)." คน");
                    // End LINE Notify API
                } else { echo '{"success": false, "reason": [3, "Unable to register. Please try again."]}'; slog("webForm", "PathwaySCon", "register", "new", strtolower($attr['email']), "fail", $remote, "InvalidQuery"); }
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
                        ); if ($rmte) {
                            $keyid = substr($tcl -> encode((intval($result['ptpid'])+138)*138, 1), 0, 13); // 5d3
							$keyid = substr($keyid, 0, 4)."-".substr($keyid, 4, 5)."-".substr($keyid, 9, 4);
                        } echo '{"success": true, "info": ["'.($keyid??"").'", "'.$result['namef']." ".$result['namel'].'", "'.$result['namen'].'"]}';
                        slog($_SESSION['evt']['user'], "PathwaySCon", "account", "login", "", "pass", $remote);
                    } else { echo '{"success": false, "reason": [3, "Incorrect E-mail address or phone number"]}'; slog("webForm", "PathwaySCon", "account", "login", $attr['user'].",".$attr['pass'], "fail", $remote, "Incorrect"); }
                } else { echo '{"success": false, "reason": [3, "Unable to sign you in. Please try again."]}'; slog("webForm", "PathwaySCon", "account", "login", $attr['user'].",".$attr['pass'], "fail", $remote, "InvalidQuery"); }
            } else if ($cmd == "logout") {
                $user = $rmte ? strval(intval($tcl -> decode(str_replace("-", "", $attr['user'])."5d3"))/138-138) : ($_SESSION['evt']['user'] ?? "");
                if (isset($_SESSION['evt'])) {
                    slog($user, "PathwaySCon", "account", "logout", "", "pass", $remote);
                    unset($_SESSION['evt']);
                } else slog($user, "PathwaySCon", "account", "logout", "", "fail", $remote, "NotExisted");
                header("Location: ".($rmte?"https://PathwaySpeechContest.cf":"/e/Pathway-Speech-Contest/"));
            }
        } else if ($app == "stat") {
            if ($cmd == "fetch") {
                $getappall = $db -> query("SELECT COUNT(ptpid)-2 AS amt FROM PathwaySCon_attendees");
                $getappsnd = $db -> query("SELECT COUNT(smid) AS amt FROM PathwaySCon_submission WHERE round=".$_SESSION['event']['round']);
                $getvdoall = $db -> query("SELECT COUNT(smid) AS amt FROM PathwaySCon_submission");
                $getdonate = $db -> query("SELECT COUNT(dnid) AS amt FROM PathwaySCon_donation");
                $db = create_database_connection("tiantcl_inf");
                $getpageview = $db -> query("SELECT COUNT(logid) AS amt FROM log_pageview WHERE url LIKE '%e/Pathway-Speech-Contest/%' AND NOT url LIKE '%e/Pathway-Speech-Contest/organize/%'");
                if ($getappall || $getappsnd || $getpageview || $getdonate) {
                    $result = array(
                        "ptp-all" => ($getappall ? ($getappall -> fetch_array(MYSQLI_ASSOC))['amt'] : "-"),
                        "ptp-att" => ($getappsnd ? ($getappsnd -> fetch_array(MYSQLI_ASSOC))['amt'] : "-"),
                        "vdo-mark" => "0",
                        "vdo-clip" => ($getvdoall ? ($getvdoall -> fetch_array(MYSQLI_ASSOC))['amt'] : "-"),
                        "pageview" => ($getpageview ? ($getpageview -> fetch_array(MYSQLI_ASSOC))['amt'] : "-"),
                        "transac" => ($getdonate ? ($getdonate -> fetch_array(MYSQLI_ASSOC))['amt'] : "-")
                    ); echo '{"success": true, "info": '.json_encode($result).'}';
                } else echo '{"success": false, "reason": [3, "Unable to fetch statics."]}';
            }
        } else if ($app == "giveaway") {
            if ($cmd == "get") {
                $admin = $_SESSION['evt2']['user'];
                $user = $db -> real_escape_string(strtolower(trim($attr['user'])));
                $book = $db -> real_escape_string(trim($attr['book']));
                $getdup = $db -> query("SELECT gaid FROM PathwaySCon_giveaway WHERE client='$user' AND sheet='$book'");
                if ($getdup -> num_rows == 1) {
                    $id = ($getdup -> fetch_array(MYSQLI_ASSOC))['gaid'];
                    slog($admin, "PathwaySCon", "giveaway", "new", $id, "fail", $remote, "duplicate", false, true);
                    die('{"success": true, "info": "'.($tcl -> encode($id, 2)).'"}');
                } else {
                    $success = $db -> query("INSERT INTO PathwaySCon_giveaway (client,sheet,ip) VALUES ('$user','$book','$ip')");
                    if ($success) {
                        $id = $db -> insert_id;
                        slog($admin, "PathwaySCon", "giveaway", "new", $id, "pass", $remote, "", false, true);
                        die('{"success": true, "info": "'.($tcl -> encode($id, 2)).'"}');
                    } else {
                        slog($admin, "PathwaySCon", "giveaway", "new", "$user,$book", "fail", $remote, "InvalidQuery", false, true);
                        die('{"success": false, "reason": [3, "Unable to generate link. Please try again."]}');
                    }
                }
            }
        } else if ($app == "donate") {
            if ($cmd == "submit") {
                unset($attr['remote']);
                foreach ($attr as $ef => $ev) $attr[$ef] = $db -> real_escape_string(trim($ev));
                $transac = empty($attr['when']) ? "" : date("Y-m-d H:i:s", strtotime($attr['when'].":00"));
                $success = $db -> query("INSERT INTO PathwaySCon_donation (email,donor,amt,transac,refer,ip) VALUES ('".strtolower($attr['email'])."','".$attr['sender']."','".$attr['amount']."','$transac','".$attr['reference']."','$ip')");
                if ($success) {
                    $newid = $db -> insert_id;
                    echo '{"success": true}'; slog("webForm", "PathwaySCon", "donate", "new", $newid, "pass", $remote);
                    // Notify team via LINE application
                    require($dirPWroot."resource/php/lib/LINE.php");
                    $LINE -> setToken("970F4tFzYzTrBZ4ayvrhqKihmGFCrvPsM11sKrNhPPU");
                    $LINE -> notify("มีผู้ร่วมสนับสนุนทุนเพิ่ม → ".$attr['sender']."\r\nจำนวนเงิน ".$attr['amount']." บาท");
                    // End LINE Notify API
                } else { echo '{"success": false, "reason": [3, "Unable to record your donation. Please try again."]}'; slog("webForm", "PathwaySCon", "donate", "new", strtolower($attr['email']), "fail", $remote, "InvalidQuery"); }
            }
        } else if ($app == "workshop") {
            if ($cmd == "view") {
                unset($attr['remote']);
                $name = $db -> real_escape_string(trim($attr['name']));
                $clip = $db -> real_escape_string(trim($attr['clip']));
                $geturl = $db -> query("SELECT wvid,link FROM PathwaySCon_workshop WHERE namen='$name' AND video=$clip");
                if ($geturl) {
                    if ($geturl -> num_rows == 1) {
                        $readurl = $geturl -> fetch_array(MYSQLI_ASSOC);
                        $db -> query("UPDATE PathwaySCon_workshop SET view=view+1 WHERE vwid=".$readurl['vwid']);
                        echo '{"success": true, "info": "'.$readurl['link'].'"}';
                        slog($readurl['vwid'], "PathwaySCon", "video", "view", "", "pass", $remote);
                        $_SESSION['event']['workshop-URL'] = $readurl['link'];
                    } else {
                        $link = $tcl -> uuid("$name-$clip");
                        $success = $db -> query("INSERT INTO PathwaySCon_workshop (namen,video,link,ip) VALUES ('$name',$clip,'$link','$ip')");
                        if ($success) {
                            echo '{"success": true, "info": "'.$link.'"}';
                            slog($db -> insert_id, "PathwaySCon", "video", "view", "", "pass", $remote);
                            $_SESSION['event']['workshop-URL'] = $link;
                        } else { echo '{"success": false'; slog("webForm", "PathwaySCon", "video", "view", "$name,$clip", "fail", $remote, "NotEligible"); }
                    }
                } else { echo '{"success": false, "reason": [3, "Unable to load your video. Please try again."]}'; slog("webForm", "PathwaySCon", "video", "view", "$name,$clip", "fail", $remote, "InvalidQuery"); }
            }
        }
		$db -> close();
	}
?>