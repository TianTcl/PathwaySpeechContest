<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Scoring Criteria";
	$header_desc = "เกณฑ์การพิจารณาคะแนน";

	require_once($dirPWroot."resource/php/core/config.php");

	# $schedule = $_COOKIE['set_lang']=="th"?"เปิดรับสมัครตั้งแต่วันที่ 1 - 31 ธันวาคม 2564":"Open for registration at 1<sup>st</sup> - 31<sup>st</sup> December 2021";
	# $schedule = $_COOKIE['set_lang']=="th"?"เปิดรับสมัครตั้งแต่วันที่ 12 - 31 มีนาคม 2565":"Open for registration at 12<sup>th</sup> - 31<sup>st</sup> March 2022";
	$schedule = $_COOKIE['set_lang']=="th"?"เปิดรับสมัครตั้งแต่วันที่ 15 - 30 ตุลาคม 2565":"Open for registration at 15<sup>th</sup> - 30<sup>th</sup> October 2022";

	$filePath = "e%2FPathway-Speech-Contest%2Fresource%2Ffile%2FScoring%20Criteria%20S02%20v2.pdf";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main div.container { overflow-x: visible !important; }
			main .date.message { transition: var(--time-tst-medium); }
			main .crit {
				background-color: var(--clr-psc-skin-shiny);
				border-radius: 5px;
			}
			main .crit thead { background-color: var(--fade-black-8); }
			main .crit thead:not(:first-child) { border-top: 1.125px solid var(--clr-gg-grey-500); border-bottom: none; }
			main .crit thead:not(:last-child) { border-bottom: 1.125px solid var(--clr-gg-grey-500); }
			main .crit tbody { border: none; }
			main .crit tr > *:nth-child(1) { text-align: left; }
			main .crit tr > *:nth-child(2) { text-align: right; }
			main .crit tr td:nth-child(1) { padding-left: 25px; }
			main .crit tr td:nth-child(2) { color: var(--clr-bs-gray); }
			main a#ref_stat {
				color: #0D6EFD; font-family: "Google Sans", "IBM Plex Sans Thai", "Prompt", "Kanit", "Sarabun";
				border-radius: 5px;
			}
			main a#ref_stat:hover { text-decoration: underline; }
			main a#ref_stat:active { color: #0A58CA; }
			main .stat table { min-width: 70%; }
			main .stat th, main .stat td { padding: 0px 5px; }
			main .stat thead th:nth-child(1) { text-align: left; }
			main .stat tbody td:nth-child(n+2) { text-align: center; }
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				// setTimeout(changeTopic, 1250);
			});
			function viewFile(me) {
				go_on(); return false;
				function go_on() {
					let src = $(me).attr("href");
					app.ui.lightbox.open("top", {title: "Scoring Criteria - PDF", allowclose: true, html: '<iframe src="'+src+'" style="width:90vw;height:80vh;border:none">Loading...</iframe>'});
				}
			}
			function view_competitive_ratio() {
				$.get("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/api?app=stat&cmd=race", function(res, hsc) {
					var dat = JSON.parse(res);
					if (dat.success) {
						var TBL = '', htmlDOM = document.querySelector("main .stat table tbody");
						Object.keys(dat.info).forEach((group) => TBL += '<tr><td>'+group+'</td><td>'+dat.info[group][0]+' คน</td><td>'+dat.info[group][1]+' คน</td></tr>');
						htmlDOM.innerHTML = TBL;
						$(htmlDOM.parentNode.parentNode).toggle("fold", "linear", "slow");
						$("main a#ref_stat").attr("disabled", "");
					} else app.ui.notify(1, dat.reason);
				});
			}
			function changeTopic() {
				var pos = 16, topic = "World Environment Day", speed = 50,
					target = $("main span.evn");
				function remove() {
					if (pos > 0) {
						target.html(target.html().substring(0, --pos));
						setTimeout(remove, speed);
					} else setTimeout(typein, speed*2.5);
				}
				function typein() {
					if (pos < topic.length) {
						target.html(target.html() + topic.charAt(pos++));
						setTimeout(typein, speed);
					} else $("main .date.message").toggleClass("blue yellow");
				} remove();
			}
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>เกณฑ์ในการประกวด</h2>
				<ul>
					<li><?=$_COOKIE['set_lang']=="th"?'หัวข้อ <span class="evn">World Wildlife Conservation Day</span>':'<span class="evn">World Wildlife Conservation Day</span> Topic.'?></li>
					<li><?=$_COOKIE['set_lang']=="th"?"ความยาวในการพูด 2-3 นาที":"2-3 minutes speech."?></li>
				</ul>
				<div class="message blue date"><?=$schedule?></div>
				<h2>Scoring Criteria</h2>
				<div class="crit table"><table>
					<thead><tr><th>Content</th><th><?=$config['criteria'][10]?> pts</th></tr></thead>
					<tbody>
						<tr><td>Accuracy and Consistency</td><td><?=$config['criteria'][11]?> pts</td></tr>
						<tr><td>Form & Organization Of Speech</td><td><?=$config['criteria'][12]?> pts</td></tr>
						<tr><td>Creativity</td><td><?=$config['criteria'][13]?> pts</td></tr>
					</tbody>
					<thead><tr><th>Language Competence & Fluency</th><th><?=$config['criteria'][20]?> pts</th></tr></thead>
					<tbody>
						<tr><td>Vocabulary</td><td><?=$config['criteria'][21]?> pts</td></tr>
						<tr><td>Structure & Connectors</td><td><?=$config['criteria'][22]?> pts</td></tr>
						<tr><td>Pronunciation, Stress, Intonation, Rhythm, Pausing and Pace</td><td><?=$config['criteria'][23]?> pts</td></tr>
						<tr><td>Tone</td><td><?=$config['criteria'][24]?> pts</td></tr>
					</tbody>
					<thead><tr><th>Presentation</th><th><?=$config['criteria'][30]?> pts</th></tr></thead>
					<tbody>
						<tr><td>Communication</td><td><?=$config['criteria'][31]?> pts</td></tr>
						<tr><td>Personality</td><td><?=$config['criteria'][32]?> pts</td></tr>
					</tbody>
					<thead><tr><th>Time</th><th><?=$config['criteria'][40]?> pts</th></tr></thead>
					<tbody>
						<tr><td>Speech duration</td><td><?=$config['criteria'][41]?> pts</td></tr>
					</tbody>
					<thead><tr style="line-height: 1.5; background-color: var(--fade-black-8);"><th>Total</th><th>100 pts</th></tr></thead>
				</table></div>
				<div class="form"><div class="group split" style="margin-bottom: 0px;">
					<div class="group">
						<a id="ref_stat" role="button" href="javascript:view_competitive_ratio()"><?=$_COOKIE['set_lang']=="th"?"ดูกลุ่มการแข่งขัน":"View contest groups"?></a>
					</div>
					<div class="group">
						<a href="https://docs.google.com/viewerng/viewer?embedded=true&url=https%3A%2F%2Finf.bodin.ac.th%2F<?=$filePath?>" 
							class="cyan" role="button" onClick="return viewFile(this)" data-title="Preview File"><i class="material-icons">visibility</i></a>
						<a href="/resource/dl?furl=<?=$filePath?>" download="Scoring Criteria - Pathway Speech Contest.pdf"
							class="green" role="button"><i class="material-icons">download</i> Download</a>
						<a href="https://docs.google.com/viewerng/viewer?url=https%3A%2F%2Finf.bodin.ac.th%2F<?=$filePath?>" 
							class="blue" role="button" target="_blank" data-title="Open in new tab"><i class="material-icons">open_in_new</i></a>
					</div>
				</div></div>
				<div class="stat message black" style="display: none;"><table><thead><tr>
					<th><?=$_COOKIE['set_lang']=="th"?"กลุ่มการจัดลำดับ":"Ranking Group"?></th>
					<th><?=$_COOKIE['set_lang']=="th"?"จำนวนผู้สมัคร":"Registrants"?></th>
					<th><?=$_COOKIE['set_lang']=="th"?"ส่งผลงานแล้ว":"Submissions"?></th>
				</tr></thead><tbody></tbody></table></div>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>