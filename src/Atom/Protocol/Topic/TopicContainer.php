<?php

namespace Atom\Protocol\Topic;

class TopicContainer {

	private $topics;

	function __construct($loop) {
		$this->loop = $loop;
	}
	
	public function attach(\Atom\Protocol\Topic\TopicInterface $topic) {
		if(!isset($this->topics[$topic->name])) {
			$this->topics[$topic->name] = $topic;
		} else {
			throw new \Exception("Topic Already Exists");
		}
	}

	public function exists($topic = null) {
		return isset($this->topics[$topic]);
	}

	public function toArray() {
		return $this->topics;
	}

	public function publish($time, $topic, $data) {
		$this->loop->addPeriodicTimer($time, function() use($topic, $data) {
			echo $topic . " : " . $data . PHP_EOL;
		});

	}

	public function __get($topic = null) {
		if(isset($this->topics[$topic])) {
			return $this->topics[$topic];
		}

		throw new \Exception("Topic Not Found");
	}
	
}