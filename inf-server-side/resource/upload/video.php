<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ps.php");
	$header_title = "Speech Video";
	header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");

	$remoting = (isset($_REQUEST['remote']) && preg_match('/^100\d{2}$/', $_REQUEST['remote']));
	$my_url = "?return_url=e%2FPathway-Speech-Contest2Fresource%2Fupload%2Fvideo%3Fview%3D".urlencode($_REQUEST['view']);
	if (!(isset($_SESSION['evt2']) && $_SESSION['evt2']['EventID']==2) && !$remoting) header("Location: ../../organize/$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("Location: ../../organize/new-password$my_url");
	$permitted = has_perm("grader") || has_perm("judge") || $remoting; if ($permitted) {
		$playable = false;
		if (isset($_REQUEST['view']) && !empty(trim($_REQUEST['view']))) {
			require($dirPWroot."e/resource/db_connect.php");
			$smID = $db -> real_escape_string(strval(intval(base_convert(base64_decode(ltrim(trim($_REQUEST['view']), "ID")), 36, 10)) / 138));
			$getptp = $db -> query("SELECT a.ptpid,CONCAT(b.namef,' ',b.namel,' (',b.namen,')') AS name,a.round FROM PathwaySCon_submission a INNER JOIN PathwaySCon_attendees b ON a.ptpid=b.ptpid WHERE a.smid=$smID");
			if ($getptp) {
				if ($getptp -> num_rows == 1) {
					$playable = true;
					$readptp = $getptp -> fetch_array(MYSQLI_ASSOC);
					$ptpid = intval($readptp['ptpid']);
					require_once($dirPWroot."e/Pathway-Speech-Contest/resource/php/config.php");
					$player_name = $config['nameSub']." - ".$readptp['name']; $player_thumbnail = ""; $player_secured = false;
					$videoPath = $dirPWroot."e/Pathway-Speech-Contest/resource/upload/sv-".$readptp['round']."/$ptpid.mp4";
					if (file_exists($videoPath)) {
						require_once($dirPWroot."resource/php/core/config.php");
						$video_size = size2text(filesize($videoPath));
					}
				} else $error = 'gray">ผู้สมัครไม่ได้ส่งคลิปวีดีโอ';
			} else $error = 'yellow">Unable to load video';
			$db -> close();
		} else $error = 'red">Invalid Request';
	}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/heading.php"); require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			html body main div.fill-screen {
				width: 100vw; height: 100vh;
			}
			html body main div.fill-screen .player {
				--lt-col: 248, 255, 250;
				--lt-stroke: 76, 108, 87;
				--lt-bgcol: 89, 214, 130;
				--li-dl: 19, 139, 94;
				--li-dd: 113, 252, 200;
				--li-dbs: 41, 243, 167;
				--bar-color: #28DE53;
				--track-size: 3.45px;
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				if (self == top) document.querySelector("html body main").innerHTML = '<iframe src="/error/902">902: Wrong</iframe>';
				<?php if ($playable) {
					echo "startApp('".base64_encode(base_convert($ptpid*138, 10, 36))."');";
					if (isset($video_size) && !empty($video_size)) echo 'app.ui.notify(1, [1, "Loading '.$video_size.' video..."]);';
				} ?>
			});
			function startApp(uniqueID) {
				var ID = (parseInt(atob(uniqueID), 36) / 138).toString();
				var tprevent = $(".player div.full.control"), control = $(".player div.full.control div.media");
				var loadert = $(".player div.full.video div.view div.load-text"), loaderi = $(".player div.full.video div.view div.load-img");
				var loaddot = "", loading = setInterval(function() {
					loadert.text("Loading"+loaddot);
					loaddot = (loaddot == "...") ? "" : loaddot+".";
				}, 250); loaderi.show(); tprevent.attr("style", "cursor: wait !important;"); control.css("pointer-events", "none");
				var vdo = document.querySelector(".player div.full.video div.view video"),
					realSource = "/e/Pathway-Speech-Contest/resource/upload/sv-<?=$readptp['round']??$config['round']?>/"+ID+".mp4";
				if (!isSafari) fetchVideo(realSource).then(function(blobObj) {
					var viewLink = URL.createObjectURL(blobObj);
					vdo.src = viewLink; // gen_blob();
					setTimeout(videoManifest, 250);
				}); else {
					vdo.src = realSource;
					setTimeout(videoManifest, 250);
				} function videoManifest() {
					var durationI = parseInt(vdo.duration), durationT;
					if (durationI < 3600) durationT = Math.floor(durationI/60).toString()+":"+(((durationI%60).toString().length==1)?"0":"")+(durationI%60).toString();
					else durationT = Math.floor(durationI/3600).toString()+":"+(((Math.floor(durationI%3600/60)).toString().length==1)?"0":"")+Math.floor(durationI%3600/60).toString()+":"+(((durationI%3600%60).toString().length==1)?"0":"")+(durationI%3600%60).toString();
					document.querySelector('.player span[name="duration"]').innerText = durationT;
					jsinit(where=".player", (isSafari ? viewLink : realSource), durationT);
					clearInterval(loading); loadert.text(""); loaderi.hide(); tprevent.removeAttr("style"); control.css("pointer-events", "auto");
				} // setTimeout(videoManifest, 250);
			}
			var fetchVideo = function(url) { return fetch(url, { mode: "no-cors" }).then(function(response) { return response.blob(); }); }
			const record = false;
		</script>
	</head>
	<body>
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<?php
				if (!$permitted) echo '<iframe src="/error/901">901: No Permission</iframe>';
				else if (isset($error)) echo '<div class="container"><center class="message '.$error.'</center></div>';
				else { echo '<div class="fill-screen">'; require("video_player.php"); echo '</div>'; }
			?>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>