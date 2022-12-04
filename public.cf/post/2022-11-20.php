<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "ประกาศผล";
	$header_desc = "Ranking result announcement";

	$permitted = true; # isset($_SESSION['evt2']); # (has_perm("art", false) || has_perm("dev"));
	if ($permitted && $_SESSION['evt2']["force_pwd_change"] ?? false) header("Location: ../organize/new-password?return_url=..%2Fpost%2F2022-11-20");
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
					<summary><?=$_COOKIE['set_lang']=="th"?"ประกาศผลรอบ World Wildlife Conservation Day":"Results for World Wildlife Conservation Day topic"?></summary-->
				<p><?=$_COOKIE['set_lang']=="th"?"ประกาศผลรอบ World Wildlife Conservation Day":"Results for World Wildlife Conservation Day topic"?></p>
				<div class="rankrs">
					<h3>Congratulations!</h3>
					<center><p><?=$_COOKIE['set_lang']=="th"?"ระดับประถมศึกษา":"Elementary Level"?></p></center>
					<div class="card right">
						<div class="info longnme">
							<div class="prize"><span>Winner</span></div>
							<div class="name"><span>Ratchatapann Thanabodithammachari</span></div>
							<div class="school"><span>Sarasas Ektra School</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Pann">
								<img data-dark="false" src="/resource/images/participant/1288525775.jpg" alt="Pann">
								<div class="play" data-href="874520850213673">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="Tamtam">
								<img data-dark="false" src="/resource/images/participant/1186829084.jpg" alt="Tamtam">
								<div class="play" data-href="434549275515704">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info">
							<div class="prize"><span>1<sup>st</sup> Runner Up</span></div>
							<div class="name"><span>Napapat Kittisarn</span></div>
							<div class="school"><span>Sarasas Ektra School</span></div>
						</div>
					</div>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>2<sup>nd</sup> Runner Up</span></div>
							<div class="name"><span>Yanisa Klinjaroen</span></div>
							<div class="school"><span>Watmahaeyong School</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Nampunch">
								<img data-dark="false" src="/resource/images/participant/3178736012.jpg" alt="Nampunch">
								<div class="play" data-href="576347590925880">
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
								<div class="play" data-href="547293624073659">
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
							<div class="name"><span>Natat Kittisarn</span></div>
							<div class="school"><span>Sarasas Ektra School</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Tangtang">
								<img data-dark="false" src="/resource/images/participant/3638101695.jpg" alt="Tangtang">
								<div class="play" data-href="1041193843939687">
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
								<div class="play" data-href="670447424791083">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info longsch">
							<div class="prize"><span>2<sup>nd</sup> Runner Up</span></div>
							<div class="name"><span>Taksapon Srikram</span></div>
							<div class="school"><span>Bodindecha (Sing Singhaseni) School</span></div>
						</div>
					</div>
					<center><p><?=$_COOKIE['set_lang']=="th"?"ระดับมัธยมศึกษาตอนปลาย":"High School"?></p></center>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>Winner</span></div>
							<div class="name"><span>Emily Bulterman</span></div>
							<div class="school"><span>Sarasas Ektra School</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Emily">
								<img data-dark="false" src="/resource/images/participant/4276704694.jpg" alt="Emily">
								<div class="play" data-href="1591046464664186">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="Chin">
								<img data-dark="false" src="/resource/images/participant/2314102048.jpg" alt="Chin">
								<div class="play" data-href="1073376940013123">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info longsch">
							<div class="prize"><span>1<sup>st</sup> Runner Up</span></div>
							<div class="name"><span>Yanawut Sunggoed</span></div>
							<div class="school"><span>Nawamintrachinuthit Satriwittaya 2 School</span></div>
						</div>
					</div>
					<div class="card right">
						<div class="info longnme">
							<div class="prize"><span>2<sup>nd</sup> Runner Up</span></div>
							<div class="name"><span>Pongsarun Vongwiwatana</span></div>
							<div class="school"><span>Triam Udom Suksa School</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Paully">
								<img data-dark="false" src="/resource/images/participant/1246022066.jpg" alt="Paully">
								<div class="play" data-href="819318915851740">
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
									<a href="https://bod.in.th/!PSC-V_s03a" role="button" class="cyan dont-ripple" target="_blank">Elementary Level</a>
								</div>
								<div class="group">
									<span><i class="material-icons">queue_music</i></span>
									<a href="https://bod.in.th/!PSC-V_s03b" role="button" class="cyan dont-ripple" target="_blank">Middle School</a>
								</div>
								<div class="group">
									<span><i class="material-icons">queue_music</i></span>
									<a href="https://bod.in.th/!PSC-V_s03c" role="button" class="cyan dont-ripple" target="_blank">High School</a>
								</div>
							</div>
							<a href="/go?url=https%3A%2F%2Ffacebook.com%2FPathway.speechcontest%2Fposts%2F685754866229189" role="button" class="blue dont-ripple" target="_blank">
								<img src="/resource/images/nav-share-facebook.png">
								<span>Share to Facebook</span>
							</a>
						</div>
					</div>
				<!/details></div>
				<nav class="post">
					<hr>
					<div class="hold">
						<a href="2022-04-17">← Previous (17/04/2022)</a>
						<span class="mnfst">By: Admin | 20/11/2022</span>
						<a></a>
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