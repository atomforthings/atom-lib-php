<?php

namespace Atom\Protocol;

class Flag {

	const FLAG_ACK = 0b1000;

	protected $flags = null;

	function __construct($flags = array()) {

		$this->flags = $flags;

	}

	private function prepFlags() {
		return self::FLAG_ACK;
	}

	static function isValid($flag) {
		if(!defined("self::$flag")) {
			return false;
		}

		return true;
	}

	function __toString() {
		return sprintf("%'04b", $this->prepFlags());
	}
}