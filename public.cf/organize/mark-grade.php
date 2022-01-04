<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "ลงคะแนน";
	
	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("location: new-password$my_url");
	$permitted = has_perm("grader"); if ($permitted) {
		
	}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main .app {
				min-height: calc(100vh - 167px);
				display: grid;
				grid-template-columns: 1fr 3fr; grid-template-rows: 1fr 1.5fr;
				grid-column-gap: 12.5px; grid-row-gap: 10px;
			}
			main .app .slot.lists { grid-area: 1 / 1 / 3 / 2; }
			main .app .slot.video { grid-area: 1 / 2 / 2 / 3; }
			main .app .slot.grade { grid-area: 2 / 2 / 3 / 3; }
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
			main .app .slot.video div.wrapper iframe {
				width: 100%; height: 100%;
				border: none;
			}
			main .app .slot.grade { height: fit-content; }
			main .app .slot.grade thead { background-color: var(--fade-black-8); }
			main .app .slot.grade thead:not(:first-child) { border-top: 1.125px solid var(--clr-gg-grey-500); border-bottom: none; }
			main .app .slot.grade thead:not(:last-child) { border-bottom: 1.125px solid var(--clr-gg-grey-500); }
			main .app .slot.grade tbody { border: none; }
			main .app .slot.grade tr > *:nth-child(1) { text-align: left; }
			main .app .slot.grade :not(:first-child) tr > th:nth-child(2) {
				padding-right: 12.5px;
				text-align: right;
			}
			main .app .slot.grade :not(:first-child) tr > td:nth-child(2) { text-align: center; }
			main .app .slot.grade :not(:first-child) tr > :nth-child(3) { text-align: right; }
			main .app .slot.grade tr td:nth-child(1) { padding-left: 25px; }
			main .app .slot.grade tr td:nth-child(3) { color: var(--clr-bs-gray); }
			main .app .slot.grade input[type="number"] {
				padding: 1.25px 5px;
				font-size: 1em; text-align: right;
			}
			main .app .slot.grade input[type="number"]:invalid { color: var(--clr-bs-red); }
			main .app .slot.grade output[type="number"] { color: var(--clr-bs-blue); }
			main .app .slot.grade output[type="number"]:not([name="s:0"]) { font-weight: normal; }
			main .app .slot.grade table { border-bottom: 1.1px solid var(--clr-bs-gray-dark); }
			main .app .slot.grade div.group { padding: 0px 12.5px; }
			main .app .slot.grade div.group span { background-color: transparent; border: none; }
			@media only screen and (max-width: 768px) {
				main .app {
					/* grid-template-columns: repeat(1, 1fr); grid-template-rows: 1fr 2fr 2.5fr;
					grid-row-gap: 12.5px; */
					display: flex; flex-direction: column;
				}
				main .app .slot { margin-bottom: 10px; }
				main .app .slot.lists { /* grid-area: 1 / 1 / 2 / 2; */ max-height: 300px !important; }
				main .app .slot.video { /* grid-area: 2 / 1 / 3 / 2; */ max-height: 540px; height: 540px; }
				main .app .slot.grade { /* grid-area: 3 / 1 / 4 / 2; */ max-height: 720px; }
				main .app .slot.grade input[type="number"] { padding: 1.25px 2.5px; }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				grader.init();
			});
			function gradefx() {
				const cv = { APIurl: "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/override", myID: "<?=$_SESSION['evt2']['user']?>" };
				var sv = { inited: false, loaded: false, viewLink: {} };
				var initiate = function() {
					if (!sv.inited) {
						sv.inited = true;
						// Fetch list
						$.get(cv.APIurl+"?app=grade&cmd=list", function(res, hsc) {
							var dat = JSON.parse(res);
							if (dat.success) {
								if (dat.info.length) {
									$("main .app .slot.lists div.vg ul").html("");
									dat.info.forEach(ei => {
										let name = '<font>'+ei.name.replace(" (", '</font> (');
										$("main .app .slot.lists div.vg-"+ei.group+" ul").append('<li class="txtoe ripple-click" onClick="grader.load(\''+ei.ID+'\')">'+name+'</li>');
									}); ppa.ripple_click_program();
									if (navigator.userAgent.indexOf("Safari") > -1) $("main .form span.ripple-effect").remove();
								}
							} else app.ui.notify(1, dat.reason);
						}); // Calculation
						$(window).on("resize", function() { setTimeout(function() {
							$("main .app .slot.lists").css("max-height", ($("main .app .slot.grade form").outerHeight()*5/3+10).toString()+"px");
						}, 500); }).trigger("resize");
						// Realtime events
						$('main .app .slot.grade input[name^="p:"]').on("input", previewMark);
					} else app.ui.notify(1, [3, "Re-initializing not allowed"]);
				};
				var previewMark = function() {
					var mark = [0, 0, 0, 0];
					document.querySelectorAll('main .app .slot.grade input[name^="p:"]').forEach(em => {
						let score = em.value, sg = em.name.charAt(2);
						if (score != "") {
							score = parseInt(score);
							mark[0] += score;
							if (sg != "4") mark[sg] += score;
						}
					}); document.querySelectorAll('main .app .slot.grade output[name^="s:"]').forEach(em =>
						em.value = mark[parseInt(em.name.charAt(2))]
					); return mark[0];
				};
				var getScore = function(encID) {
					$("main .app .slot.lists").attr("disabled", "");
					$.post(cv.APIurl, {app: "grade", cmd: "load", attr: encID, remote: cv.myID}, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							$("main .app .slot.lists div.vg ul li[selected]").removeAttr("selected");
							$('main .app .slot.lists div.vg ul li[onClick="grader.load(\''+encID+'\')"]').attr("selected", "");
							if (!sv.loaded) {
								sv.loaded = true;
								$("main .app .slot.grade form").removeAttr("disabled");
							} sv.currentMark = dat.info; sv.currentID = encID;
							var actionButton = $('main .app .slot.grade button[onClick$="grader.score.save()"]');
							if (dat.info == null) actionButton.attr("class", "full-x green").children().last().text("Mark");
							else actionButton.attr("class", "full-x blue").children().last().text("Update Mark");
							loadScore();
							// Show VDO
							document.querySelector("main .app .slot.video div.wrapper iframe").src = "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/upload/video?view=ID"+btoa(encID)+"&remote="+cv.myID;
						} else app.ui.notify(1, dat.reason);
						setTimeout(function() { $("main .app .slot.lists").removeAttr("disabled"); }, 750);
					});
				};
				var loadScore = function() {
					if (sv.currentMark == null) $('main .app .slot.grade input[name^="p:"], main .app .slot.grade textarea').val("");
					else {
						document.querySelectorAll('main .app .slot.grade input[name^="p:"]').forEach(em => {
							em.value = sv.currentMark[em.name.replace(":", "")];
						}); document.querySelector('main .app .slot.grade textarea[name="comment"]').value = sv.currentMark["comment"];
					} previewMark();
				};
				var focusfield = function(which) { $('main .app .slot.grade input[name="'+which+'"]').focus(); }
				var saveScore = function() {
					go_on(); return false;
					function go_on() {
						var next = validated();
						if (next.pass) {
							var mode = (sv.currentMark == null ? "write" : "edit");
							next.info["returnTo"] = (mode == "edit" ? sv.currentMark["returnTo"] : sv.currentID);
							next.info["mark"] = previewMark();
							$.post(cv.APIurl, {app: "grade", cmd: mode, attr: next.info, remote: cv.myID }, function(res, hsc) {
								var dat = JSON.parse(res);
								if (dat.success) {
									app.ui.notify(1, [0, "Marks saved"]);
									if (mode == "write") next.info["returnTo"] = dat.info;
									if (sv.currentMark == null) $('main .app .slot.grade button[onClick$="grader.score.save()"]').attr("class", "full-x blue").children().last().text("Update Mark");
									sv.currentMark = next.info;
								} else app.ui.notify(1, dat.reason);
							});
						}
					}
				};
				var resetScore = function() { loadScore(); return false; }
				var validated = function() {
					var pass = true, info = {}, tmp;
					document.querySelectorAll('main .app .slot.grade input[name^="p:"]').forEach((ef) => {
						tmp = ef.value.trim();
						if (!tmp.length || parseInt(tmp) < ef.min || parseInt(tmp) > ef.max) {
							if (pass) { focusfield(ef.name); pass = false; app.ui.notify(1, [2, "Invalid score."]); }
						} info[ef.name.replace(":", "")] = parseInt(tmp);
					}); info["comment"] = document.querySelector("main .app .slot.grade textarea").value.trim();
					if (info.comment.length && info.comment.length > 1000) {
						if (pass) { focusfield("comment"); pass = false; app.ui.notify(1, [2, "Your comment is too long.<br>(Longer than 1,000 characters)"]); }
					} return { pass: pass, info: info };
				};
				return {
					init: initiate,
					load: getScore,
					score: {
						save: saveScore,
						reset: resetScore
					}
				};
			} const grader = gradefx(); delete gradefx;
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<?php if (!$permitted) echo '<iframe src="/error/901">901: No Permission</iframe>'; else { ?>
			<div class="container">
				<h2>ลงคะแนนวีดีโอคลิป</h2>
				<div class="app">
					<div class="slot lists slider">
						<div class="vg vg-1">
							<h4>ประถม 3-6</h4>
							<ul><!-- List A --></ul>
						</div>
						<div class="vg vg-2">
							<h4>มัธยม 1-3</h4>
							<ul><!-- List B --></ul>
						</div>
						<div class="vg vg-3">
							<h4>มัธยม 4-6</h4>
							<ul><!-- List C --></ul>
						</div>
					</div>
					<div class="slot video">
						<div class="wrapper">
							<iframe></iframe>
						</div>
					</div>
					<div class="slot grade">
						<form class="table extend form" disabled>
							<table>
								<thead><tr style="line-height: 1.5; background-color: var(--fade-black-8);"><th>หัวข้อ</th><th>คะแนนที่ได้</th><th>คะแนนที่เต็ม</th></tr></thead>
								<thead><tr>
									<th>Content</th>
									<th><output type="number" name="s:1"></th>
									<th>35 pts</th>
								</tr></thead>
								<tbody>
									<tr>
										<td>Accuracy and Consistency</td>
										<td><input type="number" name="p:11" required min="0" max="15" step="1"></td>
										<td>15 pts</td>
									</tr><tr>
										<td>Form & Organization Of Speech</td>
										<td><input type="number" name="p:12" required min="0" max="10" step="1"></td>
										<td>10 pts</td>
									</tr><tr>
										<td>Creativity</td>
										<td><input type="number" name="p:13" required min="0" max="10" step="1"></td>
										<td>10 pts</td>
									</tr>
								</tbody>
								<thead><tr>
									<th>Language Competence & Fluency</th>
									<th><output type="number" name="s:2"></th>
									<th>45 pts</th>
								</tr></thead>
								<tbody>
									<tr>
										<td>Vocabulary</td>
										<td><input type="number" name="p:21" required min="0" max="5" step="1"></td>
										<td>5 pts</td>
									</tr><tr>
										<td>Structure & Connectors</td>
										<td><input type="number" name="p:22" required min="0" max="10" step="1"></td>
										<td>10 pts</td>
									</tr><tr>
										<td>Pronunciation, Stress, Intonation, Rhythm, Pausing and Pace</td>
										<td><input type="number" name="p:23" required min="0" max="20" step="1"></td>
										<td>20 pts</td>
									</tr><tr>
										<td>Tone</td>
										<td><input type="number" name="p:24" required min="0" max="10" step="1"></td>
										<td>10 pts</td>
									</tr>
								</tbody>
								<thead><tr>
									<th>Presentation</th>
									<th><output type="number" name="s:3"></th>
									<th>15 pts</th>
								</tr></thead>
								<tbody>
									<tr>
										<td>Communicaton</td>
										<td><input type="number" name="p:31" required min="0" max="10" step="1"></td>
										<td>10 pts</td>
									</tr><tr>
										<td>Personality</td>
										<td><input type="number" name="p:32" required min="0" max="5" step="1"></td>
										<td>5 pts</td>
									</tr>
								</tbody>
								<thead><tr>
									<th>Time</th>
									<th style="padding: 2.5px 5px; text-align: center;"><input type="number" name="p:40" required min="0" max="5" step="1"></th>
									<th>5 pts</th>
								</tr></thead>
								<thead><tr style="line-height: 1.5; background-color: var(--fade-black-8);">
									<th>Total</th>
									<th><output type="number" name="s:0"></th>
									<th>100 pts</th>
								</tr></thead>
							</table>
							<div class="group extra">
								<textarea name="comment" placeholder="Type a comment ..." style="resize: none;" maxlength="1000" rows="3"></textarea>
							</div>
							<div class="group spread">
								<button type="reset" class="red full-x hollow" onClick="return grader.score.reset()">Reset</button>
								<span></span>
								<button type="submit" class="blue full-x" onClick="return grader.score.save()"><span>Mark</span></button>
							</div>
						</form>
					</div>
				</div>
			</div><?php } ?>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>