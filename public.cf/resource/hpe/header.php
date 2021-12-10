<header>
    <section class="slider hscroll sscroll"><div class="ocs">
		<div class="head-item menu">
			<a onClick="app.ui.toggle.navtab()" href="javascript:void(0)" opened="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>"><div>
				<span class="bar"></span>
				<span class="bar"></span>
				<span class="bar"></span>
			</div></a>
		</div>
		<!--div class="head-item logo contain-img">
			<a href="/"><img src="/resource/images/logo.png" data-dark="false" /></a>
		</div-->
		<div class="head-item text">
			<a href="/"><span>Pathway Speech Contest</span></a>
			<!--a href="/criteria"><span>Scoring Criteria</span></a-->
			<a href="/donate"><span>Donate</span></a>
			<a href="/FaQ"><span>FaQ</span></a>
			<a href="/contact"><span>Contact us</span></a>
			<?php if (!isset($_SESSION['evt2'])) { if (!isset($_SESSION['evt'])) { ?>
				<a href="/register"><span>Register</span></a>
				<a href="/login"><span>Sign in</span></a>
			<? } else { ?>
				<a href="/submit/"><span>ส่งผลงาน</span></a>
				<a href="/logout"><span>Sign out</span></a>
			<? } } else { ?>
				<a href="/organize/home"><span>Dashboard</span></a>
				<a href="/organize/logout"><span>ออกจากระบบ</span></a>
			<? } ?>
		</div>
	</div></section>
    <section class="slider hscroll sscroll"><div class="ocs">
		<div class="head-item lang"><select name="hl">
			<option>th</option>
			<option>en</option>
		</select></div>
		<div class="head-item clrt contain-img">
			<a onCLick="app.ui.change.theme('dark')" href="javascript:void(0)"><i class="material-icons">brightness_6</i></a>
		</div>
	</div></section>
</header>