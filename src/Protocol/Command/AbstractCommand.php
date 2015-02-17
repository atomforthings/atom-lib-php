<?php

namespace Atom\Protocol\Command;

abstract class AbstractCommand implements CommandInterface {


	function __construct() {
		;
	}

	function __toString() {
		return sprintf("%'04b", self::VALUE);
	}

	function toDecimal() {
		return sprintf("%02u", self::VALUE);
	}

	function toHex() {
		return sprintf("%02X", self::VALUE);
	}

	function toBinary() {
		return sprintf("%'04b", self::VALUE);
	}

	function setFlags(\Atom\Protocol\Flag\FlagCollectionInterface $flags) {

	}

}