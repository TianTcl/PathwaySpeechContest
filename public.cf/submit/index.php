<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "การส่งไฟล์";

	if (!isset($_SESSION['evt'])) header("Location: ../login");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main .table {
				background-color: var(--clr-psc-skin-shiny);
				border-radius: 5px;
				overflow: visible;
			}
			main .table th:nth-child(1) { text-align: left; }
			main .table th a i.material-icons { transform: translateY(2.5px) rotate(120deg); }
			main .table th a[disabled] i.material-icons { animation: rot_rfi 1.5s ease-in-out infinite; }
			@keyframes rot_rfi {
				from {transform: translateY(2.5px) rotate(120deg);}
				to {transform: translateY(2.5px) rotate(-240deg);}
			}
			main .table td:nth-child(2) { text-align: center; }
			main div.sl-frame {
				margin-top: 30px;
				height: 0px;
				border: 1px solid var(--clr-bs-gray); border-radius: 5px;
				display: none; overflow: hidden;
			}
			main div.sl-frame p {
				margin: 2.5px 0px 0px; padding: 1.25px 35px 1.25px 5px;
				height: 30px; line-height: 30px;
				background-color: var(--fade-white-5);
				border-bottom: 1px solid var(--clr-bs-gray);
			}
			main div.sl-frame iframe {
				width: 100%; height: 500px;
				background-color: var(--fade-white-6);
				border: none;
			}
			main div.sl-frame div {
				position: absolute; right: 11px; transform: translateY(calc(-100% - 1px));
				width: 35px; height: 35px;
				text-align: center;
				cursor: pointer; transition: background-color var(--time-tst-xfast), color var(--time-tst-fast);
			}
			main div.sl-frame div:hover { background-color: var(--fade-black-8); }
			main div.sl-frame div:active { color: var(--clr-bs-red); }
			main div.sl-frame div i.material-icons { transform: translateY(5px); pointer-events: none; }
			main p span.pid {
				float: right;
				font-family: "TianTcl-en_01", "pixelmix", "Dosis", "Permanent Marker", "Ranchers", "Mali", "Oswald";
				font-size: 1.5em;
			}
			@media only screen and (max-width: 768px) {
				main div.sl-frame div { width: 22.5px; height: 22.5px; }
				main div.sl-frame div i.material-icons { transform: none; }
			}
		</style>
		<script type="text/javascript">
			var ttl = 30, is_active = true;
			$(document).ready(function() {
				seek_param();
				setInterval(function() { ttl--; if (ttl == 0) get_submission_status(); }, 999); get_submission_status();
				$(window).on("blur", function() { is_active = false; });
				$(window).on("focus", function() { is_active = true; });
			});
			function seek_param() { if (location.hash.length > 1) {
				var page = location.hash.substr(2).trim();
				if (document.querySelector('main .fileSelect a[href="'+page+'"]') != null)
					$('main .fileSelect a[href="'+page+'"]').click();
			} }
			var sl_viewed = false;
			function viewSubmission(me) {
				loadFrame(me); return false;
				function loadFrame(me) {
					var ifr = document.querySelector("main div.sl-frame iframe");
					var viewurl = me.href;
					if (!sl_viewed) {
						var viewport = $("main div.container div.sl-frame");
						viewport.css("display", "block"); viewport.animate({height: 500}, 1000, ppa.ripple_click_program);
						sl_viewed = true;
					}
					$(ifr.parentNode).children().first().text($(me).attr("data-text"));
					ifr.src = viewurl;
					history.replaceState(null, null, location.pathname+"#"+viewurl.substr(38));
				}
			}
			function cfv() {
				if (sl_viewed) {
					var viewport = $("main div.sl-frame");
					viewport.animate({height: 0}, 1000, function() { viewport.removeAttr("style"); });
					sl_viewed = false;
					history.replaceState(null, null, location.pathname);
				}
			}
			const statusTxt = state => (state ? '<font style="color: var(--clr-bs-green)"><?=$_COOKIE['set_lang']=="th"?"ส่งแล้ว":"Sent"?></font>' : '<font style="color: var(--clr-bs-red)"><?=$_COOKIE['set_lang']=="th"?"ไม่มีไฟล์":"Empty"?></font>');
			const statusTxt2 = state => (state ? '<font style="color: var(--clr-bs-green)"><?=$_COOKIE['set_lang']=="th"?"มีผลคะแนน":"Graded"?></font>' : '<font style="color: var(--clr-bs-red)"><?=$_COOKIE['set_lang']=="th"?"ไม่มีผลคะแนน":"Not graded"?></font>');
			function get_submission_status() {
				ttl = 30;
				if (is_active) {
					var btn = $("main .table th a");
					btn.attr("disabled", ""); setTimeout(function() { btn.removeAttr("disabled"); }, 3000);
					$.get("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/submit/file?status&remote=<?php echo $_SESSION['evt']['encid']; ?>", function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							["v", "s"].forEach((file) => { document.querySelector('main .table td[name="'+file+'"]').innerHTML = statusTxt(dat.info[file]); });
							document.querySelector('main .table td[name="g"]').innerHTML = statusTxt2(dat.info["g"]);
						} else app.ui.notify(1, dat.reason);
					});
				}
			}
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>กรุณาเลือกประเภทไฟล์ที่จะส่ง</h2>
				<div class="table fileSelect"><table><thead>
					<tr>
						<th><?=$_COOKIE['set_lang']=="th"?"ไฟล์":"File"?></th>
						<th><?=$_COOKIE['set_lang']=="th"?"สถานะ":"Status"?> <a onClick="get_submission_status()" href="javascript:void(0)" data-title="Refresh status"><i class="material-icons">sync</i></a></th>
					</tr>
				</thead><tbody>
					<tr>
						<td><a href="speech-video" onClick="return viewSubmission(this)" data-text="<?=$_COOKIE['set_lang']=="th"?"วีดีโอสุนทรพจน์":"Speech Video"?>">Speech Video</a></td>
						<td name="v"></td>
					</tr>
					<tr>
						<td disabled><strike><a href="payment-slip" onClick="return viewSubmission(this)" data-text="<?=$_COOKIE['set_lang']=="th"?"สลิปการโอนเงิน":"Payment Slip"?>">Payment Slip</a></strike></td>
						<td name="s"></td>
					</tr>
					<tr>
						<td><a href="view-score" onClick="return viewSubmission(this)" data-text="<?=$_COOKIE['set_lang']=="th"?"ผลการพิจรณาคะแนน":"My Score"?>">My Score</a></td>
						<td name="g"></td>
					</tr>
				</tbody></table></div>
				<div class="sl-frame"><p class="txtoe"></p><div onClick="cfv()" class="ripple-click"><i class="material-icons">close</i></div><iframe></iframe></div>
				<p><i>You are signed in as <?=$_SESSION['evt']['namea']?></i><span class="pid">ID: <i><?=$_SESSION['evt']['myID']?></i></span></p>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>