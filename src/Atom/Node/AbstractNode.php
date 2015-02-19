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

    function __construct($protocol = 'tcp://', $host = '127.0.0.1', $port = '4347', $ssl = false) {
    	$this->status = self::STATUS_NEW;
    	$this->protocol = $protocol;
    	$this->host = $host;
    	$this->port = $port;
    	$this->ssl = $ssl;

    	if($ssl) {
    		$this->protocol = 'ssl://';
    	}

    	$this->address = $this->protocol.$this->host;
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