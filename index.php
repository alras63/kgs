<?php
$file = file_get_contents(__DIR__.'/top.txt');
$users = unserialize($file);
	header('Content-Type: application/json');
echo json_encode($users);

	
	
	
	
	
	
	
	
	
	
?>