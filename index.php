<?php

date_default_timezone_set('UTC');

include "vendor/autoload.php";


// class Atom extends Hoa\Socket\Connection\Handler {

//     protected function _run ( Hoa\Socket\Node $node ) {

//     	print_r($node);
//         $connection = $node->getConnection();
//         $line       = $connection->readLine();

//         if(empty($line)) {

//             $connection->disconnect();
//             return;
//         }

//         echo '< ', $line, "\n";
//         $this->send(strtoupper($line));

//         return;
//     }

//     protected function _send ( $message, Hoa\Socket\Node $node ) {

//         return $node->getConnection()->writeLine($message);
//     }
// }

// $atom = new Atom(new Hoa\Socket\Server('tcp://192.168.1.5:4347'));



// $atom->run();
$loop = \React\EventLoop\Factory::create();

$s = new Atom\PubSub\Subscriptions($loop);
$s->addTopic(new \Atom\Protocol\Topic\Topic('sensors/temperature'));
$s->subscribe('sensors/temperature', new \Atom\Node\Node($loop));
$s->publish('1.0', 'sensors/temperature', "10");
$loop->run();
die();

use Atom\Atom;

$atom = new Atom('tcp://', '127.0.0.1', 4347, false, array());

$atom->on('connection', function($node) {
	// var_dump($node);
	echo "New Conneciton: " . $node->getRemoteAddress() . PHP_EOL;
});

$atom->addTopic(new \Atom\Protocol\Topic\Topic('sensors/temperature'));

$atom->publish('1.0', 'sensors/temperature', "10");

$atom->addTopic(new \Atom\Protocol\Topic\Topic('sensors/altitude'));

$atom->publish('0.5', 'sensors/altitude', function() {
	return "100";
});

// $atom->connect('192.168.1.4', 4347);

$atom->run();

// use Atom\Atom;
// use Atom\Node\Node;
// use Atom\Protocol\Topic;

// $topics = new \Atom\Protocol\Topic\TopicContainer();
// $topics->attach(new \Atom\Protocol\Topic\Topic('sensors/temperature'));

// $node = new Node('tcp://', '192.168.1.5', 4347, false);

// $atom = new Atom('tcp://', '192.168.1.5', 4347, false, array());

// $atom->on('connection', function($node) {
// 	echo "New Conneciton: " . $node->getRemoteAddress() . PHP_EOL;
// });

// $atom->on('data', function($node) {
// 	echo "New Data " . $node->getRemoteAddress() . PHP_EOL;
// });



// $atom->run();



// // $loop = \React\EventLoop\Factory::create();

// // $node = new \Atom\Node\Node($loop, 'tcp://', '192.168.1.21', 4347, false);
// // $node->on('data', function($data, $node) {

// // 	$frame = new \Atom\Protocol\Frame(new \Atom\Protocol\Command\Connect);
// // 	$node->send($frame);
// // });


// // $loop->run();
// // die();
// // $topics = new \Atom\Protocol\Topic\TopicContainer();
// // $topics->attach(new \Atom\Protocol\Topic\Topic('sensors/temperature'));
// // $topics->attach(new \Atom\Protocol\Topic\Topic('sensors/humidity'));
// // $topics->attach(new \Atom\Protocol\Topic\Topic('sensors/geolocation'));
// // $topics->attach(new \Atom\Protocol\Topic\Topic('sensors/altitude'));

// // var_dump($topics->exists('sensors/temperature'));
// // print_R($topics->toArray());

// // die();

// // try {
	
// // 	$atom = new \Atom\Atom($node);

// // 	$atom->publish('sensors/temperature', '12');

// // 	$atom->on('connected.established', function ($data) {
		
// // 	});

// // 	$atom->on('error', function ($data) {
// // 		print_r($data);
// // 	});

// // 	$atom->on('initialized', function ($data) {
// // 		echo "initialized";
// // 	});

// // 	$atom->connect();
	
// // } catch (\Exception $e) {
// //     die('Connection failed: ' . $e->getMessage());
// // }
