<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "การส่งไฟล์";
	$header_desc = "ไฟล์วีดีโอสุนทรพจน์";

	if (!isset($_SESSION['evt'])) header("Location: ../login");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main form div.box {
				margin-bottom: 10px;
				width: calc(100% - 5px); height: 125px;
				border-radius: 5px; border: 2.5px dashed var(--clr-bs-gray);
				background-color: var(--clr-gg-grey-300);
				/* display: flex; justify-content: center; */
				overflow: hidden;
			}
			main form div.box:after {
				margin: auto;
				position: relative; top: -50%; transform: translateY(-62.5%);
				text-align: center;
				display: block; content: "Drag & Drop your video here or Browse";
				pointer-events: none;
			}
			main form input[type="file"] {
				margin: auto;
				width: 100%; height: 100%; transform: translateY(-2.5px);
				opacity: 0%; filter: opacity(0%);
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
						$(dat.info.v?'<div class="message green">คุณได้ส่งไฟล์แล้ว</div><div class="message yellow">การส่งไฟล์ใหม่จะเป็นการส่งทับไฟล์เดิม และทางผู้ดำเนินกิจกรรมจะประเมินจากไฟล์ล่าสุดเท่านั้น</div>':'<div class="message gray">คุณยังไม่เคยส่งไฟล์</div>').insertBefore("main form");
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
					cond = (["mp4"].includes(filename[filename.length-1])) && (f[0].size > 0 && f[0].size < 25600000); // 25 MB
					if (cond) document.querySelector("main form output").value = f[0].name;
					else app.ui.notify(1, [2, "Please check if your video is .MP4 file and its size is less than or equal to 25 MB"]);
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
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<!--iframe src="/error/903">Loading...</iframe-->
			<div class="container">
				<form method="post" action="https://inf.bodin.ac.th/e/Pathway-Speech-Contest/submit/file?upload=v&remote=<?php echo $_SESSION['evt']['encid']; ?>" enctype="multipart/form-data">
					<div class="box"><input type="file" name="usf" accept=".mp4" required></div>
					<div class="right">
						<output></output>
						<button class="blue ripple-click" onClick="return upload()" disabled>Upload</button>
					</div>
				</form>
				<p>หากไฟล์มีขนาดใหญ่กว่า 25MB หรือไม่ใช่นามสกุล MP4 อนุญาตให้ผู้สมัครส่งวีดีโอมาที่ <a href="mailto:devtech@PathwaySpeechContest.cf?subject=PSC%20Speech%20Video&body=เลขที่ผู้สมัคร%20<?=$_SESSION['evt']['myID']?>" target="_blank">devtech@PathwaySpeechContest.cf</a> ได้</p>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>