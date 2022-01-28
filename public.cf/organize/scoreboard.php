<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Scoreboard";
	$header_desc = "ตารางคะแนนเฉลี่ยทั้งหมด";
	
	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("Location: new-password$my_url");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			
		</style>
		<style type="text/css" for="lb-scoreboard">
			.scoreboard .load { padding: 12.5px 5px; }
			.scoreboard .message { font-size: 18.75px; }
			.scoreboard table > thead:nth-of-type(n+3) { border-top: 1.125px solid var(--clr-gg-grey-500); }
			.scoreboard table > thead:first-child tr * {
				padding: 5px 2.5px;
				text-align: left; /* Not working */ font-weight: normal;
			}
			.scoreboard table > thead:first-child tr * > span {
				writing-mode: vertical-rl;
				transform: rotate(180deg);
			}
			.scoreboard table > thead:first-child tr :nth-child(1), .scoreboard table > thead:first-child tr :nth-child(2), .scoreboard table > thead:first-child tr :last-child {
				padding: 2.5px 5px;
				text-align: center;
			}
			.scoreboard table > thead tr td { text-align: center; font-weight: bold; }
			.scoreboard table > :nth-child(2n+3) tr > *:last-child { font-weight: 600; }
			.scoreboard table > tbody tr td:nth-child(2) { color: var(--clr-bs-gray); font-size: 0.75em; }
			.scoreboard table > tbody tr td:nth-child(2) font { color: var(--clr-main-black-absolute); font-size: 1em; }
			.scoreboard table > tbody tr td:not(:nth-child(2)) { text-align: center; font-family: 'IBM Plex Sans Thai', monospace; font-size: 0.75em; }
		</style>
		<script type="text/javascript">
			const cv = { APIurl: "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/api" };
			$(document).ready(function() {
				loadSB();
			});
			function loadSB() {
				$.post(cv.APIurl, {app: "stat", cmd: "sboard"}, function(res, hsc) {
					var dat = JSON.parse(res);
					if (dat.success) {
						var table = 1;
						dat.info.forEach(et => {
							var target = document.querySelector("main .stat .g"+(table++).toString()), rank = 1;
							if (et.length) {
								target.innerHTML = "";
								et.forEach(er => {
									let name = '<font>'+er.name.replace(" (", "</font> (");
									target.innerHTML += '<tr><td>'+String(rank++).padStart(3, "0")+'</td><td>'+name+'</td><td>'+scoreRender(er.p11)+'</td><td>'+scoreRender(er.p12)+'</td><td>'+scoreRender(er.p13)+'</td><td>'+scoreRender(er.p21)+'</td><td>'+scoreRender(er.p22)+'</td><td>'+scoreRender(er.p23)+'</td><td>'+scoreRender(er.p24)+'</td><td>'+scoreRender(er.p31)+'</td><td>'+scoreRender(er.p32)+'</td><td>'+scoreRender(er.p41)+'</td><td>'+scoreRender(er.mark)+'</td></tr>';
								});
							} else target.innerHTML = '<tr><td><center class="message yellow"><?=$_COOKIE['set_lang']=="th"?"ไม่มีรายการคะแนนในหมวดหมู่นี้":"No score list in this category"?></center></td></tr>';
						});
					} else app.ui.notify(1, dat.reason);
				});
			}
			function scoreRender(score) {
				var result = (Math.round(parseFloat(score) * 100) / 100).toString();
				if (!(result.split(".")[0].length-1)) result = "0" + result;
				if (!result.includes(".")) return result += ".00";
				return result + "0".repeat(2 - result.split(".")[1].length);
			}
			function ro(col) {
				w3.sortHTML("div.table table tbody:nth-of-type(1)", "tr", "td:nth-child("+col.toString()+")");
				w3.sortHTML("div.table table tbody:nth-of-type(2)", "tr", "td:nth-child("+col.toString()+")");
				w3.sortHTML("div.table table tbody:nth-of-type(3)", "tr", "td:nth-child("+col.toString()+")");
			}
		</script>
		<script type="text/javascript" src="/resource/js/lib/w3.min.js"></script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2><?=$_COOKIE['set_lang']=="th"?"กระดานคะแนน":"Scoreboard"?></h2>
				<div class="stat table scoreboard"><table><thead>
					<th onClick="ro(1)"><?=$_COOKIE['set_lang']=="th"?"ลำดับ":"Rank"?></th>
					<th onClick="ro(2)"><?=$_COOKIE['set_lang']=="th"?"ชื่อเต็ม":"Fullname"?></th>
					<th onClick="ro(3)"><span>1.1) Accuracy</span></th>
					<th onClick="ro(4)"><span>1.2) Organization</span></th>
					<th onClick="ro(5)"><span>1.3) Creativity</span></th>
					<th onClick="ro(6)"><span>2.1) Vocabulary</span></th>
					<th onClick="ro(7)"><span>2.2) Structure, Connectors</span></th>
					<th onClick="ro(8)"><span>2.3) Stress, Rhythm</th>
					<th onClick="ro(9)"><span>2.4) Tone</span></th>
					<th onClick="ro(10)"><span>3.1) Communication</span></th>
					<th onClick="ro(11)"><span>3.2) Personality</span></th>
					<th onClick="ro(12)"><span>4) Duration</span></th>
					<th onClick="ro(13)"><?=$_COOKIE['set_lang']=="th"?"คะแนนรวม":"Total"?></th>
				</thead><thead><tr><td colspan="13"><?=$_COOKIE['set_lang']=="th"?"ประถม 3-6":"Elementary 3-6"?></td></tr></thead><tbody class="g1">

				</tbody><thead><tr><td colspan="13"><?=$_COOKIE['set_lang']=="th"?"มัธยม 1-3":"Middle School"?></td></tr></thead><tbody class="g2">
					
				</tbody><thead><tr><td colspan="13"><?=$_COOKIE['set_lang']=="th"?"มัธยม 4-6":"High School"?></td></tr></thead><tbody class="g3">
					
				</tbody></table></div>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>