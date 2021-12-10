<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "I'm attending!";
	$header_desc = "ลงทะเบียนเข้าร่วมการแข่งขัน";

	if (isset($_SESSION['evt'])) header("Location: submit/");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main .message { font-family: "Sarabun", sans-serif; }
			main .message > * { margin: 0px 0px 10px; }
			main .form, main .form form { margin-bottom: 10px; }
			main .group-inline { display: flex; justify-content: space-between; }
			main .group-inline .group { width: 48.75%; }
			main .form { --mvlbt: -5px; }
			main .form input[type="text"], main .form input[type="tel"], main .form input[type="email"], main .form select {
				margin: 7.5px 2.5px; padding: 2.5px 5px;
				width: calc(100% - 17px); height: 30px;
				font-size: 20px; line-height: 30px; font-family: "Sarabun", serif;
				border: 1px solid var(--clr-bs-gray-dark); border-radius: 3px;
				background-color: var(--clr-psc-skin-shiny);
				transition: var(--time-tst-fast);
			}
			main .form select { width: calc(100% - 5px); height: 35px; line-height: 35px; }
			main .form input + label {
				padding: 2.5px 5px;
				position: absolute; left: 15px; transform: translate(calc(var(--mvlbt) + 12.5px), 10px);
				height: var(--txt-ipt-h);
				font-size: 18.75px; font-family: 'Sarabun', sans-serif; color: gray; line-height: var(--txt-ipt-h);
				transition: calc(var(--time-tst-xfast)/1.5); pointer-events: none;
			}
			main .group-inline .group:nth-child(2) input + label { left: calc(50% + 25px); }
			main .form input[required] + label { color: var(--clr-bs-red); }
			main .form input:focus + label, main .form input[filled="true"] + label {
				transform: translate(var(--mvlbt), -7px) scale(0.75);
				color: var(--clr-main-black-absolute);
				background-image: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,0) 42.4%, var(--clr-psc-skin-shiny) 42.5%, var(--clr-psc-skin-shiny) 55%, rgba(255,255,255,0) 55.1%);
			}
			main .form span {
				padding: 0px 5px;
				transform: translateY(-5px);
				font-size: 0.75rem; line-height: 17px; color: var(--clr-bs-gray-dark);
				display: flex;
			}
			main .form span i.material-icons {
				margin-right: 2.5px;
				width: 17px; height: 17px;
				font-size: 16px;
				display: block;
			}
			main .form p { margin: 5px 0px 0px; }
			@media only screen and (max-width: 768px) {
				main .form input + label { transform: translate(calc(var(--mvlbt) + 7.5px), 12.5px); }
    			main .form input:focus + label, main .form input[filled="true"] + label { transform: translate(calc(var(--mvlbt) - 7.5px), -4px) scale(0.75); }
				main .group-inline .group:nth-child(2) input + label { left: calc(50% + 15px); }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				<?php if (isset($notify)) echo "app.ui.notify(1, [$notify]);"; ?>
				$('main .form input[type="text"], main .form input[type="tel"], main .form input[type="email"]').on("input change", validate_field);
				validate_field(); // app.io.confirm("leave");
				if (location.hash == "#start") setTimeout(reg.start, 1250);
			});
			function validate_field() {
				document.querySelectorAll('main .form input[type="text"], main .form input[type="tel"], main .form input[type="email"]').forEach((eio) => {
					var ei = $(eio);
					ei.attr("filled", (ei.val()==""?"false":"true"));
				});
			}
			function chooseSchool() { school.choose("กรุณาพิมพ์โรงเรียนที่ท่านศึกษาอยู่", 'form input[name="school"]', 'form input[name="school"] + input'); }
			function regisfx() {
				const APIurl = "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/api"; var sv = null;
				var focusfield = function(which) { $('.form [name="'+which+'"]').focus(); }
				var validate = function() {
					var pass = true, info = {};
					document.querySelectorAll(".form [name]").forEach((ef) => { info[ef.name] = ef.value.trim(); });
					if (Object.values(info).includes("")) {
						pass = false; focusfield(Object.keys(info).find(key => info[key] === ""));
						app.ui.notify(1, [1, "Please fill in all the fields"]);
					} else {
						if (!/^((เด็ก(ชาย|หญิง)|ด.(ช|ญ).|นาย|นางสาว)[ก-๛]{1,44}|[A-Z][a-z]{0,49})$/.test(info.namef)) {
							if (pass) { focusfield("namef"); pass = false; }
							app.ui.notify(1, [2, "Invalid First name format"]);
						} if (!/^([ก-๛]{1,50}|[A-Z][a-z]{0,49})$/.test(info.namel)) {
							if (pass) { focusfield("namel"); pass = false; }
							app.ui.notify(1, [2, "Invalid Last name format"]);
						} if (!/^([ก-๛]{1,30}|[A-Z][a-z]{0,29})$/.test(info.namen)) {
							if (pass) { focusfield("namen"); pass = false; }
							app.ui.notify(1, [2, "Invalid Nick name format"]);
						} if (!/^\d$/.test(info.grade)) {
							if (pass) { focusfield("grade"); pass = false; }
							app.ui.notify(1, [2, "Invalid grade selected"]);
						} if (!/^[ก-๛ \.()\-]{1,200}$/.test(info.school)) {
							if (pass) { focusfield("school"); pass = false; }
							app.ui.notify(1, [2, "Invalid school name"]);
						} if (!/^0[1-9]\d{8}$/.test(info.phone)) {
							if (pass) { focusfield("phone"); pass = false; }
							app.ui.notify(1, [2, "Invalid phone number format"]);
						} if (!/^[0-9A-Za-z\._]{3,50}$/.test(info.line)) {
							if (pass) { focusfield("line"); pass = false; }
							app.ui.notify(1, [2, "Invalid line ID format"]);
						} if (!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@([a-zA-Z0-9\-_]+\.)+[a-zA-Z]{2,13}$/.test(info.email) || info.email.length > 255) {
							if (pass) { focusfield("email"); pass = false; }
							app.ui.notify(1, [2, "Invalid E-mail address format"]);
						}
					} if (pass) {
						// Check if mail is registered
						document.querySelector('.form button[type="submit"]').disabled = true;
						$.post(APIurl, {app: "register", cmd: "chkEml", attr: info.email}, function(res, hsc) {
							var dat = JSON.parse(res);
							if (dat.success) {
								if (dat.isTaken) {
									focusfield("email");
									app.ui.notify(1, [1, "This E-mail address has already been used"]);
								} else { // No problem, then register
									sv = info;
									registrationConfirm();
								}
							} else app.ui.notify(1, dat.reason);
							document.querySelector('.form button[type="submit"]').disabled = false;
						});
					}
				};
				var registrationConfirm = function(cnf = null) {
					// <div class="table"><table><tbody><tr><td></td><td></td></tr><tr><td></td><td></td></tr></tbody></table></div>
					if (cnf == null) {
						let grade = $('.form select[name="grade"] option:checked').text().split(" ");
						app.ui.lightbox.open("top", {title: "โปรดตรวจสอบข้อมูล", allowclose: true, autoclose: 90000, html: '<style type="text/css">.cnf-wrapper { font-size: 1.25rem; } .cnf-wrapper > * { margin: 0px 0px 10px; } .cnf-wrapper output { text-decoration: underline; } .cnf-wrapper div.action { display: flex; justify-content: space-evenly; }</style><div class="cnf-wrapper"><center class="message red">คุณไม่สามารถกลับมาแก้ไขข้อมูลได้ในภายหลัง</center><div class="info">ฉัน <output>'+sv.namef+'  '+sv.namel+' ('+sv.namen+')</output> ศึกษาอยู่ชั้น<output>'+grade[0]+'ศึกษาปีที่ '+grade[1]+'</output> โรงเรียน<output>'+sv.school+'</output> ใช้ไอดีไลน์ <output>'+sv.line+'</output> และเบอร์โทรศัพท์มือถือ <output>'+sv.phone+'</output><br>ที่อยู่อีเมลสำหรับติดต่อของฉันคือ <output>'+sv.email+'</output></div><div class="action"><button onClick="reg.confirm(false)" class="gray hollow ripple-click">กลับไปแก้ไขข้อมูล</button><button onClick="reg.confirm(true)" class="green ripple-click">ยืนยันข้อมูลการลงทะเบียน</button></div></div>'});
						setTimeout(function() { ppa.ripple_click_program(); }, 750);
					} else {
						app.ui.lightbox.close();
						if (cnf) sendForm();
					}
				};
				var sendForm = function() {
					$(".form").attr("disabled", "");
					$.post(APIurl, {app: "register", cmd: "addNew", attr: sv}, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							$(".form").addClass("message");
							$('<div class="message cyan" style="display: none;"><div>การลงทะเบียนเสร็จสิ้น<br>กรุณาเข้าสู่ระบบเพื่อส่งผลงาน</div><div style="display: flex;"><a href="login#user='+encodeURI(sv.email)+'" role="button" class="blue hollow full-x ripple-click" draggable="false">เข้าสู่ระบบ</a></div></div>').insertAfter(".form").toggle("fold");
							setTimeout(function() { ppa.ripple_click_program(); }, 500);
							sv = null;
						} else {
							app.ui.notify(1, dat.reason);
							$(".form").removeAttr("disabled");
						}
					});
				};
				var clearForm = function() {
					$(".form input, .form select").val("");
					validate_field();
				};
				var openForm = function() {
					$(".option").toggle("slide");
					$(".form").toggle("clip");
					setTimeout(function() {
						$(".form .dont-ripple").removeClass("dont-ripple").addClass("ripple-click");
						ppa.ripple_click_program();
					}, 500);
				};
				return {
					start: openForm,
					reset: clearForm,
					submit: validate,
					confirm: registrationConfirm
				}
			} const reg = regisfx(); delete regisfx;
		</script>
		<script src="/resource/js/extend/fs-school.js" type="text/javascript"></script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>การลงทะเบียนเข้าร่วมประกวด</h2>
				<div class="message blue">
					<h3>กำหนดการ (รอบที่ 1)</h3>
					<p>เปิดรับสมัคร<strike>วันที่ 1 ธันวาคม 2564</strike><b>วันนี้</b><br>ปิดรับสมัครวันที่ 31 ธันวาคม 2564<br>ประกาศผลวันที่ 15 มกราคม 2565</p>
				</div>
				<div class="option">
					<p>หากท่านได้ทำการลงทะเบียนไว้เรียบร้อยแล้ว และต้องการส่งผลงาน โปรด<a href="login">เข้าสู่ระบบ</a><br>หากท่านยังไม่ลงทะเบียน ท่านสามารถ<a href="javascript:reg.start()">เริ่มกรอกฟอร์ม</a>ได้เลย</p>
				</div>
				<div class="form modern gray" style="display: none;">
					<form>
						<p><?php echo $_COOKIE['set_lang']=="th" ? "ข้อมูลผู้สมัคร" : "Attendee information"; ?></p>
						<div class="group-inline">
							<div class="group">
								<input type="text" name="namef" maxlength="50"><label><?php echo $_COOKIE['set_lang']=="th" ? "(คำนำหน้า) ชื่อจริง" : "First name"; ?></label>
							</div>
							<div class="group">
								<input type="text" name="namel" maxlength="50"><label><?php echo $_COOKIE['set_lang']=="th" ? "นามสกุล" : "Last name"; ?></label>
							</div>
						</div>
						<span><i class="material-icons">information</i> <?php echo $_COOKIE['set_lang']=="th" ? "ใช้ในการออกเกียรติบัตร" : "Your name will be used to issue your certificate"; ?></span>
						<div class="group-inline">
							<div class="group">
								<input type="text" name="namen" maxlength="30"><label><?php echo $_COOKIE['set_lang']=="th" ? "ชื่อเล่น" : "Nick name"; ?></label>
							</div>
							<div class="group">
								<select name="grade"><option selected disabled value=""><?php echo $_COOKIE['set_lang']=="th" ? "ระดับชั้น" : "Grade"; ?></option>
									<option value="0"><?php echo $_COOKIE['set_lang']=="th" ? "ประถม 3" : "Grade 3 (P.3)"; ?></option>
									<option value="1"><?php echo $_COOKIE['set_lang']=="th" ? "ประถม 4" : "Grade 4"; ?></option>
									<option value="2"><?php echo $_COOKIE['set_lang']=="th" ? "ประถม 5" : "Grade 5"; ?></option>
									<option value="3"><?php echo $_COOKIE['set_lang']=="th" ? "ประถม 6" : "Grade 6"; ?></option>
									<option value="4"><?php echo $_COOKIE['set_lang']=="th" ? "มัธยม 1" : "Grade 7 (M.1)"; ?></option>
									<option value="5"><?php echo $_COOKIE['set_lang']=="th" ? "มัธยม 2" : "Grade 8"; ?></option>
									<option value="6"><?php echo $_COOKIE['set_lang']=="th" ? "มัธยม 3" : "Grade 9"; ?></option>
									<option value="7"><?php echo $_COOKIE['set_lang']=="th" ? "มัธยม 4" : "Grade 10 (M.4)"; ?></option>
									<option value="8"><?php echo $_COOKIE['set_lang']=="th" ? "มัธยม 5" : "Grade 11"; ?></option>
									<option value="9"><?php echo $_COOKIE['set_lang']=="th" ? "มัธยม 6" : "Grade 12"; ?></option>
								</select>
							</div>
						</div>
						<div class="group">
							<input type="hidden" name="school"><input type="text" readonly onFocus="chooseSchool()"><label><?php echo $_COOKIE['set_lang']=="th" ? "โรงเรียน" : "School"; ?></label>
						</div>
						<span><?php echo $_COOKIE['set_lang']=="th" ? "กรุณาเลือกโรงเรียนที่นักเรียนศึกษาอยู่ในปีการศึกษาปัจจุบัน" : "Please put in the school you study in current academic year"; ?></span>
						<span><?php echo $_COOKIE['set_lang']=="th" ? "หากไม่มีชื่อโรงเรียนคุณขึ้น กรุณาติดต่อมาที่พวกเรา" : "If your school name doesn't show up please contact us"; ?></span>
						<p><?php echo $_COOKIE['set_lang']=="th" ? "ข้อมูลการติดต่อ" : "Your contact information"; ?></p>
						<div class="group-inline">
							<div class="group">
								<input type="tel" name="phone" maxlength="10"><label><?php echo $_COOKIE['set_lang']=="th" ? "เบอร์โทรศัพท์มือถือ" : "Mobile phone number"; ?></label>
							</div>
							<div class="group">
								<input type="text" name="line" maxlength="50" pattern="[0-9A-Za-z\._]{3,50}"><label><?php echo $_COOKIE['set_lang']=="th" ? "ไอดีไลน์" : "line ID"; ?></label>
							</div>
						</div>
						<div class="group">
							<input type="email" name="email" maxlength="255"><label><?php echo $_COOKIE['set_lang']=="th" ? "ที่อยู่อีเมล" : "E-mail address"; ?></label>
						</div>
						<span><i class="material-icons">information</i> <?php echo $_COOKIE['set_lang']=="th" ? "สำหรับส่งเกียรติบัตร" : "For sending certificate only"; ?></span>
					</form>
					<div class="group-inline">
						<div class="group"><button type="reset" class="red hollow full-x dont-ripple" onClick="reg.reset()"><?php echo $_COOKIE['set_lang']=="th" ? "เริ่มใหม่" : "Reset form"; ?></button></div>
						<div class="group"><button type="submit" class="blue full-x dont-ripple" onClick="reg.submit()"><?php echo $_COOKIE['set_lang']=="th" ? "ลงทะเบียน" : "Register"; ?></button></div>
					</div>
				</div>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>