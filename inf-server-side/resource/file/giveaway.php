<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/init_ps.php");
    header("Access-Control-Allow-Origin: https://pathwayspeechcontest.cf");
    $rmte = (isset($attr['remote']) && $attr['remote']); $remote = $rmte ? "remote" : "";

    if (isset($_GET['sheet']) && !empty(trim($_GET['sheet']))) {
        require($dirPWroot."e/resource/db_connect.php");
        require_once($dirPWroot."resource/php/lib/TianTcl.php");
        $viewid = $tcl -> decode(trim($_GET['sheet']), 2);
        $getinfo = $db -> query("SELECT client,sheet FROM PathwaySCon_giveaway WHERE gaid='$viewid'");
        if ($getinfo -> num_rows == 1) {
            $download = isset($_GET['download']);
            $readinfo = $getinfo -> fetch_array(MYSQLI_ASSOC);
            require_once($dirPWroot."e/Pathway-Speech-Contest/resource/php/giveaway.php");
            $viewer = $readinfo['client']; $originalfile = $fileinfoes[intval($readinfo['sheet']) - 1];
            $filesignature = ($download ? "Download": "View")." by $viewer at ".date("d/m/Y H:i:s");
            $exportname = $originalfile['title']." - Pathway Speech Contest ($viewer)";
            /* --- PDF generation --- (BEGIN) */
            require_once($dirPWroot."resource/php/lib/tcpdf/tcpdf.php"); require_once($dirPWroot."resource/php/lib/fpdi/fpdi.php");
            $formattedfile = new FPDI($originalfile['orien'], PDF_UNIT, $originalfile['paper'], true, 'UTF-8', false);
            // Configuration
            $formattedfile -> SetProtection(array("modify", "copy", "annot-forms", "fill-forms", "extract", "assemble"), "", null, 0, null);
            $formattedfile -> SetCreator("Pathway Speech Contest - Web Application");
            $formattedfile -> SetAuthor("Pathway Speech Contest - Team");
            $formattedfile -> SetTitle("Giveaway) $exportname");
            $formattedfile -> SetSubject($originalfile['title']);
            $formattedfile -> setPrintHeader(false);
            $formattedfile -> setPrintFooter(false);
            $formattedfile -> SetKeywords($filesignature);
            $formattedfile -> SetAutoPageBreak(false, 0);
            // Edit
            $pages = $formattedfile -> setSourceFile($dirPWroot."e/Pathway-Speech-Contest/resource/file/".$originalfile['fname'].".pdf");
            for ($pageno = 1; $pageno <= $pages; $pageno++) {
                // Get original page
                $temppage = $formattedfile -> importPage($pageno);
                $tempinfo = $formattedfile -> getTemplateSize($temppage);
                $formattedfile -> addPage($tempinfo['h'] > $tempinfo['w'] ? "P" : "L");
                $formattedfile -> useTemplate($temppage);
                // Add signature
                $formattedfile -> SetTextColor(0);
                $formattedfile -> SetFont("thsarabun", "I", 12);
                if ($tempinfo['h'] > $tempinfo['w']) $formattedfile -> SetXY(18, 285);
                else $formattedfile -> SetXY(13, 198);
                $formattedfile -> Cell(0, 0, $filesignature, 0, 1, $originalfile['signs'], 0, "", 0);
            } // Send out file
            $formattedfile -> Output("$exportname.pdf", ($download ? "D": "I"));
            /* --- PDF generation --- (END) */
            slog($viewid, "PathwaySCon", "giveaway", ($download ? "save": "view"), ($viewid=="" ? $viewer.",".$readinfo['sheet'] : ""), "pass", $remote);
            $header_title = "Giveaway";
            $header_desc = $originalfile['title'];
        } else $error = "901";
        $db -> close();
    } else $error = "902";
    if (isset($error)) {
        $header_title = "Giveaway";
        $header_desc = "Error ($error)";
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
    <body>
        <?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/header.php"); ?>
        <main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
            <iframe src="/error/<?=$error?>">Loading...</iframe>
        </main>
        <?php require($dirPWroot."resource/hpe/material.php"); ?>
        <footer>
            <?php require($dirPWroot."e/Pathway-Speech-Contest/resource/hpe/footer.php"); ?>
        </footer>
    </body>
</html>
<?php } ?>