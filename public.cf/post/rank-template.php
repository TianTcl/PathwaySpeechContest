<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "ประกาศผล";
	$header_desc = "Ranking result announcement";

	$permitted = isset($_SESSION['evt2']); # (has_perm("art", false) || has_perm("dev"));
	if ($permitted && $_SESSION['evt2']["force_pwd_change"] ?? false) header("Location: ../organize/new-password?return_url=..%2Fpost%2F20YY-MM-DD");

	// Regex Searcher: \{[A-Z_]+\}
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
					<summary><?=$_COOKIE['set_lang']=="th"?"ประกาศผลรอบ World ___ Day":"Results for World ___ Day topic"?></summary-->
				<p><?=$_COOKIE['set_lang']=="th"?"ประกาศผลรอบ World ___ Day":"Results for World ___ Day topic"?></p>
				<div class="rankrs">
					<h3>Congratulations!</h3>
					<center><p><?=$_COOKIE['set_lang']=="th"?"ระดับประถมศึกษา":"Elementary Level"?></p></center>
					<div class="card right">
						<div class="info --longnme --longsch">
							<div class="prize"><span>Winner</span></div>
							<div class="name"><span>{FULL_NAME}</span></div>
							<div class="school"><span>{SCHOOL_NAME}</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="{NICK_NAME}">
								<img data-dark="false" src="/resource/images/participant/{AVATAR_ID}.jpg" alt="{NICK_NAME}">
								<div class="play" data-href="{VIDEO_ID}">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="{NICK_NAME}">
								<img data-dark="false" src="/resource/images/participant/{AVATAR_ID}.jpg" alt="{NICK_NAME}">
								<div class="play" data-href="{VIDEO_ID}">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info">
							<div class="prize"><span>1<sup>st</sup> Runner Up</span></div>
							<div class="name"><span>{FULL_NAME}</span></div>
							<div class="school"><span>{SCHOOL_NAME}</span></div>
						</div>
					</div>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>2<sup>nd</sup> Runner Up</span></div>
							<div class="name"><span>{FULL_NAME}</span></div>
							<div class="school"><span>{SCHOOL_NAME}</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="{NICK_NAME}">
								<img data-dark="false" src="/resource/images/participant/{AVATAR_ID}.jpg" alt="{NICK_NAME}">
								<div class="play" data-href="{VIDEO_ID}">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<center><p><?=$_COOKIE['set_lang']=="th"?"ระดับมัธยมศึกษาตอนต้น":"Middle School"?></p></center>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="{NICK_NAME}">
								<img data-dark="false" src="/resource/images/participant/{AVATAR_ID}.jpg" alt="{NICK_NAME}">
								<div class="play" data-href="{VIDEO_ID}">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info">
							<div class="prize"><span>Winner</span></div>
							<div class="name"><span>{FULL_NAME}</span></div>
							<div class="school"><span>{SCHOOL_NAME}</span></div>
						</div>
					</div>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>1<sup>st</sup> Runner Up</span></div>
							<div class="name"><span>{FULL_NAME}</span></div>
							<div class="school"><span>{SCHOOL_NAME}</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="{NICK_NAME}">
								<img data-dark="false" src="/resource/images/participant/{AVATAR_ID}.jpg" alt="{NICK_NAME}">
								<div class="play" data-href="{VIDEO_ID}">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="{NICK_NAME}">
								<img data-dark="false" src="/resource/images/participant/{AVATAR_ID}.jpg" alt="{NICK_NAME}">
								<div class="play" data-href="{VIDEO_ID}">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info">
							<div class="prize"><span>2<sup>nd</sup> Runner Up</span></div>
							<div class="name"><span>{FULL_NAME}</span></div>
							<div class="school"><span>{SCHOOL_NAME}</span></div>
						</div>
					</div>
					<center><p><?=$_COOKIE['set_lang']=="th"?"ระดับมัธยมศึกษาตอนปลาย":"High School"?></p></center>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>Winner</span></div>
							<div class="name"><span>{FULL_NAME}</span></div>
							<div class="school"><span>{SCHOOL_NAME}</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="{NICK_NAME}">
								<img data-dark="false" src="/resource/images/participant/{AVATAR_ID}.jpg" alt="{NICK_NAME}">
								<div class="play" data-href="{VIDEO_ID}">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="{NICK_NAME}">
								<img data-dark="false" src="/resource/images/participant/{AVATAR_ID}.jpg" alt="{NICK_NAME}">
								<div class="play" data-href="{VIDEO_ID}">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info longnme longsch">
							<div class="prize"><span>1<sup>st</sup> Runner Up</span></div>
							<div class="name"><span>{FULL_NAME}</span></div>
							<div class="school"><span>{SCHOOL_NAME}</span></div>
						</div>
					</div>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>2<sup>nd</sup> Runner Up</span></div>
							<div class="name"><span>{FULL_NAME}</span></div>
							<div class="school"><span>{SCHOOL_NAME}</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="{NICK_NAME}">
								<img data-dark="false" src="/resource/images/participant/{AVATAR_ID}.jpg" alt="{NICK_NAME}">
								<div class="play" data-href="{VIDEO_ID}">
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
									<a href="https://bod.in.th/!PSC-V_s0Sa" role="button" class="cyan dont-ripple" target="_blank">Elementary Level</a>
								</div>
								<div class="group">
									<span><i class="material-icons">queue_music</i></span>
									<a href="https://bod.in.th/!PSC-V_s0Sb" role="button" class="cyan dont-ripple" target="_blank">Middle School</a>
								</div>
								<div class="group">
									<span><i class="material-icons">queue_music</i></span>
									<a href="https://bod.in.th/!PSC-V_s0Sc" role="button" class="cyan dont-ripple" target="_blank">High School</a>
								</div>
							</div>
							<a href="/go?url=https%3A%2F%2Ffacebook.com%2FPathway.speechcontest%2Fposts%2F{POST_ID}" role="button" class="blue dont-ripple" target="_blank">
								<img src="/resource/images/nav-share-facebook.png">
								<span>Share to Facebook</span>
							</a>
						</div>
					</div>
				<!/details></div>
				<nav class="post">
					<hr>
					<div class="hold">
						<a href="2022-01-26">← Previous (26/01/2022)</a>
						<span class="mnfst">By: Admin | 17/04/2022</span>
						<a href="2022-11-20">(20/11/2022) Next →</a>
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