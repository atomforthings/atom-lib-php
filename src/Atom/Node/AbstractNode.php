<?php

namespace Atom\Node;
use Evenement\EventEmitter;

abstract class AbstractNode extends EventEmitter implements NodeInterface, NodeStatusInterface {

    private $status;
	private $address;
	private $protocol;
	private $host;
	private $port;
	private $ssl;
    private $connection;

    function __construct(\React\EventLoop\LoopInterface $loop, $protocol = 'tcp://', $host = '127.0.0.1', $port = '4347', $ssl = false) {
        $this->status = self::STATUS_NEW;
        $this->protocol = $protocol;
        $this->host = $host;
        $this->port = $port;
        $this->ssl = $ssl;
        $this->loop = $loop;

    	if($this->ssl === true) {
    		$this->protocol = 'ssl://';
    	}

        $this->address = $this->protocol.$this->host . ":" . $this->port;
        

        if (false === $fd = @stream_socket_client($this->address, $errno, $errstr)) {
            $message = "Could not bind to $this->address: $errstr";
            throw new \Exception($message, $errno);
        }
        
        $this->connection = new \React\Stream\Stream($fd, $this->loop);

        $this->emit('connection.established', array("Asd"));
        try {
            $this->connection->write("hahaha");
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
    }

    public function __get($key) {
    	return $this->{$key};
    }

	public function __set($key, $value) {
    	$this->{$key} = $value;
    }

    public function getStatus() {
    	return $this->status;
    }

    public function setStatus($status) {
    	$this->status = $status;
    }


}