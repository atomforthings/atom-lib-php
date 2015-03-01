<?php

namespace Atom;

use Evenement\EventEmitter;
use Atom\Node\Connection;

class Atom extends EventEmitter {

    private $loop;
    private $peers;

    private $topics;

    function __construct($protocol = 'tcp://', $host = '127.0.0.1', $port = 4347, $ssl = false, array $options = array()) {
        $this->loop = \React\EventLoop\Factory::create();
        $this->protocol = $protocol;
        $this->host = $host;
        $this->port = $port;
        $this->ssl = $ssl;

        if($this->ssl === true) {
            $this->protocol = 'ssl://';
        }

        $this->address = $this->protocol.$this->host . ":" . $this->port;

        $this->peers = new \Atom\Node\Collection();
        $this->topics = new \Atom\Protocol\Topic\TopicContainer($this->loop);

    }

    public function listen() {
        if (strpos($this->host, ':') !== false) {
            // enclose IPv6 addresses in square brackets before appending port
            $this->host = '[' . $this->host . ']';
        }

        $this->stream = @stream_socket_server("$this->protocol$this->host:$this->port", $errno, $errstr);
        if (false === $this->stream) {
            $message = "Could not bind to tcp://$host:$port: $errstr";
            throw new \Exception($message, $errno);
        }
        
        stream_set_blocking($this->stream, 0);

        $this->loop->addReadStream($this->stream, function ($stream) {
            $newSocket = stream_socket_accept($stream);
            if (false === $newSocket) {
                $this->emit('error', array(new \RuntimeException('Error accepting new connection')));

                return;
            }
            $this->handleConnection($newSocket);
        });
    }

    public function handleConnection($socket) {
        stream_set_blocking($socket, 0);

        $node = $this->createConnection($socket);

        $this->peers->attach($node, $this->peers->getHash($node));

        $this->emit('connection', array($node));
    }

    public function getPort() {
        $name = stream_socket_get_name($this->stream, false);

        return (int) substr(strrchr($name, ':'), 1);
    }

    public function shutdown() {
        $this->loop->removeStream($this->stream);
        fclose($this->stream);
        $this->removeAllListeners();
    }

    public function createConnection($socket) {
        $node = new \Atom\Node\Node($this->loop);
        $node->setConnection(new Connection($socket, $this->loop));
        // $node->setLoop($this->loop);
        return $node;
    }

    // private function setupEvents() {

    //  // $_this = $this;

    //  // $this->node->on('connection', function($node) use($_this) {
    //  //  $_this->emit('connection', array($node));

    //  //  $node->on('data', function($data) use ($node, $_this) {
    //  //      $_this->emit('data', array($data, $node));
    //  //      $node->write($data);
    //  //  });
    //  // });
        
    // }

    public function run() {
        $this->listen();
        $this->loop->run();
    }


    public function publish($time, $topic, $data) {
        $this->topics->publish($time, $topic, $data);
    }

    public function addTopic(\Atom\Protocol\Topic\TopicInterface $topic) {
        $this->topics->attach($topic);
    }

    public function connect($address, $port) {

        $dnsResolverFactory = new \React\Dns\Resolver\Factory();
        $dns = $dnsResolverFactory->createCached('8.8.8.8', $this->loop);
        $connector = new \React\SocketClient\Connector($this->loop, $dns);

        $connector->create($address, $port)->then(function (\React\Stream\Stream $stream) {
            $stream->write('...');
            echo "ASDASD";
            $node = new Node();
            $node->setConnection = close();
            // $stream->$node;
        });
    }

}