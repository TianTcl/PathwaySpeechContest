<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "ประกาศผล";
	$header_desc = "Ranking result announcement";

	$permitted = isset($_SESSION['evt2']); # (has_perm("art", false) || has_perm("dev"));
	if ($permitted && $_SESSION['evt2']["force_pwd_change"]) header("Location: ../organize/new-password?return_url=..%2Fpost%2F2022-04-17");
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
					<summary><?=$_COOKIE['set_lang']=="th"?"ประกาศผลรอบ World Health Day":"Results for World Health Day topic"?></summary-->
				<p><?=$_COOKIE['set_lang']=="th"?"ประกาศผลรอบ World Health Day":"Results for World Health Day topic"?></p>
				<div class="rankrs">
					<h3>Congratulations!</h3>
					<center><p><?=$_COOKIE['set_lang']=="th"?"ระดับประถมศึกษา":"Elementary Level"?></p></center>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>Winner</span></div>
							<div class="name"><span>Thee Thanawutikul</span></div>
							<div class="school"><span>Anuban Nakhonpathom</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Idea">
								<img data-dark="false" src="/resource/images/participant/3718819804.jpg" alt="Idea">
								<div class="play" data-href="501895711405360">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="Dew">
								<img data-dark="false" src="/resource/images/participant/1580828531.jpg" alt="Dew">
								<div class="play" data-href="1082223582636180">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info longsch">
							<div class="prize"><span>1<sup>st</sup> Runner Up</span></div>
							<div class="name"><span>Sisiratha Kaewkraisorn</span></div>
							<div class="school"><span>Thetsaban 1 Chomchon Ban Udomtong</span></div>
						</div>
					</div>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>2<sup>nd</sup> Runner Up</span></div>
							<div class="name"><span>Meyanee Paophongngam</span></div>
							<div class="school"><span>Wattana Wittaya Academy</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Manie">
								<img data-dark="false" src="/resource/images/participant/779442320.jpg" alt="Manie">
								<div class="play" data-href="788683605849992">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<center><p><?=$_COOKIE['set_lang']=="th"?"ระดับมัธยมศึกษาตอนต้น":"Middle School"?></p></center>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="Indy">
								<img data-dark="false" src="/resource/images/participant/835501526.jpg" alt="Indy">
								<div class="play" data-href="1641960109506392">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info longsch">
							<div class="prize"><span>Winner</span></div>
							<div class="name"><span>Than Thanawutikul</span></div>
							<div class="school"><span>Patumwan Demonstration School Srinakharinwirot University</span></div>
						</div>
					</div>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>1<sup>st</sup> Runner Up</span></div>
							<div class="name"><span>Thannicha Iampanit</span></div>
							<div class="school"><span>Ekamai International School</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="CC">
								<img data-dark="false" src="/resource/images/participant/1968709139.jpg" alt="CC">
								<div class="play" data-href="668695067730409">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="Bonus">
								<img data-dark="false" src="/resource/images/participant/1582911614.jpg" alt="Bonus">
								<div class="play" data-href="702631710771661">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info">
							<div class="prize"><span>2<sup>nd</sup> Runner Up</span></div>
							<div class="name"><span>Pichamon Kasosot</span></div>
							<div class="school"><span>Nareerat School Phrae</span></div>
						</div>
					</div>
					<center><p><?=$_COOKIE['set_lang']=="th"?"ระดับมัธยมศึกษาตอนปลาย":"High School"?></p></center>
					<div class="card right">
						<div class="info longnme longsch">
							<div class="prize"><span>Winner</span></div>
							<div class="name"><span>Panitpicha Sathitamorntham</span></div>
							<div class="school"><span>Mahidol University International Demonstration School</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Pancake">
								<img data-dark="false" src="/resource/images/participant/2152004955.jpg" alt="Pancake">
								<div class="play" data-href="294366869528747">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
					</div>
					<div class="card left">
						<div class="avatar">
							<div class="wrapper" data-title="Ping">
								<img data-dark="false" src="/resource/images/participant/2246546115.jpg" alt="Ping">
								<div class="play" data-href="690480452098227">
									<div class="link"><i class="material-icons">play_circle_outline</i></div>
									<label>Watch</label>
								</div>
							</div>
						</div>
						<div class="info longnme">
							<div class="prize"><span>1<sup>st</sup> Runner Up</span></div>
							<div class="name"><span>Sirisopha Ekarattanawong</span></div>
							<div class="school"><span>Suankularbwittayalai Rangsit</span></div>
						</div>
					</div>
					<div class="card right">
						<div class="info">
							<div class="prize"><span>2<sup>nd</sup> Runner Up</span></div>
							<div class="name"><span>Natsinee Rawipong</span></div>
							<div class="school"><span>Mater Dei School</span></div>
						</div>
						<div class="avatar">
							<div class="wrapper" data-title="Prim">
								<img data-dark="false" src="/resource/images/participant/973119883.jpg" alt="Prim">
								<div class="play" data-href="3224520557865403">
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
									<a href="https://bod.in.th/!PSC-V_s02a" role="button" class="cyan dont-ripple" target="_blank">Elementary Level</a>
								</div>
								<div class="group">
									<span><i class="material-icons">queue_music</i></span>
									<a href="https://bod.in.th/!PSC-V_s02b" role="button" class="cyan dont-ripple" target="_blank">Middle School</a>
								</div>
								<div class="group">
									<span><i class="material-icons">queue_music</i></span>
									<a href="https://bod.in.th/!PSC-V_s02c" role="button" class="cyan dont-ripple" target="_blank">High School</a>
								</div>
							</div>
							<a href="/go?url=https%3A%2F%2Ffacebook.com%2FPathway.speechcontest%2Fposts%2F___" role="button" class="blue dont-ripple" target="_blank">
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