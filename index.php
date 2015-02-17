<?php

date_default_timezone_set('UTC');

include "vendor/autoload.php";

try {
	
	$atom = new \Atom\Atom('127.0.0.1', 4347, false);
	
	$atom->on('connected.established', function ($data) {
		$command = new \Atom\Protocol\Command\Connect();
		$c = new \Atom\Protocol\Flag\FlagCollection(array(new \Atom\Protocol\Flag\Confirm));
		// $atom->send();
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
