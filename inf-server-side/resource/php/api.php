<?php
	session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");
	if (isset($_POST['app']) && isset($_POST['cmd'])) { $has_data = true; $app = $_POST['app']; $cmd = $_POST['cmd']; $attr = $_POST['attr']; }
	else if (isset($_GET['app']) && isset($_GET['cmd'])) { $has_data = true; $app = $_GET['app']; $cmd = $_GET['cmd']; $attr = $_GET['attr']; }
	else $has_data = false; if ($has_data) {
		require($dirPWroot."e/resource/db_connect.php"); require_once($dirPWroot."resource/php/core/config.php");
        require_once($dirPWroot."resource/php/lib/TianTcl.php"); require($dirPWroot."resource/php/core/getip.php");
        require_once($dirPWroot."e/Pathway-Speech-Contest/resource/php/config.php");
        $rmte = ((isset($attr['remote']) && $attr['remote']) || (isset($_REQUEST['remote']) && $_REQUEST['remote'])); $remote = $rmte ? "remote" : "";
        $num2grade = array("ป.3", "ป.4", "ป.5", "ป.6", "ม.1", "ม.2", "ม.3", "ม.4", "ม.5", "ม.6");
		if ($app == "register") {
            if ($cmd == "chkEml") {
                $attr = $db -> real_escape_string(trim($attr));
                $success = $db -> query("SELECT ptpid FROM PathwaySCon_attendees WHERE email='$attr'");
                if ($success) echo '{"success": true, "isTaken": '.json_encode($success -> num_rows > 0).'}';
                else echo '{"success": false, "reason": [3, "Unable to check E-mail address duplicates"]}';
            } else if ($cmd == "addNew") {
                foreach ($attr as $ef => $ev) $attr[$ef] = $db -> real_escape_string(trim($ev));
                $getid = $db -> query("SELECT COUNT(ptpid) AS newid FROM PathwaySCon_attendees"); $newID = ($getid -> fetch_array(MYSQLI_ASSOC))['newid'];
                $pubID = date("y", time()).$_SESSION['event']['round'].str_repeat("0", 3-strlen($newID)).$newID;
                $success = $db -> query("INSERT INTO PathwaySCon_attendees (email,namef,namel,namen,phone,school,grade,publicID,line,ip) VALUES ('".strtolower($attr['email'])."','".$attr['namef']."','".$attr['namel']."','".$attr['namen']."','".$attr['phone']."','".$attr['school']."',".$attr['grade'].",".$pubID.",'".$attr['line']."','$ip')");
                if ($success) {
                    $newid = $db -> insert_id;
                    echo '{"success": true}'; slog("webForm", "PathwaySCon", "register", "new", $newid, "pass", $remote);
                    // Notify team via LINE application
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
                $success = $db -> query("SELECT ptpid,namef,namel,namen,publicID FROM PathwaySCon_attendees WHERE email='".$attr['user']."' AND phone='".$attr['pass']."'");
                if ($success) {
                    if ($success -> num_rows == 1) {
                        $result = $success -> fetch_array(MYSQLI_ASSOC);
                        $_SESSION['evt'] = array(
                            "user" => $result['ptpid'],
                            "namea" => $result['namef']." ".$result['namel'],
                            "namen" => $result['namen'],
                            "myID" => $result['publicID']
                        ); if ($rmte) {
                            $keyid = vsprintf("%s%s%s%s-%s%s%s%s%s-%s%s%s%s", str_split(substr($tcl -> encode((intval($result['ptpid'])+138)*138, 1), 0, 13)));
                        } echo '{"success": true, "info": ["'.($keyid??"").'", "'.$result['namef']." ".$result['namel'].'", "'.$result['namen'].'", '.$result['publicID'].']}';
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
                $getappall = $db -> query("SELECT COUNT(ptpid) AS amt FROM PathwaySCon_attendees WHERE ptpid > 1");
                $getappsnd = $db -> query("SELECT COUNT(smid) AS amt FROM PathwaySCon_submission WHERE ptpid > 1 AND round=".$_SESSION['event']['round']);
                $getvdogrd = $db -> query("SELECT COUNT(a.scid) AS amt FROM PathwaySCon_score a INNER JOIN PathwaySCon_submission b ON a.smid=b.smid WHERE b.ptpid > 1 GROUP BY a.smid");
                $getvdoall = $db -> query("SELECT COUNT(smid) AS amt FROM PathwaySCon_submission WHERE ptpid > 1");
                $getdonate = $db -> query("SELECT COUNT(dnid) AS amt FROM PathwaySCon_donation");
                $getschamt = $db -> query("(SELECT school,COUNT(ptpid) AS amount,GROUP_CONCAT(namen) AS peoples FROM PathwaySCon_attendees WHERE ptpid > 1 GROUP BY school HAVING amount > 1) UNION SELECT 'โรงเรียนอื่นๆ' AS school,COUNT(a.ptpid) AS amount,GROUP_CONCAT(a.namen) AS peoples FROM PathwaySCon_attendees a WHERE a.ptpid > 1 AND NOT EXISTS (SELECT b.school FROM PathwaySCon_attendees b WHERE a.school=b.school HAVING COUNT(ptpid) > 1) ORDER BY amount DESC,school");
                $getgrdamt = $db -> query("SELECT grade,COUNT(ptpid) AS amount,GROUP_CONCAT(namen) AS peoples FROM PathwaySCon_attendees WHERE ptpid > 1 GROUP BY grade ORDER BY amount DESC,grade");
                $db = create_database_connection("tiantcl_inf");
                $getpageview = $db -> query("SELECT COUNT(logid) AS amt FROM log_pageview WHERE url LIKE '%e/Pathway-Speech-Contest/%' AND NOT url LIKE '%e/Pathway-Speech-Contest/organize/%'");
                if ($getappall || $getappsnd || $getvdogrd || $getvdoall || $getdonate || $getschamt || $getgrdamt || $getpageview) {
                    $result = array(
                        "raw" => array(
                            "ptp-all" => ($getappall ? ($getappall -> fetch_array(MYSQLI_ASSOC))['amt'] : "-"),
                            "ptp-att" => ($getappsnd ? ($getappsnd -> fetch_array(MYSQLI_ASSOC))['amt'] : "-"),
                            "vdo-mark" => ($getvdogrd ? ($getvdogrd -> num_rows) : "-"),
                            "vdo-clip" => ($getvdoall ? intval(($getvdoall -> fetch_array(MYSQLI_ASSOC))['amt'])-($getvdogrd ? ($getvdogrd -> num_rows) : 0) : "-"),
                            "pageview" => ($getpageview ? ($getpageview -> fetch_array(MYSQLI_ASSOC))['amt'] : "-"),
                            "transac" => ($getdonate ? ($getdonate -> fetch_array(MYSQLI_ASSOC))['amt'] : "-")
                        ), "tbl" => array(
                            "schl-amt" => array(),
                            "grde-amt" => array()
                        )
                    ); if ($getschamt -> num_rows > 0) { while ($schamt = $getschamt -> fetch_assoc()) array_push($result['tbl']['schl-amt'], array(
                        "key" => $schamt['school'],
                        "amt" => intval($schamt['amount']),
                        "ppl" => $schamt['peoples']
                    )); } if ($getgrdamt -> num_rows > 0) { while ($grdamt = $getgrdamt -> fetch_assoc()) array_push($result['tbl']['grde-amt'], array(
                        "key" => $num2grade[intval($grdamt['grade'])],
                        "amt" => intval($grdamt['amount']),
                        "ppl" => $grdamt['peoples']
                    )); } echo '{"success": true, "info": '.json_encode($result).'}';
                } else echo '{"success": false, "reason": [3, "Unable to fetch statics."]}';
            } else if ($cmd == "race") {
                $getgroup = $db -> query("SELECT (CASE WHEN a.grade BETWEEN 0 AND 3 THEN 'ประถม' WHEN a.grade BETWEEN 4 AND 6 THEN 'มัธยมต้น' WHEN a.grade BETWEEN 7 AND 9 THEN 'มัธยมปลาย' END) AS Category, COUNT(a.ptpid) AS Amount, COUNT(b.smid) AS Submit FROM PathwaySCon_attendees a LEFT JOIN PathwaySCon_submission b ON a.ptpid=b.ptpid AND b.round=1 WHERE a.ptpid>1 GROUP BY Category");
                if ($getgroup) {
                    $result = array();
                    if ($getgroup -> num_rows > 0) { while ($grpamt = $getgroup -> fetch_assoc())
                        $result[$grpamt['Category']] = array($grpamt['Amount'], $grpamt['Submit']);
                    } echo '{"success": true, "info": '.json_encode($result).'}';
                } else echo '{"success": false, "reason": [3, "Unable to fetch amount."]}';
            } else if ($cmd == "sboard") {
                $sqlpre = "SELECT b.smid,CONCAT(c.namen, ' (', c.namef, ' ', c.namel, ')') AS name,CAST(AVG(a.p11) AS VARCHAR(5)) AS p11,CAST(AVG(a.p12) AS VARCHAR(5)) AS p12,CAST(AVG(a.p13) AS VARCHAR(5)) AS p13,CAST(AVG(a.p21) AS VARCHAR(5)) AS p21,CAST(AVG(a.p22) AS VARCHAR(5)) AS p22,CAST(AVG(a.p23) AS VARCHAR(5)) AS p23,CAST(AVG(a.p24) AS VARCHAR(5)) AS p24,CAST(AVG(a.p31) AS VARCHAR(5)) AS p31,CAST(AVG(a.p32) AS VARCHAR(5)) AS p32,CAST(AVG(a.p40) AS VARCHAR(5)) AS p40,CAST(AVG(a.mark) AS VARCHAR(5)) AS mark FROM PathwaySCon_score a INNER JOIN PathwaySCon_submission b ON a.smid=b.smid INNER JOIN PathwaySCon_attendees c ON b.ptpid=c.ptpid WHERE c.ptpid>1 AND";
                $sqlpre2 = "SELECT b.smid FROM PathwaySCon_score a INNER JOIN PathwaySCon_submission b ON a.smid=b.smid INNER JOIN PathwaySCon_attendees c ON b.ptpid=c.ptpid WHERE c.ptpid>1 AND"; $sqlpost2 = "AND a.judge=10011 ORDER BY a.mark DESC,b.lasttime,c.time LIMIT 3";
                $gettop = $db -> query("($sqlpre2 c.grade BETWEEN 0 AND 3 $sqlpost2) UNION ALL ($sqlpre2 c.grade BETWEEN 4 AND 6 $sqlpost2) UNION ALL ($sqlpre2 c.grade BETWEEN 7 AND 9 $sqlpost2)");
                $prim = array(); $midl = array(); $high = array(); $tops = array();
                if ($gettop && $gettop -> num_rows) { while ($readtop = $gettop -> fetch_assoc()) array_push($tops, $readtop['smid']); }
                $gettop = $db -> query("($sqlpre b.smid=".implode(" $sqlpost) UNION ALL ($sqlpre b.smid=", $tops)." $sqlpost)");
                if ($gettop && $gettop -> num_rows) { while ($readtop = $gettop -> fetch_assoc()) {
                    if (count($prim) < 3) array_push($prim, $readtop);
                    else if (count($midl) < 3) array_push($midl, $readtop);
                    else if (count($high) < 3) array_push($high, $readtop);
                    if (!empty($readtop['smid'])) array_push($tops, $readtop['smid']);
                } } $sqlpost = (count($tops) ? "AND NOT b.smid IN(".implode(",", $tops).") " : "")."GROUP BY a.smid ORDER BY mark DESC,b.lasttime,c.time";
                $getprim = $db -> query("$sqlpre c.grade BETWEEN 0 AND 3 $sqlpost"); if ($getprim && $getprim -> num_rows) { while ($readprim = $getprim -> fetch_assoc()) array_push($prim, $readprim); }
                $getmidl = $db -> query("$sqlpre c.grade BETWEEN 4 AND 6 $sqlpost"); if ($getmidl && $getmidl -> num_rows) { while ($readmidl = $getmidl -> fetch_assoc()) array_push($midl, $readmidl); }
                $gethigh = $db -> query("$sqlpre c.grade BETWEEN 7 AND 9 $sqlpost"); if ($gethigh && $gethigh -> num_rows) { while ($readhigh = $gethigh -> fetch_assoc()) array_push($high, $readhigh); }
                echo '{"success": true, "info": ['.json_encode($prim).', '.json_encode($midl).', '.json_encode($high).']}';
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
            if ($cmd == "submit" || $cmd == "addnew") {
                if ($cmd == "submit") {
                    $exor = "webForm";
                    unset($attr['remote']);
                    foreach ($attr as $ef => $ev) $attr[$ef] = $db -> real_escape_string(trim($ev));
                    $transac = empty($attr['when']) ? "NULL" : ("'".date("Y-m-d H:i:s", strtotime($attr['when'].":00"))."'");
                } else if ($cmd == "addnew") {
                    $exor = "botGoogle";
                    $attr = json_decode(file_get_contents('php://input'), true);
                    $transac = empty($attr['transac']) ? "NULL" : ("'".date("Y-m-d H:i:s", strtotime($attr['transac']))."'");
                } $time = $attr['time'] ?? "current_timestamp()"; if ($time <> "current_timestamp()") $time = "'".date("Y-m-d H:i:s", strtotime($attr['time']))."'";
                $attr['contact'] = (preg_match('/^0[689](\d{8}|\d-\d{3}-\d{4}|(-\d{4}){2})$/', $attr['contact'])) ? str_replace("-", "", $attr['contact']): strtolower($attr['contact']);
                $address = $attr['address'] ?? 'NULL'; if ($address <> "NULL") $address = "'$address'";
                // File mgmt
                if (isset($_FILES['slip'])) {
                    $target_dir = "../upload/slip/"; $imageFileType = strtolower(pathinfo(basename($_FILES['slip']["name"]), PATHINFO_EXTENSION));
                    $etfn = "people".$attr['reference'].".$imageFileType"; $target_file = $target_dir.$etfn;
                    $uploadOk = ($_FILES['slip']["size"] > 0 && $_FILES['slip']["size"] <= 3072000); // 3 MB
                    if (!in_array($imageFileType, array("png", "jpg", "jpeg", "gif", "heic"))) $uploadOk = false;
                    if ($uploadOk) {
                        if (file_exists($target_file)) unlink($target_file);
                        if (move_uploaded_file($_FILES['slip']["tmp_name"], $target_file)) $slip = $imageFileType;
                        else { slog($exor, "PathwaySCon", "donate", "new", $attr['contact'], "fail", $remote, "FileNoMove"); die('{"success": false, [3, "Unable to upload your photo. Please try again"]}'); }
                    } else { slog($exor, "PathwaySCon", "donate", "new", $attr['contact'], "fail", $remote, "notEligible"); die('{"success": false, [3, "Invalid photo property"]}'); }
                } if (!isset($slip)) $slip = "NULL";
                $success = $db -> query("INSERT INTO PathwaySCon_donation (contact,donor,amt,transac,refer,address,slip,ip,time) VALUES ('".$attr['contact']."','".$attr['sender']."','".$attr['amount']."',$transac,'".$attr['reference']."',$address,$slip,'$ip',$time)");
                if ($success) {
                    $newid = $db -> insert_id;
                    echo '{"success": true}'; slog($exor, "PathwaySCon", "donate", "new", $newid, "pass", $remote);
                    // Notify team via LINE application
                    require($dirPWroot."resource/php/lib/LINE.php");
                    $LINE -> setToken("970F4tFzYzTrBZ4ayvrhqKihmGFCrvPsM11sKrNhPPU");
                    $LINE -> notify("มีผู้ร่วมสนับสนุนทุนเพิ่ม → ".$attr['sender']."\r\nจำนวนเงิน ".$attr['amount']." บาท");
                    // End LINE Notify API
                } else { echo '{"success": false, "reason": [3, "Unable to record your donation. Please try again."]}'; slog($exor, "PathwaySCon", "donate", "new", $attr['contact'], "fail", $remote, "InvalidQuery"); }
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
                        $db -> query("UPDATE PathwaySCon_workshop SET view=view+1 WHERE wvid=".$readurl['wvid']);
                        echo '{"success": true, "info": "'.$readurl['link'].'"'.($rmte ? ', "clip": "'.($tcl -> encode($clip, 3)).'"' : "").'}';
                        slog($readurl['wvid'], "PathwaySCon", "video", "view", "", "pass", $remote);
                        $_SESSION['event']['workshop-URL'] = $readurl['link'];
                    } else {
                        $link = $tcl -> uuid("$name-$clip");
                        $success = $db -> query("INSERT INTO PathwaySCon_workshop (namen,video,link,ip) VALUES ('$name',$clip,'$link','$ip')");
                        if ($success) {
                            echo '{"success": true, "info": "'.$link.'"'.($rmte ? ', "clip": "'.($tcl -> encode($clip, 3)).'"' : "").'}';
                            slog($db -> insert_id, "PathwaySCon", "video", "view", "", "pass", $remote);
                            $_SESSION['event']['workshop-URL'] = $link;
                        } else { echo '{"success": false'; slog("webForm", "PathwaySCon", "video", "view", "$name,$clip", "fail", $remote, "NotEligible"); }
                    }
                } else { echo '{"success": false, "reason": [3, "Unable to load your video. Please try again."]}'; slog("webForm", "PathwaySCon", "video", "view", "$name,$clip", "fail", $remote, "InvalidQuery"); }
            }
        } else if ($app == "main") {
            $user = $rmte ? strval(intval($tcl -> decode(str_replace("-", "", $_REQUEST['remote'])."5d3"))/138-138) : ($_SESSION['evt']['user'] ?? "");
            if ($user == "") die('{"success": false, "reason": [3, "You are not signed in."]}');
            $round = $_SESSION['event']['round'];
            if ($cmd == "get") {
                if ($attr == "score") {
                    $getscore = $db -> query("SELECT CAST(AVG(a.p11) AS VARCHAR(5)) AS p11,CAST(AVG(a.p12) AS VARCHAR(5)) AS p12,CAST(AVG(a.p13) AS VARCHAR(5)) AS p13,CAST(AVG(a.p21) AS VARCHAR(5)) AS p21,CAST(AVG(a.p22) AS VARCHAR(5)) AS p22,CAST(AVG(a.p23) AS VARCHAR(5)) AS p23,CAST(AVG(a.p24) AS VARCHAR(5)) AS p24,CAST(AVG(a.p31) AS VARCHAR(5)) AS p31,CAST(AVG(a.p32) AS VARCHAR(5)) AS p32,CAST(AVG(a.p40) AS VARCHAR(5)) AS p40,CAST(AVG(a.mark) AS VARCHAR(5)) AS mark FROM PathwaySCon_score a INNER JOIN PathwaySCon_submission b ON a.smid=b.smid WHERE b.ptpid=$user AND b.round=$round GROUP BY a.smid");
                    if ($getscore) {
                        if ($getscore -> num_rows == 1) {
                            $readscore = $getscore -> fetch_array(MYSQLI_ASSOC);
                            echo '{"success": true, "info": {"type": true, "data": '.json_encode($readscore).'}}';
                        } else echo '{"success": true, "info": {"type": false, "data": ["gray", "'.($_COOKIE['set_lang']=="th"?"ยังไม่มีผลคะแนน":"No grades.").'"]}}';
                    } else echo '{"success": false, "reason": [3, "Unable to get scores."]}';
                } else if ($attr == "comment") {
                    $getperm = $db -> query("SELECT viewCmt FROM PathwaySCon_submission WHERE ptpid=$user AND round=$round");
                    if ($getperm && $getperm -> num_rows == 1) {
                        $permission = ($getperm -> fetch_array(MYSQLI_ASSOC))['viewCmt'];
                        if ($permission == "N") echo '{"success": true, "info": {"html": "'.($_COOKIE['set_lang']=="th"?"คุณไม่มีสิทธิ์ในการดูข้อความจากผู้พิจรณา":"You don't have permission to view comments.").' <a role=\\"button\\" onClick=\\"reqComment(\''.base_convert(time(), 10, 36).'\')\\" href=\\"javascript:void(0)\\" class=\\"yellow\\" draggable=\\"false\\">'.($_COOKIE['set_lang']=="th"?"ขอสิทธิ์":"Request permission").'</a>"}}';
                        else if ($permission == "R") echo '{"success": true, "info": {"html": "'.($_COOKIE['set_lang']=="th"?"คำขอสิทธิ์การดูข้อความอยู่ระหว่างการพิจรณา.<br>กรุณาเข้ามาใหม่ภายหลัง.":"Your request to view comments is under review.<br>Please come back later.").'"}}';
                        else if ($permission == "Y") {
                            $getcmt = $db -> query("SELECT a.scid,a.comment FROM PathwaySCon_score a INNER JOIN PathwaySCon_submission b ON a.smid=b.smid WHERE NOT a.comment='' AND b.ptpid=$user AND b.round=$round");
                            if ($getcmt) {
                                if ($getcmt -> num_rows > 0) {
                                    $info = ""; while ($readcmt = $getcmt -> fetch_assoc()) {
                                        $keyid = base_convert(intval($readcmt['scid']) * 138, 10, 36);
                                        $info .= '<li name=\\"'.$keyid.'\\">'.$readcmt['comment'].'</li><hr>';
                                    } echo '{"success": true, "info": {"html": "<ul class=\\"list\\">'.$info.'</ul>"}}';
                                } else echo '{"success": true, "info": {"html": "<center>'.($_COOKIE['set_lang']=="th"?"ไม่มีข้อความจากผู้พิจรณาคะแนน":"No comment.").'</center>"}}';
                            } else echo '{"success": false, "reason": [3, "Unable to load comments."]}';
                        } else echo '{"success": false, "reason": [2, "Invalid permission responded."]}';
                    } else echo '{"success": false, "reason": [3, "Unable to get permission."]}';
                }
            } else if ($cmd == "comment") {
                if ($attr == "request") {
                    $getperm = $db -> query("SELECT viewCmt FROM PathwaySCon_submission WHERE ptpid=$user AND round=$round");
                    if ($getperm && $getperm -> num_rows == 1) {
                        $permission = ($getperm -> fetch_array(MYSQLI_ASSOC))['viewCmt'];
                        if ($permission == "N") {
                            $success = $db -> query("UPDATE PathwaySCon_submission SET viewCmt='R',viewCmt_req=current_timestamp() WHERE ptpid=$user AND round=$round");
                            if ($success) {
                                echo '{"success": true, "info": {"html": "'.($_COOKIE['set_lang']=="th"?"ส่งคำขอสิทธิ์การดูข้อความจากผู้พิจรณาคะแนนแล้ว.<br>กรุณาเข้ามาใหม่ภายหลัง.":"Permission request sent.<br>Please come back later.").'"}}';
                                slog($user, "PathwaySCon", "comment", "request", "view", "pass", $remote);
                            } else {
                                echo '{"success": false, "reason": [3, "'.($_COOKIE['set_lang']=="th"?"เกิดข้อผิดพลาด.<br>กรุณาลองอีกครั้ง.":"There's an error.<br>Please try again.").'"]}';
                                slog($user, "PathwaySCon", "comment", "request", "view", "fail", $remote, "InvalidQuery");
                            }
                        } else if ($permission == "R") echo '{"success": true, "info": {"html": "'.($_COOKIE['set_lang']=="th"?"คำขอสิทธิ์การดูข้อความอยู่ระหว่างการพิจรณา.<br>กรุณาเข้ามาใหม่ภายหลัง.":"Your request to view comments is under review.<br>Please come back later.").'"}}';
                        else if ($permission == "Y") echo '{"success": false, "reason": [2, "'.($_COOKIE['set_lang']=="th"?"เกิดขอผิดผลาด.<br>คุณมีสิทธิ์ดูความคิดเห็นแล้ว ไม่สามารถยื่นคำขอซ้ำได้.":"There's an error.<br>You already have a permission to view comments. You cannot request view permission again.").'"]}';
                    } else echo '{"success": false, "reason": [3, "Unable to get permission."]}';
                }
            }
        }
		$db -> close();
	}
?>