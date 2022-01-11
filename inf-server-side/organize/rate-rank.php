<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ps.php");
	$header_title = "จัดลำดับ";
	$header_desc = "เรียงลำดับผลงานผู้เข้าประกวดตามสาย";
	
	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("Location: new-password$my_url");
	$permitted = has_perm("judge"); if ($permitted) {
		
	}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/heading.php"); require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			
		</style>
		<style type="text/css" for="lb-submission">
			.lightbox .s-wrapper { max-height: calc(100vh - 100px); }
			.s-wrapper .load { padding: 12.5px 5px; }
			.s-wrapper .message { font-size: 18.75px; }
			.s-wrapper .video {
				margin-bottom: 10px;
				min-width: 640px; width: 100%; min-height: 360px; height: 360px;
			}
			.s-wrapper .video .wrapper { width: 100%; height: 100%; }
			.s-wrapper .video .wrapper iframe {
				width: inherit; height: inherit;
				border: none;
			}
			.s-wrapper .mark.table thead { background-color: var(--fade-black-8); }
			.s-wrapper .mark.table thead:not(:first-child) { border-top: 1.125px solid var(--clr-gg-grey-500); border-bottom: none; }
			.s-wrapper .mark.table thead:not(:last-child) { border-bottom: 1.125px solid var(--clr-gg-grey-500); }
			.s-wrapper .mark.table tbody { border: none; }
			.s-wrapper .mark.table tr > *:nth-child(1) { text-align: left; }
			.s-wrapper .mark.table :not(:first-child) tr > *:nth-child(2) { text-align: center; }
			.s-wrapper .mark.table :not(:first-child) tr > *:nth-child(3) { text-align: right; }
			.s-wrapper .mark.table tr td:nth-child(1) {
				padding-left: 25px;
				white-space: pre-wrap;
			}
			.s-wrapper .mark.table tr td:nth-child(3) { color: var(--clr-bs-gray); }
			.s-wrapper .mark.table tr output[type="number"] { color: var(--clr-bs-blue); }
			.s-wrapper .mark.table thead:last-child tr output[type="number"] { color: var(--clr-bs-green); }
			.s-wrapper .comment { margin-top: 10px; }
			.s-wrapper .comment ul { margin: 0px; padding: 0px; }
			.s-wrapper .comment ul li { list-style-type: none; }
			@media only screen and (max-width: 768px) {
				.s-wrapper .message { font-size: 12.5px; }
				.s-wrapper .video { min-width: 400; min-height: 320px; height: 320px; }
			}
		</style>
		<script type="text/javascript">
			var viewCustomSubmission = function() {
				go_on(); return false;
				function go_on() {
					var idobj = document.querySelector('main form.tmp input[name="sbmtID"]'); const rawID = idobj.value.trim();
					if (rawID.length) {
						if (parseInt(rawID) < idobj.min || parseInt(rawID) > idobj.max) app.ui.notify(1, [2, "Invalid submission ID"]);
						else {
							app.ui.lightbox.open("top", {title: "<?=$_COOKIE['set_lang']=="th"?"ดูผลงานและคะแนน":"View Submission & Score"?>", allowclose: true, html: '<div class="s-wrapper"><center class="load"><img src="/resource/images/widget-load_spinner.gif" draggable="false" height="50"></center></div>'});
							/* $(".lightbox .head label").on("click", function() {
								history.replaceState(null, null, location.pathname+location.hash.replace(/&view=score$/, ""));
							}); */
							setTimeout(function() {
								$(".lightbox .s-wrapper").load("/e/Pathway-Speech-Contest/resource/html/judge-grade.html?of=ID"+btoa((parseInt(rawID)*138).toString(36)));
								// history.replaceState(null, null, location.pathname+location.hash+"&view=score");
							}, 750);
						}
					} else app.ui.notify(1, [1, "Please enter submission ID"]);
				}
			};
		</script>
	</head>
	<body>
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<?php if (!$permitted) echo '<iframe src="/error/901">901: No Permission</iframe>'; else { ?>
			<div class="container">
				<h2><?=$_COOKIE['set_lang']=="th"?"จัดลำดับสุนทรพจน์":"Rank speech"?></h2>
				<form class="tmp form inline"><div class="group">
					<span><?=$_COOKIE['set_lang']=="th"?"เลขที่รายการรับ":"Submission ID"?></span>
					<input type="number" name="sbmtID" min="2" max="46">
					<button onClick="return viewCustomSubmission()" class="blue" data-title="View" style="display: inline-flex; align-items: center;"><i class="material-icons">toc</i></button>
				</div></form>
			</div><?php } ?>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>