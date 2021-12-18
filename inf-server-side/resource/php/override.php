<?php
	session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");
	if (isset($_POST['app']) && isset($_POST['cmd'])) { $has_data = true; $app = $_POST['app']; $cmd = $_POST['cmd']; $attr = $_POST['attr']; }
	else if (isset($_GET['app']) && isset($_GET['cmd'])) { $has_data = true; $app = $_GET['app']; $cmd = $_GET['cmd']; $attr = $_GET['attr']; }
	else $has_data = false; if ($has_data) {
		require($dirPWroot."e/resource/db_connect.php"); require_once($dirPWroot."resource/php/core/config.php");
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
					break; default: die('{"success": false, "reason", [2, "Invalid message selected"]}'); break;
				} $mail['pos'] = pow(2, $mail['pos']);
				$list = $db -> query("SELECT ptpid,email,mail FROM PathwaySCon_attendees WHERE ptpid IN('".implode("','", $mail['recipient'])."')");
				if ($list && $list -> num_rows > 0) {
					$return = array(); while ($ptp = $list -> fetch_assoc()) {
						$bool = !(intval($ptp['mail']) & $mail['pos']); if ($bool) {
							array_push($return, intval($ptp['ptpid']));
							array_push($mail['recipients'], array("email" => $ptp['email']));
							array_push($mail['settings'], array("substitutions" => $mail['setting'], "email" => $ptp['email']));
					} }
					// Setting
					$email = curl_init(); curl_setopt($email, CURLOPT_URL, "https://api.mailersend.com/v1/email");
					curl_setopt($email, CURLOPT_RETURNTRANSFER, 1); curl_setopt($email, CURLOPT_POST, 1);
					$header = json_encode(array(
						"from" => array("email" => "TianTcl@pathwayspeechcontest.cf", "name" => "Tecillium (UFDT)"),
						"to" => $mail['recipients'], "subject" => "Pathway Speech Contest - ".$mail['topic'],
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
					} else echo '{"success": false, "reason": [3, "Unable to send Email'.(count($mail['recipient'])>1?"s":"").'."]}';
				} else echo '{"success": false, "reason": [2, "No recipient"}';
            } else if ($cmd == "list") {
				$get = $db -> query("SELECT ptpid,namef,namel,namen,grade,mail FROM PathwaySCon_attendees WHERE ptpid > 0 ORDER BY namen");
				$user = array(); while ($read = $get -> fetch_assoc()) array_push($user, $read);
				echo '{"success": true, "info": '.json_encode($user).'}';
			}
        }
		$db -> close();
	}
?>