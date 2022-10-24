<?php
	session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");

    if (!isset($_REQUEST['set']) || trim($_REQUEST['set'])=="") exit(0);

    require($dirPWroot."e/resource/db_connect.php"); # require($dirPWroot."/resource/php/lib/TianTcl.php");
    $reqG = trim($_REQUEST['g'] ?? ""); if ($reqG == "") $reqG = trim($_REQUEST['group'] ?? "");
    $reqData = trim($_REQUEST['data'] ?? "");
    $reqQ = $db -> real_escape_string(trim($_REQUEST['q'] ?? ""));
    $rmte = isset($_REQUEST['remote']); $remote = ($rmte ? "remote" : "");
    $rdrPrefix = ($rmte ? "https://PathwaySpeechContest.cf" : "/e/Pathway-Speech-Contest");
    switch (trim($_REQUEST['set'])) {
        case "donate": {
            if (isset($_FILES['tax:slip']) && isset($_POST['address'])) {
                $getinfo = $db -> query("SELECT dnid,slip,SUBSTRING(COALESCE(transac, time), 1, 10) AS date FROM PathwaySCon_donation WHERE refer='$reqQ'");
                if ($getinfo) {
                    if ($getinfo -> num_rows == 1) {
                        $readinfo = $getinfo -> fetch_array(MYSQLI_ASSOC);
                        if (!empty($readinfo['slip'])) header("Location: $rdrPrefix/donate/$reqQ/edit#status=901");
                        else {
                            $exor = "webForm";
                            // File mgmt
                            $slipF = $_FILES['tax:slip'];
                            $target_dir = "../../resource/upload/slip/"; $slipT = strtolower(pathinfo(basename($slipF["name"]), PATHINFO_EXTENSION));
                            $etfn = str_replace("-", "_", $readinfo['date'])."-$reqQ.$slipT"; $target_file = $target_dir.$etfn;
                            $uploadOk = ($slipF["size"] > 0 && $slipF["size"] <= 3072000); // 3 MB
                            if (!in_array($slipT, array("png", "jpg", "jpeg", "gif", "heic"))) $uploadOk = false;
                            if ($uploadOk) {
                                if (file_exists($target_file)) unlink($target_file);
                                if (!move_uploaded_file($slipF["tmp_name"], $target_file)) {
                                    slog($exor, "PathwaySCon", "donate", "edit", $readinfo['dnid'], "fail", $remote, "FileNoMove");
                                    header("Location: $rdrPrefix/donate/$reqQ/edit#status=908");
                                }
                            } else {
                                slog($exor, "PathwaySCon", "donate", "edit", $readinfo['dnid'], "fail", $remote, "notEligible");
                                header("Location: $rdrPrefix/donate/$reqQ/edit#status=909");
                            } // Update record
                            $address = $db -> real_escape_string(trim($_POST['address']));
                            $success = $db -> query("UPDATE PathwaySCon_donation SET address='$address',slip='$slipT' WHERE dnid=".$readinfo['dnid']);
                            if ($success) {
                                slog($exor, "PathwaySCon", "donate", "edit", $readinfo['dnid'], "pass", $remote);
                                header("Location: $rdrPrefix/donate/$reqQ/edit?complete=success");
                            } else {
                                slog($exor, "PathwaySCon", "donate", "edit", $readinfo['dnid'], "fail", $remote, "InvalidQuery");
                                header("Location: $rdrPrefix/donate/$reqQ/edit#status=910");
                            }
                        }
                    } else header("Location: $rdrPrefix/donate/$reqQ/edit#status=900");
                } else header("Location: $rdrPrefix/donate/$reqQ/edit#status=905");
            } else header("Location: $rdrPrefix/donate/$reqQ/edit#status=902");
        } break;
        case "video-form": {
            require_once($dirPWroot."e/Pathway-Speech-Contest/resource/php/config.php"); $round = $config['round'];
            $attr = json_decode(file_get_contents('php://input'), true);
            $user = $db -> real_escape_string(substr(trim($attr["ID"]), 3));
            $email = $db -> real_escape_string(trim($attr["email"]));
            $time = $db -> real_escape_string(trim($attr["time"]));
            $time = str_replace("T", " ", explode(".", $time)[0]);
            $ip = $db -> real_escape_string(trim($attr["IP"]));
            // Check authorization
            $checkAuth = $db -> query("SELECT ptpid FROM PathwaySCon_attendees WHERE ptpid=$user AND email='$email'");
            if (!$checkAuth || $checkAuth -> num_rows <> 1) {
                slog($user, "PathwaySCon", "file", "new", "v", "fail", "botGoogle", "Unauthorized");
                echo json_encode(array("success" => false, "info" => array("user" => $user), "reason" => array(array(3, "Authorization error."))), JSON_PRETTY_PRINT);
            } else {
                // Save
                $checkdup = $db -> query("SELECT smid FROM PathwaySCon_submission WHERE ptpid=$user AND round=$round");
                if ($checkdup && $checkdup -> num_rows == 1) $db -> query("UPDATE PathwaySCon_submission SET edit=edit+1,lasttime='$time',ip='$ip' WHERE smid=".($checkdup -> fetch_array(MYSQLI_ASSOC))['smid']);
                else $db -> query("INSERT INTO PathwaySCon_submission (ptpid,round,firsttime,lasttime,ip) VALUES ($user,$round,'$time','$time','$ip')");
                slog($user, "PathwaySCon", "file", "new", "v", "pass", "botGoogle");
                echo json_encode(array("success" => true, "info" => array("user" => $user)), JSON_PRETTY_PRINT);
            }
        } break;
    } $db -> close();
?>