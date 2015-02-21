<?php

namespace Atom;

use Atom\Protocol\Frame as Frame;
use Atom\Protocol\Command as Command;
use Atom\Protocol\Flag as Flag;

use Evenement\EventEmitter;

class Atom extends EventEmitter {

	private $loop = null;

	function __construct(\Atom\Node\NodeInterface $node, array $options = array()) {
		$this->loop = \React\EventLoop\Factory::create();
		$this->node = $node;
		$this->listen();
	}

	public function listen() {

        $this->master = @stream_socket_server($this->node->address, $errno, $errstr);
        if (false === $this->master) {
            $message = "Could not bind to $this->node->address: $errstr";
            throw new \Exception($message, $errno);
        }
        stream_set_blocking($this->master, 0);
        $this->loop->addReadStream($this->master, function ($master) {
            $newSocket = stream_socket_accept($master);
            if (false === $newSocket) {
                $this->emit('error', array(new \RuntimeException('Error accepting new connection')));
                return;
            }
            $this->handleConnection($newSocket);
        });

	}

	public function connect() {

		$self = $this;
		
		// $dnsResolverFactory = new \React\Dns\Resolver\Factory();
		// $dns = $dnsResolverFactory->createCached('8.8.8.8', $this->loop);

		// $connector = new \React\SocketClient\Connector($this->loop, $dns);

		// $connector->create($this->node->host, $this->node->port)->then(function (\React\Stream\Stream $stream) use ($self) {
			
		// 	$self->emit('connected.established', array($stream));

		// 	/* connection established, send connect frame */
		// 	$connectCommand = new \Atom\Protocol\Command\Connect;
		// 	$data = "";

		// 	$frame = new \Atom\Protocol\Frame($connectCommand);
		// 	$frame->setBody($data);
		// 	echo $frame;

		// 	$stream->write($frame);

		// 	$stream->on('data', function($data) use ($stream) {
		// 		echo $data;
		// 	});

		// 	$stream->on('error', function($data) use ($stream) {
		// 		echo "Error Data Received: " . var_dump($data);
		// 	});
			
		// 	$stream->on('end', function($data) use ($stream) {
		// 		echo "end";
		// 	});
			
		// 	$stream->on('close', function($data) use ($stream) {
		// 		echo "close";
		// 	});
			
		// }, function($e) use ($self) {

		// 	$self->emit('error', array('message' => $e->getMessage()));
		// 	// throw new \Exception($e->getMessage(), $e->getCode());

		// }, function($e) {

		// 	echo $e->getMessage() . PHP_EOL;
		// 	throw new \Exception($e);

		// });

		// $this->loop->run();

	}

	// public function start() {

	// 	if (false === $fd = @stream_socket_server($this->node->address, $errno, $errstr)) {
 //            $message = "Could not bind to $this->address: $errstr";
 //            throw new \Exception($message, $errno);
 //        }
        
 //        stream_set_blocking($this->node->connection, 0);
 //        $this->node->connection = new \React\Stream\Stream($fd, $this->loop);

	// 	$this->loop->run();
	// }

	public function run() {
        $this->loop->run();
    }

    public function handleConnection($socket) {
        stream_set_blocking($socket, 0);
        $client = $this->createConnection($socket);
        var_dump($client);
        $this->emit('connection', array($client));
    }

    public function getPort() {
        $name = stream_socket_get_name($this->master, false);
        return (int) substr(strrchr($name, ':'), 1);
    }

    public function shutdown() {
        $this->loop->removeStream($this->master);
        fclose($this->master);
        $this->removeAllListeners();
    }

    public function createConnection($socket) {
        return $this->node->connection = new \React\Stream\Stream($socket, $this->loop);
    }

}