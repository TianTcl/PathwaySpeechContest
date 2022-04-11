<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ps.php");
	$header_title = "Custom";
	$header_desc = "Issue Season Certificate";
	$forceExternalBrowser = true;
	$pageName = "f614e6e9-8bc7-4fe0-af5f-2135b1e96c8d";
	
	if (isset($_REQUEST["action-token"]) && $_REQUEST["action-token"]=="d1c411cb-eda8-4fb6-8979-532fa6367bd1") {
		if (!has_perm("lead")) die(json_encode(array("success" => false, "reason" => array(array(2, "You are unauthorized")))));
		$check_action = json_decode(file_get_contents("a3d8eca4-7622-4146-912e-b6c8f5893a50.json", false)) -> r2;
		if ($check_action) die(json_encode(array("success" => false, "reason" => array(array(3, "ก็ยอกว่าออกไปแล้วไงไอสั*")))));
		/* Pass to update */
		require($dirPWroot."resource/php/core/getip.php"); require($dirPWroot."e/resource/db_connect.php");
		$sqlPrefix = "UPDATE PathwaySCon_submission SET rank_time=CURRENT_TIMESTAMP(),rank_ip='$ip'";
		// Season 2
		$ranks = array(
			"$sqlPrefix,rank='1G' WHERE smid IN(85, 92, 53)",
			"$sqlPrefix,rank='2S' WHERE smid IN(97, 123, 56)",
			"$sqlPrefix,rank='3B' WHERE smid IN(138, 101, 124)",
			"$sqlPrefix,rank='5N' WHERE NOT smid IN(85, 92, 53, 97, 123, 56, 138, 101, 124)"
		);
		// Process
		$success = $db -> multi_query(implode(";", $ranks)); $db -> close();
		if ($success) {
			file_put_contents("a3d8eca4-7622-4146-912e-b6c8f5893a50.json", json_encode(array("r2" => true), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
			die(json_encode(array("success" => true)));
		} else die(json_encode(array("success" => false, "reason" => array(array(3, "ไม่รู้ error อะไรอะ แต่ไม่สำเร็จ..รู้แค่นี้"), array(1, "หาที่บอกเอาแล้วกันนะ55")))));
	}

	if (!(isset($_SESSION['evt2']) && $_SESSION['evt2']['EventID']==2)) header("Location: ../organize/?return_url=..%2Fresource%2F$pageName");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("Location: ../organize/new-password?return_url=..%2Fresource%2F$pageName");
	$permitted = has_perm("lead"); if ($permitted) {
		require_once($dirPWroot."e/Pathway-Speech-Contest/resource/php/config.php");
		
	}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/heading.php"); require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main .message.default > :not(:last-child), section.lightbox .operation > :not(:last-child) { margin: 0px 0px 10px; }
			main .timeout { font-family: "Roboto", "Kodchasan", "Caladea", monospace; font-weight: 600; font-size: 1.75em; }
		</style>
		<link rel="stylesheet" href="/resource/css/extend/mod-directory.css">
		<script type="text/javascript">
			$(document).ready(function() {
				issue.init();
			});
			const issue = (function(cv, sv) {
				var initiate = function() {
					if (!sv.inited) {
						sv.inited = true;
						(Date.parse(cv.release) > Date.now() ? startClock() : openForm());
					}
				};
				var startClock = function() {
					var timer;
					function loadTime() {
						let time = new Date(), now;
						now = time.getFullYear()+"-"+time.getMonth()+"-"+time.getDate()+" ";
						now += (time.getHours()<10?"0":"")+time.getHours()+":";
						now += (time.getMinutes()<10?"0":"")+time.getMinutes()+":";
						now += (time.getSeconds()<10?"0":"")+time.getSeconds();
						time = (Date.parse(cv.release) - Date.now())/1000; now = "";
						if (time >= 0) {
							if (Math.floor(time/86400)) now += Math.floor(time/86400).toString() + "D&emsp;";
							now += Math.floor(time%86400/3600).toString() + ":";
							now += (Math.floor(time%86400%3600/60)<10?"0":"")+Math.floor(time%86400%3600/60).toString() + ":";
							now += (Math.floor(time%86400%3600%60)<10?"0":"")+Math.floor(time%86400%3600%60).toString();
						} else {
							now = "--D --:--:--";
							openForm();
							clearInterval(timer);
						} document.querySelector("main .timeout").innerHTML = now;
					} loadTime();
					timer = setInterval(loadTime, 998);
				};
				var openForm = function() {
					if (!sv.opened) {
						sv.opened = true;
						$("main button.aqua").removeAttr("disabled");
					}
				};
				var certificate = function() {
					if (Date.parse(cv.release) > Date.now()) {
						app.ui.notify(1, [3, "ยังไม่ถึงเวลาจ้า อิเวน"]);
						lockStartBtn();
					} else {
						app.ui.lightbox.open("mid", {title: "ยืนยันการกระทำการ", allowclose: false,
							html: '<div class="operation"></div>'
						}); setTimeout(function() {
							// Check availability
							$.getJSON("/e/Pathway-Speech-Contest/resource/a3d8eca4-7622-4146-912e-b6c8f5893a50.json?t="+Date.now(), function(dat) {
								$("section.lightbox .operation").html(dat[cv.roundpos] ? '<h2 class="message red">Certificates for this season has been issued.</h2><button class="gray full-x" onClick="issue.complete()">ปิด</button>' : '<div style="display: flex; justify-content: space-evenly; margin: 10px 2.5px 5px;"><button class="red hollow" onClick="issue.proceed(false)">Abort</button><button class="green" onClick="issue.proceed(true)">Continue</button></div>');
								if (!dat[cv.roundpos]) {
									sv.allowNext = true;
									lockStartBtn();
								}
							});
						}, 25);
					}
				};
				var closeUp = function() {
					app.ui.lightbox.close();
					lockStartBtn();
				};
				var lockStartBtn = function() { $("main button.aqua").attr("disabled", ""); };
				var processAction = function(option) {
					if (!option) {
						app.ui.lightbox.close();
						setTimeout(function() { $("main button.aqua").removeAttr("disabled"); }, 250)
					} else if (typeof sv.allowNext !== "boolean" || !sv.allowNext) {
						app.ui.notify(1, [3, "ก็ error อะ.. จะทำไม"]);
						closeUp();
						if (typeof sv.allowNext === "boolean") sv.allowNext = undefined;
					} else {
						$("section.lightbox .operation").html('<h2 style="font-weight: normal;">Processing request...</h2><center><img height="50" src="/resource/images/widget-load_spinner.gif" alt="Loading..." draggable="false"></center><p align="center">Attempting to issue certificates...</p>');
						$.post(cv.APIurl, { "action-token": "d1c411cb-eda8-4fb6-8979-532fa6367bd1" }, function(res, hsc) {
							var dat = JSON.parse(res);
							setTimeout(function() {
								if (dat.success) {
									sv.allowNext = undefined;
									$("section.lightbox .operation").html('<h2 class="message purple" align="center">กระทำการสำเร็จแล้วโว่ยย</h2><button class="blue full-x" onClick="issue.complete()">รับทราบ (ปิด)</button>');
								} else {
									dat.reason.forEach(em => app.ui.notify(1, em));
									$("section.lightbox .operation").html('<h2 class="message red" align="center">Failed.</h2><button class="gray full-x" onClick="issue.proceed(false)">ปิด</button><button class="black full-x" onClick="issue.proceed(true)">ลองใหม่ดิ๊</button>');
								}
							}, 1750);
						});
					}
				};
				var closeFul = function() {
					closeUp(); setTimeout(function() {
						$("main div.container").append('<center class="message lime" style="display: none;"><h2 style="margin: 0px 0px 10px;">Success</h2><hr><p style="margin: 0px;">ออก Certificate สำเร็จเรียบร้อยแล้วจ้าา</p></center>');
						$("main .message.lime").toggle("blind", "linear", "slow");
					}, 500)
				};
				return {
					init: initiate,
					cert: certificate,
					// fail: closeUp,
					proceed: processAction,
					complete: closeFul
				};
			}({ APIurl: "/e/Pathway-Speech-Contest/resource/<?=$pageName?>", release: "2022-04-17 00:00:00", roundpos: "r2" },
			{ inited: false, opened: false }));
		</script>
	</head>
	<body>
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<?php if (!$permitted) echo '<iframe src="/error/901">901: No Permission</iframe>'; else { ?>
			<div class="container">
				<h2>Issue Season Certificate</h2>
				<p class="message orange">การกระทำนี้สำคัญมากกกก(ถึงฉิบหาย) ห้ามทำพลาดโดยเด็ดขาด</p>
				<div class="message default">
					<p class="message pink">กดได้ครั้งเดียวเท่านั้น</p>
					<center class="message cyan timeout">--D --:--:--</center>
					<button class="aqua full-x" onClick="issue.cert()" disabled>ออกประกาศณียบัตรรร!!!</button>
				</div>
			</div><?php } ?>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>