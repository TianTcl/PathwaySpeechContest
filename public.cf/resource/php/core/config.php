<?php
	if (!isset($_SESSION)) session_start();
    /* if (!isset($_SESSION['event'])) */ $_SESSION['event'] = array(
        "round" => "1", "nameSub" => "New Year's Day",
        "criteria" => array(
            10 => 35, 11 => 15, 12 => 10, 13 => 10,
            20 => 35, 21 => 5, 22 => 10, 23 => 10, 24 => 10,
            30 => 20, 31 => 10, 32 => 10,
            40 => 10, 41 => 10
        )
    );
?>