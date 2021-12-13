<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main center > * { margin: 0px 0px 10px; }
			div.register div.btn-warp { display: flex; justify-content: center; }
			div.register div.btn-warp a[role="button"] {
				padding: 7.5px 32.5px;
				height: 40px; line-height: 40px;
				text-transform: uppercase; font-family: "IBM Plex Sans Thai";
			}
			div.register div.btn-warp a[role="button"]:first-child { border-radius: 27.5px 0px 0px 27.5px; }
			div.register div.btn-warp a[role="button"]:last-child { border-radius: 0px 27.5px 27.5px 0px; }
			@media only screen and (max-width: 768px) {
				div.register div.btn-warp a[role="button"] { font-size: 18.75px; }
			}
		</style>
		<script type="text/javascript">
			
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<center>
					<p>การแข่งขัน</p>
					<h1>Pathway Speech Contest</h1>
					<p>ในหัวข้อ</p>
					<h2>NEW YEAR'S DAY</h2>
					<p><font style="color: var(--clr-bs-red);">ฟรี! ค่าสมัคร</font></p>
				</center>
				<hr>
				<p>ชื่อโครงการ โครงการประกวดสุนทรพจน์หัวข้อ “New Year’s Day”</p>
				<p>เวลาในการแข่งขัน นักเรียนควรใ้ช้เวลาในการพูดสุนทรพจน์ 2-3 นาที</p>
				<hr>
				<span>คุณสมบัติผู้เข้าแข่งขัน</span><ul>
					<li>นักเรียนระดับชั้น ป. 3-6</li>
					<li>นักเรียนระดับชั้น ม. 1-3</li>
					<li>นักเรียนระดับชั้น ม. 4-6</li>
				</ul>
				<hr>
				<span>วัตถุประสงค์ของการจัดการประกวด</span><ul>
					<li>เพื่อนำเงินบริจาคทั้งหมดที่ได้รับจากการจัดการประกวดสุนทรพจน์ภาษาอังกฤษไปบริจาคแก่<a href="https://inf.bodin.ac.th/go?url=http%3A%2F%2Fdpf.or.th" target="_blank">มูลนิธิดวงประทีป</a>ใน<a href="https://inf.bodin.ac.th/go?url=http%3A%2F%2Fdpf.or.th%2Fautopagev4%2Fshow_page.php%3Ftopic_id%3D10%26auto_id%3D3" target="_blank">โครงการอนุบาลชุมชน</a>
					<li>เพื่อส่งเสริมและสนับสนุนให้นักเรียนมีความกล้าแสดงออกทางความคิด และยังรวมถึงการใช้ความคิดสร้างสรรค์ในการพูดและสื่อสารเป็นภาษาอังกฤษ</li>
					<li>เพื่อให้นักเรียนใช้เวลาว่างให้เกิดประโยชน์</li>
				</ul>
				<hr>
				<p>รูปแบบกิจกรรม</p>
				<p>การพูดสุนทรพจน์เป็นภาษาอังกฤษในหัวข้อ “New Year’s Day” โดยการอัดคลิปวิดีโอแล้วอัปโหลดคลิปส่งผ่านเวปไซต์ <a href="https://PathwaySpeechContest.cf">PathwaySpeechContest.cf</a></p>
				<span>วิธีการส่งคลิปวิดีโอเพื่อแข่งขัน</span><ul>
					<li>อัดคลิปวิดีโอครึ่งตัวโดยให้เห็นใบหน้าที่ชัดเจน</li>
					<li>แนะนำตัวให้น่าจดจำไม่เกิน 30 วินาที</li>
					<li>กล่าวสุนทรพจน์เป็นภาษาอังกฤษเกี่ยวกับความทรงจำในวันปีใหม่ประมาณ 2-3 นาที</li>
				</ul>
				<hr>
				<!--ul><span>กรรมการตัดสิน</span>
					<li>นางสาวฉัตรชนก ปรุงเกียรติ</li>
				</ul>
				<hr--><br>
				<div class="register">
					<div class="btn-warp">
						<a href="<?=isset($_SESSION['evt'])?"submit/":"register"?>" role="button" class="cyan"><?=isset($_SESSION['evt'])?($_COOKIE['set_lang']=="th"?"ส่งผลงาน":"Submit it"):($_COOKIE['set_lang']=="th"?"ลงทะเบียน":"Register")?></a>
						<a href="donate" role="button" class="green"><?=$_COOKIE['set_lang']=="th"?"บริจาค":"Donate"?></a>
					</div>
					<center><br><a href="criteria">เกณฑ์การพิจรณาคะแนน</a> | <a href="contact">ช่องทางการติดต่อสอบถาม</a> | <a href="team">คณะผู้ประสานงาน</a></center>
				</div>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>