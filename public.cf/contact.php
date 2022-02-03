<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Contact us";
	$header_desc = "ช่องทางการติดต่อสอบถาม";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			html body main div.container { overflow: visible; }
			html body main div.container div.app-logo {
				--cardW: calc(320px - 2px); --cardH: calc(420px - 2px);
				padding: 5px 0px;
				height: calc(var(---cardH) + 2px);
				display: flex; justify-content: space-evenly;
			}
			div.app-logo div.card {
				margin: 0px 2.5px;
				min-width: var(--cardW); width: var(--cardW); height: var(--cardH);
				border-radius: 7.5px; border: 1px solid var(--clr-pp-teal-a700);
				background-color: var(--clr-bs-light);
				overflow: hidden; transition: var(--time-tst-xfast);
			}
			div.app-logo div.card:hover {
				transform: scale(0.95) translateY(-7.5px);
				border: 1px solid transparent;
				box-shadow: 0px 0px var(--shd-big) var(--fade-black-6);
			}
			div.app-logo div.card a {
				width: 100%; height: 100%;
				display: flex; flex-direction: column;
			}
			div.app-logo div.card a:hover { text-decoration: none !important; }
			div.app-logo div.card a * { transition: var(--time-tst-xfast); }
			div.app-logo div.card a div.img-container { width: var(--cardW); height: var(--cardW); }
			div.app-logo div.card:hover a div.img-container { height: calc(var(--cardW) - 120px); }
			div.app-logo div.card a div.img-container * { transition: calc(var(--time-tst-xfast) * 1.5) ease-out; }
			div.app-logo div.card a div.img-container div.imgfill {
				width: calc(var(--cardW) - 2px); height: calc(var(--cardW) - 2px);
				border: 1px solid transparent; border-bottom: 1px solid var(--clr-pp-teal-a700);
				overflow: hidden; object-fit: contain;
			}
			div.app-logo div.card:hover a div.img-container div.imgfill {
				transform: translateY(-40px) scale(0.575);
				border-radius: 50%; border: 1px solid var(--clr-pp-teal-a700);
			}
			/* div.app-logo div.card a div.img-container div.imgfill:before {
				width: 100%; height: 100%;
				background-color: var(--fade-white-6);
				backdrop-filter: blur(2.5px);
				display: block;
			} */
			div.app-logo div.card a div.img-container div.imgfill img {
				transform: scale(0.75);
				width: inherit; height: inherit;
				filter: drop-shadow(2.5px 5px 6.25px var(--clr-main-black-absolute));
				cursor: pointer;
			}
			div.app-logo div.card:hover a div.img-container div.imgfill img { transform: scale(1); }
			div.app-logo div.card a div.linker {
				width: 100%; height: calc(100% - var(--cardW));
				display: flex; flex-direction: column; justify-content: space-evenly;
				transition: calc(var(--time-tst-xfast) * 0.75) linear;
			}
			div.app-logo div.card:hover a div.linker { height: calc(100% - var(--cardW) + 120px); }
			div.app-logo div.card a div.linker * { transition: calc(var(--time-tst-xfast) * 0.75) linear; }
			div.app-logo div.card a div.linker > * {
				margin: 5px; padding: 0px 7.5px;
				width: calc(100% - 15px);
				color: var(--clr-main-black-absolute);
				display: block;
			}
			div.app-logo div.card:hover a div.linker > * { justify-content: center; }
			div.app-logo div.card:hover a div.linker > *:nth-child(1) { font-size: 1.5em; }
			div.app-logo div.card:hover a div.linker > *:nth-child(2) { font-size: 1.25em; }
			div.app-logo div.card:hover a div.linker > * { justify-content: center; }
			div.app-logo div.card a div.linker span {
				transform: translateX(0%);
				display: inline-block;
			}
			div.app-logo div.card:hover a div.linker span { transform: translateX(calc(-50% + 151.5px)); }
			div.app-logo div.card a div.linker > * > font { color: var(--clr-bs-gray); }
			html body main div.container div.msn-chat, div.msn-chat * { --chatH: 200px; --chatS: 125px; }
			html body main div.container div.msn-chat {
				padding: 7.5px 10px;
				height: var(--chatH);
				display: flex;
			}
			div.msn-chat div.head {
				--moveP: translate(calc((var(--chatH) - var(--chatS)) * 7 / 20), calc((var(--chatH) - var(--chatS)) * 2 / 5));
				width: var(--chatH); height: var(--chatH);
			}
			div.msn-chat div.head a {
				transform: var(--moveP);
				width: var(--chatS); height: var(--chatS);
				border-radius: 50%;
				object-fit: contain;
				display: block; overflow: hidden;
				transition: var(--time-tst-fast) ease-out;
			}
			div.msn-chat div.head a:hover { transform: var(--moveP) scale(1.125); }
			div.msn-chat div.head a div.imgfill {
				width: inherit; height: inherit;
			}
			div.msn-chat div.head a div.imgfill img {
				transform: scale(0.75);
				width: inherit; height: inherit;
				filter: drop-shadow(2.5px 5px 6.25px var(--clr-main-black-absolute));
				cursor: pointer;
			}
			div.msn-chat div.head span.tap {
				position: absolute; transform: var(--moveP);
				width: var(--chatS); height: var(--chatS);
				pointer-events: none;
			}
			div.msn-chat div.msg-area { width: calc(100% - var(--chatH)); height: 100%; }
			div.msn-chat div.msg-area div.msg-box {
				padding: 5px 0px;
				transform: translate(15px, 20px);
				width: 480px; height: 95px;
				border-radius: 12.5px; border: 1.875px solid var(--clr-bs-dark);
				background-color: var(--clr-pp-grey-200);
				display: flex; flex-direction: column; justify-content: space-evenly;
				transition: var(--time-tst-fast) ease-out;
			}
			div.msn-chat div.head:hover + div.msg-area div.msg-box { transform: translate(-5px, 35px); }
			div.msn-chat div.msg-area div.msg-box > * { margin: 7.5px 0px; padding: 0px 10px; }
			div.msn-chat div.msg-area div.msg-box > h4 {
				height: 42px;
				line-height: 35px; font-size: 1.25em;
			}
			div.msn-chat div.msg-area div.msg-box > p {
				height: 23px;
				line-height: 20px; font-size: 1em;
			}
			@media only screen and (max-width: 768px) {
				html body main div.container div.app-logo {
					height: calc((var(--cardH) + 12.5px) * 3);
					flex-direction: column;
				}
				div.app-logo div.card { margin: 0px auto; }
				div.app-logo div.card:hover { transform: scale(0.95); }
				html body main div.container div.msn-chat, div.msn-chat * { --chatH: 150px; --chatS: 70px; }
				div.msn-chat div.head { --moveP: translate(calc((var(--chatH) - var(--chatS)) * 3 / 10), calc((var(--chatH) - var(--chatS)) * 1 / 5)); }
				div.msn-chat div.msg-area div.msg-box {
					transform: translate(-25px, 0px);
					width: 210px; height: 50px;
				}
				div.msn-chat div.head:hover + div.msg-area div.msg-box { transform: translate(-40px, 10px); }
				div.msn-chat div.msg-area div.msg-box > h4 { height: 12px; line-height: 12px; }
				div.msn-chat div.msg-area div.msg-box > p { height: 8px; line-height: 8px; }
			}
			@media only screen and (overflow-block: scroll) {
				html body main div.container div.app-logo { justify-content: flex-start; }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				setTimeout(function() {
					Grade(document.querySelectorAll("/* div.app-logo div.card a div.img-container */ div.imgfill"));
				}, (navigator.userAgent.indexOf("Mac OS")>-1?500:50));
				Facebook_chat()
			});
			function Facebook_chat() {
				var chatbox = document.getElementById('fb-customer-chat');
				chatbox.setAttribute("page_id", "111070124738792");
				chatbox.setAttribute("attribution", "biz_inbox");
				window.fbAsyncInit = function() { FB.init({ xfbml : true, version : 'v12.0' }); };
				(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
			}
		</script>
		<script type="text/javascript" src="/resource/js/lib/grade.min.js"></script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2 id="shortcuts">Social medias</h2>
				<div class="app-logo slider">
					<div class="card platform"><a href="/go?url=https%3A%2F%2Ffb.me%2FPathway.speechcontest" target="_blank">
						<div class="img-container">
							<div class="imgfill"><img src="/resource/images/nav-share-facebook.png" data-dark="false"></div>
						</div>
						<div class="linker">
							<h4><span>Facebook</span></h4>
							<p><span>Pathway.speechcontest</span></span></p>
						</div>
					</a></div>
					<div class="card platform"><a href="/go?url=https%3A%2F%2Finstagr.am%2FPathway.speechcontest" target="_blank">
						<div class="img-container">
							<div class="imgfill"><img src="/resource/images/nav-share-instagram.png" data-dark="false"></div>
						</div>
						<div class="linker">
							<h4><span>Instagram</span></h4>
							<p><span><font>@</font>Pathway.speechcontest</p>
						</div>
					</a></div>
					<div class="card platform"><a href="/go?url=mailto%3APathway.SpeechContest@gmail.com" target="_blank">
						<div class="img-container">
							<div class="imgfill"><img src="/resource/images/nav-share-gmail.png" data-dark="false"></div>
						</div>
						<div class="linker">
							<h4><span>E-mail address</span></h4>
							<p><span>Pathway.speechcontest@gmail.com</span></p>
						</div>
					</a></div>
				</div>
				<p>Inbox are open for messaging.</p>
				<div class="msn-chat">
					<div class="head">
						<span class="tap -suggest-click"></span>
						<a href="/go?url=https%3A%2F%2Fm.me%2FPathway.speechcontest" target="_blank">
							<div class="imgfill"><img src="/resource/images/nav-share-messenger.png" data-dark="false"></div>
						</a>
					</div>
					<div class="msg-area"><div class="msg-box">
						<h4><span>Messenger</span></h4>
						<p><span>Pathway.speechcontest</span></p>
					</div></div>
				</div>
				<p>Feel free to chat together with us.</p>
				<div class="msn-chat">
					<div class="head">
						<span class="tap -suggest-click"></span>
						<a href="/go?url=https%3A%2F%2Fline.me%2Fti%2Fg2%2FiJPG9CO3YDuIWnvEZ-bQhEGMytEegoW-I3mJtQ" target="_blank"><!-- instagr.am%2Fdirect%2Ft%2F340282366841710300949128190289155194062 -->
							<div class="imgfill"><img src="/resource/images/nav-share-line.png" data-dark="false"></div>
						</a>
					</div>
					<div class="msg-area"><div class="msg-box">
						<h4><span>Line</span></h4>
						<p><span>Open chat</span></p>
					</div></div>
				</div>
			</div>
		</main>
		<div id="fb-root"></div><div id="fb-customer-chat" class="fb-customerchat"></div>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>