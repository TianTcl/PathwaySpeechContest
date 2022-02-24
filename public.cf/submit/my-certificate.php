<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "การส่งผลงาน";
	$header_desc = "รับประกาศนียบัตร";

	if (!isset($_SESSION['evt'])) header("Location: ../login#next=".end(explode("/", $_SERVER['REQUEST_URI'])));
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main div.box {
				display: flex; justify-content: /* space-around */ space-evenly; flex-wrap: wrap;
				overflow: visible;
			}
			main div.box .message { width: 100%; }
			main div.box .card {
				margin: 7.5px 10px 12.5px; padding: 7.5px;
				width: 225px; height: 175px;
				border-radius: 7.5px;
				background-color: var(--clr-bs-light);
				box-shadow: 2.5px 5px var(--shd-huge) var(--clr-gg-grey-500);
				display: flex; flex-direction: column; justify-content: space-between;
			}
			main div.box .card label {
				font-size: 1.75em;
				display: block;
			}
			main div.box .card .form > div.group {
				margin: 10px 0px 0px;
				justify-content: flex-end;
			}
			main div.box .card .form > div.group button {
				padding: 2.5px 5px;
				height: 35px; line-height: 35px;
			}
			@media only screen and (max-width: 768px) {
				main div.box .card label { font-size: 2.5em; }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				loadCertList();
			});
			function loadCertList() {
				$.post("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/api", {app: "main", cmd: "certify", attr: "list", remote: "<?=$_SESSION['evt']['encid']?>"}, function(res, hsc) {
					var dat = JSON.parse(res);
					if (dat.success) {
						var display = document.querySelector("main div.box");
						if (!dat.info) display.innerHTML = '<div class="message gray"><?=$_COOKIE['set_lang']=="th"?"ขณะนี้กรรมการยังพิจรณาคะแนนไม่เสร็จ จึงยังไม่มีการออกประกาศนียบัตร<br>กรุณาเข้ามาใหม่ภายหลัง":"The judgement isn\\'t finish. Please come back later."?></div>';
						else {
							display.innerHTML = certCard("<?=$_COOKIE['set_lang']=="th"?"ประกาศนียบัตรการเข้าร่วม":"Certificate of Participation"?>", {type: "p"});
							if (dat.info >= 2) display.innerHTML += certCard("<?=$_COOKIE['set_lang']=="th"?"ประกาศนียบัตระดับรางวัล":"Certificate of Completion"?>", {type: "a"});
						}
					} else {
						app.ui.notify(1, dat.reason);
						app.ui.notify(1, [1, "Retrying in 20 seconds..."]);
						setTimeout(loadCertList, 20000);
					}
					function certCard(name, data) {
						return '<div class="card"><label>'+name+'</label><div class="form"><div class="group" data-type="'+data.type+'"><button class="blue" data-title="View" onClick="view(this)"><i class="material-icons">visibility</i></button><button class="gray" data-title="Print" onClick="certPrint(this)"><i class="material-icons">print</i></button><button class="green" data-title="Download" onClick="download(this)"><i class="material-icons">download</i></button></div></div></div>';
					}
				});
			}
			function view(me) {
				var type = me.parentNode.getAttribute('data-type');
				const tab = window.open("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/file/certificate?remote=<?=$_SESSION['evt']['encid']?>&type="+type+"&export=view", "_blank", "width=840,height=680");
			}
			function certPrint(me) {
				var type = me.parentNode.getAttribute('data-type');
				printJS("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/file/certificate?type="+type+"&export=print");
			}
			function download(me) {
				var type = me.parentNode.getAttribute('data-type');
				const tab = window.open("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/file/certificate?remote=<?=$_SESSION['evt']['encid']?>&type="+type+"&export=download");
			}
		</script>
		<script type="text/javascript" src="/resource/js/lib/print.min.js"></script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<!--h2><?=$_COOKIE['set_lang']=="th"?"ประกาศนียบัตรของฉัน":"My Certificate"?></h2-->
				<p class="message yellow"><?=$_COOKIE['set_lang']=="th"?"กรุณาเปิดใน browser ที่รองกับการเปิดหรือดาวน์โหลดไฟล์ PDF":"Please open this page in a browser that supports openning/downlading a PDF file."?></p>
				<div class="box"></div>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>