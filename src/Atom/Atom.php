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
		$this->node->loop = $this->loop;

		$this->setupEvents();
	}

	private function setupEvents() {
		$this->node->on('connection', function($data){
			$this->emit('connection', array($data));
		});
	}

	public function run() {
		$this->node->listen();
        $this->loop->run();
    }

}