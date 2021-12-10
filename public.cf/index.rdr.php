<?php # header("Location: https://inf.bodin.ac.th/e/Pathway-Speech-Contest/"); ?>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="author" content="Tecillium (UFDT)" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Pathway Speech Contest</title>
		<script src="//TianTcl.net/resource/js/lib/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			var frameSource = "https://inf.bodin.ac.th/e/Pathway-Speech-Contest/<?php echo ltrim($_GET['url']??"", "/"); ?>";
			$(function() {
				// $("head").load(frameSource+" head");
			});
			setInterval(function() {
				/* var frame = document.querySelector("iframe");
				if (frame.src != frameSource) {
					frameSource = frame.src;
					history.replaceState(null, null, frameSource.substr(48));
				} */
				if (window.top.var_url != frameSource) {
					frameSource = window.top.var_url;
					history.replaceState(null, null, frameSource);
				}
			}, 2500);
		</script>
	</head>
	<body style="margin:0;padding:0;">
		<iframe src="https://inf.bodin.ac.th/e/Pathway-Speech-Contest/<?php echo ltrim($_GET['url']??"", "/"); ?>" style="width:100vw;height:100vh;border:none;">
	</body>
</html>