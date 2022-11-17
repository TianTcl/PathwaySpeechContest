<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "ประกาศผล";
	$header_desc = "Ranking result announcement";

	$permitted = true; # isset($_SESSION['evt2']); # (has_perm("art", false) || has_perm("dev"));
	if ($permitted && $_SESSION['evt2']["force_pwd_change"] ?? false) header("Location: ../organize/new-password?return_url=..%2Fpost%2F2022-01-21");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<link rel="stylesheet" href="/resource/css/extend/post.css">
		<link rel="stylesheet" href="/resource/css/extend/post-rank.css">
		<script type="text/javascript">
			$(document).ready(function() {
				$("main .rankrs .card .avatar .wrapper .play[data-href]").on("click", function() { listen_to_speech(this.getAttribute("data-href")); })
			});
			function listen_to_speech(link) {
				var newtab = window.open("https://inf.bodin.ac.th/go?url=https%3A%2F%2Ffacebook.com%2FPathway.speechcontest%2Fvideos%2F"+link+"%2F");
			}
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<?php if (!$permitted) echo '<iframe src="/error/903">903: Page under construction</iframe>'; else { ?>
			<div class="container">
				<h2><?=$_COOKIE['set_lang']=="th"?"ประกาศ":"Announcements"?></h2>
				<!--details class="message green rankrs" open>
					<summary><?=$_COOKIE['set_lang']=="th"?"ประกาศผลรอบ New Year's Day":"Results for New Year's Day topic"?></summary-->
				<p><?=$_COOKIE['set_lang']=="th"?"ประกาศผลรอบ New Year's Day":"Results for New Year's Day topic"?></p>
				<div class="rankrs">
					<h3>Congratulations!</h3>
					<center><p><?=$_COOKIE['set_lang']=="th"?"ระดับประถมศึกษา":"Elementary Level"?></p></center>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>Winner</span></div>
							<div class="name"><span>Meyanee Paophongngam</span></div>
							<div class="school"><span>Wattana Wittaya Academy</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Manie">
								<img data-dark="false" src="/resource/images/participant/779442320.jpg" alt="Manie">
								<div class="play" data-href="265011345702772">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="Khokhao">
								<img data-dark="false" src="/resource/images/participant/799891427.jpg" alt="Khokhao">
								<div class="play" data-href="3066540583597759">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info longsch">
							<div class="prize"><span>1<sup>st</sup> Runner Up</span></div>
							<div class="name"><span>Thanpicha Phimsen</span></div>
							<div class="school"><span>Ramkhamhaeng University Demonstration School (Elementary Level)</span></div>
						</div>
					</div>
					<div class="card right">
						<div class="info longsch">
							<div class="prize"><span>2<sup>nd</sup> Runner Up</span></div>
							<div class="name"><span>Svanda Dantrakul</span></div>
							<div class="school"><span>Ramkhamhaeng University Demonstration School (Elementary Level)</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Punpun">
								<img data-dark="false" src="/resource/images/participant/4214550375.jpg" alt="Punpun">
								<div class="play" data-href="1052741938607625">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<center><p><?=$_COOKIE['set_lang']=="th"?"ระดับมัธยมศึกษาตอนต้น":"Middle School"?></p></center>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="Proud">
								<img data-dark="false" src="/resource/images/participant/1679553582.jpg" alt="Proud">
								<div class="play" data-href="1073767400069134">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info">
							<div class="prize"><span>Winner</span></div>
							<div class="name"><span>Proud Klampaiboon</span></div>
							<div class="school"><span>Homeschool</span></div>
						</div>
					</div>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>1<sup>st</sup> Runner Up</span></div>
							<div class="name"><span>Thaksaorn Chitsamankhun</span></div>
							<div class="school"><span>The Newton Sixth Form School</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Nita">
								<img data-dark="false" src="/resource/images/participant/1819274625.jpg" alt="Nita">
								<div class="play" data-href="599753197764769">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="Idea">
								<img data-dark="false" src="/resource/images/participant/2864276859.jpg" alt="Idea">
								<div class="play" data-href="441205940996132">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info longsch longnme">
							<div class="prize"><span>2<sup>nd</sup> Runner Up</span></div>
							<div class="name"><span>Taksapon Srikram</span></div>
							<div class="school"><span>Bodindecha (Sing Singhaseni) School</span></div>
						</div>
					</div>
					<center><p><?=$_COOKIE['set_lang']=="th"?"ระดับมัธยมศึกษาตอนปลาย":"High School"?></p></center>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>Winner</span></div>
							<div class="name"><span>Krittameth Rakwong</span></div>
							<div class="school"><span>Yasothon Pittayakom School</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Kung">
								<img data-dark="false" src="/resource/images/participant/398661705.jpg" alt="Kung">
								<div class="play" data-href="643246120428547">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="Indy">
								<img data-dark="false" src="/resource/images/participant/2908309946.jpg" alt="Indy">
								<div class="play" data-href="734718097412158">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info longsch longnme">
							<div class="prize"><span>1<sup>st</sup> Runner Up</span></div>
							<div class="name"><span>Siriwatana Chantanakome</span></div>
							<div class="school"><span>Bodindecha (Sing Singhaseni) School</span></div>
						</div>
					</div>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>2<sup>nd</sup> Runner Up</span></div>
							<div class="name"><span>Narapat Waitayachaiwat</span></div>
							<div class="school"><span>Satree Prasertsin School</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Anna">
								<img data-dark="false" src="/resource/images/participant/2171474313.jpg" alt="Anna">
								<div class="play" data-href="650656069629676">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div><hr>
					<div class="form">
						<h3>View playlists <span>- listen to their speeches</span></h3>
						<div class="group split">
							<div class="lists">
								<div class="group">
									<span><i class="material-icons">queue_music</i></span>
									<a href="https://bod.in.th/!PSC-V_s01a" role="button" class="cyan dont-ripple" target="_blank">Elementary Level</a>
								</div>
								<div class="group">
									<span><i class="material-icons">queue_music</i></span>
									<a href="https://bod.in.th/!PSC-V_s01b" role="button" class="cyan dont-ripple" target="_blank">Middle School</a>
								</div>
								<div class="group">
									<span><i class="material-icons">queue_music</i></span>
									<a href="https://bod.in.th/!PSC-V_s01c" role="button" class="cyan dont-ripple" target="_blank">High School</a>
								</div>
							</div>
							<a href="https://inf.bodin.ac.th/go?url=https%3A%2F%2Ffacebook.com%2FPathway.speechcontest%2Fposts%2F125907753255029" role="button" class="blue dont-ripple" target="_blank">
								<img src="/resource/images/nav-share-facebook.png">
								<span>Share to Facebook</span>
							</a>
						</div>
					</div>
				<!/details></div>
				<nav class="post">
					<hr>
					<div class="hold">
						<a href="2021-12-11">← Previous (11/12/2021)</a>
						<span class="mnfst">By: Admin | 21/01/2022</span>
						<a href="2022-01-26">(26/01/2022) Next →</a>
					</div>
				</nav>
			</div><?php } ?>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>