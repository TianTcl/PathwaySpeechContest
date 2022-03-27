<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Teams";
	$header_desc = "คณะผู้ประสานงานโครงการ";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css" media="max-width: 0px">
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
			main div.wrapper div.people > :not(.image) {
				height: calc(75px / 2);
				line-height: calc(75px / 2); text-align: center;
			}
			main div.wrapper div.people > .role { font-size: 1.025rem; }
			@media only screen and (max-width: 768px) {
				main div.wrapper div.people > .role { font-size: 0.75rem; }
			}
		</style>
		<style type="text/css">
			main .wrapper {
				--tabAmt: 3;
				/* border-radius: 10px; box-shadow: 0px 0px var(--shd-big) var(--fade-black-7); */
			}
			main .wrapper div.tab {
				margin: 0px;
				/* border-radius: 10px 10px 0px 0px; */
				display: flex; overflow: hidden;
			}
			main .wrapper div.tab div {
				padding: 7.5px 10px;
				width: 100%; height: 30px;
				line-height: 30px; text-align: center;
				cursor: pointer; transition: var(--time-tst-xfast) ease;
			}
			main .wrapper div.tab div:hover { background-color: var(--clr-psc-green-light-shiny); }
			main .wrapper div.tab div.active {
				background-color: var(--fade-black-8);
				/* border-radius: 10px 10px 0px 0px; */
				pointer-events: none;
			}
			main .wrapper div.tab + span.bar-responsive {
				margin-bottom: 0px;
				transform: translate(calc(100% * var(--show)), -100%);
				width: calc(100% / var(--tabAmt)); height: 2.5px;
				background-color: var(--clr-psc-green-dark-high);
				display: block; transition: var(--time-tst-xfast);
				pointer-events: none;
			}
			/* main .wrapper div.tab:active + span.bar-responsive { animation: bar_moving var(--time-tst-fast) ease 1; } */
			@keyframes bar_moving {
				0%, 100% { width: calc(100% / var(--tabAmt)); }
				5%, 95% { width: calc(100% / var(--tabAmt) * 1.25); }
				50% { width: calc(100% / var(--tabAmt) * 0.75); }
			}
			main .wrapper div.tbs { transform: translateY(-2.5px); }
			main .wrapper div.tbs > div {
				padding: 5px;
				width: calc(100% - 10px);
				border-radius: 0px 0px 10px 10px;
				display: flex; flex-direction: column;
			}
			main div.role, main div.role .people, main div.role .people a.bio { display: flex; flex-direction: column; }
			main .wrapper div.tbs > div *:not(.people):not(:last-child) { margin: 0px 0px 10px; }
			main div.role h3 {
				margin: 17.5px 0px 1.25px;
				transform: scale(1.125);
				text-align: center;
			}
			main .subrole div.role h3 {
				margin: 0px;
				transform: none;
			}
			main div.role .member { display: flex; justify-content: center; flex-wrap: wrap; }
			main :not(.subrole) > div.role > div.member, main .subrole div.role {
				padding: 5px;
				border-radius: 7.5px;
				transition: var(--time-tst-fast);
			}
			main :not(.subrole) > div.role > div.member:hover, main .subrole div.role:hover { background-color: var(--fade-white-5); }
			main div.role .people {
				margin: 7.5px;
				width: 175px; height: 225px;
				border-radius: 5px; background-color: var(--clr-psc-skin-low);
				box-shadow: 1.25px 5px var(--shd-small) var(--fade-black-7);
			}
			main div.role .people .avatar {
				height: 175px;
				border-radius: 5px 5px 0px 0px;
				overflow: hidden;
			}
			main div.role .people .avatar > img {
				width: 100%; height: 100%;
				object-fit: scale-down;
			}
			main div.role .people .bio { padding: 5px 0px; }
			main div.role .people .bio .name { font-family: "Krub", "IBM Plex Sans Thai"; }
			@media only screen and (min-width: 768.003px) {
				main .subrole div.role h3 { text-align: left; }
				main .subrole div.role .member { justify-content: flex-start; }
			}
			@media only screen and (max-width: 768px) {
				main div.role h3 { transform: scale(1.25); }
				main div.role .people { width: 150px; height: 200px; }
				main div.role .people .avatar { height: 150px; }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				teams.start();
			});
			const teams = (function() {
				const cv = { season: 2 };
				var tdi, sv = { rendered: [] };
				var init = function() {
					// Get configuration
					$.getJSON("/resource/json/config-team.json", function(res) {
						save_config(res);
						seek_param(); // load(cv.season);
					}); const save_config = data => tdi = data;
					sv.lang = ppa.getCookie("set_lang");
				};
				function seek_param() { // Modified
					var hash = {};
					if (location.hash.length > 1) {
						// Extract hashes
						location.hash.substring(1, location.hash.length).split("&").forEach((ehs) => {
							let ths = ehs.split("=");
							hash[ths[0]] = ths[1];
						});
						// history.replaceState(null, null, location.pathname);
					} // Let's see
					if (typeof hash.season !== "undefined") load(parseInt(hash.season));
					else load(cv.season);
				}
				var load = function(season) {
					if (!sv.rendered.includes(season)) read_and_render(season);
					change_to_tab(season);
				};
				var change_to_tab = function(what) {
					var tab = (parseInt(what) - 1).toString();
					$("main .wrapper div.tab div.active").removeClass("active");
					$('main .wrapper div.tab div[onClick$="show('+what.toString()+')"]').addClass("active");
					$("main .wrapper div.tab + span.bar-responsive").css("--show", tab);
					$("main .wrapper div.tbs > div").hide();
					$('main .wrapper div.tbs > div[order="'+tab+'"]').show();
					history.replaceState(null, null, location.pathname+"#season="+what.toString());
				};
				var read_and_render = function(season) {
					if (sv.rendered.includes(season)) return;
					sv.rendered.push(season);
					// setTimeout(()=>{console.log(tdi)},125);
					sv.info = tdi.ss[season - 1]; sv.display = "";
					sv.info["order"].forEach(eachRole => {
						var has_member = (sv.info[eachRole] != null && sv.info[eachRole].length);
						sv.display += '<div class="role">';
						sv.display += '<div class="title"><h3>'+tdi.mnf[eachRole][sv.lang]+'</h3></div>';
						if (has_member) {
							sv.display += '<div class="member">';
							sv.info[eachRole].forEach(eachMbr => {
								var mbr = tdi.ppl[eachMbr];
								sv.display += '<div class="people">';
								sv.display += '<div class="avatar'+(mbr.avatar == null ? "" : " real")+'">';
								if (mbr.avatar == null) mbr.avatar = "default.jpg";
								sv.display += '<img src="/resource/images/people-'+mbr.avatar+'" data-dark="false" draggable="false" alt="Avatar">';
								sv.display += '</div><div class="bio">';
								sv.display += '<div class="name"><center><span data-title="'+mbr.fullname+'">'+mbr.nickname+'</span></center></div>';
								sv.display += '</div></div>';
							}); sv.display += '</div>';
						} if (typeof tdi.tree[eachRole] !== "undefined" && tdi.tree[eachRole].length) {
								sv.display += '<div class="subrole">';
								tdi.tree[eachRole].forEach(readRole);
								sv.display += '</div>';
						} sv.display += '</div>';
					}); var tab = (parseInt(season) - 1).toString();
					$('main .wrapper div.tbs > div[order="'+tab+'"]').html(sv.display);
					delete sv.info, sv.display;
					setTimeout(function() { Grade(document.querySelectorAll('main div[order="'+tab+'"] div.people > .avatar:not(.real)')); }, 250);
					setTimeout(function() { Grade(document.querySelectorAll('main div[order="'+tab+'"] div.people > .avatar.real')); }, 750);
					setTimeout(function() { Grade(document.querySelectorAll('main div[order="'+tab+'"] div.people > .avatar.real')); }, 2500);
					<?php if (has_perm("dev")) echo '$(\'main div[order="\'+tab+\'"] div.member\').sortable();'; ?>
				};
				var readRole = eachRole => {
					if (sv.info[eachRole] != null && sv.info[eachRole].length) {
						sv.display += '<div class="role">';
						sv.display += '<div class="title"><h3>'+tdi.mnf[eachRole][sv.lang]+'</h3></div>';
						sv.display += '<div class="member">';
						sv.info[eachRole].forEach(eachMbr => {
							var mbr = tdi.ppl[eachMbr];
							sv.display += '<div class="people">';
							sv.display += '<div class="avatar'+(mbr.avatar == null ? "" : " real")+'">';
							if (mbr.avatar == null) mbr.avatar = "default.jpg";
							sv.display += '<img src="/resource/images/people-'+mbr.avatar+'" data-dark="false" draggable="false" alt="Avatar">';
							sv.display += '</div><div class="bio">';
							sv.display += '<div class="name"><center><span data-title="'+mbr.fullname+'">'+mbr.nickname+'</span></center></div>';
							sv.display += '</div></div>';
						}); sv.display += '</div>';
						sv.display += '</div>';
					}
				};
				return {
					start: init,
					show: load
				};
			}());
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
					<div class="tab">
						<div onClick="teams.show(1)">Season 1</div>
						<div onClick="teams.show(2)">Season 2</div>
						<div onClick="teams.show(3)" disabled>Season 3</div>
					</div><span class="bar-responsive"></span>
					<div class="tbs">
						<div order="0">

						</div>
						<div order="1">

						</div>
						<div order="2">

						</div>
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