<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Donation list";
	$header_desc = "ตรวจสอบและจัดการสถานะรายการบริจาค";

	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("Location: new-password$my_url");
	$permitted = has_perm("money"); if ($permitted) {

	}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main .wrapper {
				--box-border: 1.25px solid var(--clr-bs-gray-dark);
				display: flex; flex-direction: column;
			}
			main .wrapper div.head {
				padding: 2.5px 12.5px;
				border: var(--box-border); border-radius: 7.5px 7.5px 0px 0px;
				background-color: var(--clr-pp-grey-400);
				overflow: hidden; align-items: center;
			}
			main .wrapper div.head select { background-color: var(--clr-pp-grey-200); }
			main .wrapper div.body {
				max-height: 640px;
				border-top: none; border-bottom: var(--box-border); border-left: var(--box-border); border-right: var(--box-border);
				border-radius: 0px 0px 7.5px 7.5px;
				background-color: var(--clr-gg-grey-300);
				overflow: auto;
			}
			main .wrapper div.body tr > * {
				padding: 5px;
				border-left: none; border-right: none;
			}
			main .wrapper div.body tr th:nth-child(2) { text-align: left; }
			main .wrapper div.body tr td { transition: var(--time-tst-fast); }
			main .wrapper div.body tr:hover td { background-color: var(--fade-black-7); }
			main .wrapper div.body tr td:nth-child(1) { font-family: "IBM Plex Sans Thai", monospace; }
			main .wrapper div.body tr td:nth-child(3) > span[data-pad] { display: inline-block; }
			main .wrapper div.body tr td:nth-child(3) > span[data-pad]:before {
				color: transparent;
				content: attr(data-pad);
			}
			main .wrapper div.body tr td:nth-child(4) { font-family: "IBM Plex Sans Thai", "Sarabun", sans-serif; }
			main .wrapper div.body tr td:not(:nth-child(2)) { text-align: center; }
			main .wrapper div.body tr td:nth-child(4) > span[data-title] { color: var(--clr-pp-red-900); }
			main .wrapper div.body tbody tr[onClick^="don.open("] { cursor: pointer; }
			@media only screen and (max-width: 768px) {
				main .wrapper div.head { padding: 2.5px 7.5px; }
			}
		</style>
		<style type="text/css" for="lb-info">
			.don-info {
				padding: 0px 5px;
				font-size: 18.75px;
			}
			.don-info div.slip {
				--shadow-eff: ;
				margin: 0px auto 15px;
				width: 360px; height: 360px;
				border-radius: 5px; border: 1px solid var(--clr-bs-gray);
				box-shadow: 0px -0.5px var(--shd-big) var(--fade-black-6) inset;
				background-size: contain; background-repeat: no-repeat; background-position: center;
				overflow: hidden; transition: var(--time-tst-fast);
			}
			.don-info div.slip:hover { box-shadow: none; }
			/* .don-info div.slip > img {
				width: 100%; height: 100%;
				object-fit: contain;
			} */
			.don-info div.slip > span {
				width: 100%; height: 100%;
				display: block;
			}
			.don-info div.slip > a {
				margin: auto;
				left: 50%; transform: translate(-50%, calc(-100% - 7.5px));
				text-shadow: 0.25px 0.25px var(--fade-black-3);
				opacity: 0%; filter: opacity(0);
				display: inline-block; transition: calc(var(--time-tst-xfast) * 2 / 3) ease-in;
				/* visibility: hidden; */ pointer-events: none;
			}
			.don-info div.slip:hover > a {
				opacity: 100%; filter: opacity(1);
				/* visibility: visible; */ pointer-events: auto;
			}
			@media only screen and (max-width: 768px) {
				.don-info { font-size: 12.5px; }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				don.init();
			});
			function donfx() {
				const cv = { APIurl: "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/override", myID: parseInt("<?=$_SESSION['evt2']['user']?>") };
				var sv = { inited: false, ID: null };
				var initialize = function() {
					if (!sv.inited) {
						sv.inited = true;
						$('main .wrapper select[name="date"]').on("change", () => don.load());
						don.refresh();
					} else app.ui.notify(1, [1, "Re-initializing not allowed"]);
				};
				var getMonth = function() {
					$.post(cv.APIurl, {app: "donate", cmd: "list", attr: "date"}, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							$("main .wrapper").removeAttr("disabled");
							var opts = $('main .wrapper select[name="date"]');
							dat.info.forEach(em => opts.append('<option value="'+em.month+'">'+em.month.replace("-", "/")+' ['+em.trns+']</option>'));
						} else {
							app.ui.notify(1, dat.reason);
							$("main .wrapper").attr("disabled", "");
						}
					});
				};
				var getList = function() {
					var q = document.querySelector('main .wrapper select[name="date"]').value.trim();
					if (q.length) $.post(cv.APIurl+"?month="+q, {app: "donate", cmd: "list", attr: "trns"}, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							var target = document.querySelector("main .wrapper div.body tbody"); target.innerHTML = "";
							var empty = $("main .wrapper div.body center");
							if (dat.info.length) {
								empty.hide();
								dat.info.forEach(et => {
									let amount = '<span data-pad="'+"0".repeat(5-et.amount.length)+'">'+et.amount+'</span>';
									target.innerHTML += '<tr onClick="don.open(this)" data-ref="'+et.no+'"><td>'+et.no+'</td><td>'+et.name+'</td><td>'+amount+'</td><td><span'+(parseInt(et.isReal)?"":' data-title="เวลาที่บันทึก"')+'>'+et.trns+'</span></td><td>'+status2text(et.state)+'</td></tr>';
								});
							} else empty.show();
						} else app.ui.notify(1, dat.reason);
					}); else app.ui.notify(1, [1, "Please select a valid month"]);
				};
				var status2text = status => (status == "W" ? "<?=$_COOKIE['set_lang']=="th"?"รอการยืนยัน":"Not confirmed"?>" : (status == "Y" ? "<?=$_COOKIE['set_lang']=="th"?"ได้รับแล้ว":"Received"?>" : "<?=$_COOKIE['set_lang']=="th"?"ไม่ได้รับ":"False transaction"?>"));
				var loadDonation = function(me) {
					var refID = me.getAttribute("data-ref").trim();
					if (typeof refID === "undefined" || !refID.length) app.ui.notify(1, [2, "Invalid donation"]);
					else {
						var donor = me.children[1].innerText.trim();
						$.post(cv.APIurl, {app: "donate", cmd: "load", attr: refID}, function(res, hsc) {
							var dat = JSON.parse(res);
							if (dat.success) {
								var view = '<div class="don-info">', hasSlip = dat.info.file != null;
								if (ppa.getCookie("set_lang") == "th") view += "<p>บริจากจำนวน "+dat.info.amount.toString()+".- บาท "+(dat.info.ttype ? "โอน" : "บันทึก")+"เมื่อ "+dat.info.time+"</p>";
								else view += "<p>Donated "+dat.info.amount.toString()+".- Baht. "+(dat.info.ttype ? "Transact" : "Record")+" at "+dat.info.time+".</p>";
								// if (hasSlip) view += '<div class="slip"><img src="https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/upload/slip/'+dat.info.time.substr(0, 10).replaceAll("-", "_")+"-"+refID+'.'+dat.info.file+'" data-dark="false" alt="Slip" draggable="false"><a onClick="popImg(this)" href="javascript:void(0)" draggable="false" data-title="<?=$_COOKIE['set_lang']=="th"?"เปิดบนหน้าใหม่":"Open in larger window"?>"><i class="material-icons">open_in_browser</i></a></div>';
								if (hasSlip) view += '<div class="slip"><span></span><a onClick="don.popImg()" href="javascript:void(0)" draggable="false" data-title="<?=$_COOKIE['set_lang']=="th"?"เปิดบนหน้าใหม่":"Open in larger window"?>"><i class="material-icons">open_in_browser</i></a></div>';
								view += '<div class="form"><div class="group"><span><?=$_COOKIE['set_lang']=="th"?"ยืนยัน":"Confirm"?></span><select><option value="W"><?=$_COOKIE['set_lang']=="th"?"ภายหลัง":"Later"?></option><option value="Y"><?=$_COOKIE['set_lang']=="th"?"ว่าได้รับ":"Received"?></option><option value="N"><?=$_COOKIE['set_lang']=="th"?"ไม่มีรายการ":"No transaction"?></option></select><button onClick="don.save()" class="blue"><?=$_COOKIE['set_lang']=="th"?"บันทึก":"Save"?></button></div></div></div>';
								app.ui.lightbox.open("top", {title: "<?=$_COOKIE['set_lang']=="th"?"รายการบริจาคของ":"Donation of"?> "+donor, allowclose: false, autoclose: 180000, html: view});
								if (hasSlip) {
									var imgURL = 'https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/upload/slip/'+dat.info.time.substr(0, 10).replaceAll("-", "_")+"-"+refID+'.'+dat.info.file;
									sv.imgURL = imgURL;
									if (!isSafari) fetchFile(imgURL).then(function(blobObj) {
										imgURL = URL.createObjectURL(blobObj);
										$(".don-info div.slip").css("background-image", "url('"+imgURL+"')");
										setTimeout(function() { URL.revokeObjectURL(imgURL); }, 60000);
									}); else $(".don-info div.slip").css("background-image", "url('"+imgURL+"')");
								} document.querySelector(".don-info select").value = dat.info.state;
								sv.ID = dat.info.encID; sv.state = dat.info.state; sv.replace = refID;
							} else app.ui.notify(1, dat.reason);
						});
					}
				};
				var update = function() {
					if (sv.ID == null || sv.state == null || sv.replace == null) {
						app.ui.notify(1, [1, "There's an error."]);
						closeBox();
					} else {
						var newval = $(".don-info select").val();
						if (sv.state == newval) closeBox();
						else $.post(cv.APIurl, {app: "donate", cmd: "set", attr: [sv.ID, newval], remote: cv.myID}, function(res, hsc) {
							var dat = JSON.parse(res);
							if (dat.success) {
								app.ui.notify(1, [0, "Status updated."]);
								document.querySelector('main .wrapper div.body tbody tr[data-ref="'+sv.replace+'"] td:nth-child(5)').innerHTML = status2text(newval);
								closeBox();
							} else app.ui.notify(1, dat.reason);
						});
					}
					function closeBox() {
						app.ui.lightbox.close();
						sv.ID = null; sv.state = null; sv.replace = null; sv.imgURL = null;
					}
				}
				var fetchFile = function(url) { return fetch(url, { mode: "no-cors" }).then(function(response) { return response.blob(); }); }
				var openImg = function() {
					if (sv.imgURL.length) {
						/* $.post(sv.imgURL, {viewer: 1}, function(res, hsc) {
							var blob = new Blob([res], {type: "text/html"});
							var tmpURL = URL.createObjectURL(blob);
							const tab = window.open(tmpURL, "_blank", "noreferrer,left=100,top=100,width=640,height=640");
							setTimeout(function() { URL.revokeObjectURL(tmpURL); }, 180000);
						}); */ const tab = window.open(sv.imgURL, "_blank", "noreferrer,left=100,top=100,width=640,height=640");
						/* var tmpHTML = '<body onLoad="location=\''+sv.imgURL+'\'"></body>';
						var blob = new Blob([tmpHTML], {type: "text/html"});
						var tmpURL = URL.createObjectURL(blob);
						const tab = window.open(tmpURL, "_blank", "noreferrer,left=100,top=100,width=640,height=640");
						setTimeout(function() { URL.revokeObjectURL(tmpURL); }, 180000); */
					} else app.ui.notify(1, [3, "There's an error."]);
				}
				return {
					init: initialize,
					refresh: getMonth,
					load: getList,
					open: loadDonation,
					save: update,
					popImg: openImg
				};
			} const don = donfx(); delete donfx;
			
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<?php if (!$permitted) echo '<iframe src="/error/901">901: No Permission</iframe>'; else { ?>
			<div class="container">
				<h2><?=$_COOKIE['set_lang']=="th"?"รายการบริจาค":"Donation list"?></h2>
				<div class="wrapper">
					<div class="head form inline">
						<?=$_COOKIE['set_lang']=="th"?"เดือนที่บันทึกข้อมูล":"Month of records"?> <select name="date">
							<option value selected disabled>---<?=$_COOKIE['set_lang']=="th"?"กรุณาเลือก":"Please select"?>---</option>
						</select>
					</div>
					<div class="body table">
						<table><thead><tr>
							<th><?=$_COOKIE['set_lang']=="th"?"เลขที่":"No."?></th>
							<th><?=$_COOKIE['set_lang']=="th"?"นามผู้บริจาค":"Donor"?></th>
							<th><?=$_COOKIE['set_lang']=="th"?"จำนวน":"Amt."?></th>
							<th><?=$_COOKIE['set_lang']=="th"?"เวลา":"Time"?></th>
							<th><?=$_COOKIE['set_lang']=="th"?"สถานะ":"Statement"?></th>
						</tr></thead><tbody></tbody></table>
						<center class="message gray"><?=$_COOKIE['set_lang']=="th"?"ไม่มีการทำรายการ<br>กรุณาเลือกวันที่อื่น":"No transactions.<br>Please select other date."?></center>
					</div>
				</div>
			</div><?php } ?>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>