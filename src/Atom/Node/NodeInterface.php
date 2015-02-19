<?php

namespace Atom\Node;

interface NodeInterface {
	
	const STATUS_NEW = 0;
    const STATUS_CONNECTING = 1;
    const STATUS_CONNECTED = 2;
    const STATUS_DISCONNECTING = 3;
    const STATUS_DISCONNECTED = 4;

    function __construct($protocol, $host, $port, $ssl);
    public function getStatus();
    public function setStatus($status);

    public function __get($key);
    public function __set($key, $value);

}