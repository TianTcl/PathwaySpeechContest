<?php
	session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");
	if (isset($_POST['app']) && isset($_POST['cmd'])) { $has_data = true; $app = $_POST['app']; $cmd = $_POST['cmd']; $attr = $_POST['attr']; }
	else if (isset($_GET['app']) && isset($_GET['cmd'])) { $has_data = true; $app = $_GET['app']; $cmd = $_GET['cmd']; $attr = $_GET['attr']; }
	else $has_data = false; if ($has_data) {
		require($dirPWroot."e/resource/db_connect.php"); require_once($dirPWroot."resource/php/core/config.php");
		require_once($dirPWroot."resource/php/lib/TianTcl.php"); require($dirPWroot."resource/php/core/getip.php");
        $rmte = (isset($_REQUEST['remote']) && $_REQUEST['remote']); $remote = $rmte ? "remote" : "";
		if ($app == "mail") {
            if ($cmd == "send") {
				// Config
				$mail = array("recipients" => array(), "settings" => array()); $mail['setting'] = array(
					array("var" => "account.name", "value" => "Pathway Speech Contest"),
					array("var" => "support_mail", "value" => "devtech@PathwaySpeechContest.cf")
				); $mail['recipient'] = explode(",", $attr['rcp']); switch ($attr['mode']) {
					case "remind-12":
						$mail['templateID'] = "ynrw7gy50k42k8e3"; $mail['pos'] = 0;
						$mail['topic'] = "Reminder";
						$mail['day'] = strval(floor((strtotime("2021-12-31 23:59:59") - time()) / 86400));
						array_push($mail['setting'], array("var" => "day", "value" => $mail['day']));
					break; case "remind-8":
						$mail['templateID'] = "ynrw7gy50k42k8e3"; $mail['pos'] = 1;
						$mail['topic'] = "Reminder";
						$mail['day'] = strval(floor((strtotime("2021-12-31 23:59:59") - time()) / 86400));
						array_push($mail['setting'], array("var" => "day", "value" => $mail['day']));
					break; case "remind-5":
						$mail['templateID'] = "ynrw7gy50k42k8e3"; $mail['pos'] = 2;
						$mail['topic'] = "Reminder";
						$mail['day'] = strval(floor((strtotime("2021-12-31 23:59:59") - time()) / 86400));
						array_push($mail['setting'], array("var" => "day", "value" => $mail['day']));
					break; case "remind-0":
						$mail['templateID'] = "ynrw7gy50k42k8e3"; $mail['pos'] = 3;
						$mail['topic'] = "Reminder";
						$mail['day'] = strval(floor((strtotime("2021-12-31 23:59:59") - time()) / 86400));
						array_push($mail['setting'], array("var" => "day", "value" => $mail['day']));
					break; default: die('{"success": false, "reason", [2, "Invalid message selected"]}'); break;
				} $mail['pos'] = pow(2, $mail['pos']);
				$list = $db -> query("SELECT ptpid,email,namef,namel,mail FROM PathwaySCon_attendees WHERE ptpid IN('".implode("','", $mail['recipient'])."') AND NOT mail&".$mail['pos']);
				if ($list && $list -> num_rows > 0) {
					$return = array(); while ($ptp = $list -> fetch_assoc()) {
						array_push($return, intval($ptp['ptpid']));
						array_push($mail['recipients'], array("email" => $ptp['email']));
						$each_setting = $mail['setting']; array_push($each_setting, array("var" => "recp.name", "value" => $ptp['namef']." ".$ptp['namel']));
						array_push($mail['settings'], array("substitutions" => $each_setting, "email" => $ptp['email']));
					}
					// Setting
					$email = curl_init(); curl_setopt($email, CURLOPT_URL, "https://api.mailersend.com/v1/email");
					curl_setopt($email, CURLOPT_RETURNTRANSFER, 1); curl_setopt($email, CURLOPT_POST, 1);
					$header = json_encode(array(
						"from" => array("email" => "TianTcl@pathwayspeechcontest.cf", "name" => "Tecillium (UFDT)"),
						"to" => $mail['recipients'], "subject" => "Pathway Speech Contest - ".$mail['topic'], // Can be ["to", "cc", "bcc"]
						"variables" => $mail['settings'], "template_id" => $mail['templateID']
					)); curl_setopt($email, CURLOPT_POSTFIELDS, $header); $headers = array(
						"Content-Type: ". "application/json", "X-Requested-With: ". "XMLHttpRequest",
						"Authorization: ". "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMGQ4NjZhZTIyMDg1OWZiN2QxZGU5YjljY2MyNTlkZWYzMDI1YmFjYTNlY2YwODQ3Yjk4M2E1OWQ4YTAwNTlkMDVkYjg1YmE0YmRhZTU5NzIiLCJpYXQiOjE2Mzk4NDgyMTguNjkxMDI1LCJuYmYiOjE2Mzk4NDgyMTguNjkxMDI4LCJleHAiOjQ3OTU1MjE4MTguNjM1MjA2LCJzdWIiOiIxNzQ5NyIsInNjb3BlcyI6WyJlbWFpbF9mdWxsIiwidGVtcGxhdGVzX2Z1bGwiXX0.JHvcjSqX5wNzcALozE6jUA01VluQQsANTWlM0I2nvXs9e_Vm6eFgNrsZFBWtqyRxa4E7Xez_WCfNPh48pFspCPtj-cwixBKYiU7I9jF6_B7zz3teX5lU2Uk6u4Jb32BPS6J1PeJhYEDMXWpbW7Jki8gOwLY0oTcWreVOCoTHhez0P8wAGdJZgQ1P8WXmzPiK5wnpGQq-gV8UT70pgauvbVUny98pDBMURU-GJ_w5LsNK-1_yrZ81nyJspH3UrT3kbNHHHYRSP8sDM42JfxyBapIdymLArykemJ4KVXz4w9v66DQuCdG_X4kZY_L17gXI2PxKtJAbdnVPyzpwedkmR270t1j4JnX-WYjPV4glNFVl-fX6A3MgTvGZvAGZeBrHRwAEBVoABQeLJJpBlT3pyRB1-fy2PDGN0RzTmDRLnUpDtKt8eUpYtIv7b0zFT_Imgw_q5ayHNq-SUCV9oRCSuREXegdyJQw48TfIjOutCKlYDzuWCkHuri32FQZqtQqyHASlVGtEHMWm_8rnxwBQfATMNNAvtyNCaPgRguPUv7eiarJnzEAgbCO839dvdicRHzT6vZstWwAWOJ4drkIWgkJ-ARbcIoHhWyfXfbcjiYPfZA_5INBMNy2lh8lx-3X1omUe40u679klGBpAkbjTY3CARDJ8Xf_MfORVRmG4m18"
					); curl_setopt($email, CURLOPT_HTTPHEADER, $headers);
					// Output
					$result = curl_exec($email); if (curl_errno($email)) die('{"success": false, "reason": [3, "'.json_encode(curl_error($email)).'"}');
					curl_close($email); if (!$result) {
						echo '{"success": true, "info": '.json_encode($return).'}';
						$success = $db -> query("UPDATE PathwaySCon_attendees SET mail=mail+".$mail['pos']." WHERE ptpid IN(".implode(",", $return).")");
					} else echo '{"success": false, "reason": [3, "Unable to send Email'.(count($mail['recipient'])>1?"s":"").'."], "error": '.$result.'}';
				} else echo '{"success": false, "reason": [2, "No recipient"]}';
            } else if ($cmd == "list") {
				$get = $db -> query("SELECT ptpid,namef,namel,namen,grade,mail FROM PathwaySCon_attendees ORDER BY mail ASC,ptpid DESC");
				$user = array(); while ($read = $get -> fetch_assoc()) array_push($user, $read);
				echo '{"success": true, "info": '.json_encode($user).'}';
			}
        } else if ($app == "grade") {
			$user = ($rmte) ? ($db -> real_escape_string(trim($_REQUEST['remote']))) : $_SESSION['evt2']['user'];
			if ($cmd == "list") {
				$getsbmt = $db -> query("SELECT a.smid,CONCAT(b.namen,' (',b.namef,' ',b.namel,')') AS name,(CASE WHEN b.grade BETWEEN 0 AND 3 THEN 1 WHEN b.grade BETWEEN 4 AND 6 THEN 2 WHEN b.grade BETWEEN 7 AND 9 THEN 3 END) AS division FROM PathwaySCon_submission a INNER JOIN PathwaySCon_attendees b ON a.ptpid=b.ptpid WHERE b.ptpid > 1 ORDER BY division,name");
				$sbmts = array(); if ($getsbmt && $getsbmt -> num_rows > 0) {
					while ($readsbmt = $getsbmt -> fetch_assoc()) array_push($sbmts, array(
						"ID" => base_convert(intval($readsbmt['smid'])*138, 10, 36),
						"group" => intval($readsbmt['division']),
						"name" => $readsbmt['name']
					));
				} echo '{"success": true, "info": '.json_encode($sbmts).'}';
			} else if ($cmd == "load") {
				$smid = strval(intval(base_convert(trim($attr), 36, 10)) / 138);
				$getsbmt = $db -> query("SELECT scid,p11,p12,p13,p21,p22,p23,p24,p31,p32,p40,mark,comment FROM PathwaySCon_score WHERE smid=$smid AND judge=$user");
				if ($getsbmt) {
					if ($getsbmt -> num_rows == 1) {
						$mark = $getsbmt -> fetch_array(MYSQLI_ASSOC); $export = array();
						foreach ($mark as $key => $val) {
							if (preg_match('/^(p\d{2}|mark)$/', $key)) $export[$key] = intval($val);
							else if ($key == "scid") $export['returnTo'] = vsprintf("%s%s%s%s-%s%s%s%s%s-%s%s%s%s", str_split(substr($tcl -> encode((intval($val)+138)*138, 1), 0, 13))); // 5d3
							else $export[$key] = $val;
						} echo '{"success": true, "info": '.json_encode($export).'}';
					} else echo '{"success": true, "info": null}';
				} else echo '{"success": false, "reason": [3, "Unable to load submission"]}';
			} else if ($cmd == "write") {
				$toEdit = strval(intval(base_convert(trim($attr['returnTo']), 36, 10)) / 138); unset($attr['returnTo']);
				$attr['comment'] = htmlspecialchars($attr['comment']);
				foreach ($attr as $part => $mark) $attr[$part] = $db -> real_escape_string(trim($mark));
				$success = $db -> query("INSERT INTO PathwaySCon_score (smid,judge,".implode(",", array_keys($attr)).",ip) VALUES ($toEdit,$user,'".implode("','", array_values($attr))."','$ip')");
				if ($success) {
					$newid = $db -> insert_id; $encID = vsprintf("%s%s%s%s-%s%s%s%s%s-%s%s%s%s", str_split(substr($tcl -> encode((intval($newid)+138)*138, 1), 0, 13))); // 5d3
					echo '{"success": true, "info": "'.$encID.'"}'; slog($user, "PathwaySCon", "grade", "new", $newid, "pass", $remote);
				}
				else { echo '{"success": false, "reason": [3, "Unable to set marks."]}'; slog($user, "PathwaySCon", "grade", "new", $toEdit, "fail", $remote, "InvalidQuery"); }
			} else if ($cmd == "edit") {
				$toEdit = strval(intval($tcl -> decode(str_replace("-", "", $attr['returnTo'])."5d3", 1))/138-138); unset($attr['returnTo']);
				$marks = ""; foreach ($attr as $key => $val) {
					if ($key <> "comment") $marks .= "$key='".($db -> real_escape_string(trim($val)))."',";
					else $marks .= "$key='".($db -> real_escape_string(htmlspecialchars(trim($val))))."',";
				} $success = $db -> query("UPDATE PathwaySCon_score SET $marks edit=edit+1,lasttime=current_timestamp() WHERE scid=$toEdit");
				if ($success) { echo '{"success": true}'; slog($user, "PathwaySCon", "grade", "edit", "$toEdit", "pass", $remote); }
				else { echo '{"success": false, "reason": [3, "Unable to set marks."]}'; slog($user, "PathwaySCon", "grade", "edit", $toEdit, "fail", $remote, "InvalidQuery"); }
			}
		} else if ($app == "rank") {
			/* if ($cmd == "load") {
				// $attr = $db -> real_escape_string(trim($attr));
				switch ($attr) {
					case "A": $group = "AND b.grade BETWEEN 0 AND 3"; break;
					case "B": $group = "AND b.grade BETWEEN 4 AND 6"; break;
					case "C": $group = "AND b.grade BETWEEN 7 AND 9"; break;
					default: die('{"success": false, "reason": [2, "Invalid group requested."]}'); break;
				} $gethigh = $db -> query("SELECT a.smid,CONCAT(b.namen, ' (', b.namef, ' ', b.namel, ')') AS name,CAST(AVG(c.mark) AS VARCHAR(5)) AS mark,a.rank FROM PathwaySCon_submission a INNER JOIN PathwaySCon_attendees b ON a.ptpid=b.ptpid INNER JOIN PathwaySCon_score c ON a.smid=c.smid WHERE b.ptpid > 1 $group GROUP BY a.smid ORDER BY mark DESC,a.lasttime,b.time LIMIT 5");
				if ($gethigh) {
					if ($gethigh -> num_rows) {
						$toplist = array(); while ($readhigh = $gethigh -> fetch_assoc()) array_push($toplist, array(
							"ID" => base_convert(intval($readhigh['smid'])*138, 10, 36),
							"name" => str_replace(" (", " <font>(", str_replace(")", ")</font>", $readhigh['name'])),
							"mark" => ($readhigh['mark'] + 0),
							"rank" => $readhigh['rank']
						)); echo '{"success": true, info: '.json_encode($toplist).'}';
					} else echo '{"success": false, "reason": [1, "No participant in this category."]}';
				} else echo '{"success": false, "reason": [3, "Unable to fetch list."]}';
			} */ if ($cmd == "list") {
				$gettop = $db -> query("(SELECT a.smid,CONCAT(b.namen, ' (', b.namef, ' ', b.namel, ')') AS name,1 AS division FROM PathwaySCon_submission a INNER JOIN PathwaySCon_attendees b ON a.ptpid=b.ptpid INNER JOIN PathwaySCon_score c ON a.smid=c.smid WHERE b.ptpid > 1 AND b.grade BETWEEN 0 AND 3 GROUP BY a.smid ORDER BY AVG(c.mark) DESC,a.lasttime,b.time LIMIT 5) UNION ALL (SELECT a.smid,CONCAT(b.namen, ' (', b.namef, ' ', b.namel, ')') AS name,2 AS division FROM PathwaySCon_submission a INNER JOIN PathwaySCon_attendees b ON a.ptpid=b.ptpid INNER JOIN PathwaySCon_score c ON a.smid=c.smid WHERE b.ptpid > 1 AND b.grade BETWEEN 4 AND 6 GROUP BY a.smid ORDER BY AVG(c.mark) DESC,a.lasttime,b.time LIMIT 5) UNION ALL (SELECT a.smid,CONCAT(b.namen, ' (', b.namef, ' ', b.namel, ')') AS name,3 AS division FROM PathwaySCon_submission a INNER JOIN PathwaySCon_attendees b ON a.ptpid=b.ptpid INNER JOIN PathwaySCon_score c ON a.smid=c.smid WHERE b.ptpid > 1 AND b.grade BETWEEN 7 AND 9 GROUP BY a.smid ORDER BY AVG(c.mark) DESC,a.lasttime,b.time LIMIT 5)");
				$sbmts = array(); if ($gettop && $gettop -> num_rows > 0) {
					while ($readtop = $gettop -> fetch_assoc()) array_push($sbmts, array(
						"ID" => base_convert(intval($readtop['smid'])*138, 10, 36),
						"group" => intval($readtop['division']),
						"name" => $readtop['name']
					));
				} echo '{"success": true, "info": '.json_encode($sbmts).'}';
			}
		} else if ($app == "cmtperm") {
			if ($cmd == "list") {
				$getwait = $db -> query("SELECT a.smid,b.namen,b.namef,b.namel FROM PathwaySCon_submission a INNER JOIN PathwaySCon_attendees b ON a.ptpid=b.ptpid WHERE a.viewCmt='R' ORDER BY viewCmt_req ASC");
				if ($getwait) {
					if ($getwait -> num_rows) {
						$waiter = array(); while ($readwait = $getwait -> fetch_assoc()) array_push($waiter, array(
							"ID" => base_convert(intval($readwait['smid'])*138, 10, 36),
							"nickname" => $readwait['namen'],
							"longname" => $readwait['namef']." ".$readwait['namel']
						)); echo '{"success": true, "info": '.json_encode($waiter).'}';
					} else echo '{"success": true, "info": []}';
				} else echo '{"success": false, "reason": [3, "Unable to load waiting list."]}';
			} else if ($cmd == "load") {
				$smID = $db -> real_escape_string(strval(intval(base_convert(trim($attr), 36, 10))/138));
				$getperm = $db -> query("SELECT viewCmt FROM PathwaySCon_submission WHERE smid=$smID");
				if ($getperm && $getperm -> num_rows == 1) {
					$perm = ($getperm -> fetch_array(MYSQLI_ASSOC))['viewCmt'];
					if ($perm <> "R") echo '{"success": true, "info": null}';
					else {
						$getcmt = $db -> query("SELECT substring(b.display, 4, LENGTH(b.display)) as judge,a.comment FROM PathwaySCon_score a INNER JOIN PathwaySCon_organizer b ON a.judge=b.user_id WHERE a.smid=$smID AND NOT a.comment=''");
						if ($getcmt) {
							if ($getcmt -> num_rows) {
								$comments = array(); while ($readcmt = $getcmt -> fetch_assoc()) array_push($comments, array(
									"commenter" => $readcmt['judge'],
									"message" => $readcmt['comment']
								)); echo '{"success": true, "info": '.json_encode($comments).'}';
							} else echo '{"success": true, "info": []}';
						} else echo '{"success": false, "reason": [3, "Unable to get comments."]}';
					}
				} else echo '{"success": false, "reason": [3, "Unable to get status."]}';
			} else if ($cmd == "give") {
				$user = $rmte ? trim($_REQUEST['remote']) : ($_SESSION['evt2']['user'] ?? "");
				if ($user == "") die('{"success": false, "reason": [3, "You are not signed in."]}');
				$smID = $db -> real_escape_string(strval(intval(base_convert(trim($attr), 36, 10))/138));
				$success = $db -> query("UPDATE PathwaySCon_submission SET viewCmt='Y',viewCmt_ans=CURRENT_TIMESTAMP() WHERE smid=$smID");
				if ($success) { echo '{"success": true}'; slog($user, "PathwaySCon", "comment", "allow", $smID, "pass", $remote); }
				else { echo '{"success": false, "reason": [3, "Unable to update permission."]}'; slog($user, "PathwaySCon", "comment", "allow", $smID, "fail", $remote); }
			}
		}
		$db -> close();
		# $keyid = vsprintf("%s%s%s%s-%s%s%s%s%s-%s%s%s%s", str_split(substr($tcl -> encode((intval($result['ptpid'])+138)*138, 1), 0, 13))); // 5d3
	}
?>