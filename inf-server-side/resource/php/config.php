<?php
    if (!isset($_SESSION)) session_start();
    /* if (!isset($_SESSION['event'])) */ $_SESSION['event'] = array(
        "round" => "1", "nameSub" => "New Year's Day"
    );
?>