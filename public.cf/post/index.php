<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Posts";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main .list {
				--bds: solid var(--clr-pp-green-900);
				margin-top: 15px !important; padding-left: 25px;
				list-style-type: none;
			}
			main .list li { border-top: 1.25px var(--bds); }
			main .list li:last-child { border-bottom: 1.25px var(--bds); }
			main .list .wrapper { display: flex; transition: var(--time-tst-xfast); }
			main .list .wrapper:hover {
				background-color: var(--fade-white-7);
				text-decoration: none;
			}
			main .list .wrapper .banner {
				margin-right: 5px;
				width: 225px; height: 150px;
				overflow: hidden;
			}
			main .list .wrapper .banner img {
				width: 100%; height: 100%; transform: scale(0.925);
				filter: drop-shadow(1.25px 2.5px 1.25px var(--fade-black-6));
				object-fit: contain;
				transition: var(--time-tst-fast) ease;
			}
			main .list .wrapper .banner[eimg] img {
				transform: scale(0.975);
				filter: drop-shadow(0px 0px 2.5px var(--fade-black-5));
				object-fit: cover;
			}
			main .list .wrapper:hover .banner img {
				transform: scale(1.025);
				/* object-fit: cover; */
			}
			main .list .wrapper .content {
				padding-left: 5px;
				max-width: calc(100% - 230px - 5px);
				color: var(--clr-main-black-absolute);
			}
			main .list .wrapper .content .title { margin: 10px 2.5px; }
			main .list .wrapper .content .breif {
				margin: 5px;
				max-height: calc(150px - 47px - 10px);
				color: var(--clr-bs-gray-dark); white-space: pre-wrap;
			}
			/* main .list > hr { margin: 12.5px 2.5px; } */
			@media only screen and (max-width: 767px) {
				main .list { margin-top: 10px !important; padding-left: 5px; } 
				main .list .wrapper .banner {
					margin-right: 2.5px;
					width: 100px; height: 85px;
				}
				main .list .wrapper .content {
					padding-left: 2.5px;
					max-width: calc(100% - 130px - 2.5px);
				}
				main .list .wrapper .content .title { margin: 5px 2.5px; }
				main .list .wrapper .content .breif {
					margin: 2.5px;
					max-height: calc(85px - 37px - 5px);
				}
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				setTimeout(function() { Grade(document.querySelectorAll("main .list .wrapper .banner:not([eimg])")); }, 250);
				// setTimeout(function() { Grade(document.querySelectorAll("main .list .wrapper .banner[eimg]")); }, 1250);
				$("main .list .wrapper .content > *").addClass("txtoe");
			});
		</script>
		<script type="text/javascript" src="/resource/js/lib/grade.min.js"></script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2><?=$_COOKIE['set_lang']=="th"?"ประกาศ":"Posts"?></h2>
				<ul class="list">
					<!--li><a class="wrapper" href="">
						<div class="banner">
							<img src="" alt="" data-dark="false">
						</div>
						<div class="content">
							<h3 class="title"></h3>
							<p class="breif"></p>
						</div>
					</a></li-->
					<li><a class="wrapper" href="2022-04-17">
						<div class="banner">
							<img src="/resource/images/PathwaySpeechContest.png" alt="Season 2 Results" data-dark="false">
						</div>
						<div class="content">
							<h3 class="title">Season 2 Results</h3>
							<p class="breif">Ranks announcement for "World Health Day" topic</p>
						</div>
					</a></li>
					<li><a class="wrapper" href="2022-01-26">
						<div class="banner">
							<img src="/resource/images/PathwaySpeechContest.png" alt="DPF.org Donation" data-dark="false">
						</div>
						<div class="content">
							<h3 class="title">Duang Prateep Foundation Donation</h3>
							<p class="breif">Donating funds to Duang Prateep Foundation</p>
						</div>
					</a></li>
					<li><a class="wrapper" href="2022-01-21">
						<div class="banner">
							<img src="/resource/images/PathwaySpeechContest.png" alt="Season 1 Results" data-dark="false">
						</div>
						<div class="content">
							<h3 class="title">Season 1 Results</h3>
							<p class="breif">Ranks announcement for "New Year's Day" topic</p>
						</div>
					</a></li>
					<li><a class="wrapper" href="2021-12-11">
						<div class="banner" eimg>
							<img src="/resource/images/news/workshop-1.png" alt="1st Workshop" data-dark="false">
						</div>
						<div class="content">
							<h3 class="title">1<sup>st</sup> Workshop</h3>
							<p class="breif">Online conference, focusing on speech structure</p>
						</div>
					</a></li>
				</ul><!hr><ul class="list">
					<li><a class="wrapper" href="Instagram">
						<div class="banner">
							<img src="/resource/images/nav-share-instagram.png" alt="Instagram posts" data-dark="false">
						</div>
						<div class="content">
							<h3 class="title">Instagram posts</h3>
							<p class="breif">Posts from our Instagram account</p>
						</div>
					</a></li>
				</ul>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>