<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "เมนูหลักทีมงาน";

	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("Location: new-password$my_url");
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
				<p><?=$_COOKIE['set_lang']=="th"?"คุณสามารถเลือกการทำงานได้จากเมนูด้านบนหรือตัวเลือกด้านล่าง":"You can expand and choose from menu below."?></p>
				<input name="about" type="checkbox" id="ref_menu-a"><label for="ref_menu-a"><?=$_COOKIE['set_lang']=="th"?"เกี่ยวกับ":"About"?></label><ul>
					<li class="dt"><?=$_COOKIE['set_lang']=="th"?"บัญชี":"Account"?></li>
					<li><a href="profile"><?=$_COOKIE['set_lang']=="th"?"โปรไฟล์ของฉัน":"My profile"?></a></li>
					<li class="dt">Request</li>
					<li><a href="grant-permission"><?=$_COOKIE['set_lang']=="th"?"คำขออนุญาติอ่านคอมเม้น":"Comment view request"?></a></li>
					<li><a href="send-email"><?=$_COOKIE['set_lang']=="th"?"ส่งอีเมลแจ้งเตือน":"Notify with e-mail"?></a></li>
				</ul>
				<input name="contest" type="checkbox" id="ref_menu-b"><label for="ref_menu-b"><?=$_COOKIE['set_lang']=="th"?"การแข่งขัน":"Competition"?></label><ul>
					<li class="dt"><?=$_COOKIE['set_lang']=="th"?"การประกวด":"Contest"?></li>
					<li><a href="attendees"><?=$_COOKIE['set_lang']=="th"?"รายชื่อผู้สมัคร":"Applicant list"?></a></li>
					<li><a href="mark-grade"><?=$_COOKIE['set_lang']=="th"?"ลงคะแนนวีดีโอคลิป":"Grade speech"?></a></li>
					<li hidden><a href="rate-rank"><?=$_COOKIE['set_lang']=="th"?"จัดลำดับและตัดสิน":"Rank speech"?></a></li>
					<li class="dt"><?=$_COOKIE['set_lang']=="th"?"ผลการแข่งขัน":"Results"?></li>
					<li><a href="scoreboard"><?=$_COOKIE['set_lang']=="th"?"กระดานคะแนน":"Scoreboard"?></a></li>
				</ul>
				<input name="donate" type="checkbox" id="ref_menu-c"><label for="ref_menu-c"><?=$_COOKIE['set_lang']=="th"?"การบริจาค":"Donate"?></label><ul>
					<li><a href="giveaway"><?=$_COOKIE['set_lang']=="th"?"บริหาร Giveaway":"Giveaway management"?></a></li>
					<li class="dl">&nbsp;</li>
					<li><a href="donation"><?=$_COOKIE['set_lang']=="th"?"รายการที่รับบริจาค":"Donation list"?></a></li>
				</ul>
				<input name="stats" type="checkbox" id="ref_menu-d"><label for="ref_menu-d"><?=$_COOKIE['set_lang']=="th"?"สถิติต่างๆ":"Statics"?></label><ul>
					<li><a href="statics"><?=$_COOKIE['set_lang']=="th"?"สถิติของโครงการ":"Event statics"?></a></li>
					<li><a href="https://inf.bodin.ac.th/go?url=https%3A%2F%2Fdatastudio.google.com%2Freporting%2F3be6fa8d-bb84-47d7-9075-614dfa12c915" target="_blank"><?=$_COOKIE['set_lang']=="th"?"รายงานการเข้าเว็บ":"Website views"?></a></li>
				</ul>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>