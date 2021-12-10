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
				box-shadow: 2.5px 2.5px var(--shd-tiny) var(--fade-black-4);
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
			@media only screen and (max-width: 768px) {
				main .form .binfo + div { flex-wrap: wrap; }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				donate.init();
			});
			function donation() {
				const cv = { APIurl: "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/api" };
				var sv = { inited: false };
				var initialize = function() {
					if (!sv.inited) {
						sv.inited = true;
						$(window).on("resize", function() { setTimeout(resize_box, 500); }); resize_box();
						setTimeout(function() { $('.form .part-1 [name="email"]').focus(); }, 500);
						sv.tsignature = Date.now().toString(36);
					}
				};
				var next_step = function() {
					go_on(); return false;
					function go_on() {
						if (verify_info()) {
							sv.reference = crc32(sv.info.email + sv.tsignature);
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
						if (!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@([a-zA-Z0-9\-_]+\.)+[a-zA-Z]{2,13}$/.test(info.email) || info.email.length > 255) {
							if (pass) { focusfield("email"); pass = false; }
							app.ui.notify(1, [2, "Invalid E-mail address format"]);
						} if (info.sender.length <= 0 || info.sender.length > 75) {
							if (pass) { focusfield("sender"); pass = false; }
							app.ui.notify(1, [2, "Invalid donor format"]);
						} if (!/^[1-9]\d{0,6}(0|5|9)$/.test(info.amount)) {
							if (pass) { focusfield("amount"); pass = false; }
							app.ui.notify(1, [2, "Invalid amout set"]);
						}
					} sv.info = pass ? info : undefined;
					return pass;
				};
				var focusfield = function(which) { $('.form [name="'+which+'"]').focus(); }
				var go_back = function() {
					toPage(1); return false;
				};
				var submit = function() {
					go_on(); return false;
					function go_on() {
						if (!verify_info()) {
							if (parseInt($("main .form .fill > div").css("--page")) == 2) toPage(1);
						} else {
							document.querySelectorAll(".form .part-2 [name]").forEach((ef) => { sv.info[ef.name] = ef.value.trim(); });
							$(".form .part:not(.part-3)").attr("disabled", "");
							$.post(cv.APIurl, {app: "donate", cmd: "submit", attr: {...sv.info, remote: true}}, function(res, hsc) {
								var dat = JSON.parse(res);
								if (dat.success) {
									toPage(3);
									$("main .form .fill").removeClass("cyan").addClass("blue");
									sv.tsignature = sv.info = sv.reference = undefined;
								} else {
									app.ui.notify(1, dat.reason);
									setTimeout(function() { $(".form .part:not(.part-3)").removeAttr("disabled"); }, 1250);
								}
							});
						}
					}
				};
				var clear_form = function() {
					go_on(); return false;
					function go_on() {
						$(".form .part [name]").val("");
						sv.tsignature = Date.now().toString(36);
						toPage(1);
						$("main .form .fill").removeClass("blue").addClass("cyan");
						$(".form .part:not(.part-3)").removeAttr("disabled");
					}
				}
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
				return {
					init: initialize,
					step: next_step,
					edit: go_back,
					save: submit,
					renew: clear_form,
					copy: copyRef
				};
			} const donate = donation(); delete donation;
			const resize_box = function() { $("main .form .fill").height($("main .form .part-"+$("main .form .fill > div").css("--page")).outerHeight()); }
		</script>
		<script type="text/javascript" src="/resource/js/extend/php-function.js"></script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>Donate</h2>
				<div class="form">
					<section class="fill message cyan" style="height: 0px;">
						<div style="--page:1;">
							<form class="part part-1" show>
								<div class="group">
									<span>ที่อยู่อีเมล</span>
									<input type="email" name="email" maxlength="255" placeholder="name@domain.tld">
								</div>
								<div class="group">
									<span>ผู้บริจาค</span>
									<input type="text" name="sender" maxlength="75" placeholder="นายชัยณัฏฐ์ / คณะผู้จัดกิจกรรม / บริษัท...">
								</div>
								<div class="group">
									<span>จำนวนเงิน</span>
									<input type="number" name="amount" min="10" max="99999999" step="10">
								</div>
								<div class="group split navigation">
									&nbsp;
									<button class="blue ripple-click" onClick="return donate.step()"><?php echo $_COOKIE['set_lang']=="th"?"ถัดไป":"Next"; ?></button>
								</div>
							</form>
							<form class="part part-2">
								<div class="binfo">
									<img src="resource/images/kbank.png" alt="ธนาคารกสิกรไทย | KBANK">
									<div><div>
										<h2>เลขที่บัญชี 114-3-95610-1</h2>
										<h3>จิณณ์นิชา บุญอยู่ <i class="material-icons" data-title="สาขาเซ็นทรัลรามอินทรา">information</i></h3>
									</div></div>
								</div>
								<div class="group-inline group split">
									<div class="group">
										<span>วันเวลาโอน</span>
										<input type="datetime-local" name="when" min="<?=date("Y-m-d\TH:i", time())?>">
									</div>
									<div class="group">
										<span>Reference</span>
										<input type="number" name="reference" readonly>
										<button class="gray" onClick="return donate.copy()" data-title="Copy"><i class="material-icons">content_copy</i></button>
									</div>
								</div>
								<div class="group split navigation">
									<button class="white ripple-click" onClick="return donate.edit()" type="reset"><?php echo $_COOKIE['set_lang']=="th"?"ย้อนกลับ":"Back"; ?></button>
									<button class="green ripple-click" onClick="return donate.save()" type="submit"><?php echo $_COOKIE['set_lang']=="th"?"บริจาค":"Donate"; ?></button>
								</div>
							</form>
							<form class="part part-3">
								<img src="/resource/images/donate-Thank_you.png" alt="Thank you">
								<center class="message green">การบริจาคเสร็จสมบูรณ์.<br>🍃ขอขอบคุณผู้ร่วมทำบุญที่มีจิตใจเมตตาทุกๆท่านที่ไว้ใจพวกเรา Pathway Speech Contest<hr>จะส่งมอบเงินบริจาคทั้งหมดให้แก่มูลนิธิดวงประทีปในโครงการอนุบาลชุมชน💝<span hidden>อย่าลืมร่วมสนุกเข้าประกวดในโครงการ Pathway Speech Contest ด้วยน้าาา</span></center>
								<button class="blue ripple-click" onClick="return donate.renew()">บริจาคเพิ่ม</button>
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