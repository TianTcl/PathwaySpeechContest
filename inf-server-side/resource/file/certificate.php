<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ps.php");
    header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");
    $header_title = "Certificate";
    
    require_once($dirPWroot."e/Pathway-Speech-Contest/resource/php/config.php"); require_once($dirPWroot."resource/php/lib/TianTcl.php");

    function decode_key($key) { global $tcl; return strval(intval($tcl -> decode(str_replace("-", "", $key)))/138-138); }
    function check_req($param) { return (isset($_REQUEST[$param]) && trim($_REQUEST[$param])<>""); }

    $rmte = (isset($_REQUEST['remote']) && $_REQUEST['remote']); $remote = $rmte ? "remote" : "";
    $user = $rmte ? decode_key($_REQUEST['remote']) : ($_SESSION['evt']['user'] ?? ""); if ($user == "" && check_req("view-id")) $user = decode_key($_REQUEST['view-id']);
    $round = $config['round'];

    if ($user == "") $error = "901";
    else if (check_req("type") && check_req("export")) {
        $export = trim($_REQUEST['export']);
        $certType = trim($_REQUEST['type']);
        if ($export <> "view") {
            require($dirPWroot."e/resource/db_connect.php");
            $download = ($export == "download");
            $getinfo = $db -> query("SELECT a.smid,a.edit,a.rank,a.rank_time,CONCAT(b.namef, '  ', b.namel) AS name,(CASE WHEN b.grade BETWEEN 0 AND 3 THEN 'A' WHEN b.grade BETWEEN 4 AND 6 THEN 'B' WHEN b.grade BETWEEN 7 AND 9 THEN 'C' END) AS `group` FROM PathwaySCon_submission a INNER JOIN PathwaySCon_attendees b ON a.ptpid=b.ptpid WHERE a.ptpid=$user AND a.round=$round");
            if ($getinfo && $getinfo -> num_rows == 1) {
                $readinfo = $getinfo -> fetch_array(MYSQLI_ASSOC);
                if (empty($readinfo['rank'])) $error = "900";
                else if ($readinfo['rank'] == "5N" && $certType == "a") $error = "902";
                else {
                    $isAward = ($certType == "a");
                    switch ($certType) {
                        case "p": $certName = "Participation"; $certPath = "pa"; $certNumber = "A"; break;
                        case "a": $certName = "Completion"; $certPath = "g".$readinfo['group']; $certNumber = "B"; break;
                    } switch (intval($round)) {
                        case 1: $cfgCert = array(
                            "pageSize" => array(357.5, 275), "sourceSize" => array(1456, 1126), "sourceType" => "jpg", "sourceDPI" => 96,
                            "fontFamily" => "Pattaya", "fontSize" => 41, "fontColor" => array(9, 95, 34),
                            "pos" => array("xID" => 15, "yName" => ($isAward ? 112.5 : 120), "yPlace" => 145)
                        ); break;
                        case 2: $cfgCert = array(
                            "pageSize" => array(357.5, 275), "sourceSize" => array(1650, 1275), "sourceType" => "jpg", "sourceDPI" => 150,
                            "fontFamily" => "Pattaya", "fontSize" => 42, "fontColor" => array(33, 102, 153),
                            "pos" => array("xID" => 13, "yName" => ($isAward ? 106.5 : 118.25), "yPlace" => 143)
                        ); break;
                        case 3: $cfgCert = array(
                            "pageSize" => array(357.5, 275), "sourceSize" => array(1650, 1275), "sourceType" => "jpg", "sourceDPI" => 150,
                            "fontFamily" => "Pattaya", "fontSize" => 42, "fontColor" => array(36, 92, 45),
                            "pos" => array("xID" => 13, "yName" => ($isAward ? 106.5 : 118.25), "yPlace" => 143)
                        ); break;
                    } $exportname = "Certificate - Pathway Speech Contest ($certName)";
                    /* --- PDF generation --- (BEGIN) */
                    require_once($dirPWroot."resource/php/lib/tcpdf/tcpdf.php"); # require_once($dirPWroot."resource/php/lib/fpdi/fpdi.php");
                    $certifile = new TCPDF("L", PDF_UNIT, "A4", true, 'UTF-8', false);
                    // Configuration
                    $certifile -> SetProtection(array("modify", "copy", "annot-forms", "fill-forms", "extract", "assemble"), "", null, 0, null);
                    $certifile -> SetCreator("Pathway Speech Contest - Web Application");
                    $certifile -> SetAuthor("Pathway Speech Contest - Team");
                    $certifile -> SetTitle($exportname);
                    $certifile -> SetSubject($exportname);
                    $certifile -> setPrintHeader(false);
                    $certifile -> setPrintFooter(false);
                    $certifile -> SetKeywords("Pathway Speech Contest");
                    $certifile -> SetAutoPageBreak(false, 0);
                    // Edit
                    $certifile -> AddPage("L", array($cfgCert["pageSize"][0], $cfgCert["pageSize"][1]));
                    $sourcePath = $dirPWroot."e/Pathway-Speech-Contest/resource/file/cert/s".(strlen($round)<2?"0":"")."$round-$certPath.".$cfgCert["sourceType"];
                    $certifile -> Image($sourcePath, 0, 0, $cfgCert["pageSize"][0], $cfgCert["pageSize"][1], strtoupper($cfgCert["sourceType"]), "", "", false, $cfgCert["sourceDPI"], "", false, false, 0);
                    $certifile -> setPageMark();
                    $certifile -> setTextColor($cfgCert["fontColor"][0], $cfgCert["fontColor"][1], $cfgCert["fontColor"][2]);
                    // Add cert-id
                    $certID = strrev(strtoupper(base_convert(strtotime($readinfo['rank_time']), 10, 36)))."-".
                        str_rot13($readinfo['rank'].$readinfo['edit'].$certNumber.strtoupper($tcl -> encode($readinfo['smid'], 1)));
                    $certifile -> setFont("thsarabun", "I", 15);
                    $certifile -> SetXY($cfgCert["pos"]["xID"], 10);
                    $certifile -> Cell(75, 0, "Cert-ID: $certID", 0, 1, 'L', 0, '', 0);
                    // Add name
                    $certifile -> setFont($cfgCert["fontFamily"], "B", $cfgCert["fontSize"]);
                    $certifile -> SetXY($cfgCert["pageSize"][0]/2-175, $cfgCert["pos"]["yName"]);
                    $certifile -> Cell(350, 0, $readinfo['name'], 0, 1, 'C', 0, '', 0);
                    if ($isAward) { // Add Place
                        $certifile -> setFont($cfgCert["fontFamily"], "", $cfgCert["fontSize"] - 2);
                        $certifile -> SetXY($cfgCert["pageSize"][0]/2-175, $cfgCert["pos"]["yPlace"]);
                        switch ($readinfo['rank']) {
                            case "1G": $rankText = "1st"; break;
                            case "2S": $rankText = "2nd"; break;
                            case "3B": $rankText = "3rd"; break;
                            default: $error = "905"; break;
                        } $certifile -> Cell(350, 0, ($rankText??"")." Place", 0, 1, 'C', 0, '', 0);
                    } // Send out file
                    if (!isset($error)) {
                        $certifile -> Output("$exportname.pdf", ($download ? "D": "I"));
                        /* --- PDF generation --- (END) */
                        slog($user, "PathwaySCon", "certificate", ($download ? "save": ($export == "print" ? "print" : "view")), $certType, "pass", $remote);
                    }
                }
            } else $error = "900";
            $db -> close();
        } else {
            $viewer = true;
            $keyid = vsprintf("%s-%s-%s-%s", str_split($tcl -> encode((intval($user)+138)*138, 1), 4));
        }
    } else $error = "902";
    if (isset($error)) $header_desc = "Error ($error)";
    if (isset($error) || isset($viewer)) {
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/heading.php"); require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ss.php"); ?>
        <style type="text/css">
            main > iframe { position: absolute; top: 0px; z-index: 1; }
        </style>
        <script type="text/javascript">
            
        </script>
    </head>
    <body <?=(isset($viewer)?'class="nohbar"':"")?>>
        <?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/header.php"); ?>
        <main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
            <?php if (isset($error)) echo '<iframe src="/error/'.$error.'">Loading...</iframe>'; else { ?>
                <div class="container">
                    <div class="message yellow"><?=$_COOKIE['set_lang']=="th"?'หากประกาศนียบัตรไม่ปรากฏขึ้นใน 10 วินาที กรุณากด reload หน้านี้':'If the certificate doesn\'t show up within 10 seconds. Please reload the page.'?></div>
                </div>
                <iframe src="https://docs.google.com/viewerng/viewer?embedded=true&url=https%3A%2F%2Finf.bodin.ac.th%2Fe%2FPathway-Speech-Contest%2Fresource%2Ffile%2Fcertificate%3Fview-id%3D<?="$keyid%26type%3D$certType"?>%26export%3Dshow">Loading...</iframe>
            <?php } ?>
        </main>
        <?php require($dirPWroot."resource/hpe/material.php"); ?>
        <footer>
            <?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/footer.php"); ?>
        </footer>
    </body>
</html>
<?php } ?>