<?php

session_start();

session_unset();
session_destroy();

header("Location: /ujian_asts/login.php");
exit;

?>
