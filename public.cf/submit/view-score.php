<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "การส่งผลงาน";
	$header_desc = "ดูผลคะแนน";

	if (!isset($_SESSION['evt'])) header("Location: ../login#next=".end(explode("/", $_SERVER['REQUEST_URI'])));
	
	require_once($dirPWroot."resource/php/core/config.php");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main * .load { padding: 12.5px 5px; }
			main .mark .table {
				background-color: var(--clr-psc-skin-shiny);
				border-radius: 5px;
			}
			main .mark .table thead { background-color: var(--fade-black-8); }
			main .mark .table thead:not(:first-child) { border-top: 1.125px solid var(--clr-gg-grey-500); border-bottom: none; }
			main .mark .table thead:not(:last-child) { border-bottom: 1.125px solid var(--clr-gg-grey-500); }
			main .mark .table tbody { border: none; }
			main .mark .table tr > *:nth-child(1) { text-align: left; }
			main .mark .table :not(:first-child) tr > *:nth-child(2) { text-align: center; }
			main .mark .table :not(:first-child) tr > *:nth-child(3) { text-align: right; }
			main .mark .table tr td:nth-child(1) {
				padding-left: 25px;
				white-space: pre-wrap;
			}
			main .mark .table tr td:nth-child(3) { color: var(--clr-bs-gray); }
			main .mark .table tr output[type="number"] { color: var(--clr-bs-blue); }
			main .mark .table thead:last-child tr output[type="number"] { color: var(--clr-bs-green); }
			main .comment ul.list { margin: 0px; padding: 0px; }
			main .comment ul.list li { list-style-type: none; }
			main .comment ul.list hr:last-child { display: none; }
		</style>
		<script type="text/javascript">
			const cv = { APIurl: "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/api", ID: "<?=$_SESSION['evt']['encid']?>" };
			$(document).ready(function() {
				setTimeout(function() {
					$.post(cv.APIurl, {app: "main", cmd: "get", attr: "score", remote: cv.ID}, function(res, hsc) {
						var dat = JSON.parse(res);
						$("main .mark .load").remove();
						if (dat.success) {
							if (dat.info.type) {
								Object.keys(dat.info.data).forEach(mark => {
									document.querySelector('main .mark .table output[name="'+mark+'"]').value = parseFloat(dat.info.data[mark]);
								}); $("main .mark .table").toggle("fold", "linear", "slow");
							} else $('<center class="message '+dat.info.data[0]+'">'+dat.info.data[1]+'</center>').insertAfter("main .mark .table");
						} else app.ui.notify(1, dat.reason);
					});
				}, 750);
			});
			var onCommentLoadComplete = function(res, hsc) {
				var dat = JSON.parse(res);
				$("main .comment .load").remove();
				if (dat.success) $("main .comment").html(dat.info.html);
				else app.ui.notify(1, dat.reason);
			};
			function loadComment() {
				$("main .comment .action").toggle("clip", function() {
					$(this).remove();
				}); $("main .comment .load").show();
				setTimeout(function() {
					$.post(cv.APIurl, {app: "main", cmd: "get", attr: "comment", remote: cv.ID}, onCommentLoadComplete);
				}, 750);
			}
			function reqComment(token) {
				$("main .comment").html('<center class="load"><img src="/resource/images/widget-load_spinner.gif" draggable="false" height="50"></center>');
				setTimeout(function() {
					$.post(cv.APIurl, {app: "main", cmd: "comment", attr: "request", remote: cv.ID}, onCommentLoadComplete);
				}, 750);
			}
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2><?=$_COOKIE['set_lang']=="th"?"คะแนนที่ฉันได้":"My Score"?></h2>
				<div class="mark">
					<center class="load"><img src="/resource/images/widget-load_spinner.gif" draggable="false" height="50"></center>
					<div class="table" style="display: none;"><table>
						<thead><tr style="line-height: 1.5; background-color: var(--fade-black-8);">
							<th><?=$_COOKIE['set_lang']=="th"?"หัวข้อ":"Critition"?></th>
							<th><?=$_COOKIE['set_lang']=="th"?"คะแนนที่ได้":"Score"?></th>
							<th><?=$_COOKIE['set_lang']=="th"?"คะแนนเต็ม":"Max points"?></th>
						</tr></thead>
						<thead><tr>
							<th>Content</th>
							<th><output type="number" name="s:1"></output></th>
							<th><?=$config['criteria'][10]?> pts</th>
						</tr></thead>
						<tbody>
							<tr>
								<td>Accuracy and Consistency</td>
								<td><output type="number" name="p11"></output></td>
								<td><?=$config['criteria'][11]?> pts</td>
							</tr><tr>
								<td>Form & Organization Of Speech</td>
								<td><output type="number" name="p12"></output></td>
								<td><?=$config['criteria'][12]?> pts</td>
							</tr><tr>
								<td>Creativity</td>
								<td><output type="number" name="p13"></output></td>
								<td><?=$config['criteria'][13]?> pts</td>
							</tr>
						</tbody>
						<thead><tr>
							<th>Language Competence & Fluency</th>
							<th><output type="number" name="s:2"></output></th>
							<th><?=$config['criteria'][20]?> pts</th>
						</tr></thead>
						<tbody>
							<tr>
								<td>Vocabulary</td>
								<td><output type="number" name="p21"></output></td>
								<td><?=$config['criteria'][21]?> pts</td>
							</tr><tr>
								<td>Structure & Connectors</td>
								<td><output type="number" name="p22"></output></td>
								<td><?=$config['criteria'][22]?> pts</td>
							</tr><tr>
								<td>Pronunciation, Stress, Intonation, Rhythm, Pausing and Pace</td>
								<td><output type="number" name="p23"></output></td>
								<td><?=$config['criteria'][23]?> pts</td>
							</tr><tr>
								<td>Tone</td>
								<td><output type="number" name="p24"></output></td>
								<td><?=$config['criteria'][24]?> pts</td>
							</tr>
						</tbody>
						<thead><tr>
							<th>Presentation</th>
							<th><output type="number" name="s:3"></output></th>
							<th><?=$config['criteria'][30]?> pts</th>
						</tr></thead>
						<tbody>
							<tr>
								<td>Communication</td>
								<td><output type="number" name="p31"></output></td>
								<td><?=$config['criteria'][31]?> pts</td>
							</tr><tr>
								<td>Personality</td>
								<td><output type="number" name="p32"></output></td>
								<td><?=$config['criteria'][32]?> pts</td>
							</tr>
						</tbody>
						<thead><tr>
							<th>Time</th>
							<th><output type="number" name="s:4"></output></th>
							<th><?=$config['criteria'][40]?> pts</th>
						</tr></thead>
						<tbody>
							<tr>
								<td>Speech duration</td>
								<td><output type="number" name="p41"></output></td>
								<td><?=$config['criteria'][41]?> pts</td>
							</tr>
						</tbody>
						<thead><tr style="line-height: 1.5; background-color: var(--fade-black-8);">
							<th>Total</th>
							<th><output type="number" name="mark"></output></th>
							<th>100 pts</th>
						</tr></thead>
					</table></div>
				</div>
				<h3><?=$_COOKIE['set_lang']=="th"?"ข้อความ":"Comments"?></h3>
				<div class="comment message black">
					<center class="action">
						<button onClick="loadComment()" class="gray"><?=$_COOKIE['set_lang']=="th"?"โหลด":"Load"?></button>
					</center>
					<center class="load" style="display: none;"><img src="/resource/images/widget-load_spinner.gif" draggable="false" height="50"></center>
				</div>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>