<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ps.php");
	$header_title = "Custom";
	$header_desc = "All submission video list";

	if (!(isset($_SESSION['evt2']) && $_SESSION['evt2']['EventID']==2)) header("Location: ../organize/?return_url=..%2Fresource%2Fab1ec7e3-cda1-4872-9323-f5325a53f17f");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("Location: ../organize/new-password?return_url=..%2Fresource%2Fab1ec7e3-cda1-4872-9323-f5325a53f17f");
	$permitted = (has_perm("grader") || has_perm("judge", false)); if ($permitted) {
		require_once($dirPWroot."e/Pathway-Speech-Contest/resource/php/config.php");
		
	}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/heading.php"); require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main .app {
				height: calc(100vh - 167px);
				display: flex; flex-direction: row;
			}
			main .app .slot.lists { width: calc(22.5% - 5px); }
			main .app .slot.video { width: calc(77.5% - 5px); margin-left: 10px; overflow-y: auto; }
			main .app .slot {
				border-radius: 2.5px;
				box-shadow: 1.25px 2.5px var(--shd-big) var(--fade-black-7);
				overflow: hidden;
			}
			main .app .slot.lists {
				border: 0.5px solid var(--clr-gg-grey-300);
				display: flex; flex-direction: column;
				transition: var(--time-tst-fast) ease-in-out;
				overflow-x: hidden !important; overflow-y: auto;
			}
			main .app .slot.lists div.vg { margin: 5px 0px; }
			main .app .slot.lists div.vg > * { margin: 0px; padding: 0px 5px; }
			main .app .slot.lists div.vg h4 { background-color: var(--clr-pp-green-100); }
			main .app .slot.lists div.vg ul li {
				padding: 2.5px 5px 2.5px 10px;
				list-style-type: none;
				border: 1.25px solid transparent;
				color: var(--clr-bs-gray);
				cursor: pointer; transition: background-color var(--time-tst-fast), border var(--time-tst-xfast), border-color var(--time-tst-fast);
			}
			main .app .slot.lists div.vg ul li:hover { background-color: var(--fade-black-8); border-color: var(--fade-black-8); }
			main .app .slot.lists div.vg ul li:active { /* font-weight: 600; */ border-color: var(--fade-black-7); }
			main .app .slot.lists div.vg ul li[selected] {
				background-color: var(--clr-pp-green-50);
				border: 1.25px solid var(--clr-gg-green-700); border-radius: 2.5px;
				font-weight: 600;
				pointer-events: none;
			}
			main .app .slot.lists div.vg ul li font { color: var(--clr-main-black-absolute); }
			main .app .slot.video div.wrapper { width: 100%; height: 100%; }
			@media only screen and (max-width: 768px) {
				main .app { flex-direction: column; }
				main .app .slot { margin-bottom: 10px; }
			}
		</style>
		
		<style type="text/css" for="lb-submission">
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
			$(document).ready(function() {
				grader.init();
			});
			const cv = { APIurl: "/e/Pathway-Speech-Contest/resource/php/override", myID: "<?=$_SESSION['evt2']['user']?>" };
			function gradefx() {
				var sv = { inited: false, loaded: false, viewLink: {} };
				var initiate = function() {
					if (!sv.inited) {
						sv.inited = true;
						// Fetch list
						$.get(cv.APIurl+"?app=grade&cmd=list&attr=special-S1", function(res, hsc) {
							var dat = JSON.parse(res);
							if (dat.success) {
								if (dat.info.length) {
									$("main .app .slot.lists div.vg ul").html("");
									dat.info.forEach(ei => {
										let name = '<font>'+ei.name.replace(" (", '</font> (');
										$("main .app .slot.lists div.vg-"+ei.group+" ul").append('<li class="txtoe dont-ripple" onClick="grader.load(\''+ei.ID+'\')">'+name+'</li>');
									}); // ppa.ripple_click_program();
									if (navigator.userAgent.indexOf("Safari") > -1 && navigator.userAgent.indexOf("Chrome") < 0) $("main span.ripple-effect").remove();
								}
							} else app.ui.notify(1, dat.reason);
						});
					} else app.ui.notify(1, [3, "Re-initializing not allowed"]);
				};
				var getScore = function(encID) {
					$("main .app .slot.lists").attr("disabled", "");
					$.post(cv.APIurl, {app: "grade", cmd: "load", attr: encID}, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							$("main .app .slot.lists div.vg ul li[selected]").removeAttr("selected");
							$('main .app .slot.lists div.vg ul li[onClick="grader.load(\''+encID+'\')"]').attr("selected", "");
							// Show VDO
							// document.querySelector("main .app .slot.video div.wrapper iframe").src = "/e/Pathway-Speech-Contest/resource/html/judge-grade.html?of=ID"+btoa(encID);
							$("main .app .slot.video div.wrapper").load("/e/Pathway-Speech-Contest/resource/html/judge-grade.html?of=ID"+btoa(encID));
						} else app.ui.notify(1, dat.reason);
						setTimeout(function() { $("main .app .slot.lists").removeAttr("disabled"); }, 750);
					});
				};
				return {
					init: initiate,
					load: getScore
				};
			} const grader = gradefx(); delete gradefx;
		</script>
	</head>
	<body>
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<?php if (!$permitted) echo '<iframe src="/error/901">901: No Permission</iframe>'; else { ?>
			<div class="container">
				<h2><?=$_COOKIE['set_lang']=="th"?"วีดีโอทั้งหมดจากช่วงที่ 1":"All Seaon 1 Submissions"?></h2>
				<div class="app">
					<div class="slot lists slider">
						<div class="vg vg-1">
							<h4><?=$_COOKIE['set_lang']=="th"?"ประถม 3-6":"Elementary 3-6"?></h4>
							<ul><!-- List A --></ul>
						</div>
						<div class="vg vg-2">
							<h4><?=$_COOKIE['set_lang']=="th"?"มัธยม 1-3":"Middle School"?></h4>
							<ul><!-- List B --></ul>
						</div>
						<div class="vg vg-3">
							<h4><?=$_COOKIE['set_lang']=="th"?"มัธยม 4-6":"High School"?></h4>
							<ul><!-- List C --></ul>
						</div>
					</div>
					<div class="slot video">
						<div class="wrapper s-wrapper">
							
						</div>
					</div>
				</div>
			</div><?php } ?>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>