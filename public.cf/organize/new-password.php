<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Set up password";
	$header_desc = "ตั้งรหัสผ่าน";

	if (!isset($_SESSION['evt2'])) header("Location: ".($_GET['return_url'] ?? "./"));
	else if (!$_SESSION['evt2']["force_pwd_change"]) header("location: ".($_GET['return_url'] ?? "home"));
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				<?php if(isset($notify))echo"app.ui.notify(1,[$notify]);"; ?>
			});
			function fillAll() {
				document.querySelector("main form button").disabled = [
					document.querySelector('main form input[name="pwd-old"]').value.trim(),
					document.querySelector('main form input[name="pwd-new"]').value.trim(),
					document.querySelector('main form input[name="pwd-cnf"]').value.trim()
				].includes("");
			}
			const regex = /^[0-9A-Za-z!@#$%^&*\-_.]{4,255}$/, user = "<?=$_SESSION['evt2']['usrn']?>";
			function validate() {
				go_on(); return false;
				function go_on() {
					var pwd = [
						document.querySelector('main form input[name="pwd-old"]').value.trim(),
						document.querySelector('main form input[name="pwd-new"]').value.trim(),
						document.querySelector('main form input[name="pwd-cnf"]').value.trim()
					];
					if (!regex.test(pwd[0])) app.ui.notify(1, [1, "Invalid old password."]);
					else if (!regex.test(pwd[1])) app.ui.notify(1, [1, "Invalid new password."]);
					else if (!regex.test(pwd[2])) app.ui.notify(1, [1, "Invalid confirmation password."]);
					else if (pwd[0] == pwd[1]) app.ui.notify(1, [2, "Old password and new password can't be the same."]);
					else if (pwd[1] == user) app.ui.notify(1, [2, "New password can't be your username."]);
					else if (pwd[1] != pwd[2]) app.ui.notify(1, [2, "New password does not match."]);
					else {
						$("main form").removeAttr("disabled", ""); document.querySelector("main form button").disabled = true;
						$.post("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/admin-remote?user=<?=$_SESSION['evt2']['user']?>&do=new-pswd", {
							"pwd-old": pwd[0],
							"pwd-new": pwd[1],
							"pwd-cnf": pwd[2]
						}, function(res, hsc) {
							var dat = JSON.parse(res);
							if (dat.success) {
								$.post("/resource/php/core/override", {app: "account", cmd: "new-pswd", attr: ""}, function(res, hsc) {
									var next = location.search.substr(12);
									location = "/organize/"+(next.length > 0 ? next : "home");
								});
							} else {
								app.ui.notify(1, dat.reason);
								$("main form").removeAttr("disabled", "");
							}
						});
					}
				}
			}
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>New password (Setup)</h2>
				<div class="message yellow">ในขณะนี้ ระบบยังไม่รองรับการเปลี่ยนรหัสผ่าน</div>
				<form method="post" class="form" onInput="fillAll()">
					<label>Old password</label>
					<input name="pwd-old" type="password" maxlength="255" required autofocus />
					<label>New password</label>
					<input name="pwd-new" value="<?=$_POST['pwd-new']??""?>" type="new-password" maxlength="255" required />
					<label>Confirm new password</label>
					<input name="pwd-cnf" type="password" maxlength="255" required />
					<label>ตัวอักษรที่อนุญาต: 0-9 A-Z a-z ! @ # $ % ^ & * - _ .</label>
					<button class="green full-x ripple-click" onClick="return validate()" disabled>Set password</button>
				</form>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>