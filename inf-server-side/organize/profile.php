<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ps.php");
    header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");
    $rmte = (isset($_GET['remote']) && !empty($_GET['remote'])); $remote = $rmte ? "remote" : "";
	$header_title = "My Profile";

	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("location: new-password$my_url");

	require($dirPWroot."e/resource/db_connect.php");
	$user = $rmte ? trim($_GET['remote']) : $_SESSION['evt2']['user'];
	if (isset($_POST['name']) && !empty(trim($_POST['name']))) {
		if ($rmte) $back = "https://PathwaySpeechContest.cf/organize/profile";
		$name = $db -> real_escape_string(trim($_POST['name']));
		require($dirPWroot."resource/php/core/getip.php");
		if (isset($_FILES['usf'])) {
			$target_dir = "../resource/images/"; $imageFileType = strtolower(pathinfo(basename($_FILES['usf']["name"]), PATHINFO_EXTENSION));
			$newFileName = crc32($user).".$imageFileType";
            $etfn = "people-$newFileName"; $target_file = $target_dir.$etfn;
            $uploadOk = ($_FILES['usf']["size"] > 0 && $_FILES['usf']["size"] <= 5120000); // 5 MB
            if (!in_array($imageFileType, array("png", "jpg", "jpeg", "gif", "heic"))) $uploadOk = false;
            if ($uploadOk) {
                if (file_exists($target_file)) unlink($target_file);
                if (move_uploaded_file($_FILES['usf']["tmp_name"], $target_file)) $avatersql = ",avatar='$newFileName'";
                else { $avatersql = ""; $notify2 = '3, "Unable to upload your photo. Please try again"'; }
            } else { $avatersql = ""; $notify2 = '3, "Invalid photo property"'; }
		} else $avatersql = "";
		$success = $db -> query("UPDATE PathwaySCon_organizer SET display='$name'$avatersql WHERE user_id=$user");
		if ($success) { $notify = '0, "Your profile is updated"'; slog($user, "PathwaySCon", "account", "edit", "", "pass", $remote); }
		else { $notify = '0, "Your profile is updated"'; slog($user, "PathwaySCon", "account", "edit", "", "fail", $remote, "InvalidQuery"); }
		if ($rmte) header("Location: $back#".(isset($notify)?("msg=".urlencode("app.ui.notify(1,[".str_replace(" ", "--20", $notify)."])").(isset($notify2)?("&msg2=".urlencode("app.ui.notify(1,[".str_replace(" ", "--20", $notify2)."])")):"")):""));
	} $myinfo = $db -> query("SELECT avatar,display FROM PathwaySCon_organizer WHERE user_id=$user");
	$mydata = $myinfo -> fetch_array(MYSQLI_ASSOC);
	$db -> close();
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/heading.php"); require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main form div.box {
				margin-bottom: 10px;
				width: calc(100% - 5px); height: 125px;
				border-radius: 5px; border: 2.5px dashed var(--clr-bs-gray);
				background-color: var(--clr-gg-grey-300); background-size: contain; background-repeat: no-repeat; background-position: center;
				background-image: url("/e/Pathway-Speech-Contest/resource/images/people-<?=(!empty($mydata['avatar'])?$mydata['avatar']:"default.jpg")?>");
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
				<?php if(isset($notify))echo"app.ui.notify(1,[$notify]);";?>
				<?php if(isset($notify2))echo"app.ui.notify(1,[$notify2]);";?>
				$('form input[name="usf"]').on("change", validate_file);
				$('form').on("change", function() { document.querySelector("form button").disabled = false; });
			});
			function validate_file() {
				var f = document.querySelector('form input[name="usf"]').files;
				var cond = f.length == 1; if (cond) {
					let filename = (f[0].name).toLowerCase().split(".");
					cond = (["png", "jpg", "jpeg", "heic", "gif"].includes(filename[filename.length-1])) && (f[0].size > 0 && f[0].size < 5120000); // 5 MB
					if (cond) document.querySelector("main form input[readonly]").value = f[0].name;
					else app.ui.notify(1, [2, "Please check if your photo is one of the following format PNG/JPG/GIF/HEIF and its size is less than or equal to 5 MB"]);
				} else document.querySelector("main form input[readonly]").value = "<?=$_COOKIE['set_lang']=="th"?"ใช้ภาพเดิม":"Same picture"?>";
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
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>My Profile</h2>
				<form class="form" method="post" enctype="multipart/form-data">
					<input type="text" name="name" maxlength="30" placeholder="<?=$_COOKIE['set_lang']=="th"?"ชื่อที่แสดง(ต่อสาธารณะ)":"Display name"?>" required value="<?=$mydata['display']?>">
					<div class="box"><input type="file" name="usf" accept=".png, .jpg, .jpeg, .gif, .heic" required></div>
					<div class="group">
						<span><?=$_COOKIE['set_lang']=="th"?"ภาพโปรไฟล์":"Avatar"?></span>
						<input type="text" readonly value="<?=$_COOKIE['set_lang']=="th"?"ใช้ภาพเดิม":"Same picture"?>">
						<button class="blue ripple-click" onClick="return update()" disabled>&emsp;Save&emsp;&emsp;</button>
					</div>
				</form>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>