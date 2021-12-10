<?php
	if (!function_exists("create_database_connection")) { function create_database_connection($db_name = "db_name") {
		$db = new mysqli("localhost", "user", "pass", "db_name");
		/* $db = new mysqli("localhost", "user", "pass");
		$db -> select_db($db_name); */
		# $db = new mysqli("localhost", "user", "pass", $db_name);
		$db -> set_charset("utf8");
		if ($db -> connect_errno) die("Database connection failed: ".$db -> connect_error);
		return $db;
	} }

	if (!function_exists("slog")) { function slog($iApp="", $iCmd="", $iAct="", $iData="", $iVal="", $iAttr="", $iRef="", $close=false, $forced=false) {
		// Check connection
		# if (!isset($db)) $db = $GLOBALS['db'] ?? create_database_connection();
		$db = create_database_connection();
		if (!isset($ip)) require("../extend/getip.php");
		// Clean data
		$dExor = $_SESSION['auth']['user'] ?? "";
		$dApp = trim(strval($iApp)); try { $dApp = $db -> real_escape_string($dApp); } catch(Exception$e){}
		$dCmd = trim(strval($iCmd)); try { $dCmd = $db -> real_escape_string($dCmd); } catch(Exception$e){}
		$dAct = trim(strval($iAct)); try { $dAct = $db -> real_escape_string($dAct); } catch(Exception$e){}
		$dData = trim(strval($iData)); try { $dData = $db -> real_escape_string($dData); } catch(Exception$e){}
		$dVal = trim(strval($iVal)); try { $dVal = $db -> real_escape_string($dVal); } catch(Exception$e){}
		$dAttr = trim(strval($iAttr)); try { $dAttr = $db -> real_escape_string($dAttr); } catch(Exception$e){}
		$dRef = trim(strval($iRef)); try { $dRef = $db -> real_escape_string($dRef); } catch(Exception$e){}
		// Filter user
		$avoid_user = array(/*"TianTcl"*/"test");
		if (!$forced && in_array($dExor, $avoid_user)) return false;
		// Record em
		$success = $db -> query("INSERT INTO log_action (exor,app,cmd,act,data,val,attr,ref,ip) VALUES ('$dExor','$dApp','$dCmd','$dAct','$dData','$dVal','$dAttr','$dRef','$ip')");
		// Close connection
		if (/*$close &&*/ isset($db)) $db -> close();
		// Returns status (bool)
		return $success;
	} }

	if (isset($db)) unset($db); # global $db;
	$db = create_database_connection($database??"db_name");
?>