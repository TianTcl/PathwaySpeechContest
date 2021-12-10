<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "เมนูหลักทีมงาน";

	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("location: new-password$my_url");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<link rel="stylesheet" href="https://inf.bodin.ac.th/resource/css/extend/all-index.css">
		<script type="text/javascript" src="https://inf.bodin.ac.th/resource/js/extend/all-index.js"></script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<p><?php echo ($_COOKIE['set_lang']=="en"?"Welcome ":"ยินดีต้อนรับ ").$_SESSION['evt2']['name']; ?> (<?php echo $_SESSION['evt2']['usrn']; ?>)</p>
				<p><?php echo ($_COOKIE['set_lang']=="en"?"to the Pathway Speech Contest oragnizer dashboard":"เข้าสู่ระบบแผงควบระบบ Pathway Speech Contest"); ?></p><br>
				<p>คุณสามารถเลือกการทำงานได้จากเมนูด้านบนหรือตัวเลือกด้านล่าง</p>
				<input name="contest" type="checkbox" id="ref_menu-a"><label for="ref_menu-a">การประกวด</label><ul>
					<li><a href="attendees">รายนามผู้สมัคร</a></li>
				</ul>
				<input name="donate" type="checkbox" id="ref_menu-b"><label for="ref_menu-b">การบริจาค</label><ul>
					<li><a href="giveaway">บริหารการแจก giveaway</a></li>
					<li class="dl">&nbsp;</li>
					<li disabled><a href="donation">รายการที่รับบริจาค</a></li>
				</ul>
				<input name="stats" type="checkbox" id="ref_menu-c"><label for="ref_menu-c">สถิติต่างๆ</label><ul>
					<li><a href="statics">สถิติของโครงการ</a></li>
				</ul>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>