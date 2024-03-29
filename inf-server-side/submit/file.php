<?php
	session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");

    require_once($dirPWroot."resource/php/lib/TianTcl.php");
    $rmte = (isset($_GET['remote']) && !empty($_GET['remote'])); $remote = $rmte ? "remote" : "";
    $user = $rmte ? strval(intval($tcl -> decode(str_replace("-", "", $_GET['remote'])))/138-138) : ($_SESSION['evt']['user'] ?? "");
    if ($user == "") die('{"success": false, "reason": [3, "You are not signed in."]}');
    require_once($dirPWroot."e/Pathway-Speech-Contest/resource/php/config.php"); $round = $config['round'];
    require($dirPWroot."e/resource/db_connect.php");
    if (isset($_GET['status'])) {
		echo json_encode(array(
            "success" => true,
            "info" => array(
                "v" => file_exists("../resource/upload/sv-$round/$user.mp4"),
                "s" => file_exists("../resource/upload/ps-$round/$user.png"),
                "g" => boolval($db -> query("SELECT a.scid FROM PathwaySCon_score2 a INNER JOIN PathwaySCon_submission b ON a.smid=b.smid WHERE b.ptpid=$user AND b.round=$round") -> num_rows),
                "c" => !empty(($db -> query("SELECT rank FROM PathwaySCon_submission WHERE ptpid=$user AND round=$round") -> fetch_array())['rank'])
            )
        ));
    } else if (isset($_GET['upload']) && isset($_FILES)) {
        $file = trim($_GET['upload']); switch ($file) {
            case "v": $back = "speech-video"; $tgdn = "sv"; $allow_type = array("mp4"); $maxFileSize = 25600000; /* 25 MB */ break;
            case "s": $back = "payment-slip"; $tgdn = "ps"; $allow_type = array("png"); $maxFileSize = 3072000; /* 3 MB */ break;
        } if (isset($back)) {
            $target_dir = "../resource/upload/$tgdn-$round/"; $imageFileType = strtolower(pathinfo(basename($_FILES['usf']["name"]), PATHINFO_EXTENSION));
            $etfn = "$user.$imageFileType"; $target_file = $target_dir.$etfn;
            if (!is_dir($target_dir)) mkdir($target_dir, 0755);
            $uploadOk = ($_FILES['usf']["size"] > 0 && $_FILES['usf']["size"] <= $maxFileSize);
            if (!in_array($imageFileType, $allow_type)) $uploadOk = false;
            if ($rmte) $back = "https://PathwaySpeechContest.cf/submit/$back";
            if ($uploadOk) {
                if (file_exists($target_file)) unlink($target_file);
                if (move_uploaded_file($_FILES['usf']["tmp_name"], $target_file)) {
                    require($dirPWroot."resource/php/core/getip.php");
                    if ($file == "v") {
                        $checkdup = $db -> query("SELECT smid FROM PathwaySCon_submission WHERE ptpid=$user AND round=$round");
                        if ($checkdup && $checkdup -> num_rows == 1) $db -> query("UPDATE PathwaySCon_submission SET edit=edit+1,lasttime=current_timestamp(),ip='$ip' WHERE smid=".($checkdup -> fetch_array(MYSQLI_ASSOC))['smid']);
                        else $db -> query("INSERT INTO PathwaySCon_submission (ptpid,round,ip) VALUES ($user,$round,'$ip')");
                    } slog($user, "PathwaySCon", "file", "new", $file, "pass");
                    header("Location: $back#status=success");
                }
                else { slog($user, "PathwaySCon", "file", "new", $file, "fail"); header("Location: $back#status=error"); }
            } else { slog($user, "PathwaySCon", "file", "new", $file, "fail", "", "NotEligible"); header("Location: $back#status=failed"); }
        } else header("Location: ".$_SERVER['HTTP_REFERER']."#status=unknown");
    }
    $db -> close();
?>