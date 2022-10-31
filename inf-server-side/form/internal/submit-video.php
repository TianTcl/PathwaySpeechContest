<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER["PHP_SELF"], "/")-1);
	require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ps.php");
	$header_title = "";
	$header_desc = "";

	require($dirPWroot."/resource/php/lib/TianTcl.php");

    $rmte = (isset($_REQUEST["remote"]) && $_REQUEST["remote"]); $remote = $rmte ? "remote" : "";
    $user = $rmte ? strval(intval($tcl -> decode(str_replace("-", "", $_REQUEST["remote"])))/138-138) : ($_SESSION["evt"]["user"] ?? "");
    $returnPath = $rmte ? "https://PathwaySpeechContest.cf" : "/e/Pathway-Speech-Contest";
    if ($user == "") header("Location: $returnPath/login#next=speech-video");

    require($dirPWroot."e/resource/db_connect.php");
    $user = $db -> real_escape_string($user);
    $getinfo = $db -> query("SELECT email,publicID FROM PathwaySCon_attendees WHERE ptpid=$user");
    $db -> close();

    if (!$getinfo) header("Location: $returnPath/login#next=speech-video");
    $readinfo = $getinfo -> fetch_array(MYSQLI_ASSOC);
    require($dirPWroot."resource/php/core/getip.php");
    $formLink = "https://docs.google.com/forms/d/e/1FAIpQLSfWbw41IZ2eTdECwjJeOTJH0oE-KcS3kNyTLxyIgYizyt9lJQ/viewform?entry.2027917912=".$readinfo["publicID"]."&entry.1659649865=".$readinfo["email"]."&entry.1234696549=$ip&authuser=".$readinfo["email"];
	header("Location: $formLink"); exit(0);

	if (false) {
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/heading.php"); require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ss.php"); ?>
		<style type="text/css">
			
		</style>
		<script type="text/javascript">
			
		</script>
	</head>
	<body class="nohbar">
		<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/header.php"); ?>
		<main shrink="<?php echo($_COOKIE["sui_open-nt"])??"false"; ?>">
			<iframe src="<?=$formLink?>&embedded=true">Loading...</iframe>
		</main>
		<?php require($dirPWroot."resource/hpe/material.php"); ?>
		<footer>
			<?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/footer.php"); ?>
		</footer>
	</body>
</html>
<? } ?>