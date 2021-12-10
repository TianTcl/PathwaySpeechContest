<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	include("../hpe/init_ps.php");
	$header_title = "Developers";
	$header_desc = "App css var lists";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php include("../hpe/heading.php"); include("../hpe/init_ss.php"); ?>
		<style type="text/css">
			html body main section.container {
				position: relative; top: 25px; left: 50%; transform: translateX(-50%);
				/* width: 960px; max-width: 95vw; */ width: 95%;
			}
			html body main section.container > div {
				margin: 7.5px 0px; padding: 5px 10px;
				border-radius: 7.5px;
				transition: var(--time-tst-fast);
			}
			html body main section.container > div:hover { background-color: rgba(200, 200, 200, 0.3125); }
			html body main section.container div b {
				padding-bottom: 5px;
				font-size: 25px; line-height: 30px; font-family: 'Sarabun', serif;
				border-bottom: 1.25px solid #888;
				display: block;
			}
			html body main section.container div ul {
				margin: 0px; padding: 0px;
				list-style-type: none; --lh: 18.75px;
			}
			html body main section.container div ul a {
				margin: 7.5px; padding: 7.5px;
				font-size: 12.5px; line-height: var(--lh); font-family: 'Quicksand', sans-serif;
				color: var(--clr-main-black-absolute) !important; text-decoration: none;
				background-color: #FFF;
				border-radius: 2.5px; border: 0.25px solid #999; box-shadow: 0px 0px 5px 2.5px rgba(0, 0, 0, 0.125);
				display: inline-block; transition: calc(var(--time-tst-xfast) / 2);
			}
			html body main section.container div ul a:hover { text-decoration: none !important; }
			html body main section.container div ul a:active { transform: scale(1.0625); }
			html body main section.container div ul a li * { cursor: pointer; }
			html body main section.container div.timings ul a li span {
				margin-top: 2.5px;
				width: auto; height: var(--lh);
				border-radius: var(--lh); background-color: rgba(0, 0, 0, 0.09375);
				display: block;
			}
			html body main section.container div.timings ul a li span:after {
				position: relative; top: 0px; left: 0%; transform: scale(0.75);
				width: var(--lh); height: var(--lh);
				border-radius: 50%; background-color: #999;
				display: block; content: ""; transition: var(--v) linear;
			}
			html body main section.container div.timings ul a:hover li span:after { left: calc(100% - var(--lh)); }
			html body main section.container div.colors ul a li, html body main section.container div.shadows ul a li { height: var(--lh); }
			html body main section.container div.colors ul a li div, html body main section.container div.shadows ul a li div {
				position: relative; transform: translate(-7.5px, -7.5px);
				width: calc(var(--lh) + 15px); height: calc(var(--lh) + 15px);
				background-color: var(--v); border-radius: 2.5px 0px 0px 2.5px; box-shadow: 2.5px 0px 2.5px -0.5px rgba(0, 0, 0, 0.25);
				display: inline-block;
			}
			html body main section.container div.shadows ul a li div { --f: #00000000; background-color: var(--f); box-shadow: none; }
			html body main section.container div.colors ul a li label, html body main section.container div.shadows ul a li label { position: relative; top: -19px; }
			html body main section.container div.colors ul a li label:after {
				position: absolute; top: -1px; left: 0px;
				width: 0%;
				font-size: 12.5px; line-height: var(--lh); font-family: 'Quicksand', sans-serif;
				color: var(--v); text-shadow: 0 0 2.5px #000;
				display: block; content: attr(data-text); white-space: nowrap;
				overflow: hidden; transition: calc(var(--time-tst-xfast) * 2 / 3);
			}
			html body main section.container div.colors ul a:hover li label:after { width: 100%; }
			html body main section.container div.shadows ul a li div:before {
				position: relative; top: 50%; left: 75%; transform: translate(-75%, -50%);
				width: 50%; height: 50%;
				box-shadow: var(--s); border-radius: 50%;
				display: block; content: "";
			}
		</style>
		<script type="text/javascript">
			// Initial function
			$(document).ready(css);
			function css() {
				var s_xhr = new XMLHttpRequest(); s_xhr.open("GET", "/resource/css/core/stylevar.css", true); s_xhr.responseType = "text"; s_xhr.onload = function() { return passcss(this.responseText); }; s_xhr.send();
				// $.get("/resource/css/core/stylevar.css", function(data, status) { tmp = data.replace(/\/\* [A-Z]+ \*\//, "").replace(":root {", "").split("}"); });
			}
			function passcss(tmp) {
				var nc = [], tc = tmp.replace(/((\/\* [A-Za-z0-9\s&]+ \*\/)|(\:root {))/g, "").replace(/(?:\r\n|\r|\n|\t)/g, "").split("}");
				for (let i = 0; i<tc.length; i++) {
					var ts = tc[i].split(/(;\s*--)/g);
					for (let j = 0; j<ts.length; j++) {
						if (ts[j].includes(";")||ts[j].match(/^(\s*)$/)) ts.splice(j, 1);
						else ts[j] = ts[j].replace(/(;|--)/g, "");
					} if (ts.length>0) nc.push(ts);
				}
				for (let k = 0; k<nc.length; k++) {
					for (let l = 0; l<nc[k].length; l++) {
						nc[k][l] = nc[k][l].split(": ");
						if (nc[k][l][1].includes(";")) nc[k][l][1] = nc[k][l][1].replace(";", "");
					}
				}
				render(nc);	return true;
			}
			function render(vals) {
				var ctn = $("html body main section.container"), keys = ["Timings", "Shadows & Fadings", "Colors"], str = [
					function(a,b) { return '<label>'+a+' ('+b+')</label><span style="--v:'+b+'"></span>'; },
					function(a,b) {
						var c = (b.length=="9"?"f:":"s:0 0 ")+b+(b.length!="9"?" #000":"");
						return '<div style="--'+c+'"></div><label>'+a+' ('+b+')</label>';
					}, function(a,b) { return '<div style="--v:'+b+'"></div><label style="--v:'+b+'" data-text="'+a+' ('+b.toUpperCase()+')">'+a+' ('+b.toUpperCase()+')</label>'; }
				];
				for (let i = 0; i<keys.length; i++) {
					ctn.append($('<div class="'+keys[i].toLowerCase()+'"><b>'+keys[i]+'</b><ul></ul></div>'));
					var prt = $("html body main section.container div:nth-child("+(i+1).toString()+") ul");
					for (let j = 0; j<vals[i].length; j++) {
						prt.append($('<a href="javascript:copy('+(j+1).toString()+', '+(i+1).toString()+')"><li>'+str[i](vals[i][j][0], vals[i][j][1])+'</li></a>'));
					}
				}
			}
			// Page function
			function copy(oi, of) {
				const elem = document.createElement('textarea'); let cssvarname = $("html body main section.container div:nth-child("+of.toString()+") ul a:nth-child("+oi.toString()+") li label").text(); let ctxt = cssvarname.substr(0, (cssvarname.indexOf(" ")>-1?cssvarname.indexOf(" "):cssvarname.length));
				elem.value = "--"+ctxt;
				document.body.appendChild(elem); elem.select(); document.execCommand('copy'); document.body.removeChild(elem);
				app.ui.notify(1, [0, "CSS variable name copied!<br>\""+ctxt+"\""]);
			}
		</script>
	</head>
	<body>
		<?php include("../hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<section class="container"></section>
		</main>
		<?php include("../hpe/material.php"); ?>
		<footer>
			<?php include("../hpe/footer.php"); ?>
		</footer>
	</body>
</html>