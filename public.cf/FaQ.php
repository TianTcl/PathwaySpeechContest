<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "FaQs";
	$header_desc = "Frequently asked Questions - คำถามที่พบบ่อย";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main .wrapper details {
				padding: 5px 10px;
				transition: margin var(--time-tst-medium) ease, background-color var(--time-tst-fast) ease, box-shadow var(--time-tst-fast) ease;
			}
			main .wrapper details:not([open]) { border-bottom: 1.25px solid var(--clr-psc-skin-high); }
			main .wrapper details:first-child:not([open]), main .wrapper details[open] + details:not([open]) { border-top: 1.25px solid var(--clr-psc-skin-high); }
			main .wrapper details[open] {
				margin: 12.5px 0px;
				background-color: var(--clr-psc-skin-shiny);
				border-radius: 7.5px; box-shadow: 1.25px 1.25px var(--shd-little) var(--fade-black-7);
			}
			main .wrapper details > summary {
				height: 31px; line-height: 31px;
				cursor: help; transition: var(--time-tst-xfast);
			}
			/* main .wrapper details > summary:hover { background-color: var(--clr-psc-skin-low); } */
			main .wrapper details > summary::marker { cursor: pointer; }
			main .wrapper details > *:not(summary) { margin: 0px 0px 10px; }
			main .wrapper details > *:not(summary):nth-child(2) { margin: 10px 0px; }
			main .wrapper details > *:not(summary):last-child { margin: 0px; }
			@media only screen and (max-width: 768px) {
				main .wrapper details > summary { height: 22px; line-height: 22px; }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				if (location.hash.length > 1 && document.querySelector("main .wrapper details#"+location.hash.substr(1)) != null) {
					if (location.hash.substr(1) == "special-workshop") location = "workshop/feed";
					document.querySelector("main .wrapper details#"+location.hash.substr(1)).open = true;
					$("html, body").animate({ scrollTop: $("html, body").scrollTop() + 75 }, 25);
				}
				$("main .wrapper details > summary").on("click", function() { addHash(this.parentNode); });
				Facebook_chat();
			});
			function addHash(me) {
				setTimeout(function() {
					if (me.open) history.replaceState(null, null, location.pathname+"#"+me.id);
					else if (document.querySelector("main .wrapper details[open]") == null) history.replaceState(null, null, location.pathname);
				}, 25);
			}
			function Facebook_chat() {
				var chatbox = document.getElementById('fb-customer-chat');
				chatbox.setAttribute("page_id", "111070124738792");
				chatbox.setAttribute("attribution", "biz_inbox");
				window.fbAsyncInit = function() { FB.init({ xfbml : true, version : 'v12.0' }); };
				(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
			}
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>FaQ - คำถามที่พบบ่อย</h2>
				<div class="wrapper">
					<details id="cant-send-form">
						<summary>ส่งฟอร์มเข้าสู่ระบบ ลงทะเบียน หรือบริจาคเงินไม่ได้</summary>
						<p>กรุณาลองเปิดหน้าฟอร์มนั้นใน internet browser หลัก (เช่น Chrome, Edge, Safari, Mozilla Firefox, Opera) ก่อน (ไม่ใช่การเปิดผ่านการแตะลิงก์บนข้อความใน LINE, direct message, messenger หรือเฟซบุ๊ค)</p>
						<p>หากทำตามวิธีการดังกล่าวแล้วยังไม่ได้ กรุณาลองเช็คอุปกรณ์ หากเป็น Android ต่ำกว่า v.7 (Nougat) หรือเปิดผ่าน Safari บน iPhone อาจมีปัญหาได้ ให้ลองเปลี่ยนอุปกรณ์ที่ใช้ในการกรอกฟอร์ม</p>
						<p>หากไม่สามารถส่งฟอร์มได้จริงๆ ให้จับภาพหน้าจอ (screenshot | capture) ให้ครบถ้วนทุกข้อมูลแลพส่งมาให้ทางเราลงข้อมูลให้</p>
					</details>
					<details id="cant-type-school">
						<summary>พิมพ์ชื่อโรงเรียนในฟอร์มสมัครไม่ได้</summary>
						<p>กรณี<b>มีกล่องค้นหา</b>ขึ้นให้พิมพ์ชื่อสถานศึกษาเป็น<u>ภาษาไทย</u> (ไม่ต้องใส่คำว่าโรงเรียน)</p>
						<p>กรณี<b>ไม่มีกล่องค้นหา</b>ขึ้นให้ลองแตะบริเวณอื่นก่อนแล้วแตะช่องโรงเรียนอีกรอบ</p>
						<p>หากยังไม่ได้ให้ลองทำตามขั้นตอนใน<a href="#cant-send-form">คำแนะนำเรื่องการส่งฟอร์ม</a>ก่อน และถ้าหากไม่สามารถกรอกได้จริงๆ ให้จับภาพหน้าจอ (screenshot | capture) และพิมพ์ชื่อสถานศึกษามาให้ทางเราลงข้อมูลให้</p>
					</details>
					<!--details id="special-workshop">
						<summary>Special Event - Workshop</summary>
						<p>📍พบกับ workshop จากแขกรับเชิญสุดพิเศษโดย T. Timothy Hutto อาจารย์จาก English Program ของโรงเรียนสามเสนวิทยาลัย และรุ่นพี่ในวันเสาร์ที่ 11 ธันวาคมนี้ เวลา 17.00 - 18.30 น.🍃</p>
						<p>หากสนใจเข้าร่วม workshop สามารถเข้าร่วมผ่านทาง <a href="https://bod.in.th/!PSC-line" target="_blank">openchat</a> ที่นี่</p>
						<p>เข้าร่วมการประชุมได้ที่ <a href="https://inf.bodin.ac.th/go?url=htts%3A%2F%2Fmeet.google.com%2Fgrt-zzzr-fkk" target="_blank">meet.google.com/grt-zzzr-fkk</a> หรือบันทึกใส่ <a href="https://inf.bodin.ac.th/go?url=https%3A%2F%2Fcalendar.google.com%2Fevent%3Faction%3DTEMPLATE%26amp%3Btmeid%3DMG42cWF1bzBrMzBycnF2Z2NvNjA5bjVoa2MgdGlhbnRjbEBib2Rpbi5hYy50aA%26amp%3Btmsrc%3Dtiantcl%2540bodin.ac.th" target="_blank">Google calender</a> ไว้</p>
						<p>ท่านสามารถ<a href="#joining-workshop-meet">ศึกษาวิธีการเข้าการประชุม</a>ได้ที่นี่</p>
					</details-->
					<details id="joining-workshop-meet">
						<summary>วิธีการเข้า Video Conference ของกิจกรรม Workshop</summary>
						<p>หากใช้คอมหรือโน้ตบุ๊คสามารถแตะลิงก์เข้าได้เลย</p>
						<p>หากใช้โทรศัพท์หรือแท็บเลท (รวมถึง iPad) มีแนวทางดังนี้</p>
						<ul>
							<li>นำลิงก์หรือโค้ดไปใส่ในแอป Google Meet หรือใน Gmail แถบการประชุม (Conference / Meeting)</li>
							<li>คัดลอกลิงก์ไปใส่ใน internet browser (chrome, safari)<br>หากวิธีนี้เด้งไปที่ playstore หรือ appstore ให้กดที่ปุ่มสามจุด ⋮ บริเวณมุมบนขวาแล้วเลือกขอหน้าจอเดสค์ทอป (Request desktop site) ก่อน แล้วค่อยวางลิงก์</li>
						</ul>
						<p>หากมีปัญหาไม่สามารถเข้าร่วมได้จริงๆหรือทำตามขั้นตอนด้านบนแล้วยังไมได้ กรุณาติดต่อมาที่เรา</p>
					</details>
					<details id="register-homeschool">
						<summary>วิธีการสมัครสำหรับนักเรียน Homeschool</summary>
						<p>ในช่องโรงเรียน ให้กรอกว่า "จัดการศึกษาขั้นพื้นฐานโดยครอบครัว (โอมสคูล)"</p>
						<p>หากค้นหาไม่เจอ สามารถ<a href="#school-names">ดูชื่อโรงเรียนที่พบบ่อย</a>ได้</p>
					</details>
					<details id="not-top-get-cert">
						<summary>หากไม่ติด 3 อันดับแรกของกลุ่ม จะได้รับประกาศนียบัตรไหม</summary>
						<p>ทุกคนที่ส่งวิดีโอสุนทรพจน์จะได้รับประกาศนียบัตรเข้าร่วม<br>และสำหรับผู้ที่ติด 3 อันดับแรกของกลุ่มการจัดลำดับจะได้ประกาศนียบัตรบอกลำดับที่อีกฉบับ</p>
					</details>
					<details id="when-to-claim-cert">
						<summary>จะได้รับประกาศนียบัตรเมื่อไหร่</summary>
						<p>ประกาศนียบัตรของช่วงที่สองจะสามารถดาวน์โหลดผ่าน<a href="login#next=my-certificate">ระบบ(เว็บแอปพลิเคชัน)</a>ได้</p>
						<p>ตั้งแต่วันอาทิตย์ที่ 20 พฤศจิกายน พ.ศ. 2565</p>
						<p>โดยผู้สมัคร จะได้รับอีเมลแจ้งเมื่อมีการประกาศผลแล้ว<p>
					</details>
					<details id="cert-timeout">
						<summary>ดาวน์โหลดประกาศนียบัตรไม่ทัน</summary>
						<p><strike>สามารถติดต่อมาขอประกาศนียบัตรย้อนหลังทางอีเมลได้ โดยแจ้ง ID และอีเมลที่ใช้สมัคร</strike></p>
						<p>ขณะนี้ระบบเปิดให้<a href="login#next=my-certificate">ดาวน์โหลดประกาศนียบัตรย้อนหลัง</a>ได้แล้ว</p>
					</details>
					<details id="result-announce">
						<summary>มีการประกาศผลอย่างไรบ้าง</summary>
						<p>จะมีการประกาศผลเป็นสองส่วน<p>
						<ul>
							<li>ส่วนแรก จะประกาศ <a href="post/">3 ลำดับที่แรก</a>ของทุกกลุ่มการจัดลำดับ</li>
							<li>ส่วนที่สอง จะประกาศผลคะแนนแยกตามเกณฑ์ใน<a href="login#next=view-score">ระบบ(เว็บแอปพลิเคชัน)</a> (ส่วนตัว)</li>
						</ul>
						<p>โดยผู้สมัคร จะได้รับอีเมลแจ้งเมื่อมีการประกาศผลแล้ว<p>
					</details>
					<details id="school-names">
						<summary>หาชื่อโรงเรียนไม่เจอ</summary>
						<p>รายชื่อโรงเรียนที่พบบ่อย</p>
						<div class="table"><table><thead><tr>
							<th>ชื่อทางการภาษาไทย (ในระบบ)</th>
							<th>ชื่อทางการภาษาอังกฤษ</th>
							<th>ชื่อที่รู้จัก/ชื่ออื่นๆ</th>
						</tr></thead><tbody>
							<tr>
								<td>จัดการศึกษาขั้นพื้นฐานโดยครอบครัว</td>
								<td>Homeschool</td>
								<td>โอมสคูล</td>
							</tr>
							<tr>
								<td>บีคอนเฮาส์แย้มสอาดรังสิต</td>
								<td>Beaconhouse Yamsaard Rangsit</td>
								<td></td>
							</tr>
							<!--tr>
								<td>The Newton Sixth Form School</td>
								<td>The Newton Sixth Form School</td>
								<td>Newton Sixth Form</td>
							</tr-->
							<tr>
								<td>สาธิตนานาชาติ มหาวิทยาลัยมหิดล</td>
								<td>Mahidol Univesity International Demonstration School</td>
								<td>MUIDS</td>
							</tr>
							<tr>
								<td>นานาชาติเอกมัย</td>
								<td>Ekamai International School</td>
								<td>EIS</td>
							</tr>
							<tr>
								<td>กรุงเทพคริสเตียนวิทยาลัย</td>
								<td>Bangkok Christian College</td>
								<td>กรุงเทพคริสเตียน</td>
							</tr>
							<tr>
								<td>สาธิตสถาบันการจัดการปัญญาภิวัฒน์</td>
								<td>Panyapiwat Institute of Management Demonstration School</td>
								<td>สาธิต PIM</td>
							</tr>
						</tbody></table></div>
					</details>
					<details id="how-to-submit">
						<summary>ข้อกำหนดการส่งไฟล์คลิปวิดีโอสุนทรพจน์</summary>
						<p>หากไฟล์มีขนาดไม่เกิน 25MB ให้ทำการส่งผ่าน<a href="login#next=speech-video">ระบบเว็บแอปพลิเคชัน</a><br>หากไฟล์มีขนาดไม่เกิน 1GB ให้ทำการส่งผ่าน Google Forms (โดยเข้าผ่านหน้าส่งผลงาน)<br>หากมีปัญหาเกิดขึ้นจึงสามารถส่งทางอีเมลได้(และต้องส่งเป็นไฟล์หรือผ่าน Google Drive (ที่แชร์ให้ผู้รับเปิดได้แล้ว) เท่านั้น ห้ามส่งเป็นลิงก์ Youtube หรือลิงก์อื่นเด็ดขาด) แต่ต้องระบุสาเหตุ</p>
						<p>หมายเหตุ: หากไม่ส่งผานช่องทางตามเงื่อนไขที่กำหนดมาข้างต้นอาจต้องส่งใหม่ให้ถูกต้อง หรือต้องตอบข้อซักถาม ที่ได้รับจากการตอบกลับของเจ้าหน้าที่</p>
						<p>หากผู้ได้ประสงค์จะลดขนาดไฟล์วิดีโอของตนเองสามารถศึกษา<a href="#reduce-video-filesize">วิธีการทำโดยไม่เสียคุณภาพ</a>ได้</p>
					</details>
					<details id="reduce-video-filesize">
						<summary>วิธีการลดขนาดไฟล์วิดีโอโดยไม่เสียคุณภาพ</summary>
						<ol>
							<li>ติดตั้งแอปพลิเคชัน <a href="https://inf.bodin.ac.th/go?url=https%3A%2F%2Fhandbrake.fr%2Fdownloads.php" target="_blank">Handbrake</a> (สำหรับคนที่ไม่มี)</li>
							<li>เปิดโปรแกรม</li>
							<li>เลือกไฟล์วิดีโอที่ต้องการลดขนาด</li>
							<li>ศึกษาวิธีการตั้งค่าจาก<a href="/go?url=https%3A%2F%2Fyoutu.be%2FWgZq6Sakcog%3Ft%3D190" target="_blank">คลิปวิดีโอ</a> หรือนำเข้า<a href="/resource/file/handbrake-config.json" download="reduce-filesize-sameRes.json">การตั้งค่าจากไฟล์</a></li>
							<li>กดปุ่ม "Start Encode" ด้านบนแล้วรอจนไฟล์แปลงเสร็จ</li>
							<li>นำไฟล์ที่ได้ส่งมาในระบบ</li>
						</ol>
					</details>
					<!--details id="">
						<summary></summary>
						<p></p>
					</details-->
				</div><br>
				<div class="contact form inline">
					<span>กรณีระบบมีปัญหา ต้องการแจ้งปัญหาหรือสอบถาม กรุณา</span>
					<a role="button" class="black" href="https://inf.bodin.ac.th/go?url=mailto%3APathwaySpeechContest@mail.TianTcl.net%3Fbcc%3DPathway.SpeechContest@gmail.com%3Fsubject%3DPSC%20Webapplication" target="_blank">ติดต่อผู้ดูแลระบบ</a>
				</div>
			</div>
		</main>
		<div id="fb-root"></div><div id="fb-customer-chat" class="fb-customerchat"></div>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>