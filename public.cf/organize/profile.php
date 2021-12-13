<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
    header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");
    $rmte = (isset($_GET['remote']) && !empty($_GET['remote'])); $remote = $rmte ? "remote" : "";
	$header_title = "My Profile";

	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("location: new-password$my_url");

    require_once($dirPWroot."resource/php/lib/TianTcl.php");
	$user = $_SESSION['evt2']['user'];
	$get = json_decode(file_get_contents("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/admin-remote?user=$user&do=getAdminProfile", false));
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main form div.box {
				margin-bottom: 10px;
				width: calc(100% - 5px); height: 125px;
				border-radius: 5px; border: 2.5px dashed var(--clr-bs-gray);
				background-color: var(--clr-gg-grey-300); background-size: contain; background-repeat: no-repeat; background-position: center;
				background-image: url("<?=(!empty($get -> avatar)?"https://inf.bodin.ac.th/e/Pathway-Speech-Contest":"")?>/resource/images/people-<?=(!empty($get -> avatar)?($get -> avatar):"default.jpg")?>");
				/* display: flex; justify-content: center; */
				overflow: hidden;
			}
			main form div.box:after {
				margin: auto;
				position: relative; top: -50%; transform: translateY(-62.5%);
				text-align: center; text-shadow: 1.25px 1.25px #FFFA;
				display: block; content: "Drag & Drop your image here or Browse";
				pointer-events: none;
			}
			main form input[type="file"] {
				margin: auto;
				width: 100%; height: 100%; transform: translateY(-2.5px);
				opacity: 0%; filter: opacity(0%);
			}
			main form button { white-space: nowrap; }
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				seek_param();
				$('form input[name="usf"]').on("change", validate_file);
				$('form').on("change", function() { document.querySelector("form button").disabled = false; });
			});
			function seek_param() { if (location.hash.length > 1) {
				// Extract hashes
				var hash = {}; location.hash.substring(1, location.hash.length).split("&").forEach((ehs) => {
					let ths = ehs.split("=");
					hash[ths[0]] = ths[1];
				});
				// Let's see
				if (typeof hash.msg !== "undefined") {
					eval(decodeURIComponent(hash.msg.replaceAll("--20", "%20"))+";");
					if (typeof hash.msg2 !== "undefined") eval(decodeURIComponent(hash.msg2.replaceAll("--20", "%20"))+";");
				} history.replaceState(null, null, location.pathname);
			} }
			function validate_file() {
				var f = document.querySelector('form input[name="usf"]').files;
				var cond = f.length == 1; if (cond) {
					let filename = (f[0].name).toLowerCase().split(".");
					cond = (["png", "jpg"].includes(filename[filename.length-1])) && (f[0].size > 0 && f[0].size < 3072000); // 3 MB
					if (cond) document.querySelector("main form input[readonly]").value = f[0].name;
					else app.ui.notify(1, [2, "Please check if your photo is .PNG or .JPG file and its size is less than or equal to 3 MB"]);
				} else document.querySelector("main form input[readonly]").value = "ใช้ภาพเดิม";
				// document.querySelector("form button").disabled = !cond;
				return cond;
			}
			function update() {
				if (document.querySelector('form input[name="name"]').value.trim().length == 0) {
					app.ui.notify(1, [3, "Your display name is empty"]);
					$('form input[name="name"]').focus(); return false;
				} if (validate_file()) document.querySelector("form").submit();
			}
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>My Profile</h2>
				<form class="form" method="post" enctype="multipart/form-data" action="https://inf.bodin.ac.th/e/Pathway-Speech-Contest/organize/profile?remote=<?=$user?>">
					<input type="text" name="name" maxlength="30" placeholder="ชื่อที่แสดง(ต่อสาธารณะ)" required value="<?=($get -> display)?>">
					<div class="box"><input type="file" name="usf" accept=".png, .jpg" required></div>
					<div class="group">
						<span>ภาพโปรไฟล์</span>
						<input type="text" readonly value="ใช้ภาพเดิม">
						<button class="blue ripple-click" onClick="return update()" disabled>&emsp;Save&emsp;&emsp;</button>
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