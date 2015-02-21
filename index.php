<?php

date_default_timezone_set('UTC');

include "vendor/autoload.php";


use Atom\Atom;
use Atom\Node\Node;

$node = new Node('tcp://', '192.168.1.2', 4347, false);

$atom = new Atom($node, array());

$atom->on('connection', function($node) {

});

$atom->run();



// $loop = \React\EventLoop\Factory::create();

// $node = new \Atom\Node\Node($loop, 'tcp://', '192.168.1.21', 4347, false);
// $node->on('data', function($data, $node) {

// 	$frame = new \Atom\Protocol\Frame(new \Atom\Protocol\Command\Connect);
// 	$node->send($frame);
// });


// $loop->run();
// die();
// $topics = new \Atom\Protocol\Topic\TopicContainer();
// $topics->attach(new \Atom\Protocol\Topic\Topic('sensors/temperature'));
// $topics->attach(new \Atom\Protocol\Topic\Topic('sensors/humidity'));
// $topics->attach(new \Atom\Protocol\Topic\Topic('sensors/geolocation'));
// $topics->attach(new \Atom\Protocol\Topic\Topic('sensors/altitude'));

// var_dump($topics->exists('sensors/temperature'));
// print_R($topics->toArray());

// die();

// try {
	
// 	$atom = new \Atom\Atom($node);

// 	$atom->publish('sensors/temperature', '12');

// 	$atom->on('connected.established', function ($data) {
		
// 	});

// 	$atom->on('error', function ($data) {
// 		print_r($data);
// 	});

// 	$atom->on('initialized', function ($data) {
// 		echo "initialized";
// 	});

// 	$atom->connect();
	
// } catch (\Exception $e) {
//     die('Connection failed: ' . $e->getMessage());
// }
