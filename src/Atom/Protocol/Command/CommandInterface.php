<?php

namespace Atom\Protocol\Command;

interface CommandInterface {

	function __toString();

	function toDecimal();
	function toHex();
	function toBinary();

	function setFlags(\Atom\Protocol\Flag\FlagCollectionInterface $flags);

}