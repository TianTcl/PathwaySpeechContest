<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "เข้าสู่ระบบผู้สมัคร";

	if (isset($_SESSION['evt'])) header("Location: submit/");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main form { --mvlbt: -5px; }
			main form input[type="text"], main form input[type="tel"], main form input[type="email"], main form select {
				margin: 7.5px 2.5px; padding: 2.5px 5px;
				width: calc(100% - 17px); height: 30px;
				font-size: 20px; line-height: 30px; font-family: "Sarabun", serif;
				border: 1px solid var(--clr-bs-gray-dark); border-radius: 3px;
				background-color: var(--clr-psc-skin-shiny);
				transition: var(--time-tst-fast);
			}
			main form select { width: calc(100% - 5px); height: 35px; line-height: 35px; }
			main form input + label {
				padding: 2.5px 5px;
				position: absolute; left: 15px; transform: translate(calc(var(--mvlbt) + 12.5px), 10px);
				height: var(--txt-ipt-h);
				font-size: 18.75px; font-family: 'Sarabun', sans-serif; color: gray; line-height: var(--txt-ipt-h);
				transition: calc(var(--time-tst-xfast)/1.5); pointer-events: none;
			}
			main form input[required] + label { color: var(--clr-bs-red); }
			main form input:focus + label, main form input[filled="true"] + label {
				transform: translate(var(--mvlbt), -7px) scale(0.75);
				color: var(--clr-main-black-absolute);
				background-image: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,0) 42.4%, var(--clr-psc-skin-shiny) 42.5%, var(--clr-psc-skin-shiny) 55%, rgba(255,255,255,0) 55.1%);
			}
			main form select:focus { box-shadow: 0 0 7.5px .125px var(--clr-bs-blue) }
			main form span {
				padding: 0px 5px;
				transform: translateY(-5px);
				font-size: 0.75rem; line-height: 17px; color: var(--clr-bs-gray-dark);
				display: flex;
			}
			main form span i.material-icons {
				margin-right: 2.5px;
				width: 17px; height: 17px;
				font-size: 16px;
				display: block;
			}
			@media only screen and (max-width: 768px) {
				main form input + label { transform: translate(-2.5px, 12.5px); }
    			main form input:focus + label, main form input[filled="true"] + label { transform: translate(-17.5px, -4px) scale(0.75); }
			}
		</style>
		<script type="text/javascript">
			const APIurl = "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/api";
			$(document).ready(function() {
				$(' main form input[type="tel"], main form input[type="email"]').on("input change", validate_field);
				seek_param(); validate_field();
				<?php # if (isset($_SESSION['evt'])) echo '$.ajax(APIurl+"?app=account&cmd=logout");'; ?>
			});
			function validate_field() {
				document.querySelectorAll('main form input[type="tel"], main form input[type="email"]').forEach((eio) => {
					var ei = $(eio);
					ei.attr("filled", (ei.val()==""?"false":"true"));
				});
			}
			function seek_param() { if (location.hash!="") {
					// Extract hashes
					var hash = {}; location.hash.substring(1, location.hash.length).split("&").forEach((ehs) => {
						let ths = ehs.split("=");
						hash[ths[0]] = ths[1];
					});
					// Let's see
					if (typeof hash.user !== "undefined") document.querySelector('main form input[type="email"]').value = hash.user;
					history.replaceState(null, null, location.pathname);
			} }
			function signin() {
				go_on(); return false;
				function go_on() {
					var info = {
						app: "account", cmd: "login", attr: { remote: true,
						user: document.querySelector('main form input[type="email"]').value.trim(),
						pass: document.querySelector('main form input[type="tel"]').value.trim()
					} }; if (/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@([a-zA-Z0-9\-_]+\.)+[a-zA-Z]{2,13}$/.test(info.attr.user) && /^0[1-9]\d{8}$/.test(info.attr.pass))
					$.post(APIurl, info, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) $.post("/resource/php/core/api", {app: "account", cmd: "login", attr: dat.info}, function(res, hsc) {
							var dat2 = JSON.parse(res);
							if (dat2.success) location = "submit/";
							else app.ui.notify(1, dat2.reason);
						});
						else app.ui.notify(1, dat.reason);
					}); else app.ui.notify(1, [2, "Your sign in information is invalid."]);
				}
			}
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>เข้าสู่ระบบ</h2>
				<p>กรุณาเข้าสู่ระบบเพื่อส่งผลงาน<br>หากท่านยังไม่ได้ลงทะเบียน สามารถ<a href="register#start">ลงทะเบียน</a>ได้ที่นี่</p>
				<form>
					<input type="email" maxlength="255" required><label>E-mail address</label>
					<input type="tel" maxlength="10" required><label>Phone number</label>
					<button type="submit" class="blue full-x ripple-click" onClick="return signin()" style="margin-top: 7.5px;">เข้าสู่ระบบ</button>
				</form>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>