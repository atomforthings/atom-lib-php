<?php

namespace Atom;

use Atom\Protocol\Frame as Frame;
use Atom\Protocol\Command as Command;
use Atom\Protocol\Flag as Flag;

class Atom {

	function __construct() {

	}

	public function connect() {


		$loop = \React\EventLoop\Factory::create();

		$dnsResolverFactory = new \React\Dns\Resolver\Factory();
		$dns = $dnsResolverFactory->createCached('8.8.8.8', $loop);

		$connector = new \React\SocketClient\Connector($loop, $dns);

		$connector->create('192.168.1.31', 4347)->then(function (\React\Stream\Stream $stream) {
		    
			$stream->on('data', function($data) use ($stream) {
				echo "Data Received: " . var_dump($data);
			});
			
			$frame = 0b00100000 . 3 . 'asd';
			$stream->write($frame);
			
			$stream->on('error', function($data) use ($stream) {
				echo "Error Data Received: " . var_dump($data);
			});
			
		    // $stream->end();

		}, function($e){
			echo $e->getMessage() . PHP_EOL;
		}, function($e){
			print_r($e);
		});

		$loop->run();

	}
}