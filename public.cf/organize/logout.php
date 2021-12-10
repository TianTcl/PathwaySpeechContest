<?php
    session_start();
    $dirPWroot = str_repeat("../", substr_count($_SERVER['PHP_SELF'], "/")-1);
    $user = $_SESSION['evt2']['user'] ?? "";
    if (file_get_contents("https://inf.bodin.ac.th/e/Pathway-Speech-Contest/resource/php/admin-remote?user=$user&do=logout", false)) {
        if (isset($_SESSION['evt2'])) unset($_SESSION['evt2']);
    } header("Location: ./");
?>