<?php

$configs = include('config.php');

try {
	$db = new PDO('mysql:host='.$configs['host'].';dbname='.$configs['dbname'].';port=8889', $configs['username'], $configs['password']);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
	echo $e->getMessage();
	die();
};
