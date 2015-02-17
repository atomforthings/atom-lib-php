<?php

date_default_timezone_set('UTC');

include "vendor/autoload.php";

try {
	
	$atom = new \Atom\Atom('127.0.0.1', 4347, false);
	
	$atom->on('connected.established', function ($data) {
		
	});

	$atom->on('error', function ($data) {
		print_r($data);
	});

	$atom->on('initialized', function ($data) {
		echo "initialized";
	});

	$atom->connect();
	
} catch (\Exception $e) {
    die('Connection failed: ' . $e->getMessage());
}
