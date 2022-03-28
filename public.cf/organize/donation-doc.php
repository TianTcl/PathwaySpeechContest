<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Donation list";
	$header_desc = "ตรวจสอบและจัดการสถานะรายการบริจาค";

	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("Location: new-password$my_url");
	$permitted = has_perm("money"); if ($permitted) {
		$apiData = json_decode(file_get_contents("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/admin-remote?user=".$_SESSION['evt2']['user']."&do=getDonateDoc", false));
	}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main .wrapper { max-height: 350px; overflow-y: auto; }
			main .wrapper li {
				/* list-style-type: none; */
				cursor: pointer;
				background-color: var(--clr-gg-grey-300);
				padding: 5px; margin: 0px 5px 10px 0px;
				border-radius: 5px;
			}
			main .form * { user-select: all; }
		</style>
		<script type="text/javascript">
			const n = ["วันอังคารที่ 25 มกราคม 2565 เวลา 08.00น.", "มูลนิธิกระจกเงา"];
			$(document).ready(function() {
				h();
			});
			function a(b, c, d) {
				return "เรียนคุณ "+b+"\n"+
					"\n"+
					"คุณได้บริจาคเงินจำนวน "+c+" บาทเข้ามาที่โครงการ Pathway Speech Contest ทางเจ้าหน้าที่ได้ตรวจสอบและยืนยันการได้รับเงินบริจาคแล้ว\n"+
					// "ทั้งนี้ ทางเราจะนำเงินบริจาคทั้งหมดไปบริจาคแก่"+n[1]+" หากท่านต้องการใบเสร็จการบริจาค ท่านสามารถคลิ๊กเข้าไปกรอกข้อมูลที่อยู่พร้อมส่งภาพหลักฐานการโอนเงินได้ที่ https://PathwaySpeechContest.cf/donate/"+d+"/edit ภายใน"+n[0]+" ครับ (หมายเลขอ้างอิงรายการ \""+d+"\")\n"+
					"ทั้งนี้ หากท่านต้องการใบเสร็จการบริจาค ท่านสามารถคลิ๊กเข้าไปกรอกข้อมูลที่อยู่พร้อมส่งภาพหลักฐานการโอนเงินได้ที่ https://PathwaySpeechContest.cf/donate/"+d+"/edit ครับ (หมายเลขอ้างอิงรายการ \""+d+"\")\n"+
					"\n"+
					"จึงเรียนมาเพื่อทราบ\n"+
					"\n"+
					"--------------------------\n"+
					"Chainath Limchunhanukul\n"+
					"System Administrator\n"+
					"Pathway Speech Contest Team";
			}
			function e(f, g) {
				document.querySelector("main textarea#ref_mail").value = a(...f);
				const elem = document.createElement("textarea");
				elem.value = g.innerText; document.body.appendChild(elem);
				elem.select(); document.execCommand('copy'); document.body.removeChild(elem);
			}
			function h() {
				var i = document.querySelector("main .form textarea#ref_addr"), j = 1,
					k = <?=json_encode($apiData -> addr)?>.map(l => {l.address = JSON.parse(l.address); return l;}); console.log(i);
				k.forEach(m => {
					i.value += m.name+" - "+m.amount+" บาท\n";
					i.value += "เลขที่ "+m.address.number;
					if (m.address.tract.length) i.value += " หมู่ "+m.address.tract;
					if (m.address.village.length) i.value += " หมู่บ้าน"+m.address.village;
					if (m.address.alley.length) i.value += " ซอย"+m.address.alley;
					if (m.address.road.length) i.value += " ถนน"+m.address.road;
					i.value += " แขวง/ตำบล "+m.address.subdistrict.name;
					i.value += " เขต/อำเภอ "+m.address.district.name;
					i.value += " จังหวัด "+m.address.province.name;
					// i.value += " "+m.address.subdistrict.name+" "+m.address.district.name+" "+m.address.province.name;
					i.value += " "+m.address.postcode.toString();
					if (j++ != k.length) i.value += "\n\n";
				});
			}
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<?php if (!$permitted) echo '<iframe src="/error/901">901: No Permission</iframe>'; else { ?>
			<div class="container">
				<h2><?=$_COOKIE['set_lang']=="th"?"เอกสารการบริจาค":"Donation documents"?></h2>
				<?php if ($apiData -> mail) { ?>
					<p><?=$_COOKIE['set_lang']=="th"?"ข้อขวามส่งเมลขอที่อยู่ออกใบเสร็จบริจาค":"Message for emailling for donation address"?></p>
					<ol class="wrapper slider"><?php foreach ($apiData -> mail as $each) { ?>
						<li onClick="e(['<?=$each -> donor?>', <?=$each -> amt?>, <?=$each -> refer?>], this)"><?=$each -> contact?></li>
					<?php } ?></ol><hr>
					<div class="form">
						<div class="group"><input type="text" value="Pathway Speech Contest - Donation" readonly></div>
						<textarea id="ref_mail" rows="13"></textarea>
					</div>
				<?php } if (count($apiData -> addr)) { ?>
					<p><?=$_COOKIE['set_lang']=="th"?"พิมพ์ที่อยู่":"Print address"?></p>
					<div class="form"><textarea id="ref_addr" rows="25"></textarea></div>
				<?php } ?>
			</div><?php } ?>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>