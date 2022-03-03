<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "การส่งไฟล์";
	$header_desc = "ไฟล์สลิปการโอนเงิน";

	if (!isset($_SESSION['evt'])) header("Location: ../login#next=".end(explode("/", $_SERVER['REQUEST_URI'])));
	
	require_once($dirPWroot."resource/php/core/config.php");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main div.message.green { display: flex; justify-content: space-between; align-items: center; }
			main div.message button i.material-icons { transform: translateY(5px); }
			main div.message button { border-radius: 0.3rem 0px 0px 0.3rem; }
			main div.message a { border-radius: 0px 0.3rem 0.3rem 0px; }
			main form div.box {
				margin-bottom: 10px;
				width: calc(100% - 5px); height: 125px;
				border-radius: 5px; border: 2.5px dashed var(--clr-bs-gray);
				background-color: var(--clr-gg-grey-300);
				/* display: flex; justify-content: center; */
				overflow: hidden; transition: var(--time-tst-fast);
			}
			main form div.box:after {
				margin: auto;
				position: relative; top: -50%; transform: translateY(-62.5%);
				text-align: center;
				display: block; content: "Drag & Drop your image here or Browse";
				pointer-events: none;
			}
			main form input[type="file"] {
				margin: auto;
				width: 100%; height: 100%; transform: translateY(-2.5px);
				opacity: 0%; filter: opacity(0%);
			}
			main form div.box:focus-within {
				border-color: var(--clr-bs-blue);
				box-shadow: 0px 0px 0px 0.25rem rgb(13 110 253 / 25%);
			}
			main form div.right { display: flex; justify-content: flex-end; align-items: center; }
			main form div.right output { margin-right: 7.5px; }
			main form div.i {
				padding: 3px;
				transform: translateY(5px) rotate(180deg);
				display: inline-block; overflow: hidden;
				animation: uploading 1.5s ease-in-out infinite forwards;
			}
			main form div.i i.material-icons {
				padding: 5px;
				transform: rotate(180deg);
				color: var(--clr-bs-blue);
			}
			@keyframes uploading {
				from { height: 0px; }
				to { height: 24px; }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				seek_param();
				// $("main form div.box").on("click", function() { document.querySelector('form input[name="usf"]').click(); })
				$('form input[name="usf"]').on("change", validate_file);
				$.get("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/submit/file?status&remote=<?php echo $_SESSION['evt']['encid']; ?>", function(res, hsc) {
					var dat = JSON.parse(res);
					if (dat.success) {
						$(dat.info.s?'<div class="message green"><?=$_COOKIE['set_lang']=="th"?"คุณได้ส่งไฟล์แล้ว":"You have sent your slip."?></div><div class="message yellow"><?=$_COOKIE['set_lang']=="th"?"การส่งไฟล์ใหม่จะเป็นการส่งทับไฟล์เดิม":"Re-submitting a new one will replace the previous."?></div>':'<div class="message gray"><?=$_COOKIE['set_lang']=="th"?"คุณยังไม่เคยส่งไฟล์":"You haven\\'t send any slip yet."?></div>').insertBefore("main form");
						if (document.querySelector("div.message.green") != null) addDL();
					} else app.ui.notify(1, dat.reason);
				});
			});
			function seek_param() { if (location.hash!="") {
				// Extract hashes
				var hash = {}; location.hash.substring(1, location.hash.length).split("&").forEach((ehs) => {
					let ths = ehs.split("=");
					hash[ths[0]] = ths[1];
				});
				// Let's see
				if (typeof hash.status !== "undefined") switch (hash.status) {
					case "success": app.ui.notify(1, [0, "Your file has been uploaded successfully!"]); break;
					case "error": app.ui.notify(1, [3, "Unable to upload your file...<br>Please try again."]); break;
					case "failed": app.ui.notify(1, [2, "Sorry, your file does not match the file reciever criteria.<br>Please try again."]); break;
					case "unknown": app.ui.notify(1, [3, "There's an error. The server could not process your file."]); break;
				} history.replaceState(null, null, location.pathname);
			} }
			function validate_file() {
				var f = document.querySelector('form input[name="usf"]').files;
				var cond = f.length == 1; if (cond) {
					let filename = (f[0].name).toLowerCase().split(".");
					cond = (["png"].includes(filename[filename.length-1])) && (f[0].size > 0 && f[0].size < 3072000); // 3 MB
					if (cond) document.querySelector("main form output").value = f[0].name;
					else app.ui.notify(1, [2, "<?=$_COOKIE['set_lang']=="th"?"กรุณาตรวจสอบว่าไฟล์เป็นนามสกุล .PNG และมีขนาดไม่เกิน 3 MB":"Please check if your file is .PNG file and its size is less than or equal to 3 MB"?>"]);
				} else document.querySelector("main form output").value = "";
				document.querySelector("form button").disabled = !cond;
				return cond;
			}
			function upload() {
				if (validate_file()) {
					$('<div class="i"><i class="material-icons">file_upload</i></div>').insertBefore("form button");
					document.querySelector("form").submit();
					document.querySelector("form button").disabled = true;
				}
			}
			function addDL() {
				$("div.message.green").append('<div><button onClick="preview()" class="gray hollow ripple-click"><i class="material-icons">visibility</i></button><a role="button" class="green hollow ripple-click" href="https://inf.bodin.ac.th/resource/dl?furl=e%2FPathway-Speech-Contest%2Fresource%2Fupload%2Fps-<?=$config['round']."%2F".$_SESSION['evt']['user']?>.png" target="_blank" download="Pathway Speech Contest - <?=$_SESSION['evt']['namea']?>" draggable="false" rel="noreferrer"><i class="material-icons">download</i> Download</a></div>');
				ppa.ripple_click_program();
			}
			function preview() {
				top.app.ui.lightbox.open("top", {title: "My payment slip", allowclose: true, autoclose: 180000, html: '<div style="background-image: linear-gradient(45deg,#EFEFEF 25%,rgba(239,239,239,0) 25%,rgba(239,239,239,0) 75%,#EFEFEF 75%,#EFEFEF),linear-gradient(45deg,#EFEFEF 25%,rgba(239,239,239,0) 25%,rgba(239,239,239,0) 75%,#EFEFEF 75%,#EFEFEF); background-position: 0 0,10px 10px; background-size: 21px 21px;"><img src="https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/upload/ps-<?=$config['round']."/".$_SESSION['evt']['user']?>.png" alt="My payment slip" draggable="false" style="max-width: 90vw; max-height: 80vh; object-fit: contain;"></div>'})
			}
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<!--iframe src="/error/903">Loading...</iframe-->
			<div class="container">
				<form method="post" action="https://inf.bodin.ac.th/e/Pathway-Speech-Contest/submit/file?upload=s&remote=<?php echo $_SESSION['evt']['encid']; ?>" enctype="multipart/form-data">
					<div class="box"><input type="file" name="usf" accept=".png" required></div>
					<div class="right">
						<output></output>
						<button class="blue ripple-click" onClick="return upload()" disabled><?=$_COOKIE['set_lang']=="th"?"อัปโหลด":"Upload"?></button>
					</div>
				</form>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>