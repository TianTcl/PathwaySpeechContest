<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Statics";
	$header_desc = "ติดตามยอดและสถิติการประกวด";

	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("location: new-password$my_url");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			html body main { background-image: conic-gradient( from 180deg at 50% 50%, rgba(36, 209, 101, .09) 0deg, rgba(226, 214, 54, .09) 55.3deg, rgba(254, 108, 91, .09) 120deg, rgba(204, 60, 203, .09) 165deg, rgba(159, 51, 253, .09) 213.75deg, rgba(24, 117, 243, .09) 288.12deg, rgba(22, 119, 240, .09) 320deg, rgba(36, 209, 101, .09) 360deg ); }
			main div.container h2 button {
				float: right;
				height: 44px; line-height: 44px;
			}
			main div.container button i.material-icons { transform: rotate(120deg); }
			main div.container button[disabled] i.material-icons { animation: rot_rfi 1.5s ease-in-out infinite; }
			@keyframes rot_rfi {
				from { transform: rotate(120deg); }
				to { transform: rotate(-240deg); }
			}
			main .stat > * { margin-bottom: 17.5px; }
			main .stat .grpdiv { display: flex; justify-content: space-evenly; }
			main .stat .card {
				padding: 10px;
				border-radius: 7.5px;
				display: flex; flex-direction: column;
			}
			main .stat .card h4 { margin: 5px 20px; }
			main .stat .card output {
				text-align: center; font-size: 1.5rem;
				display: block;
			}
			@media only screen and (max-width: 768px) {
				main .stat .grpdiv { justify-content: flex-start; flex-direction: column; }
				main .stat .grpdiv .card:not(:last-child) { margin-bottom: 17.5px; }
			}
		</style>
		<script type="text/javascript">
			var ttl = 30, is_active = true;
			$(document).ready(function() {
				$(".stat .card").addClass("glass");
				setInterval(function() { ttl--; if (ttl == 0) get_lastest_statics(); }, 999); get_lastest_statics();
				$(window).on("blur", function() { is_active = false; });
				$(window).on("focus", function() { is_active = true; });
			});
			function get_lastest_statics() {
				ttl = 30;
				if (is_active) {
					var btn = $("main h2 button");
					btn.attr("disabled", ""); setTimeout(function() { btn.removeAttr("disabled"); }, 3000);
					$.get("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/api?app=stat&cmd=fetch", function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							Object.keys(dat.info).forEach((ei) => { document.querySelector('main .stat .card output[name="'+ei+'"]').value = dat.info[ei]; });
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
				<h2>Event Statics<button onClick="get_lastest_statics()" class="green" data-title="refresh"><i class="material-icons">sync</i></button></h2>
				<div class="stat">
					<div class="grpdiv">
						<div class="card">
							<h4>ผู้สมัครทั้งหมด</h4>
							<output name="ptp-all"></output>
						</div>
						<div class="card">
							<h4>ผู้สมัครที่ส่งคลิป</h4>
							<output name="ptp-att"></output>
						</div>
					</div>
					<div class="grpdiv">
						<div class="card">
							<h4>คลิปที่ตรวจแล้ว</h4>
							<output name="vdo-mark"></output>
						</div>
						<div class="card">
							<h4>คลิปที่ยังไม่ตรวจ</h4>
							<output name="vdo-clip"></output>
						</div>
					</div>
					<div class="grpdiv">
						<div class="card">
							<h4>การดูเว็บทั้งหมด</h4>
							<output name="pageview"></output>
						</div>
					</div>
					<div class="grpdiv">
						<div class="card">
							<h4>จำนวนรายการบริจาค</h4>
							<output name="transac"></output>
						</div>
					</div>
				</div>
				<p>View full report on <a href="https://inf.bodin.ac.th/go?url=https%3A%2F%2Fdatastudio.google.com%2Freporting%2F3be6fa8d-bb84-47d7-9075-614dfa12c915" target="_blank">Google Datastudio</a> (from Google Analytics)</p>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>