<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "รายชื่อผู้สมัคร";
	
	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("Location: new-password$my_url");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			html body main div.container { overflow: visible; }
			html body main div.container > div.wrapper { display: flex; justify-content: space-between; }
			html body main div.container > div.wrapper div.group { height: calc(100vh - var(--top-height) - 60px); }
			html body main div.container > div.wrapper div.group:nth-child(1) { width: 360px; }
			html body main div.container > div.wrapper div.group:nth-child(2) { width: calc(100% - 360px - 25px); }
			div.group.f { display: flex; justify-content: space-between; flex-direction: column; }
			div.group.f div.search { height: 40px; }
			div.group.f div.search div.live {
				width: calc(100% - 2.5px); height: calc(100% - 2.5px);
				border-radius: 20px; border: 1.25px solid transparent;
				box-shadow: 0px 2.5px 2.5px 2.5px rgb(0, 0, 0, 0.25);
				transition: var(--time-tst-medium); overflow: hidden;
			}
			div.group.f div.search div.live:hover { border: 1.25px solid var(--clr-gg-blue-700); }
			div.group.f div.search div.live *:not(i) { font-size: 15px; line-height: 20px; font-family: "THKodchasal", serif; }
			div.group.f div.search div.live input {
				padding: 10px 15px;
				width: 100%;
			}
			div.group.f div.search div.live input::-webkit-search-cancel-button {
				transform: scale(2.5) translateY(-1.25px);
				cursor: pointer;
			}
			div.group.f div.search div.live i {
				position: absolute; right: 17.5px;
				width: 40px; height: 40px;
				font-size: 36px; line-height: 40px;
				color: var(--clr-gg-grey-500); text-align: center;
				pointer-events: none;
			}
			div.group.f div.dir {
				--lvlH: 30px;
				height: calc(100% - 40px - 25px);
				font-family: "Open Sans";
				border-radius: 2.5px; border: 1.25px solid transparent;
				box-shadow: 1.25px 2.5px var(--shd-big) var(--fade-black-7);
				overflow: auto; transition: var(--time-tst-medium);
			}
			div.group.f div.dir:hover { border: 1.25px solid var(--clr-gg-grey-700); }
			div.group.f div.dir div.wrapper {
				padding-left: 5px;
				min-width: calc(100% - 7.5px); width: auto; min-height: calc(100% - 2.5px);
				white-space: nowrap;
			}
			div.group.f div.dir div.wrapper .tree { width: fit-content; }
			div.group.f div.dir div.wrapper .tree.group {
				padding-left: var(--lvlH);
				list-style-type: disclosure-closed;
			}
			/* div.group.f div.dir div.wrapper .tree.mbr::marker { transform: translateY(-8.25px); } */
			div.group.f div.dir div.wrapper .tree.mbr, div.group.f div.dir div.wrapper .tree.group { margin-top: 2.5px; }
			div.group.f div.dir div.wrapper .tree.mbr[expand="true"] { list-style-type: disclosure-open; }
			div.group.f div.dir div.wrapper .tree.mbr.off { list-style-type: disc; }
			div.group.f div.dir div.wrapper .tree.all { transform: translateY(8.25px); }
			div.group.f div.dir div.wrapper .tree.ctrl {
				transform: translateX(calc(-1 * var(--lvlH) - 1.25px));
				display: flex;
			}
			div.group.f div.dir div.wrapper .tree.accd {
				width: var(--lvlH); height: var(--lvlH);
				border-radius: 50%;
				text-align: center;
				display: block;
				transition: var(--time-tst-xfast); overflow: hidden;
			}
			div.group.f div.dir div.wrapper .tree.accd:hover { background-color: var(--fade-black-8); }
			div.group.f div.dir div.wrapper .tree.mbr.off > .tree.all > .tree.ctrl > .tree.accd { pointer-events: none; }
			div.group.f div.dir div.wrapper .tree.accd input[type="checkbox"] {
				width: inherit; height: inherit;
				border-radius: 50%;
				opacity: 0; filter: opacity(0);
				cursor: pointer;
			}
			div.group.f div.dir div.wrapper .tree.accd + label {
				margin-left: 2.5px; padding: 0px 5px;
				height: var(--lvlH); line-height: var(--lvlH);
				border-radius: 2.5px;
				cursor: pointer; transition: var(--time-tst-xfast);
			}
			div.group.f div.dir div.wrapper .tree.accd + label:hover { text-decoration: underline; }
			div.group.f div.dir div.wrapper .tree.accd + label[selected] {
				background-color: var(--clr-pp-blue-100);
				pointer-events: none;
			}
			div.group.f div.dir div.wrapper .tree.group {
				padding-bottom: 5px;
				transform: translateY(-8.25px);
				overflow: hidden;
			}
			div.group.f div.dir div.wrapper .tree.mbr[expand="false"] > .tree.all > .tree.group { height: 0px; }
			div.group.s {
				width: calc(100% - 2.5px); height: calc(100% - 2.5px);
				border-radius: 2.5px; border: 1.25px solid transparent;
				box-shadow: 0px 0px var(--shd-big) var(--fade-black-7);
				transition: var(--time-tst-medium);
			}
			div.group.s:hover { border: 1.25px solid var(--clr-pp-blue-grey-800); }
			div.group.s div.list { width: 100%; height: 100%; }
			div.group.s div.list > iframe {
				width: 100%; height: 100%;
				border: none;
			}
			div.group.s div.list div.msg {
				position: relative; top: 50%; left: 50%; transform: translate(-50%, -50%);
				max-width: 80%;
			}
			div.group.s div.list div.table {
				width: 100%; height: calc(100% - 32.75px);
				border: none;
			}
			div.group.s div.list div.flow {
				padding: 1.25px 2.5px 2.5px;
				height: 28px; line-height: 28px;
				background-color: var(--clr-gg-grey-100);
				border-top: 1px solid transparent;
				display: flex; justify-content: space-between;
				transition: var(--time-tst-medium);
			}
			div.group.s:hover div.list div.flow { border-top: 1px solid var(--clr-pp-blue-grey-800); }
			div.group.s div.list div.table tr td:nth-of-type(4), div.group.s div.list div.table tr td:nth-of-type(6) { text-align: center; }
			div.group.s div.list div.table > table thead tr th[onClick]:hover { background-image: linear-gradient(to bottom, rgba(0 0 0 / 0.3125), rgba(0 0 0 / 0.0625)); }
			div.group.s div.list div.table > table thead tr th[onClick]:active { background-image: linear-gradient(to top, rgba(0 0 0 / 0.3125), rgba(0 0 0 / 0.0625)); }
			div.group.s div.list div.flow > div { display: flex; }
			div.group.s div.list div.flow > div > * { margin: 0px 1.25px; }
			div.group.s div.list div.flow > div a {
				width: 28px; height: 28px;
				border-radius: 50%;
				text-align: center;
				display: block;
				transition: var(--time-tst-xfast);
			}
			div.group.s div.list div.flow > div a:hover {
				text-decoration: none;
				background-color: var(--fade-black-8);
			}
			div.group.s div.list div.flow > div a i.material-icons, div.group.s div.list div.flow > div span {
				width: inherit; height: inherit;
				line-height: 28px;
				display: block;
			}
			div.group.s div.list div.flow > div select {
				padding: 0px 5px;
				border-radius: 2.5px 2.5px 0px 0px;
				border-bottom: 1px solid var(--clr-main-black-absolute);
				appearance: none; -webkit-appearance: none; -moz-appearance: none;
				transition: var(--time-tst-fast); cursor: text;
			}
			div.group.s div.list div.flow > div select:focus { border-bottom: 1px solid var(--clr-bs-blue); }
			div.group.s div.list div.flow div.perpage select { text-align: center; }
			div.group.s div.list div.flow div.pages select { text-align: right; }
			@media only screen and (max-width: 768px) {
				html body main div.container div.wrapper { flex-direction: column; height: calc(100vh - var(--top-height) - 60px); }
				html body main div.container div.wrapper div.group { width: 100% !important; }
				html body main div.container div.wrapper div.group:nth-child(1) { height: 210px; }
				html body main div.container div.wrapper div.group:nth-child(2) { height: calc(100vh - var(--top-height) - 210px - 72.5px); }
				div.group.f div.dir div.wrapper .tree.ctrl {
					transform: translateX(calc(-1 * var(--lvlH) + 2.5px));
					display: flex;
				}
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				// $(sS.slt.d).on("change", sS.complete);
				$(sS.slt.v).on("input change", sS.find);
				// Fill patterned elements
				$('div.group.f div.dir div.wrapper .tree.ctrl').prepend('<label class="tree accd"><input type="checkbox"></label>');
				$('div.group.f div.dir div.wrapper .tree.mbr:not([expand])').attr("expand", "false");
				// Add event listener
				$('div.group.f div.dir div.wrapper .tree.accd input[type="checkbox"]').on("click", function() { sD.tree(this); });
				$("div.group.f div.dir div.wrapper .tree.accd + label").on("click", function() { sD.load(this); });
				// Select default
				$('div.group.f div.dir div.wrapper .tree.accd + label[data-info="pre-select"]').click();
				$('div.group.f div.dir div.wrapper .tree.accd + label[data-info="pre-select"]').removeAttr("data-info");
			});
			var sv = { sq: null };
			const sS = {
				slt: {
					v: 'div.group.f div.search div.live input[name="search"]'
					// d: 'div.group.f div.search div.live input[name="search"] + input[type="search"]',
				}, find: function() {
					/* setTimeout(function() {
						fsa.start("ค้นหาบัญชีผู้ใช้งานทั้งหมด", sS.slt.v, sS.slt.d, "", "all");
					}, 50); */
					var search_for = document.querySelector(sS.slt.v).value.trim();
					sv.sq = (/^[^%_+]{1,75}$/.test(search_for) ? search_for : null);
					sD.load(null, "search");
				}, /* complete: function() {
					if (document.querySelector(sS.slt.v).value != "") {
						$("div.group.f div.dir div.wrapper .tree.accd + label[selected]").attr("data-info", "last-select");
						$("div.group.f div.dir div.wrapper .tree.accd + label[selected]").removeAttr("selected");
						document.querySelector(sF.slt).innerHTML = '<iframe src="edit-info?of='+document.querySelector(sS.slt.v).value+'">Loading...</iframe>';
					} else {
						$('div.group.f div.dir div.wrapper .tree.accd + label[data-info="last-select"]').click();
						$('div.group.f div.dir div.wrapper .tree.accd + label[data-info="last-select"]').removeAttr("data-info");
					}
				}, */ clear: function() {
					document.querySelector(sS.slt.v).value = "";
					// document.querySelector(sS.slt.d).value = "";
				}
			};
			const sD = {
				slt: "",
				tree: function(me) {
					var ctrler = $(me.parentNode.parentNode.parentNode.parentNode);
					let newattr = ( (ctrler.attr("expand") == "true") ? "false" : "true" );
					ctrler.attr("expand", newattr);
				}, load: function(me=null, change="pathTree") {
					if (me == null) me = "div.group.f div.dir div.wrapper .tree.accd + label[selected]";
					else {
						$("div.group.f div.dir div.wrapper .tree.accd + label[selected]").removeAttr("selected");
						me.setAttribute("selected", "");
					} var dir = $(me).attr("data-tree");
					document.querySelector("div.group.s div.list").disabled = true;
					$.post("/e/Pathway-Speech-Contest/resource/php/fetch?list=attend&change="+change+(sv.sq!=null?("&q="+encodeURIComponent(sv.sq)):""), {
						pathTree: dir,
						page: sF.ctrl.page.current,
						show: sF.ctrl.page.disp,
						sortBy: sF.ctrl.sort.col,
						sortOrder: (sF.ctrl.sort.order ? "ASC" : "DESC")
					}, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							sF.ctrl = dat.intl;
							sF.render(dat.info);
						} else document.querySelector(sF.slt).innerHTML = '<div class="msg"><center class="message red"><?=$_COOKIE['set_lang']=="th"?"เกิดปัญหาระหว่างการโหลกรายชื่อ":"Error while trying to fetch user list."?></center></div>';
						document.querySelector("div.group.s div.list").disabled = false;
					}); // sS.clear();
				}
			};
			const sF = {
				slt: "div.group.s div.list",
				render: function(data) {
					if (data.users.length == 0) document.querySelector(sF.slt).innerHTML = '<div class="msg"><center class="message gray"><?=$_COOKIE['set_lang']=="th"?"ไม่มีชื่อในหมวดหมู่นี้":"There are no user in this category."?></center></div>';
					else {
						var htmlPL = '<div class="table"><table>';
						if (typeof data.column !== "undefined" && data.column.length > 0) {
							htmlPL += "<thead><tr>";
							sF.ctrl.colList = [];
							data.column.forEach(function(ec) {
								let newHTML = "<th";
								if (ec.sortable) newHTML += ' onClick="sF.list.sort(\''+ec.ref+'\')"';
								newHTML += '>'+ec.name+'</th>';
								htmlPL += newHTML;
								sF.ctrl.colList.push(ec.ref);
							});
							htmlPL += "</tr></thead>";
						} htmlPL += "<tbody>";
						data.users.forEach(function(eu) {
							let newHTML = "<tr>";
							sF.ctrl.colList.forEach(function(bc) {
								newHTML += "<td>";
								if (typeof eu[bc].link === "string") newHTML += '<a href="'+eu[bc].link+'" draggable="false">';
								newHTML += eu[bc].val;
								if (typeof eu[bc].link === "string") newHTML += '</a>';
								newHTML += "</td>";
							});
							htmlPL += newHTML+"</tr>";
						});
						htmlPL += '</tbody></table></div><div class="flow">'+'<div class="perpage"><span>Show </span><select onChange="sF.list.disp(this.value)">';
						[10,20,25,30,50].forEach(function(pa) {
							let as = pa.toString(), defppv = (pa == sF.ctrl.page.disp);
							htmlPL += '<option value="'+as+'" '+(defppv ?"selected":"")+'>'+as+'</option>';
						}); htmlPL += '</select></div><div class="pages"><a onClick="sF.list.page(\'first\')" data-title="First Page" href="javascript:void(0)" draggable="false"><i class="material-icons">first_page</i></a><a onClick="sF.list.page(\'prev\')" data-title="Previous Page" href="javascript:void(0)" draggable="false"><i class="material-icons">chevron_left</i></a><select onChange="sF.list.page(this.value)">';
						for (let page = 1; page <= sF.ctrl.page.max; page++) {
							let p = page.toString(), defpgn = (page == sF.ctrl.page.current);
							htmlPL += '<option value="'+p+'" '+(defpgn ?"selected":"")+'>'+p+'</option>';
						} htmlPL += '</select><a onClick="sF.list.page(\'next\')" data-title="Next Page" href="javascript:void(0)" draggable="false"><i class="material-icons">chevron_right</i></a><a onClick="sF.list.page(\'last\')" data-title="Last Page" href="javascript:void(0)" draggable="false"><i class="material-icons">last_page</i></a></div></div>';
						// Display HTML
						document.querySelector(sF.slt).innerHTML = htmlPL;
						// Page controller
						var pageBtn = [
							$("div.group.s div.list div.flow div.pages a:nth-of-type(1)"),
							$("div.group.s div.list div.flow div.pages a:nth-of-type(2)"),
							$("div.group.s div.list div.flow div.pages a:nth-of-type(3)"),
							$("div.group.s div.list div.flow div.pages a:nth-of-type(4)"),
							document.querySelector("div.group.s div.list div.flow div.pages select")
						];
						if (sF.ctrl.page.current == 1) { pageBtn[0].attr("disabled", ""); pageBtn[1].attr("disabled", ""); }
						else { pageBtn[0].removeAttr("disabled"); pageBtn[1].removeAttr("disabled"); }
						if (sF.ctrl.page.current == sF.ctrl.page.max) { pageBtn[2].attr("disabled", ""); pageBtn[3].attr("disabled", ""); }
						else { pageBtn[2].removeAttr("disabled"); pageBtn[3].removeAttr("disabled"); }
						pageBtn[4].disabled = (sF.ctrl.page.max == 1);
					}
				}, ctrl: {
					page: { current: 1, max: 1, disp: 20 },
					sort: { col: "A", order: 1 }
				}, list: {
					sort: function(col) {
						if (col != sF.ctrl.sort.col) {
							sF.ctrl.sort.col = col;
							sD.load(null, "sortBy");
						} else {
							sF.ctrl.sort.order = (sF.ctrl.sort.order ? 0 : 1);
							sD.load(null, "sortOrder");
						}
					}, page: function(pgn) {
						switch (pgn) {
							case "first": sF.ctrl.page.current = 1; break;
							case "prev": sF.ctrl.page.current -= 1; break;
							case "next": sF.ctrl.page.current += 1; break;
							case "last": sF.ctrl.page.current = sF.ctrl.page.max; break;
							default: sF.ctrl.page.current = pgn; break;
						} sD.load(null, "page");
					}, disp: function(amt) {
						sF.ctrl.page.disp = amt;
						sD.load(null, "show");
					}
				}
			};
		</script>
		<script type="text/javascript" src="/resource/js/lib/w3.min.js"></script>
		<script type="text/javascript" src="/resource/js/extend/fs-account.js"></script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<div class="wrapper">
					<div class="group f">
						<div class="search">
							<div class="live filter">
								<!input name="search" type="hidden">
								<input type="search" name="search" placeholder="Search ... (ค้นหา)">
							</div>
						</div>
						<div class="dir slider">
							<div class="wrapper">
								<ul class="tree group">
									<li class="tree mbr" expand="true">
										<div class="tree all">
											<div class="tree ctrl">
												<label data-tree="cf\PathwaySpeechContest" data-info="pre-select">All registrants</label>
											</div>
											<ul class="tree group">
												<li class="tree mbr off">
													<div class="tree all">
														<div class="tree ctrl">
															<label data-tree="cf\PathwaySpeechContest\A">Primary 3-6</label>
														</div>
													</div>
												</li>
												<li class="tree mbr off">
													<div class="tree all">
														<div class="tree ctrl">
															<label data-tree="cf\PathwaySpeechContest\B">Secondary 1-3</label>
														</div>
													</div>
												</li>
												<li class="tree mbr off">
													<div class="tree all">
														<div class="tree ctrl">
															<label data-tree="cf\PathwaySpeechContest\C">Secondary 4-6</label>
														</div>
													</div>
												</li>
											</ul>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="group s">
						<div class="list">
							
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