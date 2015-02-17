<?php

namespace Atom\Protocol;
use Atom\Protocol\Flag as Flag;

class Command {

	const CONNECT = 0b0000;
	const DISCONNECT = 0b0001;
	const SUBSCRIBE = 0b0010;
	const UNSUBSCRIBE = 0b0011;

	private $command = null;
	private $flag = null;


	function __construct($command = null, $flags = array()) {

		$this->flag = new Flag($flags);
		$this->command = $command;
		
	}

	public function getCommand() {
		return $this->command;
	}

	public function getFlags() {
		return sprintf("%s", $this->flag);
	}

	function __toString() {
		return sprintf("%'04b%s", $this->command, $this->flag);
	}
}