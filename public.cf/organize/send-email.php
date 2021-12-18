<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "การแจ้งเตือน";
	$header_desc = "ส่งอีเมลหาผู้สมัคร";

	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("location: new-password$my_url");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main .form-esc select { background-color: var(--clr-psc-skin-shiny); }
			main .form-esc button { white-space: nowrap; transition: var(--time-tst-xslow); }
			main .form-esc .table tbody td:nth-child(1), main .form-esc .table tbody td:nth-child(3), main .form-esc .table tbody td:nth-child(4) { text-align: center; }
			main .form-esc input[type="checkbox"] {
				transform: scale(1.875);
				cursor: pointer; transition: var(--time-tst-fast);
			}
			@media only screen and (max-width: 768px) {
				main .form-esc input[type="checkbox"] { transform: scale(1.5); }
			}
		</style>
		<script type="text/javascript">
			const cv = {
				APIurl: "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/override",
				num2grade: ["ป.3", "ป.4", "ป.5", "ป.6", "ม.1", "ม.2", "ม.3", "ม.4", "ม.5", "ม.6"],
				msgs: {
					"remind-12": "เตือนเหลือเวลา 12 วัน",
					"remind-8": "เตือนเหลือเวลา 8 วัน",
					"remind-5": "เตือนเหลือเวลา 5 วัน"
				}, hash: 138
			};
			var sv = { all: false };
			$(document).ready(function() {
				fetch_ptp(); var opt = $('.form-esc [name="msg"]');
				Object.keys(cv.msgs).forEach((em) => opt.append('<option value="'+em+'">'+cv.msgs[em]+'</option>'));
			});
			function fetch_ptp() {
				$.post(cv.APIurl, {app: "mail", cmd: "list", attr: null}, function(res, hsc) {
					var dat = JSON.parse(res);
					if (dat.success) {
						var tbl = $(".form-esc .table tbody").html("");
						dat.info.forEach((ptp) => {
							let status = ptp.mail.toString(2); status = String(status).padStart(Object.keys(cv.msgs).length, "0").split("").reverse().map(s => '<i class="material-icons">'+(parseInt(s)?'done':'clear')+'</i>').join("");
							tbl.append('<tr><td><input type="checkbox" name="ptp" value="'+(ptp.ptpid*cv.hash).toString(36)+'"></td><td>'+ptp.namen+' ('+ptp.namef+' '+ptp.namel+')</td><td>'+cv.num2grade[ptp.grade]+'</td><td>'+status+'</td></tr>');
						}); $('.form-esc [name="ptp"]').on("change", function() { document.querySelector('.form-esc button[onClick="email()"]').disabled = false; });
					} else app.ui.notify(1, dat.reason);
				});
			}
			function ro(col) {
				w3.sortHTML("div.table table tbody", "tr", "td:nth-child("+col.toString()+")");
			}
			function email() {
				var custom = { mode: document.querySelector('.form-esc [name="msg"]').value.trim(), rcp: [] };
				document.querySelectorAll('.form-esc [name="ptp"]:checked').forEach(ep => custom.rcp.push(ep.value.trim()));
				if (custom.mode == "") {
					app.ui.notify(1, [1, "Please select a message"]);
					$('.form-esc [name="msg"]').focus();
					return false;
				} else if (custom.rcp.length == 0) {
					app.ui.notify(1, [1, "Please select at least 1 recipient"]);
					return false;
				} else custom.rcp = custom.rcp.map(p => parseInt(p, 36)/cv.hash).join(",");
				if (confirm("Are you sure you want to notify all recipients ?")) {
					$(".form-esc .form").attr("disabled", ""); document.querySelector('.form-esc button[onClick="email()"]').disabled = true;
					$.post(cv.APIurl, {app: "mail", cmd: "send", attr: custom}, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							var pos = Object.keys(cv.msgs).indexOf(custom.mode),
								ptps = dat.info.map(p => (p*cv.hash).toString(36));
							ptps.forEach(ep => {
								let rcp = document.querySelector('.form-esc [name="ptp"][value="'+ep+'"]');
								$(rcp).parent().parent().children().last().children()[pos].innerText = "done";
							}); if (confirm("Do you want to invert your recipient selections ?")) {
								document.querySelectorAll('.form-esc [name="ptp"]').forEach(ep => ep.checked = !ep.checked);
								document.querySelector('.form-esc button[onClick="email()"]').disabled = false;
							}
						} else app.ui.notify(1, dat.reason);
						$(".form-esc .form").removeAttr("disabled");
					});
				}
			}
			function toggle() {
				var ptps = $('.form-esc [name="ptp"]'), btn = $('.form-esc button[onClick="toggle()"]');
				if (sv.all) {
					ptps.removeAttr("checked");
					btn.html('<span class="ripple-effect"></span>Select All').toggleClass("red cyan");
				} else {
					ptps.attr("checked", "");
					btn.html('<span class="ripple-effect"></span>Deselect All').toggleClass("cyan red");
					document.querySelector('.form-esc button[onClick="email()"]').disabled = false;
				} sv.all = !sv.all;
			}
		</script>
		<script type="text/javascript" src="/resource/js/lib/w3.min.js"></script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>Send notofication</h2>
				<div class="form-esc">
					<div class="form">
						<div class="group">
							<span>With selected: Notify</span>
							<select name="msg">
								<option value selected disabled>---กรุณาเลือกข้อความ---</option>
							</select>
							<button onClick="email()" class="yellow" disabled>Send &nbsp;</button>
						</div>
						<div class="group">
							<span>Action</span>
							<button onClick="toggle()" class="cyan">Select All</button>
						</div>
					</div>
					<div class="table"><table><thead><tr>
						<th>Select</th>
						<th onClick="ro(2)">Name</th>
						<th onClick="ro(3)">Grade</th>
						<th>Message sent</th>
					</tr></thead><tbody></tbody></table></div>
				</div>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>