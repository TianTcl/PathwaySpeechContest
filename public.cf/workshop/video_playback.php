<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Workshop";
	$header_desc = "การย้อนดูคลิปวีดีโอ";

	if (!isset($_GET['signature']) || empty(trim($_GET['signature']))|| !isset($_SESSION['var']['workshop-URL'])
		|| $_SESSION['var']['workshop-URL'] <> trim($_GET['signature'])) header("Location: /workshop/");
	$player_secured = false; $home = "workshop/";
	switch ($_SESSION['var']['workshop-VDO']) {
		case 1:
			$player_src = "workshop_2021-12-11"; $player_name = "1st Workshop (11/12/2021)";
			$player_duration = "1:42:35"; $player_thumbnail = "/resource/images/news/workshop-1.png"; break;
		default: header("Location: /workshop/"); break;
	} $player_src = "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/images/video/$player_src.mp4";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			:root {
				--lt-col: 255, 237, 237;
				--lt-stroke: 126, 3, 3;
				--lt-bgcol: 165, 20, 20;
				--li-dl: 242, 182, 182;
				--li-dd: 167, 23, 23;
				--li-dbs: 246, 197, 197;
				--bar-color: #FF0000;
				--track-size: 3.45px;
			}
			.frame {
				position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
				width: 960px; height: 540px;
				box-shadow: /*5.75px 5.75px 4.6px 8.05px rgba(0, 0, 0, 0.46)*/0 4px 15px 2px rgba(0,0,0,0.35);
			}
			@media only screen and (max-width: 768px) {
				.frame {
					transform: translate(-50%, -50%) scale(0.575);
					width: 640px; height: 360px;
				}
			}
		</style>
		<script type="text/javascript">
			// Initialize
			const record = false;
			$(document).ready(function() {
				<?php if(isset($player_secured))echo($player_secured)?"gen_blob();":'is_not_secured();';?>
				jsinit(where=".player");
			});
			function is_not_secured() {
				document.querySelector(".player div.full.video div.view video").src = "<?php if(isset($player_src))echo $player_src; ?>";
				$(".player div.full.video div.view div.load-text").text("");
				$(".player div.full.video div.view div.load-img").hide();
			}
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="frame">
				<?php include($dirPWroot."resource/php/extend/video_player.php"); ?>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>