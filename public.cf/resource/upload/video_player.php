<div class="player">
	<!--
		Required variables: {
			php : ["player_name", "player_duration", "player_thumbnail", "player_secured"],
			cookie : ["player_origin"],
			js : [bool"record", call"gen_blob()", call"jsinit(where=".player")"], // jsinit where attr is qSel for fullscr
			css : ["--lt-col", "--lt-stroke", "--lt-bgcol", "--li-dl", "--li-dd", "--li-dbs", "--bar-color", "--track-size"]
		}
	-->
	<style type="text/css">
		@import url('//fonts.googleapis.com/css2?family=Sarabun:wght@300&display=swap');
		/* Start normal alginment */
		html, body { margin: 0; padding: 0; }
		.player div.full, .player div.full.video div.view video {
			position: absolute;
			width: 100%; height: 100%;
		}
		.player div.full.video div.view video { z-index: 0; object-fit: contain; }
		.player div.full.video div.view video::-internal-media-controls-download-button { display: none; visibility: hidden; opacity: 0%; }
		.player div.full.video div.view video::-webkit-media-controls-enclosure { overflow: hidden; }
		.player div.full.video div.view video::-webkit-media-controls-panel { width: calc(100% + 30px); }
		.player div.full.video div.view div.load-text {
			position: absolute; top: 50%; transform: translateY(-50%); z-index: 2;
			width: 100%; /*height: 23px;*/
			color: rgba(var(--lt-col), 0.92); font-size: 17.25px; line-height: 23px; -webkit-text-stroke: 0.23px rgb(var(--lt-stroke)); text-align: center;
			background-image: linear-gradient(to right, rgba(var(--lt-bgcol), 0.138), rgba(var(--lt-bgcol), 0.575), rgba(var(--lt-bgcol), 0.138));
			pointer-events: none;
		}
		.player div.full.video div.view div.load-img {
			position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1;
			width: 138px; height: 138px;
			filter: url(#gooey); pointer-events: none;
		}
		.player div.full.video div.view div.load-img span {
			position: absolute; top: 0px; left: 0px;
			width: 100%; height: 100%;
			display: block; /*filter: blur(2.875px);*/
			animation: loading /*3.45s*/3.22s ease-in-out infinite; animation-delay: calc(/*0.1725s*/0.1955s * var(--di));
		}
		.player div.full.video div.view div.load-img span:before {
			position: absolute; top: 0px; left: calc(50% - 11.5px);
			width: 31.625px; height: 31.625px;
			content: ""; background: linear-gradient(rgba(var(--li-dl), 0.805), rgba(var(--li-dd), 0.805));
			border-radius: 50%; box-shadow: 0 0 4.6px rgba(var(--li-dbs), 0.805);
		}
		@keyframes loading {
			0% { transform: rotate(0deg); }
			50%,100% { transform: rotate(360deg); }
		}
		.player div.full.video div.view div.load-img svg { width: 0px; height: 0px; }
		.player div.full.control { z-index: 1; }
		.player div.full.control div.vid-name span {
			color: #FFF;
			font-size: 17.25px; line-height: 34.5px; font-family: 'Sarabun', sans-serif;
			white-space: nowrap; text-overflow: ellipsis;
		}
		.player div.full.control div.media *[data-title]:hover:before { display: none; /* Fix new CSS */ }
		.player div.full.control div.media *[data-title]:hover:after {
			padding: 0px 5.75px;
			position: absolute; top: calc(-100% - 5.75px); left: var(--px);
			transform: none; /* Fix new CSS */
			width: auto; height: 23px;
			color: rgba(230, 230, 230, 0.805); -webkit-text-stroke: 0.23px rgba(115, 115, 115, 0.805); white-space: nowrap;
			background-color: rgba(0, 0, 0, 0.805); border-radius: 2.3px; border: 0.92px solid rgba(69, 69, 69, 0.805);
			box-shadow: none; backdrop-filter: opacity(0.75) blur(0.25px);
			font-size: 11.5px; line-height: 23px; font-weight: 400; font-family: 'Sarabun', sans-serif;
			display: block; content: attr(data-title); pointer-events: none;
		}
		.player div.full.control div.media div.trackbar input:after { transform: translateY(-17.25px) !important; }
		.player div.full.control div.media div.settings:after { top: 7.1875px !important; }
		.player div.full.control div.media div.trackbar {
			position: absolute; top: calc(17.25px - var(--track-size));
			width: 100%; height: var(--track-size);
		}
		.player div.full.control div.media div.trackbar input {
			margin: 0px;
			position: absolute; z-index: 0;
			width: inherit; height: inherit;
			background: #232323; outline: none; transition: 0.092s;
			-webkit-appearance: none; cursor: pointer;
		}
		.player div.full.control div.media div.trackbar input::-webkit-slider-thumb {
			-webkit-appearance: none; appearance: none;
			width: var(--track-size); height: var(--track-size);
			background-color: rgba(0,0,0,0) /*var(--bar-color)*/; transition: 0.092s;
		}
		.player div.full.control div.media div.trackbar input::-moz-range-thumb {
			width: var(--track-size); height: var(--track-size);
			background-color: rgba(0,0,0,0) /*var(--bar-color)*/; transition: 0.092s;
		}
		.player div.full.control div.media div.trackbar input:hover { height: calc(var(--track-size) * 17 / 12); transform: translateY(calc(var(--track-size) * -5 / 12)); }
		.player div.full.control div.media div.trackbar input:hover::-webkit-slider-thumb {
			transform: translateY(calc(var(--track-size) * 1 / 8));
			width: calc(var(--track-size) * 3); height: calc(var(--track-size) * 3);
			border-radius: 50%;
		}
		.player div.full.control div.media div.trackbar div.prg {
			margin: 0px;
			position: absolute; z-index: 3;
			height: inherit;
			background: var(--bar-color); /* transition: 0.092s; */
			pointer-events: none;
		}
		.player div.full.control div.media div.trackbar div.prg:after {
			position: absolute; top: 50%; right: 0px; transform: translate(50%, -57.5%);
			width: var(--track-size); height: var(--track-size);
			background-color: var(--bar-color); transition: 0.092s;
			display: block; content: ""; pointer-events: none;
		}
		.player div.full.control div.media div.trackbar:hover div.prg:after {
			transform: translate(50%, -50%) translateY(calc(var(--track-size) * 1 / 8));
			width: calc(var(--track-size) * 3); height: calc(var(--track-size) * 3);
			border-radius: 50%;
		}
		.player div.full.control div.media div.trackbar div.prv {
			margin: 0px;
			position: absolute; z-index: 2;
			height: inherit;
			background: rgba(138, 138, 138, 0.69); /* transition: 0.092s; */
			pointer-events: none;
		}
		.player div.full.control div.media div.trackbar:hover div { height: 5.75px; transform: translateY(-1.725px); }
		.player div.full.control div.media div img {
			width: 28.75px; height: 28.75px;
			filter: invert(100%);
		}
		.player div.full.control div.media div.lt { float: left; }
		.player div.full.control div.media div.rt { float: right; }
		.player div.full.control div.media div.lt, .player div.full.control div.media div.rt {
			position: relative; top: 17.25px;
			width: 28.75px; height: 28.75px;
		}
		.player div.full.control div.media div.playing, .player div.full.control div.media div.seek { cursor: pointer; }
		.player div.full.control div.media div.seek img { filter: invert(100%) blur(0.5175px); transition: 0.138s; }
		.player div.full.control div.media div.seek:hover img { filter: invert(100%); }
		.player div.full.control div.media div.seek:active img { filter: invert(100%) drop-shadow(0px 0px 2.3px rgba(230, 230, 230, 0.69)); }
		.player div.full.control div.media div.volume { width: auto; }
		.player div.full.control div.media div.volume div { width: 28.75px; height: 28.75px; }
		.player div.full.control div.media div.volume span {
			position: relative; top: -100%; left: 28.75px;
			width: 0px; height: 100%;
			display: block; transition: 0.23s; overflow: hidden;
		}
		.player div.full.control div.media div.volume span input {
			margin: 0px;
			position: relative; top: 46%; left: 5.75px;
			width: 57.5px; height: 3px;
			display: block; cursor: pointer;
		}
		.player div.full.control div.media div.volume:hover span { width: 97.75px; }
		.player div.full.control div.media div.time {
			padding: 0px 5.75px;
			position: relative; top: 17.25px;
			width: auto; height: 28.75px;
			color: #FFF; /*background-color: #000;*/
			font-size: 14.375px; line-height: 25.875px; font-family: 'Sarabun', sans-serif;
		}
		.player div.full.control div.media div.fullscr { cursor: pointer; }
		.player div.full.control div.media div.fullscr img {
			margin: 5.75px;
			width: calc(100% - 11.5px); height: calc(100% - 11.5px);
			transition: 0.138s;
		}
		.player div.full.control div.media div.fullscr:hover img {
			margin: 4.3125px;
			width: calc(100% - 8.625px); height: calc(100% - 8.625px);
		}
		.player div.full.control div.media div.settings {
			transition: 0.23s;
			/*overflow: hidden;*/
		}
		.player div.full.control div.media div.settings:hover {
			padding-top: 5.75px;
			transform: translateY(-5.75px);
		}
		.player div.full.control div.media div.settings img { transition: 0.23s; }
		.player div.full.control div.media div.settings:hover img { transform: rotate(46deg); }
		.player div.full.control div.media div.settings div.roll {
			position: absolute; top: calc(-23px * 3); /*left: -54.625px;*/ right: -28.75px;
			width: 152.375px; height: calc(23px * 3);
			/* background-image: linear-gradient(to top left, rgba(0, 0, 0, 0.69), rgba(0, 0, 0, 0)); */ background-color: rgba(0, 0, 0, 0.46);
			color: #FFF; font-size: 11.5px; line-height: 23px; font-family: 'Sarabun', sans-serif;
			display: none;
		}
		.player div.full.control div.media div.settings:hover div.roll { display: block; }
		.player div.full.control div.media div.settings div.roll div.set {
			top: 0px;
			width: 100%; height: 23px;
			transition: 0.138s;
		}
		.player div.full.control div.media div.settings div.roll div.set:hover { background-color: rgba(255, 255, 255, 0.138); }
		.player div.full.control div.media div.settings div.roll div.set * { float: left; }
		.player div.full.control div.media div.settings div.roll div.speed span { cursor: default; }
		.player div.full.control div.media div.settings div.roll div.speed input {
			margin: 0px 5.75px;
			transform: translateY(8.625px);
			width: 57.5px; height: 5.75px;
			display: block; cursor: pointer;
		}
		.player div.full.control div.media div.settings div.roll div.loop input, .player div.full.control div.media div.settings div.roll div.cukc input {
			margin: 2.875px 5.75px;
			width: 17.25px; height: 17.25px;
			cursor: pointer;
		}
		.player div.full.control div.media div.settings div.roll div.loop label, .player div.full.control div.media div.settings div.roll div.cukc label {
			width: calc(100% - 28.75px);
			cursor: pointer;
		}
		.player div.full.control div.media div.text { display: flex; justify-content: center; }
		.player div.full.control div.media div.text a:link, .player div.full.control div.media div.text a:visited { text-decoration: none; color: #FFFFFF; }
		.player div.full.control div.media div.text a i { transform: translateY(1.875px); transition: 0.25s; }
		.player div.full.control div.media div.text a:hover i { transform: translateY(1.875px) scale(1.125); }
		.player div.full.control div.media div.blur {
			position: absolute; left: calc(100% * -2.25 / 200); bottom: 0px; top: initial; z-index: -1;
			width: calc(100% * 100 / 97.75); height: 345%;
			background-image: linear-gradient(to top, rgba(0, 0, 0, 0.805), rgba(0, 0, 0, 0));
			pointer-events: none;
		}
	</style>
	<style type="text/css" class="css-norm">
		.player {
			position: relative;
			width: var(--width-lcol) /* 100% */; height: 100%;
			background-color: #000; /* EACA67 */
			overflow: hidden;
		}
		.player div.full.control div.vid-name {
			padding: 0px 5.75px;
			position: absolute; top: 0.23px; z-index: -1;
			width: calc(100% - 11.5px); height: 69px;
			background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.575), rgba(0, 0, 0, 0));
			display: none; overflow-x: hidden;
		}
		.player div.full.control:hover div.vid-name { display: block; }
		.player div.full.control div.media {
			position: absolute; left: 50%; bottom: -0.23px; transform: translateX(-50%); z-index: 1;
			width: 97.75%; height: 46px;
			display: none;
		}
		.player div.full.control:hover div.media { display: block !important; }
	</style>
	<style type="text/css" class="css-full" media="max-width: 1px">
		.player {
			margin: 0px 0px 23px;
			position: relative; float: left;
			width: 100%; height: var(--scr-height) !important;
			background-color: #000; /* EACA67 */
			overflow: hidden;
		}
		.player div.full.control div.vid-name {
			padding: 0px 5.75px;
			position: absolute; top: 0.23px; left: -0.5px; z-index: 1;
			width: calc(100% - 11.5px); height: 69px;
			background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.575), rgba(0, 0, 0, 0));
			opacity: 0%; overflow-x: hidden;
		}
		.player div.full.control div.vid-name:hover { opacity: 100%; }
		.player div.full.control div.media {
			position: absolute; left: 50%; bottom: calc(-0.23px + 5.75px); transform: translateX(-50%); z-index: 1;
			width: 97.75%; height: 46px;
			opacity: 0%;
		}
		.player div.full.control div.media:hover { opacity: 100% !important; }
		.player div.full.control div.media div.blur { bottom: -5.75px; }
	</style>
	<script type="text/javascript" src="/resource/js/lib/jquery.min.js"></script>
	<script type="text/javascript">
		// Initialize
		var vid_max_t, wherefs, vid_frame_rate = 25; // YT24 Cinema30
		function jsinit(where, origin, player_durations) {
			// is_safari();
			$(".player div.full.control div.media div.trackbar input").on("input", vid_frame);
			$(".player div.full.control div.media div.trackbar input").on("mousedown", function(){vid_seeking(true);});
			$(".player div.full.control div.media div.trackbar input").on("mouseup", function(){vid_seeking(false);});
			$(".player div.full.control div.media div.trackbar input").on("mousemove", vid_preview);
			$(".player div.full.control div.media div.trackbar input").on("mouseleave", vid_preview_exit);
			$(".player div.full.control div.media div.volume.lt input").on("input", vid_vol);
			$(".player div.full.control div.media div.settings div.roll div.speed input").on("input", vid_speed);
			$(".player div.full.control div.media div.settings div.roll div.loop input").on("input", vid_loop);
			$(".player div.full.control div.media div.settings div.roll div.cukc input").on("input", vid_cukc);
			var vid_max_s = player_durations.split(":"); player_duration = player_durations;
			if (vid_max_s.length == 3) { vid_max_t = (parseInt(vid_max_s[0])*3600+parseInt(vid_max_s[1])*60+parseInt(vid_max_s[2]))*vid_frame_rate; }
			else if (vid_max_s.length == 2) { vid_max_t = (parseInt(vid_max_s[0])*60+parseInt(vid_max_s[1]))*vid_frame_rate; }
			$(".player div.full.control div.media div.trackbar input").attr("max", vid_max_t);
			var video = document.querySelector(".player div.full.video div.view video");
			video.volume = <?php echo(isset($_COOKIE['set_volume']))?intval($_COOKIE['set_volume'])/100:"0.8"; ?>;
			vid_uptime(); setInterval(vid_uptime, 1000/vid_frame_rate); vid_pp(false);
			observer.observe(video, { attributes: true }); wherefs = where;
			origin = origin;
		}
		function is_safari() {
			var user_agent = navigator.userAgent;
			if (user_agent.indexOf("Safari") > -1 && user_agent.indexOf("Chrome") <= -1) goto_page("error/906");
		}
		function getCookie(gc_cname) {
			var gc_name = gc_cname + "=";
			var gc_ca = decodeURIComponent(document.cookie).split(';');
			for(var gc_i = 0; gc_i < gc_ca.length; gc_i++) {
				var gc_c = gc_ca[gc_i];
				while (gc_c.charAt(0) == ' ') {
					gc_c = gc_c.substring(1);
				}
				if (gc_c.indexOf(gc_name) == 0) {
					return gc_c.substring(gc_name.length, gc_c.length);
				}
			}
			return "";
		}
		// Video controls
		function vid_frame() {
			var video = document.querySelector(".player div.full.video div.view video");
			var frame = parseInt(document.querySelector(".player div.full.control div.media div.trackbar input").value)/vid_frame_rate;
			video.currentTime = frame;
			//$(".player div.full.control div.media div.trackbar div.prg").css("width", (frame/vid_max_t*$(".player div.full.control div.media div.trackbar input").width()+((frame/vid_max_t)-0.5)*-3.45).toString()+"px");
			//vid_time();
		}
		var timing;
		function vid_time() {
			var video = parseInt(document.querySelector(".player div.full.video div.view video").currentTime);
			var text;
			if (video < 3600) { text = Math.floor(video/60).toString()+":"+(((video%60).toString().length==1)?"0":"")+(video%60).toString(); }
			else { text = Math.floor(video/3600).toString()+":"+(((Math.floor(video%3600/60)).toString().length==1)?"0":"")+Math.floor(video%3600/60).toString()+":"+(((video%3600%60).toString().length==1)?"0":"")+(video%3600%60).toString(); }
			document.querySelector(".player div.full.control div.media div.time span").innerHTML = text;
			if (document.querySelector(".player div.full.video div.view video").currentTime == document.querySelector(".player div.full.video div.view video").duration && !document.querySelector(".player div.full.control div.media div.settings div.roll div.loop input").checked) {
				document.querySelector(".player div.full.control div.media div.playing.lt img").src = "https://self.edu.TianTcl.net/resource/images/vidctrl_play.png"; document.querySelector(".player div.full.control div.media div.playing.lt").setAttribute("data-title", "Play (k)"); document.querySelector(".player div.full.control div.media div.playing.lt").style = "--px: 0px; --sx: 41px;";
				document.querySelector(".player div.full.video div.view video").pause();
				clearInterval(timing);
			}
		}
		var full_screen = false, norm_height;
		function vid_pp(go = true) {
			var video = document.querySelector(".player div.full.video div.view video");
			var button = document.querySelector(".player div.full.control div.media div.playing.lt img");
			var object = document.querySelector(".player div.full.control div.media div.playing.lt");
			var crtler = document.querySelector(".player div.full.control div.media");
			if (go && video.src != "") {
				if (video.paused) {
					button.src = "https://self.edu.TianTcl.net/resource/images/vidctrl_pause.png"; object.setAttribute("data-title", "Pause (k)"); object.style = "--px: 0px; --sx: 51px;";
					video.play();
					if (record) timing = setInterval(c_timer, 1000/parseFloat(document.querySelector(".player div.full.control div.media div.settings.rt div.roll input").value));
				} else {
					button.src = "https://self.edu.TianTcl.net/resource/images/vidctrl_play.png"; object.setAttribute("data-title", "Play (k)"); object.style = "--px: 0px; --sx: 41px;";
					video.pause();
					if (record) clearInterval(timing);
				}
				ffon += 1;
			}
			if (video.paused && !window.full_screen) { crtler.style.opacity = "100%"; crtler.style.display = "block"; }
			else if (video.paused && window.full_screen) { crtler.style.opacity = "100%"; crtler.style.display = "block"; }
			else if (!video.paused && !window.full_screen) { crtler.style.opacity = "100%"; crtler.style.display = "none"; }
			else if (!video.paused && window.full_screen) { crtler.style.opacity = "0%"; crtler.style.display = "block"; }
		}
		function vid_seek(sktime) {
			var video = document.querySelector(".player div.full.video div.view video");
			var new_time = video.currentTime + sktime;
			if (new_time < 0) { video.currentTime = new_time; }
			else if (new_time > vid_max_t) { video.currentTime = vid_max_t; }
			else { video.currentTime = new_time; }
			vid_uptime();
		}
		function vid_vol() {
			var video = document.querySelector(".player div.full.video div.view video");
			var sound = parseInt(document.querySelector(".player div.full.control div.media div.volume.lt input").value);
			var button = document.querySelector(".player div.full.control div.media div.volume.lt div img");
			var object = document.querySelector(".player div.full.control div.media div.volume.lt div");
			video.volume = sound/100;
			var expire = new Date(); expire.setTime(expire.getTime() + (365*24*60*60*1000));
			document.cookie = "set_volume="+sound+";expires="+expire+";path=/";
			if (sound == 0) {
				button.src = "https://self.edu.TianTcl.net/resource/images/vidctrl_mute.png";
				object.setAttribute("title", "Unmute (m)");
			} else {
				button.src = "https://self.edu.TianTcl.net/resource/images/vidctrl_sound.png";
				object.setAttribute("title", "Mute (m)");
			}
		}
		function vid_scroll(scrvol) {
			var sound = document.querySelector(".player div.full.control div.media div.volume.lt input").value;
			var volumer = $(".player div.full.control div.media div.volume.lt input");
			var simulator = parseInt(sound) + scrvol;
			if (simulator >= 0 && simulator <= 100) { volumer.val(simulator); }
			else if (simulator < 0) { volumer.val(0); }
			else if (simulator > 100) { volumer.val(100); }
			vid_vol();
		}
		var prev_snd = "";
		function vid_mu() {
			var sound = document.querySelector(".player div.full.control div.media div.volume.lt input");
			if (parseInt(sound.value) != 0) {
				prev_snd = sound.value;
				sound.value = "0";
			} else {
				if (prev_snd == "" || prev_snd == "0") { sound.value = "80"; prev_snd = "80"; }
				else { sound.value = prev_snd; }
			}
			vid_vol();
		}
		function vid_speed() {
			var video = document.querySelector(".player div.full.video div.view video");
			var speed = parseFloat(document.querySelector(".player div.full.control div.media div.settings div.roll div.speed input").value);
			video.playbackRate = speed;
			if (!video.paused) { clearInterval(timing); timing = setInterval(c_timer, 1000/speed); }
			if (record) c_timer();
		}
		function vid_loop() {
			var video = document.querySelector(".player div.full.video div.view video");
			var toggler = document.querySelector(".player div.full.control div.media div.settings div.roll div.loop input").checked;
			video.loop = toggler; setTimeout(function() { app.ui.notify(0); }, 230);
		}
		function vid_uptime() {
			var vid_frame = document.querySelector(".player div.full.video div.view video").currentTime;
			var track = document.querySelector(".player div.full.control div.media div.trackbar input");
			track.value = (parseInt(vid_frame*vid_frame_rate)).toString();
			// $(".player div.full.control div.media div.trackbar div.prg").css("transition", "width 0s");
			$(".player div.full.control div.media div.trackbar div.prg").css("width", (parseInt(document.querySelector(".player div.full.control div.media div.trackbar input").value)/vid_max_t*$(".player div.full.control div.media div.trackbar input").width()+((parseInt(document.querySelector(".player div.full.control div.media div.trackbar input").value)/vid_max_t)-0.5)*-3.45).toString()+"px");
			// $(".player div.full.control div.media div.trackbar div.prg").css("transition", "width 0.092s");
			vid_time();
		}
		var bfsk = false;
		function vid_seeking(ing) {
			var video = document.querySelector(".player div.full.video div.view video");
			if (ing && !video.paused) { video.pause(); bfsk = true; }
			else if (ing && video.paused) { bfsk = false; }
			else if (!ing && bfsk) { video.play(); }
		}
		function vid_preview(e) {
			let xs = e.pageX - $(".player div.full.control div.media div.trackbar div.prv").offset().left;
			$(".player div.full.control div.media div.trackbar div.prv").width(xs);
			$(".player div.full.control div.media div.trackbar input").attr("style", "--px: "+(xs-21.75).toString()+"px; --sx: 27px;");
		}
		function vid_preview_exit() { $(".player div.full.control div.media div.trackbar div.prv").width(0); }
		// Key command & Disable scroll using ARROW keys
		var cukcmd = true;
		function vid_cukc() {
			var toggler = document.querySelector(".player div.full.control div.media div.settings div.roll div.cukc input").checked;
			cukcmd = toggler;
		}
		$(document).on("keydown", function(e){
			let prik = e.which || e.keyCode, ckeyp = String.fromCharCode(prik) || e.key || e.code, isCrtling = e.ctrlKey, isShifting = e.shiftKey, isAlting = e.altKey;
			if ([27, 32, 37, 38, 39, 40, 122].includes(prik)) { e.preventDefault(); }
			if (cukcmd && !isCrtling && !isShifting && !isAlting) {
				if (ckeyp == "K" || ckeyp == " ") { vid_pp(); }	// K | " "		32 | 107
				else if (ckeyp == "F") { vid_fullscr(); }		// F			102
				else if (ckeyp == "J") { vid_seek(-10); }		// J			106
				else if (ckeyp == "L") { vid_seek(10); }		// L			107
				else if (ckeyp == "M") { vid_mu(); }			// M			109
				else if (ckeyp == "%") { vid_seek(-5); }		// % (l-arrow)	37
				else if (ckeyp == "'") { vid_seek(5); }			// ' (r-arrow)	39
				else if (ckeyp == "&") { vid_scroll(15); }		// & (u-arrow)	38
				else if (ckeyp == "(") { vid_scroll(-15); }		// ( (d-arrow)	40
			} else if (cukcmd) {
				if (prik == 27) { vid_fullscr(true); }			// x (Esc)		27
				else if (prik == 122) { vid_fullscr(); }		// x (F11)		122
			}
		});
		function vid_fullscr(exit = false) {
			var button_elem = document.querySelector(".player div.full.control div.media div.fullscr.rt img");
			var elem = document.querySelector(wherefs);
			var css_norm = document.querySelector("style.css-norm"), css_norm_x = document.querySelector("style.css-norm-x");
			var css_full = document.querySelector("style.css-full"), css_full_x = document.querySelector("style.css-full-x");
			if (!exit) {
				if (!window.full_screen) { // (document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)
					norm_height = $(wherefs).height();
					button_elem.src = "https://self.edu.TianTcl.net/resource/images/vidctrl_fullscr-exit.png";
					css_norm.setAttribute("media", "max-width: 1px"); css_full.removeAttribute("media");
					try { css_norm_x.setAttribute("media", "max-width: 1px"); css_full_x.removeAttribute("media"); } catch(err) {}
					if (elem.requestFullScreen) { elem.requestFullScreen(); }
					else if (elem.mozRequestFullScreen) { elem.mozRequestFullScreen(); }
					else if (elem.webkitRequestFullScreen) { elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT); }
					else if (elem.msRequestFullscreen) { elem.msRequestFullscreen(); }
				} else {
					button_elem.src = "https://self.edu.TianTcl.net/resource/images/vidctrl_fullscr-open.png";
					css_full.setAttribute("media", "max-width: 1px"); css_norm.removeAttribute("media");
					try { css_full_x.setAttribute("media", "max-width: 1px"); css_norm_x.removeAttribute("media"); } catch(err) {}
					if (document.cancelFullScreen) { document.cancelFullScreen(); }
					else if (document.mozCancelFullScreen) { document.mozCancelFullScreen(); }
					else if (document.webkitCancelFullScreen) { document.webkitCancelFullScreen(); }
					else if (document.msExitFullscreen) { document.msExitFullscreen(); }
					setTimeout(function(){$("html body main").css("height", norm_height.toString()+"px");},230);
				}
				window.full_screen = !window.full_screen; vid_pp(false);
			} else if (exit && window.full_screen) {
				button_elem.src = "https://self.edu.TianTcl.net/resource/images/vidctrl_fullscr-open.png";
				css_full.setAttribute("media", "max-width: 1px"); css_norm.removeAttribute("media");
				try { css_full_x.setAttribute("media", "max-width: 1px"); css_norm_x.removeAttribute("media"); } catch(err) {}
				if (document.cancelFullScreen) { document.cancelFullScreen(); }
				else if (document.mozCancelFullScreen) { document.mozCancelFullScreen(); }
				else if (document.webkitCancelFullScreen) { document.webkitCancelFullScreen(); }
				else if (document.msExitFullscreen) { document.msExitFullscreen(); }
				setTimeout(function(){$(wherefs).css("height", norm_height.toString()+"px");},230);
				window.full_screen = false; vid_pp(false);
			} $(window).trigger('resize');
		}
		// Video mutation detection
		var observer = new MutationObserver(function(mutations, observer) { for(let mutation of mutations) { if (mutation.type === 'attributes' && mutation.attributeName != "src") { vid_resetAttr(mutation.attributeName); } } });
		function vid_resetAttr(mutantAttr) {
			observer.disconnect();
			app.ui.notify(1, [3, "Video element has been modified. Attribute \""+ mutantAttr +"\" modification was canceled"]);
			var video = $(".player div.full.video div.view video");
			video.attr("controlsList", "nodownload");
			video.attr("disablePictureInPicture", "");
			video.removeAttr("controls");
			observer.observe(document.querySelector(".player div.full.video div.view video"), { attributes: true });
		}
		// Encryption
		var blob, blobUrl, old_time = 0, ffon = 1, allowload = true, secured = <?php echo (isset($player_secured))?($player_secured?"true":"false"):"false";?>;
		var cookied = new Date(); cookied.setTime(cookied.getTime() - 3);
		var origin, player_duration;
		function gen_blob(ap = false) {
			var video = document.querySelector(".player div.full.video div.view video"), tprevent = $(".player div.full.control"), control = $(".player div.full.control div.media");
			var loadert = $(".player div.full.video div.view div.load-text"), loaderi = $(".player div.full.video div.view div.load-img");
			if (allowload && secured) { allowload = false;
				var loaddot = "", loading = setInterval(function() {
					loadert.text("Loading"+loaddot);
					loaddot = (loaddot == "...") ? "" : loaddot+".";
				}, 250); loaderi.show(); tprevent.attr("style", "cursor: wait !important;"); control.css("pointer-events", "none");
				// video.src = origin; clearInterval(loading); loader.text("");
				// Option I
				function fetchVideo(url) { return fetch(url, {mode: 'no-cors'}).then(function(response) { return response.blob(); }); }
				fetchVideo(origin).then(function(blob) {
					blobUrl = URL.createObjectURL(blob);
					old_time = video.currentTime;
					video.src = blobUrl; // $(".player div.full.control div.media div.trackbar input").attr("max", document.querySelector(".player div.full.video div.view video").webkitDecodedFrameCount);
					video.currentTime = old_time; video.playbackRate = parseFloat(document.querySelector(".player div.full.control div.media div.settings div.roll div.speed input").value);
					if (ap) video.play();
					clearInterval(loading); loadert.text(""); loaderi.hide(); tprevent.removeAttr("style"); control.css("pointer-events", "auto"); allowload = true;
				});
				/* // Option II
				var xhr = new XMLHttpRequest();
				xhr.open("GET", origin, true);
				xhr.responseType = "blob"; // arraybuffer | blob
				xhr.onload = function(oEvent) {
					blob = new Blob([oEvent.target.response], {type: "video/mp4"});
					blobUrl = URL.createObjectURL(blob);
					old_time = video.currentTime;
					video.src = blobUrl;
					video.currentTime = old_time;
					if (ap) video.play();
					clearInterval(loading); loader.text(""); allowload = true;
				};
				xhr.onprogress = function(oEvent) { if (oEvent.lengthComputable) { var percentComplete = oEvent.loaded/oEvent.total; } }
				xhr.send(); */
			} else if (!secured) {
				video.src = origin; loadert.text(""); loaderi.hide();
			}
		}
		$(window).on("blur focus", function(e) {
			var prevType = $(this).data("prevType");
			if (ffon > 0 && prevType != e.type && secured) {
				if (e.type == "focus") { gen_blob(!document.querySelector(".player div.full.video div.view video").paused); ffon = (document.querySelector(".player div.full.video div.view video").paused) ? 0 : 1; }
				else if (e.type == "blur" && allowload) { URL.revokeObjectURL(blobUrl); }
				$(this).data("prevType", e.type);
			} else if (e.type == "blur" && prevType == "" && allowload && secured) { URL.revokeObjectURL(blobUrl); $(this).data("prevType", e.type); }
		});
		// End enc
	</script>
	<div class="full control">
		<div class="vid-name">
			<span><?php echo $player_name; ?></span>
		</div>
		<div class="touch full" onClick="vid_pp()"></div>
		<div class="media">
			<div class="trackbar">
				<input type="range" value="0" min="0" data-title="Seek" style="--px: 0px; --sx: 27px;">
				<div class="prg"></div>
				<div class="prv"></div>
			</div>
			<div class="playing lt" onClick="vid_pp()" data-title="Play (k)" style="--px: 0px;"><img src="https://self.edu.TianTcl.net/resource/images/vidctrl_play.png"></div>
			<div class="seek lt" onClick="vid_seek(-10)" data-title="-10 seconds (j)" style="--px: -32.875px;"><img src="https://self.edu.TianTcl.net/resource/images/vidctrl_seek-prev.png"></div>
			<div class="seek lt" onClick="vid_seek(10)" data-title="+10 seconds (l)" style="--px: -34.825px;"><img src="https://self.edu.TianTcl.net/resource/images/vidctrl_seek-next.png"></div>
			<div class="volume lt"><div data-title="Mute (m)" style="--px: -17.875px;"><img onClick="vid_mu()" src="https://self.edu.TianTcl.net/resource/images/vidctrl_<?php echo(isset($_COOKIE['set_volume'])&&$_COOKIE['set_volume']=="0")?"mute":"sound"; ?>.png"></div><span><input type="range" value="<?php echo(isset($_COOKIE['set_volume']))?$_COOKIE['set_volume']:"80"; ?>" min="0" max="100"></span></div>
			<div class="time lt"><span>0:00</span> / <span name="duration"></span></div>
			<div class="fullscr rt" onClick="vid_fullscr()" data-title="Toggle fullscreen (f)" style="--px: -95.75px;"><img src="https://self.edu.TianTcl.net/resource/images/vidctrl_fullscr-open.png"></div>
			<div class="settings rt" style="--px: -65.25px; --sx: 46;"><!data-title="Settings">
				<img src="https://self.edu.TianTcl.net/resource/images/vidctrl_speed.png">
				<div class="roll">
					<div class="set speed"><span>&nbsp;Speed&nbsp; 0.5x</span><input type="range" value="1" min="0.5" max="2" step="0.25"><span>2x</span></div>
					<div class="set loop"><input type="checkbox" id="looping"><label for="looping">Loop</label></div>
					<div class="set cukc"><input type="checkbox" id="cukcmd" checked><label for="cukcmd">Keyboard shortcut</label></div>
				</div>
			</div>
			<!--div class="text rt" data-title="Home"><a href="/<?php echo $home; ?>" onClick="return confirm('Return to home page ?')"><i class="material-icons">home</i></a></div-->
			<div class="blur"></div>
		</div>
	</div>
	<div class="full video">
		<div class="view">
			<video controlsList="nodownload" disablePictureInPicture poster="<?php echo $player_thumbnail; ?>"></video>
			<div class="load-text">Loading...</div>
			<div class="load-img">
				<span style="--di:1;"></span>
				<span style="--di:2;"></span>
				<span style="--di:3;"></span>
				<span style="--di:4;"></span>
				<span style="--di:5;"></span>
				<!--span style="--di:6;"></span>
				<span style="--di:7;"></span-->
				<svg>
					<defs>
						<filter id="gooey">
							<feGaussianBlur in="SourceGraphic" stdDeviation="8.05" />
							<feColorMatrix values="
												   1 0 0 0 0
												   0 1 0 0 0
												   0 0 1 0 0
												   0 0 0 20 -10
												   " />
						</filter>
					</defs>
				</svg>
			</div>
		</div>
	</div>
</div>