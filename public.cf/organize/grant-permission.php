<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "อนุญาตคำขอสิทธิ์";

	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("Location: new-password$my_url");
	$permitted = has_perm("lead"); if ($permitted) {
		
	}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main ul.list {
				margin-bottom: 0px; padding-left: 10px;
				list-style-type: none;
			}
			main ul.list li { margin-bottom: 10px; }
			main ul.list li .req {
				padding: 5px 10px;
				border-radius: 7.5px; border: 1px solid transparent;
				background-color: var(--fade-white-4);
				display: flex; justify-content: space-between; align-items: center;
				transition: var(--time-tst-fast) ease;
			}
			main ul.list li .req:hover { background-color: var(--fade-white-3); border-color: var(--clr-gg-green-300); }
			main ul.list li .req .name > span { display: block; }
			main ul.list li .req .name > span:first-child { margin-bottom: 2.5px; }
			main ul.list li .req .name > span:last-child { color: var(--clr-gg-grey-700); }
		</style>
		<style type="text/css">
			.lightbox .cmt {
				padding: 0px 5px;
				list-style-type: none;
			}
			.lightbox .cmt label {
				font-weight: bold;
				display: block;
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				perm.list();
			});
			function permfx() {
				const cv = { APIurl: "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/override", myID: parseInt("<?=$_SESSION['evt2']['user']?>") };
				var sv = { ID: null };
				var list = function() {
					$.post(cv.APIurl, {app: "cmtperm", cmd: "list", remote: cv.myID}, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							var target = document.querySelector("main .list");
							if (dat.info.length) {
								target.innerHTML = "";
								dat.info.forEach(er => {
									target.innerHTML += '<li><div class="req"><div class="name"><span>'+er.nickname+'</span><span>'+er.longname+'</span></div><div class="action"><button class="blue ripple-click" onClick="perm.load(\''+er.ID+'\')"><?=$_COOKIE['set_lang']=="th"?"พิจรณา":"Review"?></button></div></div></li>';
								}); ppa.ripple_click_program();
							} else target.innerHTML = '<center class="message gray"><?=$_COOKIE['set_lang']=="th"?"ไม่มีคำขอสิทธิ์":"No requests."?></center>';
						} else app.ui.notify(1, dat.reason);
					});
				};
				var load = function(ID) {
					if (ID != "") $.post(cv.APIurl, {app: "cmtperm", cmd: "load", attr: ID, remote: cv.myID}, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							var view = ""; if (dat.info.length) {
								view += '<ul class="cmt">';
								dat.info.forEach(ec => view += '<li><label>'+ec.commenter+'</label><span>'+ec.message+'</span></li><hr>');
								view += '</ul>';
							} else view = '<center class="message gray"><?=$_COOKIE['set_lang']=="th"?"ไม่มีข้อความ":"No comments."?></center><hr>';
							view += '<div class="form"><div class="group spread"><button class="red hollow" onClick="perm.decide(false)"><?=$_COOKIE['set_lang']=="th"?"ตัดสินใจภายหลัง":"Decide later"?></button><button class="green" onClick="perm.decide(true)"><?=$_COOKIE['set_lang']=="th"?"อนุญาต":"Approve"?></button></div></div>';
							app.ui.lightbox.open("top", {allowclose: false, autoclose: 180000, html: view});
							sv.ID = ID;
						} else app.ui.notify(1, dat.reason);
					}); else app.ui.notify(1, [2, "Invalid view chosed"]);
				};
				var choose = function(decision) {
					if (sv.ID == null) {
						app.ui.lightbox.close();
						app.ui.notify(1, [1, "You haven\'t choose any request."]);
					} else if (!decision) {
						app.ui.lightbox.close();
						sv.ID = null;
					} else $.post(cv.APIurl, {app: "cmtperm", cmd: "give", attr: sv.ID, remote: cv.myID}, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							app.ui.lightbox.close();
							let target = document.querySelector('main .list button[onClick="perm.load(\''+sv.ID+'\')"]').parentNode.parentNode.parentNode;
							$(target).toggle("fold", function() { $(this).remove(); });
							sv.ID = null;
						} else app.ui.notify(1, dat.reason);
					});
				}
				return { list: list, load: load, decide: choose };
			}; const perm = permfx(); delete permfx;
		</script>
		<script type="text/javascript" src="/resource/js/lib/w3.min.js"></script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<?php if (!$permitted) echo '<iframe src="/error/901">901: No Permission</iframe>'; else { ?>
			<div class="container">
				<h2><?=$_COOKIE['set_lang']=="th"?"อนุมัติคำขอสิทธิ์":"Grant Permission"?></h2>
				<p><?=$_COOKIE['set_lang']=="th"?"อนุญาตให้ผุ้เข้าร่วมดูข้อความจากผู้พิจรณา":"Allow participants to view comments"?></p>
				<ul class="list">

				</ul>
			</div><?php } ?>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>