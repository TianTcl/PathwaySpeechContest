<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Donation";
	$header_desc = "การบริจาคเงินแก่มูลนิธิดวงประทีป";
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
				<h2>Duang Prateep Foundation Fund Donation</h2>
				<p>เมื่อวันที่ 26 กุมภาพันธ์ 2565 ทาง Pathway Hub ได้ทำการบริจาคเงินให้กับมูลนิธิดวงประทีปเป็นที่เรียบร้อยแล้ว พวกเราขอขอบพระคุณทุกๆท่านที่ร่วมสมทบทุนให้น้องๆในโครงการอนุบาลชุมชนเป็นอย่างสูงค่ะ🙏🏻❤️</p>
				<div class="images">
					<img src="/resource/images/news-dn1-01.jpg">
					<img src="/resource/images/news-dn1-02.jpg">
					<img src="/resource/images/news-dn1-03.jpg">
				</div>
				<div class="take-act">
					<a role="button" class="green hollow" href="/donate">&emsp;<?=$_COOKIE['set_lang']=="th"?"ร่วมบริจาค":"Donate"?> &nbsp;<i class="material-icons">arrow_forward</i> &nbsp; </a>
				</div>
				<nav class="post">
					<hr>
					<div class="hold">
						<a href="2022-01-21">← Previous (21/01/2022)</a>
						<span class="mnfst">By: Founder | 26/01/2022</span>
						<a></a>
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