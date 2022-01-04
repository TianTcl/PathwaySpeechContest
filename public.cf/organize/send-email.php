<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "การแจ้งเตือน";
	$header_desc = "ส่งอีเมลหาผู้สมัคร";

	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("location: new-password$my_url");
	$permitted = has_perm("lead"); if ($permitted) {
		
	}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main .form-esc select { background-color: var(--clr-psc-skin-shiny); }
			main .form-esc button { white-space: nowrap; transition: background-color var(--time-tst-xslow), box-shadow calc(var(--time-tst-xfast) * 3 / 5) ease-in-out; }
			main .form-esc .table tbody td:nth-child(1), main .form-esc .table tbody td:nth-child(3), main .form-esc .table tbody td:nth-child(4) { text-align: center; }
			main .form-esc input[type="checkbox"] {
				transform: scale(1.875);
				cursor: pointer; transition: var(--time-tst-fast);
			}
			html body main div.container div.filter {
				width: 100%; height: 100%;
				border-radius: 20px; box-shadow: 0px 2.5px 2.5px 2.5px rgb(0, 0, 0, 0.25);
				overflow: hidden;
			}
			html body main div.container div.filter *:not(i) { font-size: 15px; line-height: 20px; font-family: "THKodchasal", serif; }
			html body main div.container div.filter input {
				padding: 10px 55px 10px 15px;
				width: 100%;
			}
			html body main div.container div.filter input::-webkit-search-cancel-button {
				transform: scale(2.5) translateY(-1.25px);
				cursor: pointer;
			}
			html body main div.container div.filter i {
				position: absolute; right: 17.5px;
				width: 40px; height: 40px;
				font-size: 36px; line-height: 40px;
				color: var(--clr-gg-grey-500); text-align: center;
				pointer-events: none;
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
					"remind-12": "<?=$_COOKIE['set_lang']=="th"?"เตือนเหลือเวลา 12 วัน":"12 Days left"?>",
					"remind-8": "<?=$_COOKIE['set_lang']=="th"?"เตือนเหลือเวลา 8 วัน":"8 Days left"?>",
					"remind-5": "<?=$_COOKIE['set_lang']=="th"?"เตือนเหลือเวลา 5 วัน":"5 Days left"?>",
					"remind-0": "<?=$_COOKIE['set_lang']=="th"?"เตือนเวลาครั้งสุดท้าย":"Last call (warn)"?>"
				}, hash: 138
			};
			var sv = { all: false, req: [0, null] };
			$(document).ready(function() {
				fetch_ptp(); var opt = $('.form-esc [name="msg"]');
				Object.keys(cv.msgs).forEach((em) => opt.append('<option value="'+em+'">'+cv.msgs[em]+'</option>'));
			});
			function fetch_ptp() {
				$.post(cv.APIurl, {app: "mail", cmd: "list", attr: null}, function(res, hsc) {
					var dat = JSON.parse(res);
					if (dat.success) {
						var tbl = $(".form-esc .table tbody").html(""),
							msgno = Object.keys(cv.msgs).length;
						dat.info.forEach((ptp) => {
							let status = parseInt(ptp.mail).toString(2); status = String(status).padStart(msgno, "0").split("").reverse().map(s => '<i class="material-icons">'+(parseInt(s)?'done':'clear')+'</i>').join("");
							tbl.append('<tr><td><input type="checkbox" name="ptp" value="'+(ptp.ptpid*cv.hash).toString(36)+'"></td><td>'+ptp.namen+' ('+ptp.namef+' '+ptp.namel+')</td><td>'+cv.num2grade[ptp.grade]+'</td><td>'+status+'</td></tr>');
						}); $('.form-esc [name="ptp"]').on("change", function() { document.querySelector('.form-esc button[onClick="email()"]').disabled = false; });
					} else app.ui.notify(1, dat.reason);
				});
			}
			function ro(col) {
				w3.sortHTML("div.table table tbody", "tr", "td:nth-child("+col.toString()+")");
			}
			function fd() {
				var txt = $("html body main div.container div.filter input").val().trim();
				w3.filterHTML("div.table table tbody", "tr", txt);
			}
			const _senderMail = async function(custom, rcps) {
				$.post(cv.APIurl, {app: "mail", cmd: "send", attr: {...custom, rcp: rcps.join(",")}}, function(res, hsc) {
					var dat = JSON.parse(res);
					if (dat.success) {
						var pos = Object.keys(cv.msgs).indexOf(custom.mode),
							ptps = dat.info.map(p => (p*cv.hash).toString(36));
						ptps.forEach(ep => {
							let rcp = document.querySelector('.form-esc [name="ptp"][value="'+ep+'"]');
							$(rcp).parent().parent().children().last().children()[pos].innerText = "done";
						});
					} else app.ui.notify(1, dat.reason);
					if (++sv.req[0] == sv.req[1]) {
						sv.req = [0, null];
						if (confirm("Do you want to invert your recipient selections ?")) {
							selection.invert();
							document.querySelector('.form-esc button[onClick="email()"]').disabled = false;
						} $(".form-esc .form").removeAttr("disabled");
					}
				});
			};
			function email() {
				var custom = { mode: document.querySelector('.form-esc [name="msg"]').value.trim() }; var rcp = [];
				document.querySelectorAll('.form-esc tr:not([style="display: none;"]) [name="ptp"]:checked').forEach(ep => rcp.push(ep.value.trim()));
				if (custom.mode == "") {
					app.ui.notify(1, [1, "Please select a message"]);
					$('.form-esc [name="msg"]').focus();
					return false;
				} else if (rcp.length == 0) {
					app.ui.notify(1, [1, "Please select at least 1 recipient"]);
					return false;
				} else rcp = rcp.map(p => parseInt(p, 36)/cv.hash);
				if (confirm("Are you sure you want to notify all recipients ?")) {
					$(".form-esc .form").attr("disabled", ""); document.querySelector('.form-esc button[onClick="email()"]').disabled = true;
					sv.req[1] = Math.ceil(rcp.length/50);
					for (let set = 0; set < sv.req[1]; set++)
						// await _senderMail(custom, rcp.slice(50*set, (set==sv.req[1]-1 ? rcp.length : 50*set+50))).then(function() { continue; });
						_senderMail(custom, rcp.slice(50*set, (set==sv.req[1]-1 ? rcp.length : 50*set+50)));
				}
			}
			const selection = {
				toggle: function() {
					var ptps = document.querySelectorAll('.form-esc tr:not([style="display: none;"]) [name="ptp"]'), btn = $('.form-esc button[onClick$="toggle()"]');
					if (sv.all) {
						ptps.forEach(ep => ep.checked = false);
						btn.html('<span class="ripple-effect"></span>Select All').toggleClass("red cyan");
					} else {
						ptps.forEach(ep => ep.checked = true);
						btn.html('<span class="ripple-effect"></span>Deselect All').toggleClass("cyan red");
						document.querySelector('.form-esc button[onClick="email()"]').disabled = false;
					} sv.all = !sv.all;
				}, invert: function() {
					document.querySelectorAll('.form-esc [name="ptp"]').forEach(ep => ep.checked = !ep.checked);
				}
			};
		</script>
		<script type="text/javascript" src="/resource/js/lib/w3.min.js"></script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<?php if (!$permitted) echo '<iframe src="/error/901">901: No Permission</iframe>'; else { ?>
			<div class="container">
				<h2>Send notofication</h2>
				<div class="form-esc">
					<div class="form">
						<div class="group">
							<span>With selected: Notify</span>
							<select name="msg">
								<option value selected disabled>---<?=$_COOKIE['set_lang']=="th"?"กรุณาเลือกข้อความ":"Select a message"?>---</option>
							</select>
							<button onClick="email()" class="yellow" disabled>Send &nbsp;</button>
						</div>
						<div class="group split">
							<div class="group">
								<span>Action</span>
								<button onClick="selection.toggle()" class="cyan">Select All</button>
								<button onClick="selection.invert()" class="gray">Invert</button>
							</div>
							<div class="group">
								<span><i class="material-icons">warning</i></span>
								<button onClick="fetch_ptp()" class="red hollow">Refresh list</button>
							</div>
						</div>
					</div>
					<div class="filter" style="margin-bottom: 12.5px;"><input type="search" placeholder="Filter ... (ตัวกรอง)" onInput="fd()"/><i class="material-icons">filter_list</i></div>
					<div class="table"><table><thead><tr>
						<th>Select</th>
						<th onClick="ro(2)">Name</th>
						<th onClick="ro(3)">Grade</th>
						<th>Message sent</th>
					</tr></thead><tbody></tbody></table></div>
				</div>
			</div><?php } ?>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>