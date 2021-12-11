<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Workshop";
	$header_desc = "การย้อนดูคลิปวีดีโอ";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main form input, main form select { background-color: var(--clr-psc-skin-shiny) !important; }
		</style>
		<script type="text/javascript">
			function update() { document.querySelector("form button").disabled = false; }
			function watch() {
				go_on(); return false;
				function go_on() {
					var data = { remote: true,
						name: document.querySelector('form [name="name"]').value.trim(),
						clip: document.querySelector('form [name="clip"]').value.trim()
					}; if (data.name.length <= 0 || data.name.length > 30) {
						app.ui.notify(1, [3, "Your nickname is empty or too long"]);
						$('form [name="name"]').focus(); return false;
					} if (!/^([ก-๛]{1,30}|[A-Z][a-z]{0,29})$/.test(data.name)) {
						app.ui.notify(1, [2, "Invalid nickname format"]);
						$('form [name="name"]').focus(); return false;
					} if (data.clip.length == 0) {
						app.ui.notify(1, [3, "No video selected"]);
						$('form [name="clip"]').focus(); return false;
					} document.querySelector("form button").disabled = true;
					$.post("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/api", {app: "workshop", cmd: "view", attr: data}, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) $.post("/resource/php/core/override", {app: "workshop", cmd: "view", attr: dat}, function(res, hcs) {
							location = "/recording/"+dat.info;
						}); else {
							app.ui.notify(1, dat.reason);
							document.querySelector("form button").disabled = false;
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
				<h2>การดูคลิปวีดีโอย้อนหลัง</h2>
				<form class="form" onChange="update()">
					<input type="text" name="name" maxlength="30" placeholder="Your nickname">
					<div class="group">
						<select name="clip">
							<option selected value disabled>---Video---</option>
							<option value="1">1st Workshop (11/12/2021)</option>
						</select>
						<button class="blue full-x ripple-click" onClick="return watch()" disabled>Watch</button>
					</div>
				</form>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>