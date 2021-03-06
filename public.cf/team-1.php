<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Teams";
	$header_desc = "คณะผู้ประสานงานโครงการ";

	function role2text($roles) {
		$text = ""; foreach ($roles as $role) {
			switch ($role) {
				case "lead"; $name = "Founder" /*"ผู้จัดโครงการ"*/; break;
				case "colead"; $name = "Co-Founder" /*"ผู้ร่วมจัดโครงการ"*/; break;
				case "contact"; $name = "Coordinator" /*"ผู้ประสานงาน"*/; break;
				case "art"; $name = "Graphic Designer" /*"ดีไซน์เนอร์"*/; break;
				case "content"; $name = "Content Creator" /*"ผู้คิดเนื้อหา"*/; break;
				case "grader"; $name = "Judge" /*"กรรมการ"*/; break;
				case "judge"; $name = "Head Judge" /*"กรรมการตัดสิน"*/; break;
				case "stage"; $name = "Senior guest" /*"วิทยากร"*/; break;
				case "MC"; $name = "MC" /*"พิธีกร"*/; break;
				case "dev"; $name = "System Developer" /*"นักพัฒนาระบบ"*/; break;
				default; $name = ""; break;
			} if ($name <> "") $text .= (!empty($text) ? ", " : "").$name;
		} return $text;
	}
	function renderUser($user) {
		$avatar = $user -> avatar; if (empty($avatar)) $avatar = "default.jpg";
		$realName = $user -> name; if ($realName == "Tecillium (UFDT)") $realName = "นายชัยณัฏฐ์  ลิ้มชุณหนุกูล";
		$displayName = $user -> display; if (empty($displayName)) $displayName = "-_-_-";
		$eventRole = role2text(explode(",", $user -> role));
		?><div class="people">
			<div class="image <?=(empty($user -> avatar)?"":"real")?>">
				<img src="/resource/images/people/<?=$avatar?>" data-dark="false">
			</div>
			<div class="name"><span data-title="<?=$realName?>"><?=$displayName?></span></div>
			<div class="role"><?=$eventRole?></div>
		</div><?php
	}

	$get = json_decode(file_get_contents("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/admin-remote?user=guest&do=showTeam", false));
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main div.wrapper div.team {
				margin-bottom: 12.5px;
				display: flex; justify-content: center; flex-wrap: wrap;
			}
			main div.wrapper div.people {
				margin: 7.5px;
				width: 225px; height: 300px;
				border-radius: 5px; background-color: var(--clr-psc-skin-low);
				box-shadow: 1.25px 5px var(--shd-small) var(--fade-black-7);
				display: flex; flex-direction: column;
			}
			main div.wrapper div.people > .image {
				height: 225px;
				border-radius: 5px 5px 0px 0px;
				overflow: hidden;
			}
			main div.wrapper div.people > .image img {
				width: 100%; height: 100%;
				object-fit: scale-down;
			}
			/* main div.wrapper div.people > .image.real img { object-fit: cover; } */
			main div.wrapper div.people > :not(.image) {
				height: calc(75px / 2);
				line-height: calc(75px / 2); text-align: center;
			}
			main div.wrapper div.people > .role { font-size: 1.025rem; }
			@media only screen and (max-width: 768px) {
				main div.wrapper div.people > .role { font-size: 0.75rem; }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				setTimeout(function() { Grade(document.querySelectorAll("main div.wrapper div.people > .image:not(.real)")); }, 250);
				setTimeout(function() { Grade(document.querySelectorAll("main div.wrapper div.people > .image.real")); }, 750);
				<?php if (has_perm("dev")) echo '$("main div.wrapper div.team:nth-child(2)").sortable()'; ?>
			});
		</script>
		<script type="text/javascript" src="/resource/js/lib/grade.min.js"></script>
		<script type="text/javascript" src="/resource/js/lib/jquery-ui.min.js"></script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2><?=$_COOKIE['set_lang']=="th"?"คณะผู้ประสานงานโครงการ":"Event organizers"?></h2>
				<div class="wrapper">
					<div class="team">
						<?php foreach ($get -> lead as $readlead) renderUser($readlead); ?>
					</div>
					<div class="team">
						<?php foreach ($get -> rest as $readrest) renderUser($readrest); ?>
					</div>
				</div>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>