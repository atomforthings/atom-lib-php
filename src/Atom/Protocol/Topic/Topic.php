<?php

namespace Atom\Protocol\Topic;

class Topic extends AbstractTopic {

	private $name;

	function __construct($name) {
		$this->name = $name;
	}

	public function __get($name) {
		return $this->$name;
	}
}
