<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Scoring Criteria";
	$header_desc = "เกณฑ์การพิจรณาคะแนน";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main div.container { overflow-x: visible !important; }
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
			main .download { display: flex; justify-content: flex-end; }
			main .download > * { border-radius: 0px; }
			main .download > *:first-child { border-radius: 0.3rem 0px 0px 0.3rem; }
			main .download > *:last-child { border-radius: 0px 0.3rem 0.3rem 0px; }
			main .download a[role="button"] i.material-icons { transform: translateY(5px); }
			@media only screen and (max-width: 768px) {
				main a[role="button"] i.material-icons { transform: translateY(2.5px); }
			}
		</style>
		<script type="text/javascript">
			function viewFile(me) {
				go_on(); return false;
				function go_on() {
					let src = $(me).attr("href");
					app.ui.lightbox.open("top", {title: "Scoring Criteria - PDF", allowclose: true, html: '<iframe src="'+src+'" style="width:90vw;height:80vh;border:none">Loading...</iframe>'});
				}
			}
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>เกณฑ์ในการประกวด</h2>
				<ul>
					<li>หัวข้อ New Year's Day</li>
					<li>ความยาวในการพูด 2-3 นาที</li>
				</ul>
				<div class="message blue">เปิดรับสมัครตั้งแต่วันนี้ - 31 ธันวาคม 2564</div>
				<h2>Scoring Criteria</h2>
				<div class="crit table"><table>
					<thead><tr><th>Content</th><th>35 pts</th></tr></thead>
					<tbody>
						<tr><td>Accuracy and Consistency</td><td>15 pts</td></tr>
						<tr><td>Form & Organization Of Speech</td><td>10 pts</td></tr>
						<tr><td>Creativity</td><td>10 pts</td></tr>
					</tbody>
					<thead><tr><th>Language Competence & Fluency</th><th>45 pts</th></tr></thead>
					<tbody>
						<tr><td>Vocabulary</td><td>5 pts</td></tr>
						<tr><td>Structure & Connectors</td><td>10 pts</td></tr>
						<tr><td>Pronunciation, Stress, Intonation, Rhythm, Pausing and Pace</td><td>20 pts</td></tr>
						<tr><td>Tone</td><td>10 pts</td></tr>
					</tbody>
					<thead><tr><th>Presentation</th><th>15 pts</th></tr></thead>
					<tbody>
						<tr><td>Communicaton</td><td>10 pts</td></tr>
						<tr><td>Personality</td><td>5 pts</td></tr>
					</tbody>
					<thead><tr><th>Time</th><th>5 pts</th></tr></thead>
					<thead><tr style="line-height: 1.5; background-color: var(--fade-black-8);"><th>Total</th><th>100 pts</th></tr></thead>
				</table></div>
				<div class="download">
					<a href="https://docs.google.com/viewerng/viewer?embedded=true&url=https%3A%2F%2Finf.bodin.ac.th%2Fe%2FPathway-Speech-Contest%2Fresource%2Ffile%2FScoring%20Criteria.pdf" 
						class="cyan" role="button" onClick="return viewFile(this)" data-title="Preview File"><i class="material-icons">visibility</i></a>
					<a href="/resource/dl?furl=e%2FPathway-Speech-Contest%2Fresource%2Ffile%2FScoring%20Criteria.pdf" download="Scoring Criteria - Pathway Speech Contest.pdf"
						class="green" role="button"><i class="material-icons">download</i> Download</a>
					<a href="https://docs.google.com/viewerng/viewer?url=https%3A%2F%2Finf.bodin.ac.th%2Fe%2FPathway-Speech-Contest%2Fresource%2Ffile%2FScoring%20Criteria.pdf" 
						class="blue" role="button" target="_blank" data-title="Open in new tab"><i class="material-icons">open_in_new</i></a>
				</div>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>