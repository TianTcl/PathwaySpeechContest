<?php
    session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");
	// Configuration
	$colcode = array( // (colName, sortable, Display, Link)
		"A" => array("namen", 1, "Nick name", false),
		"B" => array("namef", 1, "First name", false),
		"C" => array("namel", 1, "Last name", false),
		"D" => array("email", 1, "E-mail address", false),
		"E" => array("grade", 1, "Grade", false),
		"F" => array("school", 1, "School", false),
		"G" => array("time", 1, "Register time", false)
	);
	// Inputs
    if (isset($_REQUEST['list'])) {
        $list = trim($_REQUEST['list']);
        if ($list == "attend") {
            if (isset($_REQUEST['pathTree']) && isset($_REQUEST['page']) && isset($_REQUEST['show']) && isset($_REQUEST['sortBy']) && isset($_REQUEST['sortOrder'])) {
                require($dirPWroot."e/resource/db_connect.php");
                // Clean up
                $pathTree = trim($_REQUEST['pathTree']);
                $page = trim($_REQUEST['page']);
                $show = trim($_REQUEST['show']);
                $sortBy = trim($_REQUEST['sortBy']);
                $sortOrder = trim($_REQUEST['sortOrder']);
                // Calculate
                if (isset($_REQUEST['change'])) {
                    $change = trim($_REQUEST['change']); switch ($change) {
                        case "pathTree": $page = "1"; $sortBy = "G"; $sortOrder = "DESC"; break;
                        case "show": $page = strval(floor($_SESSION['var-mUser_set']['page']*$_SESSION['var-mUser_set']['show']/intval($show))+1); break;
                        case "sortBy": $page = "1"; $sortOrder = "ASC"; break;
                        case "sortOrder": $page = "1"; break;
                        case "search": $page = "1"; break;
                    }
                } $_SESSION['var-mUser_set'] = array( // Save for compare
                    "pathTree" => $pathTree,
                    "page" => intval($page)-1,
                    "show" => intval($show),
                    "sortBy" => $sortBy,
                    "sortOrder" => $sortOrder
                );
                // Pre generate SQL
                $sort = "ORDER BY ".$colcode[$sortBy][0]." $sortOrder";
                $disp = "LIMIT ".strval((intval($page)-1)*intval($show)).", $show";
                $queryBegin = "SELECT namen,namef,namel,email,grade,school FROM PathwaySCon_attendees WHERE NOT ptpid IN(0, 1)";
                $queryEnd = "$sort $disp"; $sql = ""; $col = array("A", "B", "C", "E", "F"); $queryPreset = array( // Change display colums here
                    "a" => "$queryBegin AND grade BETWEEN 0 AND 3",
                    "b" => "$queryBegin AND grade BETWEEN 4 AND 6",
                    "c" => "$queryBegin AND grade BETWEEN 7 AND 9",
                ); if (isset($_REQUEST['q']) && !empty(trim($_REQUEST['q']))) {
                    $searchQeury = "AND ("; $q = $db -> real_escape_string(trim($_REQUEST['q']));
                    foreach (array("A", "B", "C", "D", "F") as $cols) $searchQeury .= $colcode[$cols][0]." LIKE '$q%' OR ";
                    $searchQeury = rtrim($searchQeury, " OR ").")";
                } else $searchQeury = "";
                // Translate
                $pt = explode("\\", $pathTree);
                if ($pt[0] == "cf") {
                    if (isset($pt[1])) {
                        if ($pt[1] == "PathwaySpeechContest") {
                            if (isset($pt[2])) {
                                if ($pt[2] == "A") {
                                    $sql = $queryPreset["a"];
                                } else if ($pt[2] == "B") {
                                    $sql = $queryPreset["b"];
                                } else if ($pt[2] == "C") {
                                    $sql = $queryPreset["c"];
                                }
                            } else $sql = $queryBegin;
                        }
                    } else $sql = $queryBegin;
                } if (!empty($sql)) {
                    // Process
                    # require($dirPWroot."e/resource/db_connect.php");
                    $result = $db -> query("$sql $searchQeury $queryEnd");
                    $all = $db -> query($sql); 
                    # $db -> close();
                    // Export
                    $num2grade = array("ป.3", "ป.4", "ป.5", "ป.6", "ม.1", "ม.2", "ม.3", "ม.4", "ม.5", "ม.6");
                    $intlOut = '"intl": {'.
                        '"page": { "current": '.$page.', "max": '.strval(max(ceil(($all -> num_rows)/intval($show)), 1)).', "disp": '.$show.' },'.
                        '"sort": { "col": "'.$sortBy.'", "order": '.( $sortOrder=="DESC" ? "0" : "1" ).' }'.
                    '}';
                    if ($result -> num_rows > 0) {
                        $send = '{ "success": true, "info": { ';
                        // send thead
                        $send .= '"column": [';
                        foreach ($col as $ec) $send .= '{ "name": "'.$colcode[$ec][2].'", "ref": "'.$ec.'", "sortable": '.($colcode[$ec][1]==0 ? "false" : "true" ).' },';
                        $send = rtrim($send, ","); $send .= '],';
                        // send tbody
                        $send .= '"users": [';
                        while ($eu = $result -> fetch_assoc()) {
                            $send .= '{ ';
                            $send .= '"A": { "val": "'.$eu[$colcode['A'][0]].'" },';
                            $send .= '"B": { "val": "'.$eu[$colcode['B'][0]].'" },';
                            $send .= '"C": { "val": "'.$eu[$colcode['C'][0]].'" },';
                            $send .= '"D": { "val": "'.$eu[$colcode['D'][0]].'" },';
                            $send .= '"E": { "val": "'.$num2grade[intval($eu[$colcode['E'][0]])].'" },';
                            $send .= '"F": { "val": "'.$eu[$colcode['F'][0]].'" },';
                            $send = rtrim($send, ","); $send .= ' },';
                        } $send = rtrim($send, ","); $send .= ']';
                        // Send out
                        echo "$send }, $intlOut }";
                    } else echo '{ "success": true, "info": { "users": [] }, '.$intlOut.' }';
                } else echo '{ "success": false }';
                $db -> close();
            } else echo '{ "success": false }';
        }
        else echo '{ "success": false }';
    } else echo '{ "success": false }';


	// Fetch results
	/* echo '{
		"success": true,
		"info": {
			"column": [
				{"name": "colA", "ref": "A", "sortable": true },
				{"name": "colB", "ref": "B", "sortable": true },
				{"name": "colC", "ref": "C", "sortable": true }
			], "users": [
				{
					"A": {"val": "1a", "link": ""},
					"B": {"val": "1b", "link": ""},
					"C": {"val": "1c", "link": ""}
				}, {
					"A": {"val": "2a", "link": ""},
					"B": {"val": "2b", "link": ""},
					"C": {"val": "2c", "link": ""}
				}, {
					"A": {"val": "3a", "link": ""},
					"B": {"val": "3b", "link": ""},
					"C": {"val": "3c", "link": ""}
				}
			]
		}, "intl": {
			"page": { "current": 1, "max": 1, "disp": 20 },
			"sort": { "col": "A", "order": 0 }
		}
	}'; */
?>