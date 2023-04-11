<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Organizer";
	$header_desc = "ทีมงานเข้าสู่ระบบ";
	
	if (isset($_SESSION['evt2'])) header("Location: ".($_GET['return_url'] ?? "home"));
	if (isset($_GET['u']) xor isset($_REQUEST['user']) xor isset($_GET['username'])) $user = trim($_GET['u'].$_GET['user'].$_GET['username']);
	if (isset($_GET['p']) xor isset($_REQUEST['pass']) xor isset($_GET['password']) xor isset($_GET['pwd']) xor isset($_GET['pswd'])) $pass = trim($_GET['p'].$_REQUEST['pass'].$_GET['password'].$_GET['pwd'].$_GET['pswd']);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			form.auth-wrapper {
				margin: 10px 0px; padding: 5px;
				display: block !important;
			}
			form.auth-wrapper > * { margin: 2.5px 0px; }
		</style>
		<script type="text/javascript">
			var allowForm = function() { document.querySelector("form.auth-wrapper button").disabled = (document.querySelector('form.auth-wrapper input[name="user"]').value.trim()=="" || document.querySelector('form.auth-wrapper input[name="pass"]').value.trim()==""); }
			function SignIn() {
				go_on(); return false;
				function go_on() {
					$("form.auth-wrapper").attr("disabled", ""); document.querySelector("form.auth-wrapper button").disabled = true;
					$.post("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/admin-remote?do=login", {
						user: document.querySelector('form.auth-wrapper input[name="user"]').value.trim(),
						pass: document.querySelector('form.auth-wrapper input[name="pass"]').value.trim()
					}, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							$.post("/resource/php/core/override", {app: "account", cmd: "login", attr: dat.info}, function(res, hsc) {
								var next = location.search.substr(12);
								location = "/organize/"+(next.length > 0 ? next : "home");
							});
						} else {
							app.ui.notify(1, dat.reason);
							$("form.auth-wrapper").removeAttr("disabled", "");
						}
					});
				}
			}
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<form class="auth-wrapper form" method="post" onInput="allowForm()">
					<h2><?=$_COOKIE['set_lang']=="th"?"ทีมงานเข้าสู่ระบบ":"Organizer sign-in"?></h2>
					<div class="message blue"><?=$_COOKIE['set_lang']=="th"?"สำหรับผู้ที่เข้าใช้งานครั้งแรก รหัสคืออีเมลที่กรอก":"First time here? Your password is the your email address."?></div>
					<label>Username</label><input name="user" type="text" <?php echo(isset($user)?"value=\"$user\"":"autofocus");?> pattern="[A-Z0-9a-z\._]{3,30}"><br>
					<label>Password</label><input name="pass" type="password" <?php echo(isset($pass)?"value=\"$pass\"":(isset($user)?"autofocus":""));?>><br>
					<button class="blue full-x dont-ripple" onClick="return SignIn()" disabled><?=$_COOKIE['set_lang']=="th"?"เข้าสู่ระบบ":"Sign in"?></button>
				</form>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>