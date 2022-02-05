<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "ประกาศผล";
	$header_desc = "Ranking result announcement";

	$permitted = true; # has_perm("dev", false);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main .rankrs { color: #0f5132; }
			main .rankrs > h3 {
				margin: 5px 0px 10px;
				transform: scale(1.125);
				font-size: 1.5em; text-align: center;
			}
			main .rankrs p {
				margin: 0px 0px 10px;
				font-weight: bold; font-size: 1.25em;
			}
			main .rankrs .card {
				margin: 0px 17.5px 17.5px; padding: 15px;
				border-radius: 15px;
				box-shadow: 1.25px 1.25px var(--shd-big) var(--fade-black-6);
				display: flex; align-items: center;
				transition: var(--time-tst-xfast) ease-out;
			}
			main .rankrs .card.left { justify-content: flex-start; }
			main .rankrs .card.right { justify-content: flex-end; }
			main .rankrs .card:last-child { margin: 0px 17.5px 15px; }
			main .rankrs center + .card { background-color: rgba(254, 226, 215, 0.5); }
			main .rankrs center + .card + .card { background-color: rgba(252, 229, 220, 0.5); }
			main .rankrs center + .card + .card + .card { background-color: rgba(255, 236, 228, 0.5); }
			main .rankrs center + .card:hover { background-color: rgba(254, 226, 215, 0.75); }
			main .rankrs center + .card + .card:hover { background-color: rgba(252, 229, 220, 0.75); }
			main .rankrs center + .card + .card + .card:hover { background-color: rgba(255, 236, 228, 0.75); }
			main .rankrs .card .avatar {
				width: 125px; min-height: 125px; height: 100%;
				display: flex; justify-content: center; align-items: center;
				transition: var(--time-tst-xfast) ease-out;
			}
			main .rankrs .card.left .avatar { margin-right: 12.5px; }
			main .rankrs .card.right .avatar { margin-left: 12.5px; }
			main .rankrs .card.left:hover .avatar { margin-right: 17.5px; }
			main .rankrs .card.right:hover .avatar { margin-left: 17.5px; }
			main .rankrs .card .avatar .wrapper {
				width: 100px; height: 100px;
				display: flex; justify-content: center; align-items: center;
				transition: var(--time-tst-xfast) ease-out;
			}
			main .rankrs .card:hover .avatar .wrapper { width: 125px; height: 125px; }
			main .rankrs .card .avatar .wrapper img {
				/* transform: scale(1.025); */
				width: inherit; height: inherit;
				border-radius: 50%; border: 1px solid var(--clr-pp-green-a700);
				object-fit: contain;
			}
			main .rankrs .card .avatar .wrapper .play {
				position: absolute;
				width: inherit; height: inherit;
				border-radius: 50%; overflow: hidden;
				transition: var(--time-tst-xfast) ease-in;
			}
			main .rankrs .card .avatar .wrapper:hover .play { background-color: #CEF4D667; }
			main .rankrs .card .avatar .wrapper .play[data-href] { cursor: pointer; }
			main .rankrs .card .avatar .wrapper .play .link {
				width: 100%; height: 100%;
				display: flex; justify-content: center; align-items: center;
			}
			main .rankrs .card .avatar .wrapper .play .link i {
				transform: scale(0);
				color: var(--clr-main-white-absolute); text-shadow: 0px 0px var(--shd-small) var(--clr-gg-grey-500);
				transition: var(--time-tst-xfast) ease-out;
			}
			main .rankrs .card .avatar .wrapper:hover .play .link i { transform: scale(1.875); }
			main .rankrs .card .avatar .wrapper .play label {
				width: 100%; height: 22.5%;
				font-size: 0.75em; color: #F37F06;
				background-color: var(--clr-gg-green-700);
				display: flex; justify-content: center; align-items: center;
				transition: var(--time-tst-xfast) ease;
			}
			main .rankrs .card .avatar .wrapper:hover .play label {
				transform: translateY(-100%);
				background-color: #047326;
			}
			main .rankrs .card .info {
				grid-template-columns: 1fr; grid-template-rows: 3.5fr 3fr 2fr;
				display: grid;
			}
			main .rankrs .card .info > div { display: flex; align-items: center; }
			main .rankrs .card.left .info > div { justify-content: left; }
			main .rankrs .card.right .info > div { justify-content: right; }
			main .rankrs .card .info .prize { grid-area: 1 / 1 / 2 / 2; }
			main .rankrs .card .info .name { grid-area: 2 / 1 / 3 / 2; }
			main .rankrs .card .info .school { grid-area: 3 / 1 / 4 / 2; }
			main .rankrs .card .info div > span { display: block; }
			main .rankrs .card.right .info div > span { text-align: right; }
			main .rankrs .card .info .prize > span {
				padding: 2.5px 12.5px;
				background-color: var(--clr-psc-skin-high); border-radius: 75px;
				font-size: 1.75em; font-weight: bold; white-space: nowrap;
			}
			main .rankrs .card .info .name > span {
				padding: 0px 5px;
				font-size: 1.375em;
			}
			main .rankrs .card .info .school > span {
				padding: 0px 5px;
				font-size: 1.025em;
			}
			main .rankrs div.form h3 { margin: 0px 0px 10px; }
			main .rankrs div.form h3 > span { font-weight: normal; }
			main .rankrs div.form .group.split .lists { padding-left: 7.5px; }
			main .rankrs div.form .group:not(.split):not(:last-child) { margin-bottom: 10px; }
			main .rankrs div.form .group.split > a { display: flex; flex-direction: column; justify-content: center; }
			main .rankrs div.form .group.split > a img {
				transform: scale(0.75);
				width: 100px; height: 100px;
				filter: brightness(2.5) contrast(0.5);
				object-fit: contain;
			}
			@media only screen and (max-width: 768px) {
				main .rankrs > h3 { margin: 12.5px 0px 10px; }
				main .rankrs .card { margin: 0px 2.5px 12.5px; padding: 7.5px; }
				main .rankrs .card:last-child { margin: 0px 2.5px 5px; }
				main .rankrs .card .avatar {
					width: 100px; min-height: 100px; height: 100%;
					/* align-items: baseline; */
				}
				main .rankrs .card.left .avatar { margin-right: 5px; }
				main .rankrs .card.right .avatar { margin-left: 5px; }
				main .rankrs .card.left:hover .avatar { margin-right: 10px; }
				main .rankrs .card.right:hover .avatar { margin-left: 10px; }
				main .rankrs .card .avatar .wrapper { width: 80px; height: 80px; }
				main .rankrs .card:hover .avatar .wrapper { width: 100px; height: 100px; }
				main .rankrs .card .info.longnme { grid-template-rows: 1.5fr 1fr 1.5fr; }
				main .rankrs .card .info.longnme.longsch { grid-template-rows: 1.5fr 2fr 1.5fr; }
				main .rankrs .card .info.longsch { grid-template-rows: 2.5fr 2fr 3fr; }
				main .rankrs .card .info .school > span { font-size: 0.975em; }
				main .rankrs .card .info.longsch .school > span { font-size: 0.875em; }
			}
		</style>
		<link rel="stylesheet" href="/resource/css/extend/post.css">
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
								<img data-dark="false" src="/resource/images/participant-779442320.jpg" alt="Manie">
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
								<img data-dark="false" src="/resource/images/participant-799891427.jpg" alt="Khokhao">
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
								<img data-dark="false" src="/resource/images/participant-4214550375.jpg" alt="Punpun">
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
								<img data-dark="false" src="/resource/images/participant-1679553582.jpg" alt="Proud">
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
								<img data-dark="false" src="/resource/images/participant-1819274625.jpg" alt="Nita">
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
								<img data-dark="false" src="/resource/images/participant-2864276859.jpg" alt="Idea">
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
								<img data-dark="false" src="/resource/images/participant-398661705.jpg" alt="Kung">
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
								<img data-dark="false" src="/resource/images/participant-2908309946.jpg" alt="Indy">
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
								<img data-dark="false" src="/resource/images/participant-2171474313.jpg" alt="Anna">
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