<?php
    if (isset($_GET['url'])) {
        $url = trim($_GET['url']);
        require_once("../php/lib/BarcodeQR.php");
        $QRgen = new BarcodeQR();
        $QRgen -> url("https://$url");
        $QRgen -> draw(325);
    } else {
        $error = 902;
        
        include("../hpe/init_ps.php");
        $header_title = "Error (".strval($error).")";
        echo '<html xmlns="http://www.w3.org/1999/xhtml">
                <head>';
                    include("../hpe/heading.php"); include("../hpe/init_ss.php");
        echo '          <style type="text/css">
                        html body main iframe {
                            width: 100%; height: 100%;
                            border: none;
                        }
                    </style>
                    <script type="text/javascript">
                        
                    </script>
                </head>
                <body>';
                    include("../hpe/header.php");
        echo '      <main shrink="'.(($_COOKIE['sui_open-nt'])??"false").'">
                        <iframe src="/error/'.strval($error).'"></iframe>
                    </main>';
                    include("../hpe/material.php");
        echo '      <footer>';
                        include("../hpe/footer.php");
        echo '      </footer>
                </body>
            </html>';
    }
?>