<?php
    session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    require_once($dirPWroot."e/Pathway-Speech-Contest/resource/php/config.php");

    $start = ($_GET["start"] ?? false); if ($start && !preg_match("/^[3-9]\d{2}$/", trim($start))) $start = false;
    if ($start) {
        require($dirPWroot."e/resource/db_connect.php");
        $get = $db -> query("SELECT namen,grade,school,ptpid FROM PathwaySCon_attendees WHERE ptpid>=$start ORDER BY ptpid ASC");
        if ($get && $get -> num_rows) {
            require($dirPWroot."resource/php/lib/LINE.php");
            $LINE -> setToken("YsS15OnSstLdNWHGJav5vq2i9c0dwF79KUsovgJMjJq");
            while ($read = $get -> fetch_assoc()) {
                $msg = "มีผู้สมัครใหม่ → ".$read['namen']."\r\n".$num2grade[intval($read['grade'])]." โรงเรียน".$read['school']."\r\nจำนวนผู้สมัครทั้งหมด ".strval(intval($read["ptpid"])-1)." คน";
                $success = $LINE -> notify($msg);
                echo "[".($success?"SUCCESS":"FAIL")."]: $success -> $msg<br>";
            }
        } $db -> close();
    } else echo "Token mismatch";
?>