<?php
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    require($dirPWroot."resource/hpe/init_ps.php");
    $header_title = "Giveaway";

    if (isset($_GET['sheet']) && !empty(trim($_GET['sheet']))) {
        require_once($dirPWroot."resource/php/lib/TianTcl.php");
        if (preg_match('/^[0-9a-f]{24}$/', $_GET['sheet'])) {
            $viewid = $tcl -> decode(trim($_GET['sheet']), 2);
            if ($viewid == "") $error = "901";
        } else $error = "901";
    } else $error = "902";
    if (isset($error)) $header_desc = "Error ($error)";
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php require($dirPWroot."resource/hpe/heading.php"); require($dirPWroot."resource/hpe/init_ss.php"); ?>
        <style type="text/css">
            main div.container h3 { display: flex; justify-content: space-between; align-items: center; }
            main div.container h3 a[role="button"] { font-size: 17.5px; }
            main div.container h3 a[role="button"] i.material-icons { transform: translateY(5px); }
            main div.container iframe {
				width: 100%; height: 540px;
				border: 1px solid var(--clr-bs-dark); border-radius: 5px;
			}
        </style>
        <script type="text/javascript">
            
        </script>
    </head>
    <body>
        <?php require($dirPWroot."resource/hpe/header.php"); ?>
        <main shrink="<?php echo($_COOKIE['sui_open-nt'])??"false"; ?>">
            <?php if (isset($error)) echo '<iframe src="/error/'.$error.'">Loading...</iframe>'; else { ?>
            <div class="container">
                <h2>Giveaway</h2>
                <h3>
                    <span>File preview</span>
                    <a role="button" class="green" target="_blank" href="https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/file/giveaway?sheet=<?=$_GET['sheet']?>&attr[remote]=true&download"><i class="material-icons">download</i> Download</a>
                </h3>
                <iframe src="https://docs.google.com/viewerng/viewer?embedded=true&url=https%3A%2F%2Finf.bodin.ac.th%2Fe%2FPathway-Speech-Contest%2Fresource%2Ffile%2Fgiveaway%3Fsheet%3D<?=$_GET['sheet']?>%26attr%5Bremote%5D%3Dtrue">Loading...</iframe>
            </div>
            <?php } ?>
        </main>
        <?php require($dirPWroot."resource/hpe/material.php"); ?>
        <footer>
            <?php require($dirPWroot."resource/hpe/footer.php"); ?>
        </footer>
    </body>
</html>