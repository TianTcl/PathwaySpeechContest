<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
	require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ps.php");
	$header_title = "Developers";
	$header_desc = "LINE Notify API";

    $token = "XP3VISKyJUIOFPe1vDyunupmaUeUoNOef8td7ENwSze"; # "p5yVDVvUBriLUtoo1H4UImuh0Jf6iLzuvNuu6fQg26n";

    if (isset($_POST['msg'])) {
        $queryData = http_build_query(array("message" => trim($_POST['msg'])), "", "&");
        $headerOptions = array("http" => array(
            "method" => "POST",
            "header" => "Content-Type: application/x-www-form-urlencoded\r\n"
                ."Authorization: Bearer $token\r\n"
                ."Content-Length: ".strlen($queryData)."\r\n",
            "content" => $queryData
        ) );
        $result = file_get_contents("https://notify-api.line.me/api/notify", false, stream_context_create($headerOptions));
        # print_r(json_decode($result));
    }
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/heading.php"); require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			
		</style>
		<script type="text/javascript">
			function test() {
                return true;
                $.ajax({
                    url: "https://notify-api.line.me/api/notify", method: "POST",
                    "Content-Type": "multipart/form-data", crossDomain: true,
                    headers: {
                        Authorization: "Bearer <?=$token?>"
                    }, data: {
                        message: document.querySelector("input").value.trim(),
                        notificationDisabled: true
                    }, dataType: "json", success: function(res, hsc) {
                        console.info(res);
                        app.ui.notify(1, [1, res.toString()]);
                        $(".container").append('<div class="message gray">'+res.toString()+'</div>');
                    }, beforeSend: function(xhr) {
                        xhr.setRequestHeader("Authorization", "Bearer <?=$token?>");
                    }, statusCode: {
                        200: function() { apiReturn(200); },
                        400: function() { apiReturn(400); },
                        401: function() { apiReturn(401); },
                        500: function() { apiReturn(500); }
                    }
                });
                function apiReturn(code) {
                    switch(code) {
                        case 200: app.ui.notify(1, [0, "Success"]); break;
                        case 400: app.ui.notify(1, [1, "Bad request"]); break;
                        case 401: app.ui.notify(1, [2, "Invalid access token"]); break;
                        case 500: app.ui.notify(1, [3, "Failure due to server error"]); break;
                        default: app.ui.notify(1, [2, "Unknown error"]); break;
                    }
                }
            }
		</script>
	</head>
	<body>
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
			<div class="container">
                <form class="form" method="post"><div class="group">
                    <input type="text" name="msg" value="สวัสดี ผู้จัดโครงการ Pathway Speech Contest">
				    <button class="yellow full-x" onClick="return test()">Test API</button>
                </div></form>
			</div>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>