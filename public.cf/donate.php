<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Donate";
	$header_desc = "ร่วมสมทบทุนบริจาคมูลนิธิดวงประทีปในโครงการโครงการอนุบาลชุมชน";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main div.container { overflow: visible; }
			main .form .fill {
				--pages: 3;
				display: block; overflow: hidden;
				transition: var(--time-tst-fast) ease;
			}
			main .form .fill > div {
				transform: translateX(calc((-100% / var(--pages)) * (var(--page) - 1)));
				width: calc((100% + 2rem) * var(--pages));
				display: flex; transition: var(--time-tst-fast) ease;
			}
			main .form .part {
				margin-right: 2rem;
				width: 100%; height: fit-content;
			}
			main .form .part > *:not(:last-child) { margin: 0px 0px 10px; }
			main .form .part-3 { text-align: center; }
			main .form .part-3 > img {
				width: 420px; max-width: 100%; height: auto;
				border-radius: 12.5px;
				box-shadow: 1.25px 1.25px var(--shd-little) var(--fade-black-5);
				object-fit: contain;
			}
			main .form .group.navigation button:last-child { width: 150px; }
			main .form .binfo { display: flex; justify-content: center; }
			main .form .binfo > img {
				margin-right: 12.5px;
				width: 125px; height: 125px;
				border-radius: 7.5px;
				box-shadow: 2.5px 2.5px var(--shd-little) var(--fade-black-4);
				object-fit: contain;
			}
			main .form .binfo > div { display: flex; align-items: center; }
			main .form .binfo > div * { margin: 10px 5px; }
			main .form .binfo i.material-icons {
				transform: translateY(5px);
				width: 24px;
				cursor: help;
			}
			/* main .form .group-inline { display: flex; justify-content: space-between; }
			main .form .group-inline .group { width: 48.75%; } */
			main .form button i.material-icons { transform: translate(0px, 5px); }
			main .form span.info {
				padding: 0px 5px;
				transform: translateY(-5px);
				font-size: 0.75rem; line-height: 17px; color: var(--clr-bs-gray-dark);
				display: flex;
			}
			main .form span.info i.material-icons {
				margin-right: 2.5px;
				width: 17px; height: 17px;
				font-size: 16px;
				display: block;
			}
			main .form #ref_tax { background-color: transparent; }
			main .form #ref_tax:after { transform: translate(0px, -21.25px) scale(1.5); }
			main .form #ref_tax:checked:after { transform: translate(calc(100% + 5px), -21.25px) scale(1.5); }
			main .form .tax > * { margin: 0px 0px 10px; }
			main .form .address { width: 100%; }
			main .form .address td:nth-child(1) {
				padding: 5px 12.5px;
				text-align: right;
			}
			main .form .address td:nth-child(2) {
				padding-right: 12.5px;
				width: 75%;
			}
			main .form div.box {
				width: calc(100% - 5px); height: 125px;
				border-radius: 5px; border: 2.5px dashed var(--clr-bs-gray);
				background-color: var(--clr-gg-grey-300); background-size: contain; background-repeat: no-repeat; background-position: center;
				/* display: flex; justify-content: center; */
				overflow: hidden; transition: var(--time-tst-fast);
			}
			main .form div.box:after {
				margin: auto;
				position: relative; top: -50%; transform: translateY(-62.5%);
				text-align: center; text-shadow: 1.25px 1.25px #FFFA;
				display: block; content: "Drag & Drop your slip here or Browse";
				pointer-events: none;
			}
			main .form div.box input[type="file"] {
				margin: auto;
				width: 100%; height: 100%; transform: translateY(-2.5px);
				opacity: 0%; filter: opacity(0%);
			}
			main .form div.box:focus-within {
				border-color: var(--clr-bs-blue);
				box-shadow: 0px 0px 0px 0.25rem rgb(13 110 253 / 25%);
			}
			/* @media only screen and (max-width: 768px) {
				main .form .binfo + div { flex-wrap: wrap; }
			} */
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				donate.init();
				if (isSafari) {
					$("main span.ripple-effect").remove();
					/* try {
						var actionCourse = document.createElement("script"), thisPage = location.href;
						actionCourse.onload = function() { top.location.assign("googlechromme://navigate?url=" + encodeURIComponent(thisPage)); }
						actionCourse.onerror = function() { location = thisPage; }
						document.head.appendChild(actionCourse); openInBrowser();
						top.location.assign("googlechromme://navigate?url=" + encodeURIComponent(thisPage));
					} catch (ex) {} */ $("main .container > .error").toggle("clip");
				}
			});
			function donation() {
				const cv = { APIurl: "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/api" };
				var sv = { inited: false };
				var initialize = function() {
					if (!sv.inited) {
						sv.inited = true;
						$(window).on("resize", function() { setTimeout(resize_box, 500); }); resize_box();
						setTimeout(function() { $('.form .part-1 [name="contact"]').focus(); }, 500);
						sv.tsignature = Date.now().toString(36);
						$("main .form #ref_tax").on("change", function() {
							this.checked ? $("main .form div.tax").show() : $("main .form div.tax").hide();
							resize_box();
						}); $('main .form input[name="tax:slip"]').on("change", function() { validate_file(false); });
						app.io.confirm("leave");
					}
				};
				var next_step = function() {
					go_on(); return false;
					function go_on() {
						if (verify_info()) {
							sv.reference = crc32(sv.info.contact + sv.tsignature);
							document.querySelector('input[name="reference"]').value = sv.reference;
							toPage(2);
						}
					}
				};
				var verify_info = function() {
					var pass = true, info = {};
					document.querySelectorAll(".form .part-1 [name]").forEach((ef) => { info[ef.name] = ef.value.trim(); });
					if (Object.values(info).includes("")) {
						pass = false; focusfield(Object.keys(info).find(key => info[key] === ""));
						app.ui.notify(1, [1, "Please fill in all the fields"]);
					} else {
						if (!/^(((([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@([a-zA-Z0-9\-_]+\.)+[a-zA-Z]{2,13}|0[689](\d{8}|\d-\d{3}-\d{4}|(-\d{4}){2}))|0[689](\d{8}|\d-\d{3}-\d{4}|(-\d{4}){2}))$/.test(info.contact) || info.contact.length > 255) {
							if (pass) { focusfield("contact"); pass = false; }
							app.ui.notify(1, [2, "Invalid E-mail address format"]);
						} if (info.sender.length <= 0 || info.sender.length > 75) {
							if (pass) { focusfield("sender"); pass = false; }
							app.ui.notify(1, [2, "Invalid donor format.<br><?=$_COOKIE['set_lang']=="th"?"นามที่ท่านใช้ยาวเกิน 75 ตัวอักษร":"Name cannot be longer than 75 characters."?>"]);
						} if (!/^[1-9]\d{0,6}(0|5|9)$/.test(info.amount)) {
							if (pass) { focusfield("amount"); pass = false; }
							app.ui.notify(1, [2, "Invalid amout set.<br><?=$_COOKIE['set_lang']=="th"?"ไม่ต้องใส่เครื่องหมายใด โดยจำนวนเงินลงท้ายด้วย 0 5 หรือ 9 และได้มากสุดไม่เกิน 8 หลัก":"Punctuations are not required. Amout must end in 0 5 or 9 and not more than 8 digits in total."?>"]);
						}
					} sv.info = pass ? info : undefined;
					return pass;
				};
				var verify_addr = function() {
					let fv = document.forms[1]; var address = {
						number: fv["addr:number"].value.trim(),
						tract: fv["addr:tract"].value.trim(),
						village: fv["addr:village"].value.trim(),
						alley: fv["addr:alley"].value.trim(),
						road: fv["addr:road"].value.trim(),
						subdistrict: {
							id: parseInt(fv["addr:subdistrict"].value.trim()),
							name: document.querySelector('.form .part-2 [name="addr:subdistrict"] + input[readonly]').value.trim()
						}, district: {
							id: parseInt(fv["addr:district"].value.trim()),
							name: document.querySelector('.form .part-2 [name="addr:district"] + input[readonly]').value.trim()
						}, province: {
							id: parseInt(fv["addr:province"].value.trim()),
							name: document.querySelector('.form .part-2 [name="addr:province"] + input[readonly]').value.trim()
						}, postcode: parseInt(fv["addr:postcode"].value.trim())
					}; if (!/^[0-9\-()/,ก-๛]+$/.test(address.number)) {
						focusfield("addr:number");
						app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"รูปแบบบ้านเลขที่ไม่ถูกต้อง":"Invalid house number format."?>"]);
						return false;
					} if (!/^\d{0,3}$/.test(address.tract)) {
						focusfield("addr:tract");
						app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"หมู่ใส่ได้แค่ตัวเลขเท่านั้น":"Tract can only be numbers."?>"]);
						return false;
					} if (!/^[0-9A-Za-zก-๛\-()\./ @]*$/.test(address.village)) {
						focusfield("addr:village");
						app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"รูปแบบชื่อหมู่บ้านไม่ถูกต้อง<br>อักขระที่รองรับ อักษรไทยอังกฤษตัวเลขไทยอาราบิก / . - ( ) @":"Invalid village name format.<br>Field only accepts TH EN alphabet, TH arabic number / . - ( ) @ characters."?>"]);
						return false;
					} if (!/^[0-9A-Za-zก-๛\-()\./ ]*$/.test(address.alley)) {
						focusfield("addr:alley");
						app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"รูปแบบซอยไม่ถูกต้อง<br>อักขระที่รองรับ อักษรไทยอังกฤษตัวเลขไทยอาราบิก / . - ( )":"Invalid alley format.<br>Field only accepts TH EN alphabet, TH arabic number / . - ( ) characters."?>"]);
						return false;
					} if (!/^[0-9A-Za-zก-๛\-()\./ ]*$/.test(address.road)) {
						focusfield("addr:road");
						app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"รูปแบบชื่อถนนไม่ถูกต้อง<br>อักขระที่รองรับ อักษรไทยอังกฤษตัวเลขไทยอาราบิก / . - ( )":"Invalid road name format.<br>Field only accepts TH EN alphabet, TH arabic number / . - ( ) characters."?>"]);
						return false;
					} if (!(address.alley + address.road).length) {
						focusfield("addr:road");
						app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"กรุณาใส่ซอยหรือถนน":"Please fill in road name."?>"]);
						return false;
					} if (address.subdistrict.id.toString() == "NaN" || !address.subdistrict.name.length) {
						focusfield("addr:subdistrict", true);
						app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"กรุณาใส่อำเภอ/แขวง":"Please fill in subdistrict name."?>"]);
						return false;
					} if (address.district.id.toString() == "NaN" || !address.district.name.length) {
						focusfield("addr:district", true);
						app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"กรุณาใส่ตำบล/เขต":"Please fill in district name."?>"]);
						return false;
					} if (address.province.id.toString() == "NaN" || !address.province.name.length) {
						focusfield("addr:province", true);
						app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"กรุณาใส่จังหวัด":"Please fill in province name."?>"]);
						return false;
					} if (!/^[1-9]\d{4}$/.test(address.postcode)) {
						focusfield("addr:postcode");
						app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"รูปแบบรหัสไปรษณีย์ไม่ถูกต้อง. กรุณาตรวจสอบ":"Invalid post code.<br>Please recheck."?>"]);
						return false;
					} /* if (!/^$/.test(address.)) {
						if (pass) { focusfield("addr:"); pass = false; }
						app.ui.notify(1, [2, ""]);
					} */ return address;
				};
				var focusfield = function(which, next = false) { $('.form [name="'+which+'"]'+(next ? " + input[readonly]" : "")).focus(); }
				var go_back = function() {
					toPage(1); return false;
				};
				var submit = function() {
					go_on(); return false;
					function go_on() {
						if (!verify_info()) {
							if (parseInt($("main .form .fill > div").css("--page")) == 2) toPage(1);
						} else {
							document.querySelectorAll('.form .part-2 [name]:not([name^="tax:"]):not([name^="addr:"])').forEach((ef) => { sv.info[ef.name] = ef.value.trim(); });
							var pass = true; if (document.querySelector('.form .part-2 [name="tax:reciept"]').checked) {
								var slipFile = validate_file(true); if (!slipFile) {
									if (pass) { focusfield("tax:slip"); pass = false; }
									app.ui.notify(1, [2, "กรุณาเลือกไฟล์ภาพสลิปการโอนเงิน"]);
								} else sv.info.slip = document.querySelector('.form .part-2 [name="tax:slip"]').files[0]; if (pass) {
									var address = verify_addr();
									if (address) sv.info.address = JSON.stringify(address);
									else pass = false;
								}
							} if (pass) {
								$(".form .part:not(.part-3)").attr("disabled", "");
								var tmp_data = new FormData(), send_data = {app: "donate", cmd: "submit", attr: sv.info};
								tmp_data.append("app", "donate"); tmp_data.append("cmd", "submit");
								for (let key in sv.info) {
									tmp_data.append((key == "slip" ? key : "attr["+key+"]"), sv.info[key]);
								} var xhr = new XMLHttpRequest; xhr.open("POST", cv.APIurl, true); xhr.onload = function() {
									var dat = JSON.parse(this.responseText);
									if (dat.success) {
										$("main .form .fill").removeClass("cyan").addClass("blue");
										toPage(3);
										sv.tsignature = sv.info = sv.reference = undefined;
									} else {
										app.ui.notify(1, dat.reason);
										setTimeout(function() { $(".form .part:not(.part-3)").removeAttr("disabled"); }, 1250);
									}
								}; xhr.send(tmp_data);
							}
						}
					}
				};
				var clear_form = function() {
					go_on(); return false;
					function go_on() {
						$(".form .part [name], .form .part input[readonly]").val("");
						$("main .form div.box").removeAttr("style"); $("main .form .tax").hide();
						if (typeof sv.img_link !== "undefined") URL.revokeObjectURL(sv.img_link);
						document.querySelector("main .form #ref_tax").checked = false;
						sv.tsignature = Date.now().toString(36); toPage(1);
						$("main .form .fill").removeClass("blue").addClass("cyan");
						$(".form .part:not(.part-3)").removeAttr("disabled");
					}
				};
				var copyRef = function() {
					go_on(); return false;
					function go_on() {
						if (sv.reference.length == 0) app.ui.notify(1, [2, "Reference empty!"]);
						else {
							var word = document.createElement("textarea");
							word.value = sv.reference; document.body.appendChild(word);
							word.select(); document.execCommand("copy"); document.body.removeChild(word);
							app.ui.notify(1, [0, "Reference copied."]);
						}
					}
				};
				var toPage = function(pageno) { $("main .form .fill > div").css("--page", parseInt(pageno)); resize_box(); }
				var validate_file = function(recheck) {
					var f = document.querySelector('.form .part-2 [name="tax:slip"]').files[0],
						preview = $("main .form div.box"), fname = document.querySelector("main .form div.box + div input[readonly]");
					// if (!recheck && typeof sv.img_link === "string") URL.revokeObjectURL(sv.img_link);
					if (typeof f !== "undefined") {
						let filename = f.name.toLowerCase().split(".");
						if ((["png", "jpg", "jpeg", "heic", "gif"].includes(filename.at(-1))) && (f.size > 0 && f.size < 3072000)) { // 3 MB
							if (!recheck) {
								fname.value = f.name; try { if (!isSafari) { sv.img_link = URL.createObjectURL(f);
								preview.css("background-image", 'url("'+sv.img_link+'")'); } } catch(ex) {}
							} return f;
						} else app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"กรุณาตรวจสอบว่าภาพของคุณเป็นประเภท PNG/JPG/GIF/HEIF และมีขนาดไม่เกิน 3 MB":"Please check if your photo is one of the following format PNG/JPG/GIF/HEIF and its size is less than or equal to 3 MB."?>"]);
					} else {
						fname.value = "<?=$_COOKIE['set_lang']=="th"?"[ยังไม่มี] ---กรุณาเลือกภาพ---":"[BLANK] ---Please choose an image---"?>"; preview.removeAttr("style");
						app.ui.notify(1, [1, "No file selected."]);
					} return false;
				};
				var add_addr = function(addr) {
					let fv = document.forms[1];
					fv["addr:subdistrict"].value = (addr == null ? "" : addr.subdistrictI);
					document.querySelector('.form .part-2 [name="addr:subdistrict"] + input[readonly]').value = (addr == null ? "" : addr.subdistrictN);
					fv["addr:district"].value = (addr == null ? "" : addr.districtI);
					document.querySelector('.form .part-2 [name="addr:district"] + input[readonly]').value = (addr == null ? "" : addr.districtN);
					fv["addr:province"].value = (addr == null ? "" : addr.provinceI);
					document.querySelector('.form .part-2 [name="addr:province"] + input[readonly]').value = (addr == null ? "" : addr.provinceN);
				};
				return {
					init: initialize,
					step: next_step,
					edit: go_back,
					save: submit,
					renew: clear_form,
					copy: copyRef,
					addAddr: add_addr
				};
			} const donate = donation(); delete donation;
			const resize_box = function() { $("main .form .fill").height($("main .form .part-"+$("main .form .fill > div").css("--page")).outerHeight()); }
			function selectArea() {
				fs.address("เลือกที่อยู่ของคุณ (ค้นหา)", donate.addAddr);
			}
		</script>
		<script type="text/javascript" src="/resource/js/extend/php-function.js"></script>
		<script type="text/javascript" src="/resource/js/extend/find-search.js"></script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>Donate</h2>
				<!--div class="message yellow"><?=$_COOKIE['set_lang']=="th"?"ขณะนี้ผู้พัฒนาระบบกำลังปรับปรุงระบบ กรุณาเข้ามาใหม่ภายหลัง<br>การกรอกฟอร์มบริจาคใดๆในช่วงที่ยังมีข้อความนี้จะไม่มีผล":"System developer is upgrading this page. Please come back later.<br>Sending the form below now doesn't counts."?><a data-role="button" class="cyan ripple-click" target="_blank" href="https://bod.in.th/!PSC-donate2" style="float: right;">&nbsp;<?=$_COOKIE['set_lang']=="th"?"ฟอร์มบริจาคชั่วคราว":"Temporary Form"?>&nbsp;</a></div-->
				<!--details class="message gray">
					<summary>ขั้นตอนวิธีการบริจาค</summary>
					<p>___บราๆๆ___ ทำไม ยังไม่เขียน ช่วยแต่งหน่อยก็ดี</p>
				</details-->
				<p><?=$_COOKIE['set_lang']=="th"?"เงินบริจาคทั้งหมดจะถูกนำไปบริจาคแก่มูลนิธิดวงประทีปในโครงการอนุบาลชุมชน":"All donations will be donated to Duangprathip foundation in Society Nursery program."?></p>
				<center class="error message yellow" style="display: none;"><?=$_COOKIE['set_lang']=="th"?"กรุณาใช้ browser อื่นในการกรอกฟอร์ม<br>หรือกรอกใน":"Please use other internet browser to fill in this form.<br>Or fill in "?><a href="https://bod.in.th/!PSC-donate2" target="_blank"><?=$_COOKIE['set_lang']=="th"?"กูเกิ้ลฟอร์ม":"Google Form"?></a>. (<?=$_COOKIE['set_lang']=="th"?"ไม่สามารถขอใบเสร็จได้":"Cannot request receipt"?>)</center>
				<div class="form">
					<section class="fill message cyan" style="height: 0px;">
						<div style="--page:1;">
							<form class="part part-1" show>
								<div class="group">
									<span><?=$_COOKIE['set_lang']=="th"?"ที่อยู่อีเมล":"E-mail address"?></span>
									<input type="text" name="contact" maxlength="255" placeholder="name@domain.tld (หรือ 0925697453)">
								</div>
								<span class="info"><i class="material-icons">information</i><?=$_COOKIE['set_lang']=="th"?"ใช้ในการติดต่อกลับ<br>หากไม่มีอีเมลสามารถกรอกเป็นหมายเลขโทรศัพท์ได้":"Use only for contacting back.<br>If you don't have an e-mail adress, you can fill in your phone number instead."?></span>
								<div class="group">
									<span><?=$_COOKIE['set_lang']=="th"?"ผู้บริจาค":"Donor name"?></span>
									<input type="text" name="sender" maxlength="75" placeholder="นายชัยณัฏฐ์ / คณะผู้จัดกิจกรรม / บริษัท...">
								</div>
								<span class="info"><i class="material-icons">information</i><?=$_COOKIE['set_lang']=="th"?"รวบรวมส่งเป็นรายนามให้ทางมูลนิธิดวงประทีป":"Added to the namelist given to the foundation."?></span>
								<div class="group">
									<span><?=$_COOKIE['set_lang']=="th"?"จำนวนเงิน":"Amount"?></span>
									<input type="number" name="amount" min="10" max="99999999" step="10" placeholder="<?=$_COOKIE['set_lang']=="th"?"เช่น":"Eg."?> 1599">
								</div>
								<span class="info"><i class="material-icons">warning</i><?=$_COOKIE['set_lang']=="th"?"ใส่จำนวนให้เท่ากับที่ท่านจะทำการโอน<br>รวบรวมส่งร่วมกับรายนามให้ทางมูลนิธิดวงประทีป":"Fill the same amount you are going to donate.<br>Added with the namelist given to the foundation."?></span>
								<div class="group split navigation">
									&nbsp;
									<button class="blue ripple-click" onClick="return donate.step()"><?php echo $_COOKIE['set_lang']=="th"?"ถัดไป":"Next"; ?></button>
								</div>
							</form>
							<form class="part part-2" method="post">
								<div class="binfo">
									<img src="resource/images/kbank.png" alt="ธนาคารกสิกรไทย | KBANK">
									<div><div>
										<h2>เลขที่บัญชี 114-3-95610-1</h2>
										<h3>จิณณ์นิชา บุญอยู่ <i class="material-icons" data-title="สาขาเซ็นทรัลรามอินทรา">information</i></h3>
									</div></div>
								</div>
								<div class="group">
									<span>Reference</span>
									<input type="number" name="reference" readonly>
									<button class="gray" onClick="return donate.copy()" data-title="Copy"><i class="material-icons">content_copy</i></button>
								</div>
								<span class="info"><!--i class="material-icons">information</i-->เลขที่อ้างอิงรายการบริจาค</span>
								<div class="group">
									<span><?=$_COOKIE['set_lang']=="th"?"วันเวลาโอน":"Transaction time"?></span>
									<input type="datetime-local" name="when" min="<?=date("Y-m-d\TH:i", time())?>">
								</div>
								<span class="info"><i class="material-icons">information</i><?=$_COOKIE['set_lang']=="th"?"ใส่เพื่อความสะดวกในการตรวจสอบ (ไม่บังคับ)":"Enter for ease of checking (optional)."?></span>
								<div class="group">
									<input type="checkbox" name="tax:reciept" class="switch emphasize" id="ref_tax">
									<label for="ref_tax">&nbsp;<?=$_COOKIE['set_lang']=="th"?"ต้องการใบเสร็จ":"Claim a reciept"?></label>
								</div>
								<div class="tax" style="display: none;">
									<hr><p><?=$_COOKIE['set_lang']=="th"?"เราจำเป็นจะต้องขอหลักฐานและข้อมูลของท่านเพิ่มเติมเพื่อใช้ในการออกใบเสร็จ":"We need to request additional evidence and your information in order to issue a receipt."?></p>
									<h4><?=$_COOKIE['set_lang']=="th"?"ที่อยู่ในการออกใบเสร็จ":"Address to issue a receipt"?></h4>
									<span class="info"><i class="material-icons">warning</i><?=$_COOKIE['set_lang']=="th"?'ใบเสร็จจะถูกออกในนามของ<u>ผู้บริจาค</u>&nbsp;ท่านสามารถกลับไป<a href="javascript:donate.edit()">แก้ไข</a>ได้':'<span>Your reciept will be issued in the name of the <u>donor</u>. You can <a href="javascript:donate.edit()">edit</a> here.</span>'?></span>
									<table class="address"><tbody>
										<tr>
											<td><?=$_COOKIE['set_lang']=="th"?"บ้านเลขที่":"House number"?>*</td>
											<td><input type="text" name="addr:number"></td>
										</tr>
										<tr>
											<td><?=$_COOKIE['set_lang']=="th"?"หมู่ที่":"Tract number"?></td>
											<td><input type="number" name="addr:tract" min="1" step="1"></td>
										</tr>
										<tr>
											<td><?=$_COOKIE['set_lang']=="th"?"หมู่บ้าน":"Village"?></td>
											<td><input type="text" name="addr:village"></td>
										</tr>
										<tr sec="2">
											<td><?=$_COOKIE['set_lang']=="th"?"ซอย":"Alley"?></td>
											<td><input type="text" name="addr:alley"></td>
										</tr>
										<tr sec="2">
											<td><?=$_COOKIE['set_lang']=="th"?"ถนน":"Road"?></td>
											<td><input type="text" name="addr:road"></td>
										</tr>
										<tr sec="1">
											<td><?=$_COOKIE['set_lang']=="th"?"แขวง/ตำบล":"Subdistrict"?>*</td>
											<td><input type="hidden" name="addr:subdistrict"><input type="text" readonly onFocus="selectArea()"></td>
										</tr>
										<tr sec="1">
											<td><?=$_COOKIE['set_lang']=="th"?"เขต/อำเภอ":"District"?>*</td>
											<td><input type="hidden" name="addr:district"><input type="text" readonly onFocus="selectArea()"></td>
										</tr>
										<tr sec="1">
											<td><?=$_COOKIE['set_lang']=="th"?"จังหวัด":"Province"?>*</td>
											<td><input type="hidden" name="addr:province"><input type="text" readonly onFocus="selectArea()"></td>
										</tr>
										<tr>
											<td><?=$_COOKIE['set_lang']=="th"?"รหัสไปรษณีย์":"Postal code"?>*</td>
											<td><input type="number" name="addr:postcode" min="10000" max="99990" step="10"></td>
										</tr>
									</tbody></table>
									<span class="info"><!--i class="material-icons">information</i--><?=$_COOKIE['set_lang']=="th"?"กรณีไม่มีข้อมูลให้เว้นว่างไว้ (ไม่ต้องเติมเครื่องหมายใดๆ)":"Leave blank if empty (no punctuation required)"?></span>
									<div class="box"><input type="file" name="tax:slip" accept=".png, .jpg, .jpeg, .gif, .heic"></div>
									<div class="group">
										<span><?=$_COOKIE['set_lang']=="th"?"ภาพสลิปการโอนเงิน":"Transaction slip image"?></span>
										<input type="text" readonly placeholder="<?=$_COOKIE['set_lang']=="th"?"[ยังไม่มี] ---กรุณาเลือกภาพ---":"[BLANK] ---Please choose an image---"?>">
									</div>
									<span class="info"><i class="material-icons">warning</i><?=$_COOKIE['set_lang']=="th"?"ประเภทไฟล์ PNG, JPG, JPEG, GIF, HEIC และมีขนาดไม่เกิน 3MB เท่านั้น":"Only PNG, JPG, JPEG, GIF, HEIC image typw with no more than 3 MB in file size."?></span>
								</div>
								<div class="group split navigation">
									<button class="white ripple-click" onClick="return donate.edit()" type="reset"><?php echo $_COOKIE['set_lang']=="th"?"ย้อนกลับ":"Back"; ?></button>
									<button class="green ripple-click" onClick="return donate.save()" type="submit"><?php echo $_COOKIE['set_lang']=="th"?"บริจาค":"Donate"; ?></button>
								</div>
							</form>
							<form class="part part-3">
								<img src="resource/images/donate-Thank_you.png" alt="Thank you">
								<center class="message green">การบริจาคเสร็จสมบูรณ์.<br>🍃ขอขอบคุณผู้ร่วมทำบุญที่มีจิตใจเมตตาทุกๆท่านที่ไว้ใจพวกเรา Pathway Speech Contest<hr>จะส่งมอบเงินบริจาคทั้งหมดให้แก่มูลนิธิดวงประทีปในโครงการอนุบาลชุมชน💝<span hidden>อย่าลืมร่วมสนุกเข้าประกวดในโครงการ Pathway Speech Contest ด้วยน้าาา</span></center>
								<button class="blue ripple-click" onClick="return donate.renew()"><?=$_COOKIE['set_lang']=="th"?"บริจาคเพิ่ม":"Donate more"?></button>
							</form>
						</div>
					</section>
					<section class="progress">

					</section>
				</div>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>