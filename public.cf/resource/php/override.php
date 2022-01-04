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
							if (preg_match('/^p\d{2}$/', $key)) $export[$key] = intval($val);
							else if ($key == "scid") $export['returnTo'] = vsprintf("%s%s%s%s-%s%s%s%s%s-%s%s%s%s", str_split(substr($tcl -> encode((intval($val)+138)*138, 1), 0, 13))); // 5d3
							else $export[$key] = $val;
						} echo '{"success": true, "info": '.json_encode($export).'}';
					} else echo '{"success": true, "info": null}';
				} else echo '{"success": false, "reason": [3, "Unable to load submission"]}';
			} else if ($cmd == "write") {
				$toEdit = strval(intval(base_convert(trim($attr['returnTo']), 36, 10)) / 138); unset($attr['returnTo']);
				$success = $db -> query("INSERT INTO PathwaySCon_score (smid,judge,".implode(",", array_keys($attr)).",ip) VALUES ($toEdit,$user,'".implode("','", array_values($attr))."','$ip')");
				if ($success) {
					$newid = $db -> insert_id; $encID = vsprintf("%s%s%s%s-%s%s%s%s%s-%s%s%s%s", str_split(substr($tcl -> encode((intval($newid)+138)*138, 1), 0, 13))); // 5d3
					echo '{"success": true, "info": "'.$encID.'"}'; slog($user, "PathwaySCon", "grade", "new", $newid, "pass", $remote);
				}
				else { echo '{"success": false, "reason": [3, "Unable to set marks."]}'; slog($user, "PathwaySCon", "grade", "new", $toEdit, "fail", $remote, "InvalidQuery"); }
			} else if ($cmd == "edit") {
				$toEdit = strval(intval($tcl -> decode(str_replace("-", "", $attr['returnTo'])."5d3"))/138-138); unset($attr['returnTo']);
				$marks = ""; foreach ($attr as $key => $val) {
					$marks .= "$key='".($db -> real_escape_string($trim($val)))."',";
				} $success = $db -> query("UPDATE PathwaySCon_score SET $marks edit=edit+1,lasttime=current_timestamp() WHERE scid=$toEdit");
				if ($success) { echo '{"success": true}'; slog($user, "PathwaySCon", "grade", "edit", "$toEdit", "pass", $remote); }
				else { echo '{"success": false, "reason": [3, "Unable to set marks."]}'; slog($user, "PathwaySCon", "grade", "edit", $toEdit, "fail", $remote, "InvalidQuery"); }
			}
		}
		$db -> close(); /*
		$keyid = vsprintf("%s%s%s%s-%s%s%s%s%s-%s%s%s%s", str_split(substr($tcl -> encode((intval($result['ptpid'])+138)*138, 1), 0, 13))); // 5d3

		$keyid 
		*/
	}
?>