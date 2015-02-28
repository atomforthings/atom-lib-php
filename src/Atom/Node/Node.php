<?php

namespace Atom\Node;

use Evenement\EventEmitter;

class Node extends EventEmitter implements NodeInterface {

    const STATUS_NEW = 0x01;

	private $_connection;
    
    public function __construct($loop) {
    	$this->status = self::STATUS_NEW;
    	$this->loop = $loop;

    	$_this = $this;
    	$this->loop->addTimer('5.0', function() use ($_this){
    		if($_this->getStatus() == constant("self::STATUS_NEW")) {
    			$_this->emit('timedout', array($_this));
    			echo "timedout";
    		}
    	});
    }

    public function setConnection( $connection ) {
    	$this->_connection = $connection;
    }

	public function setLoop( $loop ) {
    	$this->loop = $loop;
    }

    public function getRemoteAddress() {
    	return $this->_connection->getRemoteAddress();
    }

    public function getStatus() {
    	return $this->status;
    }
}

// class Node extends EventEmitter implements NodeInterface {
    
//     private $protocol;
//     private $host;
//     private $port;
//     private $ssl;
//     private $address;
//     public $stream;
//     private $loop;
//     private $stream;

//     public function __construct($protocol = 'tcp://', $host = '127.0.0.1', $port = 4347, $ssl = false) {
//         $this->protocol = $protocol;
//         $this->host = $host;
//         $this->port = $port;
//         $this->ssl = $ssl;

//         if($this->ssl === true) {
//             $this->protocol = 'ssl://';
//         }

//         $this->address = $this->protocol.$this->host . ":" . $this->port;
//     }

//     public function listen() {
//         if (strpos($this->host, ':') !== false) {
//             // enclose IPv6 addresses in square brackets before appending port
//             $this->host = '[' . $this->host . ']';
//         }

//         $this->stream = @stream_socket_server("$this->protocol$this->host:$this->port", $errno, $errstr);
//         if (false === $this->stream) {
//             $message = "Could not bind to tcp://$host:$port: $errstr";
//             throw new \Exception($message, $errno);
//         }
        
//         stream_set_blocking($this->stream, 0);

//         $this->loop->addReadStream($this->stream, function ($stream) {
//             $newSocket = stream_socket_accept($stream);
//             if (false === $newSocket) {
//                 $this->emit('error', array(new \RuntimeException('Error accepting new connection')));

//                 return;
//             }
//             $this->handleConnection($newSocket);
//         });
//     }

//     public function handleConnection($socket) {
//         stream_set_blocking($socket, 0);

//         $client = $this->createConnection($socket);

//         $this->emit('connection', array($client));
//     }

//     public function getPort() {
//         $name = stream_socket_get_name($this->stream, false);

//         return (int) substr(strrchr($name, ':'), 1);
//     }

//     public function shutdown() {
//         $this->loop->removeStream($this->stream);
//         fclose($this->stream);
//         $this->removeAllListeners();
//     }

//     public function createConnection($socket) {
//         return new Connection($socket, $this->loop);
//     }


//     public function __get($key) {
//         return $this->{$key};
//     }

//     public function __set($key, $value) {
//         $this->{$key} = $value;
//     }

// }


// // use Evenement\EventEmitter;
// // use React\EventLoop\LoopInterface;

// // /** @event connection */
// // class Node extends EventEmitter implements NodeInterface
// // {
// //     public $master;
// //     private $loop;

// //     public function __construct($protocol = 'tcp://', $host = '127.0.0.1', $port = 4347, $ssl = false) {
// //         $this->protocol = $protocol;
// //         $this->host = $host;
// //         $this->port = $port;
// //         $this->ssl = $ssl;

// //         if($this->ssl === true) {
// //             $this->protocol = 'ssl://';
// //         }

// //         $this->address = $this->protocol.$this->host . ":" . $this->port;
// //     }

// //     public function listen() {
// //         if (strpos($this->host, ':') !== false) {
// //             // enclose IPv6 addresses in square brackets before appending port
// //             $this->host = '[' . $this->host . ']';
// //         }

// //         $this->master = @stream_socket_server("$this->protocol$this->host:$this->port", $errno, $errstr);
// //         if (false === $this->master) {
// //             $message = "Could not bind to tcp://$host:$port: $errstr";
// //             throw new ConnectionException($message, $errno);
// //         }
// //         stream_set_blocking($this->master, 0);

// //         $this->loop->addReadStream($this->master, function ($master) {
// //             $newSocket = stream_socket_accept($master);
// //             if (false === $newSocket) {
// //                 $this->emit('error', array(new \RuntimeException('Error accepting new connection')));

// //                 return;
// //             }
// //             $this->handleConnection($newSocket);
// //         });
// //     }

// //     public function handleConnection($socket) {
// //         stream_set_blocking($socket, 0);

// //         $client = $this->createConnection($socket);

// //         $this->emit('connection', array($client));
// //     }

// //     public function getPort() {
// //         $name = stream_socket_get_name($this->master, false);

// //         return (int) substr(strrchr($name, ':'), 1);
// //     }

// //     public function shutdown() {
// //         $this->loop->removeStream($this->master);
// //         fclose($this->master);
// //         $this->removeAllListeners();
// //     }

// //     public function createConnection($socket) {
// //         return new Connection($socket, $this->loop);
// //     }


// //     public function __get($key) {
// //         return $this->{$key};
// //     }

// //     public function __set($key, $value) {
// //         $this->{$key} = $value;
// //     }
// // }
