<?php
	# session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	if (!isset($_REQUEST['token']) || empty(trim($_REQUEST['token']))) $error = "902";
	else if (trim($_REQUEST['token']) <> "ih43k2yt7ipn5v2vf01udp9jw") $error = "901";
	else {
		require($dirPWroot."e/resource/db_connect.php");
		$getnoti = $db -> query("SELECT nid,message FROM PathwaySCon_schedule WHERE date='".date("Y-m-d")."' AND time IS NULL ORDER BY season,date,nid");
		if (!$getnoti) $error = "905";
		else if ($getnoti -> num_rows > 0) {
			require($dirPWroot."resource/php/core/config.php");
			require($dirPWroot."resource/php/lib/LINE.php");
			$LINE -> setToken("970F4tFzYzTrBZ4ayvrhqKihmGFCrvPsM11sKrNhPPU"); // PSC
			# $LINE -> setToken("o9WmrWSWViHlMcuHUxktAKTCzPD8FKV05XqFT3BWMz6"); // Test
			$msg_date = "วันนี้วันที่ ".date("d")." ".month2text(date("m"))['th'][0]." ".date("Y").":";
			if ($getnoti -> num_rows == 1) {
				$info = $getnoti -> fetch_array(MYSQLI_ASSOC);
				$msg_main = str_replace("\\r\\n", "\r\n", $info['message']);
				$return = json_decode($LINE -> notify("$msg_date\n$msg_main"));
				$success = ($return -> status == 200 ? ",time=CURRENT_TIMESTAMP()" : "");
				$db -> query("UPDATE PathwaySCon_schedule SET result='".strval($return -> message)."'$success WHERE nid=".$info['nid']);
			} else {
				$LINE -> notify($msg_date); $saverst = array();
				while ($info = $getnoti -> fetch_assoc()) {
					$return = json_decode($LINE -> notify(str_replace("\\r\\n", "\r\n", $info['message'])));
					$success = ($return -> status == 200 ? ",time=CURRENT_TIMESTAMP()" : "");
					array_push($saverst, "UPDATE PathwaySCon_schedule SET result='".strval($return -> message)."'$success WHERE nid=".$info['nid']);
				} $db -> multi_query(implode("; ", $saverst));
			}
		} $db -> close();
	} if (isset($error)) die("Error: $error");
?>