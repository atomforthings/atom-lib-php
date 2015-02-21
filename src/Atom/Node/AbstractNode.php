<?php

namespace Atom\Node;
use Evenement\EventEmitter;

abstract class AbstractNode extends \React\Stream\Stream implements NodeInterface, NodeStatusInterface {

    public $master;
    private $loop;
    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }
    public function listen($port, $host = '127.0.0.1')
    {
        if (strpos($host, ':') !== false) {
            // enclose IPv6 addresses in square brackets before appending port
            $host = '[' . $host . ']';
        }
        $this->master = @stream_socket_server("tcp://$host:$port", $errno, $errstr);
        if (false === $this->master) {
            $message = "Could not bind to tcp://$host:$port: $errstr";
            throw new ConnectionException($message, $errno);
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
    public function handleConnection($socket)
    {
        stream_set_blocking($socket, 0);
        $client = $this->createConnection($socket);
        $this->emit('connection', array($client));
    }
    public function getPort()
    {
        $name = stream_socket_get_name($this->master, false);
        return (int) substr(strrchr($name, ':'), 1);
    }
    public function shutdown()
    {
        $this->loop->removeStream($this->master);
        fclose($this->master);
        $this->removeAllListeners();
    }
    public function createConnection($socket)
    {
        return new Connection($socket, $this->loop);
    }
}

//     private $status;
// 	private $address;
// 	private $protocol;
// 	private $host;
// 	private $port;
// 	private $ssl;
//     private $connection;

//     public $master;
//     private $loop;

//     function __construct($protocol = 'tcp://', $host = '127.0.0.1', $port = '4347', $ssl = false) {
//         $this->status = self::STATUS_NEW;
//         $this->protocol = $protocol;
//         if (strpos($host, ':') !== false) {
//             // enclose IPv6 addresses in square brackets before appending port
//             $this->host = '[' . $host . ']';
//         } else  $this->host = $host;
//         $this->port = $port;
//         $this->ssl = $ssl;
//         // $this->loop = $loop;

//     	if($this->ssl === true) {
//     		$this->protocol = 'ssl://';
//     	}

//         $this->address = $this->protocol.$this->host . ":" . $this->port;
        

//         // if (false === $fd = @stream_socket_client($this->address, $errno, $errstr)) {
//         //     $message = "Could not bind to $this->address: $errstr";
//         //     throw new \Exception($message, $errno);
//         // }
        
//         // $this->connection = new \React\Stream\Stream($fd, $this->loop);

//         // $this->emit('connection.established', array($this));
        
//         // $this->connection->write("hahaha");
        
//         // $this->connection->on('data', function($data) {
//         //     $this->emit('data', array($data, $this));
//         // });

//         // $this->connection->on('drain', function() {
//         //     $this->emit('drain');
//         // });

//         // $this->connection->on('error', function($error) {
//         //     $this->emit('error', array('data' => $data));
//         // });

//         // $this->connection->on('close', function($data) {
//         //     $this->emit('close');
//         // });

//         // $this->connection->on('pipe', function($data) {
//         //     $this->emit('pipe', array('data' => $data));
//         // });
        
//     }

//     public function send(\Atom\Protocol\Frame $frame) {
//         $this->connection->write($frame);
//     }

//     public function __get($key) {
//     	return $this->{$key};
//     }

// 	public function __set($key, $value) {
//     	$this->{$key} = $value;
//     }

//     public function getStatus() {
//     	return $this->status;
//     }

//     public function setStatus($status) {
//     	$this->status = $status;
//     }


// }