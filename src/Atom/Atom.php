<?php

namespace Atom;

use Atom\Protocol\Frame as Frame;
use Atom\Protocol\Command as Command;
use Atom\Protocol\Flag as Flag;

use Evenement\EventEmitter;

class Atom extends EventEmitter {

	private $creds = array();
	private $loop = null;

	function __construct($host = '127.0.0.1', $port = '4347', $ssl = false) {
		$this->loop = \React\EventLoop\Factory::create();
		$this->creds['host'] = $host;
		$this->creds['port'] = $port;
		$this->creds['ssl'] = $ssl;

		$this->emit('initialized', array());
	}

	public function connect() {

		$self = $this;
		
		$dnsResolverFactory = new \React\Dns\Resolver\Factory();
		$dns = $dnsResolverFactory->createCached('8.8.8.8', $this->loop);

		$connector = new \React\SocketClient\Connector($this->loop, $dns);

		$connector->create($this->creds['host'], $this->creds['port'])->then(function (\React\Stream\Stream $stream) use ($self) {

			
			$self->emit('connected.established', array($stream));

			/* connection established, send connect frame */
			$connectCommand = new \Atom\Protocol\Command\Connect;
			$data = "";

			$frame = new \Atom\Protocol\Frame($connectCommand);
			$frame->setBody($data);
			echo $frame;

			$stream->write($frame);

			$stream->on('data', function($data) use ($stream) {
				echo $data;
			});

			$stream->on('error', function($data) use ($stream) {
				echo "Error Data Received: " . var_dump($data);
			});
			
			$stream->on('end', function($data) use ($stream) {
				echo "end";
			});
			
			$stream->on('close', function($data) use ($stream) {
				echo "close";
			});
			
		}, function($e) use ($self) {

			$self->emit('error', array('message' => $e->getMessage()));
			// throw new \Exception($e->getMessage(), $e->getCode());

		}, function($e) {

			echo $e->getMessage() . PHP_EOL;
			throw new \Exception($e);

		});

		$this->loop->run();

	}

}