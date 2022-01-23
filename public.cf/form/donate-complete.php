<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Form";
	$header_desc = "เพิ่มที่อยู่และสลิปเพื่อขอใบเสร็จการบริจาค";

	$hasRef = (isset($_GET['refNo']) && trim($_GET['refNo']) <> "");
	if ($hasRef) {
		$refID = trim($_GET['refNo']);
		$apiData = json_decode(file_get_contents("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/form/work/load?set=donate&q=$refID", false));
		$isDone = (isset($_GET['complete']) && trim($_GET['complete']) == "success");
	}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
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
			main .form > p { margin: 0px 0px 10px; }
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
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				donate.init(); seek_param();
				<?php if ($hasRef) {
					if (!($apiData -> success)) echo 'app.ui.notify(1, ['.($apiData -> reason)[0].', "'.($apiData -> reason)[1].'"]);';
					if ($isDone) echo 'history.replaceState(null, null, location.pathname);';
				} ?>
			});
			function seek_param() { if (location.hash!="") {
				// Extract hashes
				var hash = {}; location.hash.substring(1, location.hash.length).split("&").forEach((ehs) => {
					let ths = ehs.split("=");
					hash[ths[0]] = ths[1];
				});
				// Let's see
				if (typeof hash.status !== "undefined") switch (parseInt(hash.status) - 900) {
					case 0: app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"ไม่พบข้อมูลของหมายเลขอ้างอิงนี้":"No record found for this reference number."?>"]); break;
					case 1: app.ui.notify(1, [1, "<?=$_COOKIE['set_lang']=="th"?"คุณได้ทำการส่งกหลักฐานพร้อมเพิ่มที่อยู่ไปแล้ว":"You've already send the evidence and added your address."?>"]); break;
					case 2: app.ui.notify(1, [3, "<?=$_COOKIE['set_lang']=="th"?"เกิดข้อผิดพลาด.<br>ไม่มีข้อมูลส่งมา":"There's an error.<br>No information sent."?>"]); break;
					case 5: app.ui.notify(1, [3, "Unable to get record."]); break;
					case 8: app.ui.notify(1, [3, "<?=$_COOKIE['set_lang']=="th"?"เกิดข้อผิดพลาด.<br>ไม่สามารถจัดการไฟล์ได้.":"There's an error.<br>Your file cannot be processed."?>"]); break;
					case 9: app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"ไฟล์ของคุณไม่ผ่านตามเกณฑ์ที่กำหนด.<br>กรุณาเลือกไฟล์ใหม่.":"Your file doesn't pass our criteria.<br>Please select a new file."?>"]); break;
					case 10: app.ui.notify(1, [3, "<?=$_COOKIE['set_lang']=="th"?"เกิดข้อผิดพลาด.<br>กรุณาลองใหม่อีกครั้ง.":"There's an error.<br>Please try again."?>"]); break;
				} history.replaceState(null, null, location.pathname);
			} }
			function setReference() {
				go_on(); return false;
				function go_on() {
					var reference = document.querySelector('main form [name="reference"]').value.trim();
					location.assign("/donate/" + reference + "/edit");
				}
			}
			function donation() {
				var sv = { inited: false };
				var initialize = function() {
					if (!sv.inited) {
						sv.inited = true;
						setTimeout(function() { $('.form [name="addr:number"]').focus(); }, 500);
						$('main .form input[name="tax:slip"]').on("change", function() { validate_file(false); });
						<?php if ($hasRef && $apiData -> info -> status == 903) echo 'app.io.confirm("leave");'; ?>
					}
				};
				var verify_addr = function() {
					let fv = document.forms[0]; var address = {
						number: fv["addr:number"].value.trim(),
						tract: fv["addr:tract"].value.trim(),
						village: fv["addr:village"].value.trim(),
						alley: fv["addr:alley"].value.trim(),
						road: fv["addr:road"].value.trim(),
						subdistrict: {
							id: parseInt(fv["addr:subdistrict"].value.trim()),
							name: document.querySelector('.form [name="addr:subdistrict"] + input[readonly]').value.trim()
						}, district: {
							id: parseInt(fv["addr:district"].value.trim()),
							name: document.querySelector('.form [name="addr:district"] + input[readonly]').value.trim()
						}, province: {
							id: parseInt(fv["addr:province"].value.trim()),
							name: document.querySelector('.form [name="addr:province"] + input[readonly]').value.trim()
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
				var submit = function() {
					go_on(); return false;
					function go_on() {
						var pass = true;
						var slipFile = validate_file(true); if (!slipFile) {
							if (pass) { focusfield("tax:slip"); pass = false; }
							app.ui.notify(1, [2, "กรุณาเลือกไฟล์ภาพสลิปการโอนเงิน"]);
						} if (pass) {
							var address = verify_addr();
							if (address) {
								document.querySelector('.form [name="address"]').value = JSON.stringify(address);
								$('.form [name^="addr:"]').removeAttr("name");
								// Validated
								$(window).off("beforeunload");
								document.querySelector("main form").submit();
							}
						}
					}
				};
				var validate_file = function(recheck) {
					var f = document.querySelector('.form [name="tax:slip"]').files[0],
						preview = $("main .form div.box"), fname = document.querySelector("main .form div.box + div input[readonly]");
					// if (!recheck && typeof sv.img_link === "string") URL.revokeObjectURL(sv.img_link);
					if (typeof f !== "undefined") {
						let filename = f.name.toLowerCase().split(".");
						if ((["png", "jpg", "jpeg", "heic", "gif"].includes(filename.at(-1))) && (f.size > 0 && f.size < 3072000)) { // 3 MB
							if (!recheck) {
								fname.value = f.name; try { if (!isSafari) { sv.img_link = URL.createObjectURL(f);
								preview.css("background-image", 'url("'+sv.img_link+'")'); } } catch(ex) {}
							} return true;
						} else app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"กรุณาตรวจสอบว่าภาพของคุณเป็นประเภท PNG/JPG/GIF/HEIF และมีขนาดไม่เกิน 3 MB":"Please check if your photo is one of the following format PNG/JPG/GIF/HEIF and its size is less than or equal to 3 MB."?>"]);
					} else {
						fname.value = "<?=$_COOKIE['set_lang']=="th"?"[ยังไม่มี] ---กรุณาเลือกภาพ---":"[BLANK] ---Please choose an image---"?>"; preview.removeAttr("style");
						app.ui.notify(1, [1, "No file selected."]);
					} return false;
				};
				var add_addr = function(addr) {
					let fv = document.forms[0];
					fv["addr:subdistrict"].value = (addr == null ? "" : addr.subdistrictI);
					document.querySelector('.form [name="addr:subdistrict"] + input[readonly]').value = (addr == null ? "" : addr.subdistrictN);
					fv["addr:district"].value = (addr == null ? "" : addr.districtI);
					document.querySelector('.form [name="addr:district"] + input[readonly]').value = (addr == null ? "" : addr.districtN);
					fv["addr:province"].value = (addr == null ? "" : addr.provinceI);
					document.querySelector('.form [name="addr:province"] + input[readonly]').value = (addr == null ? "" : addr.provinceN);
				};
				return {
					init: initialize,
					save: submit,
					addAddr: add_addr
				};
			} const donate = donation(); delete donation;
			function selectArea() {
				fs.address("เลือกที่อยู่ของคุณ (ค้นหา)", donate.addAddr);
			}
		</script>
		<script type="text/javascript" src="/resource/js/extend/find-search.js"></script>
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2><?=$_COOKIE['set_lang']=="th"?"เพิ่มที่อยู่และสลิปเพื่อขอใบเสร็จการบริจาค":"Add address & slip to request reciept."?></h2>
				<?php if (!$hasRef) { ?>
				<form class="form inline">
					<span><?=$_COOKIE['set_lang']=="th"?"เลขที่อ้างอิง":"Reference no."?></span>
					<input type="number" name="reference" min="10000000" max="9999999999" pattern="\d{8,10}" required>
					<button class="blue" onClick="return setReference()"><?=$_COOKIE['set_lang']=="th"?"ค้นหา":"Search"?></button>
				</form>
				<?php
					} else if ($apiData -> success) {
						if ($apiData -> info -> status <> 903) {
							if ($isDone) echo '<center class="message green">'.($_COOKIE['set_lang']=="th"?"ระบบทำการบันทึกข้อมูลที่อยู่และเก็บหลักฐานการโอนเงินเรียบร้อยแล้ว":"Adress and transaction evidence recoreded successfully.").'</center>';
							else echo '<center class="message '.($apiData -> info -> msg[0]).'">'.($apiData -> info -> msg[1]).'</center>';
						} else {
				?>
				<form method="post" class="form" enctype="multipart/form-data" action="https://inf.bodin.ac.th/e/Pathway-Speech-Contest/form/work/save?remote&set=donate">
					<div class="group">
						<span><?=$_COOKIE['set_lang']=="th"?"ผู้บริจาค":"Donor name"?></span>
						<input type="text" maxlength="75" value="<?=($apiData -> info -> data -> name)?>" readonly>
					</div>
					<div class="group">
						<span><?=$_COOKIE['set_lang']=="th"?"จำนวนเงิน":"Amount"?></span>
						<input type="number" maxlength="7" value="<?=($apiData -> info -> data -> amount)?>" readonly>
					</div>
					<p><i><?=$_COOKIE['set_lang']=="th"?"เลขที่อ้างอิงรายการบริจาค":"Reference no."?> <?=$refID?></i></p>
					<div class="tax">
						<hr><p><?=$_COOKIE['set_lang']=="th"?"กรุณาเพิ่มหลักฐานและกรอกข้อมูลของท่านเพิ่มเติมเพื่อออกใบเสร็จ":"Please fill in your information & place an evidence in order to issue a receipt."?></p>
						<h4><?=$_COOKIE['set_lang']=="th"?"ที่อยู่ในการออกใบเสร็จ":"Address to issue a receipt"?></h4>
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
					<div class="action">
						<input type="hidden" name="q" value="<?=$refID?>">
						<input type="hidden" name="address">
						<button class="blue full-x" onClick="return donate.save()"><?=$_COOKIE['set_lang']=="th"?"บันทึกข้อมูล":"Update information"?></button>
					</div>
				</form>
				<?php } } ?>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>