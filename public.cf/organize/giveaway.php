<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."resource/hpe/init_ps.php");
	$header_title = "Giveaway";
	$header_desc = "Generate File Link (Organizer)";

	if (!isset($_SESSION['evt2'])) header("Location: ./$my_url");
	else if ($_SESSION['evt2']["force_pwd_change"]) header("Location: new-password$my_url");
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			main form input, main form select { background-color: var(--clr-psc-skin-shiny) !important; }
			main form input { text-transform: lowercase; }
			main form input::placeholder { text-transform: initial; }
			main .link input[readonly] { user-select: all; }
			main .link button i.material-icons { transform: translate(-1px, 5px); }
			@media only screen and (max-width: 768px) {
				main .link button i.material-icons { transform: translate(-2.5px, 2.5px); }
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				$(".link").hide();
			})
			const cv = {
				APIurl: "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/api",
				msg: [
					"ขอบคุณมากๆที่มาร่วมสนุกกับทาง Pathway Speech Contest นะคะ🙏🏻🥰 หวังว่า 50 Essential IELTS Vocabulary จะช่วยเพิ่มศัพท์ในคลังได้ค่าา 💕🧚🏻‍♀️"
				]
			}; var sv = { first: true };
			function generate() {
				go_on(); return false;
				function go_on() {
					var data = {
						remote: true,
						user: document.querySelector('form [name="user"]').value.trim().toLowerCase(),
						book: document.querySelector('form [name="book"]').value.trim()
					}; if (data.user.length <= 0 || data.user.length > 50) {
						app.ui.notify(1, [3, "Username is empty or too long"]);
						$('form [name="user"]').focus(); return false;
					} if (data.book.length == 0) {
						app.ui.notify(1, [3, "No Sheet Selected"]);
						$('form [name="book"]').focus(); return false;
					} document.querySelector("form button").disabled = true;
					$.post(cv.APIurl, {app: "giveaway", cmd: "get", attr: data}, function(res, hsc) {
						var dat = JSON.parse(res);
						if (dat.success) {
							if (sv.first) {
								$(".link").toggle("drop", "linear", "slow");
								sv.first = false;
							} sv.link = "https://"+location.hostname+"/giveaway?sheet="+dat.info;
							sv.book = parseInt(data.book) - 1;
							document.querySelector(".link input[readonly]").value = sv.link;
						} else {
							app.ui.notify(1, dat.reason);
							document.querySelector("form button").disabled = false;
						}
					});
				}
			}
			function update() { document.querySelector("form button").disabled = false; }
			const linked = {
				copy: function() {
					var word = document.createElement("textarea");
					word.value = cv.msg[sv.book] + "\n" + sv.link; document.body.appendChild(word);
					word.select(); document.execCommand("copy"); document.body.removeChild(word);
					app.ui.notify(1, [0, "Link copied."]);
				}, share: function() {
					if (navigator.share) {
						navigator.share({
							title: cv.msg[sv.book],
							text: cv.msg[sv.book] + "\n" + sv.link
						}).then(function() {
							// app.ui.notify(1, [1, "Thanks for sharing"]);
						});
					} else {
						app.ui.notify(1, [2, "Your device doesn't support sharing method."]);
						linked.copy();
					}
				}
			};
		</script>
	</head>
	<body>
		<?php require($dirPWroot."resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
				<h2>Generate Giveaway Sheet Link</h2>
				<form class="form" onChange="update()">
					<input type="text" name="user" maxlength="50" placeholder="Instagram or Facebook username">
					<div class="group">
						<select name="book">
							<option selected value disabled>---Sheet---</option>
							<option value="1">1) 50 Essential IELTS Vocabulary</option>
						</select>
						<button class="blue full-x ripple-click" onClick="return generate()" disabled>Get Link</button>
					</div>
				</form>
				<div class="link form message cyan">
					<div class="group" style="margin: 0px;">
						<input type="text" readonly>
						<button class="blue ripple-click" onClick="linked.copy()"><i class="material-icons">content_copy</i></button>
						<button class="green ripple-click" onClick="linked.share()"><i class="material-icons">share</i></button>
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