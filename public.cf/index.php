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
			div.register center a { white-space: nowrap; }
			@media only screen and (max-width: 768px) {
				div.register div.btn-warp a[role="button"] { font-size: 18.75px; }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				setTimeout(changeTopic, 1250);
				setTimeout(changeTopic2, 1250);
			});
			function changeTopic() {
				var pos = 14, topic = "World No-Tobacco Day", speed = 50,
					target = document.querySelector("main .topic");
				function remove() {
					if (pos > 0) {
						target.innerHTML = target.innerHTML.substring(0, --pos);
						if (!target.innerHTML.length) target.innerHTML = "<br>";
						setTimeout(remove, speed);
					} else setTimeout(typein, speed*2.5);
				}
				function typein() {
					if (target.innerHTML == "<br>") target.innerHTML = "";
					if (pos < topic.length) {
						target.innerHTML += topic.charAt(pos++);
						setTimeout(typein, speed);
					}
				} remove();
			}
			function changeTopic2() {
				var pos = 14, topic = "World No-Tobacco Day", speed = 50,
					target = $("main span.evn");
				function remove() {
					if (pos > 0) {
						target.html(target.html().substring(0, --pos));
						setTimeout(remove, speed);
					} else setTimeout(typein, speed*2.5);
				}
				function typein() {
					if (pos < topic.length) {
						target.html(target.html() + topic.charAt(pos++));
						setTimeout(typein, speed);
					}
				} remove();
			}
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
					<h2 class="topic">NEW YEAR'S DAY</h2>
					<p><font style="color: var(--clr-bs-red);">ฟรี! ค่าสมัคร</font></p>
				</center>
				<hr>
				<p>ชื่อโครงการ โครงการประกวดสุนทรพจน์หัวข้อ “<span class="evn">New Year's Day</span>”</p>
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
				<p>การพูดสุนทรพจน์เป็นภาษาอังกฤษในหัวข้อ “<span class="evn">New Year's Day</span>” โดยการอัดคลิปวิดีโอแล้วอัปโหลดคลิปส่งผ่านเวปไซต์ <a href="https://PathwaySpeechContest.cf">PathwaySpeechContest.cf</a></p>
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
					<center><br><a href="criteria">เกณฑ์การพิจรณาคะแนน</a> | <a href="contact">ช่องทางการติดต่อสอบถาม</a> | <a href="team">คณะผู้ประสานงาน</a> | <a href="FaQ">คำถามที่พบบ่อย</a></center>
				</div>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>