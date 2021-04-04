<?php
$sgf = file_get_contents($_GET['href']);
header('Access-Control-Allow-Origin: *');

echo $sgf;
?>