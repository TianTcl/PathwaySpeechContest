<?php
	session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");

    if (isset($_REQUEST['set']) && trim($_REQUEST['set']) <> "") {
        require($dirPWroot."e/resource/db_connect.php"); # require($dirPWroot."/resource/php/lib/TianTcl.php");
        $reqG = trim($_REQUEST['g'] ?? ""); if ($reqG == "") $reqG = trim($_REQUEST['group'] ?? "");
        $reqData = trim($_REQUEST['data'] ?? "");
        $reqQ = $db -> real_escape_string(trim($_REQUEST['q'] ?? ""));
        switch (trim($_REQUEST['set'])) {
            case "donate": {
                $getinfo = $db -> query("SELECT donor,amt,slip FROM PathwaySCon_donation WHERE refer='$reqQ'");
                if ($getinfo) {
                    if ($getinfo -> num_rows == 1) {
                        $readinfo = $getinfo -> fetch_array(MYSQLI_ASSOC);
                        if (!empty($readinfo['slip'])) echo '{"success": true, "info": {"status": 901, "msg": ["yellow", "'.($_COOKIE['set_lang']=="th"?"คุณได้ทำการส่งกหลักฐานพร้อมเพิ่มที่อยู่ไปแล้ว":"You've already send the evidence and added your address.").'"]}}';
                        else echo '{"success": true, "info": {"status": 903, "data": '.json_encode(array(
                            "name" => $readinfo['donor'],
                            "amount" => $readinfo['amt']
                        )).'}}';
                    } else echo '{"success": true, "info": {"status": 900, "msg": ["red", "'.($_COOKIE['set_lang']=="th"?"ไม่พบข้อมูลของหมายเลขอ้างอิงนี้":"No record found for this reference number.").'"]}}';
                } else echo '{"success": false, "reason": [3, "Unable to get record."]}';
            }
        } $db -> close();
    }
?>