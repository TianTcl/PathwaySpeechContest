<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Workshop";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main div.container .blur {
				filter: blur(0.3rem);
				display: inline-block; pointer-events: none;
			}
			main div.container .images {
				display: flex; flex-wrap: wrap; flex-direction: column; align-items: center; justify-content: flex-start;
			}
			main div.container .images img {
				margin-bottom: 12.5px;
				max-width: 100%; max-height: 500px;
				box-shadow: 1.25px 1.25px var(--shd-big) var(--fade-black-5);
				border-radius: 2.5px;
				object-fit: contain;
			}
			main div.container .take-act {
				margin-bottom: 20px;
				text-align: right;
			}
		</style>
		<link rel="stylesheet" href="/resource/css/extend/post.css">
		<script type="text/javascript">
			
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>Special Event - Workshop</h2>
				<p>📍พบกับ workshop จากแขกรับเชิญสุดพิเศษโดย T. Timothy Hutto อาจารย์จาก English Program ของโรงเรียนสามเสนวิทยาลัย และรุ่นพี่ในวันเสาร์ที่ 11 ธันวาคมนี้ เวลา 17.00 - 18.30 น.🍃</p>
				<p>หากสนใจเข้าร่วม workshop สามารถเข้าร่วมผ่านทาง <a href="https://bod.in.th/!PSC-line" target="_blank">openchat</a> ที่นี่</p>
				<p>เข้าร่วมการประชุมได้ที่ <a disabled href="https://inf.bodin.ac.th/go?url=htts%3A%2F%2Fmeet.google.com%2Fgrt-zzzr-fkk" target="_blank">meet.google.com/<span class="blur">grt-zzzr-fkk</span></a> หรือบันทึกใส่ <a disabled href="/go?url=https%3A%2F%2Fcalendar.google.com%2Fevent%3Faction%3DTEMPLATE%26amp%3Btmeid%3DMG42cWF1bzBrMzBycnF2Z2NvNjA5bjVoa2MgdGlhbnRjbEBib2Rpbi5hYy50aA%26amp%3Btmsrc%3Dtiantcl%2540bodin.ac.th" target="_blank"><strike>Google calender</strike></a> ไว้</p>
				<p>ท่านสามารถ<a href="/FaQ#joining-workshop-meet">ศึกษาวิธีการเข้าการประชุม</a>ได้ที่นี่</p>
				<div class="images">
					<img src="/resource/images/news-ws1-01.jpg">
					<img src="/resource/images/news-ws1-02.png">
					<img src="/resource/images/news-ws1-03.png">
					<img src="/resource/images/news-ws1-04.jpg">
				</div>
				<div class="take-act">
					<a role="button" class="green hollow" href="/workshop/">&emsp;<?=$_COOKIE['set_lang']=="th"?"ดูคลิปย้อนหลัง":"View Playback"?> &nbsp;<i class="material-icons">arrow_forward</i> &nbsp; </a>
				</div>
				<nav class="post">
					<hr>
					<div class="hold">
						<a></a>
						<span class="mnfst">By: Admin | 11/12/2021</span>
						<a href="2022-01-21">(21/01/2022) Next →</a>
					</div>
				</nav>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>