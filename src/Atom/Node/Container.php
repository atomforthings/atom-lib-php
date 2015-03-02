<?php

namespace Atom\Node;

use Evenement\EventEmitter;

class Container extends EventEmitter {

	private $nodes;

	function __construct($loop) {
		$this->loop = $loop;
	}
	
	public function attach(\Atom\Node\NodeInterface $node) {
		if(!isset($this->nodes[$node->getId()])) {
			$this->nodes[$node->getId()] = $node;
		} else {
			throw new \Exception("Node Already Exists");
		}

		$that = $this;
		$node->on('data', function($data, $node) use($that) {
			// echo $topic->name . " : " . $data . PHP_EOL;
			$that->emit('data', array($data, $node));
		});
	}

	public function exists($id = null) {
		return isset($this->nodes[$id]);
	}

	public function toArray() {
		return $this->nodes;
	}

	public function send($data, $id = null) {
		// print_r($this->nodes);
		$this->nodes[$id]->write($data);

	}
	
}