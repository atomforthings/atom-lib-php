<?php

namespace Atom\Protocol;

use Atom\Protocol\Command as Command;

class Frame {

	private $command;
	private $fixedHeader;
	private $variableHeader;
	private $body;
	
	function __construct(Command $command = null, $body = null, $options = array()) {
		$this->command = $command;
		$this->body = $body;
		$this->validate();
	}

	public function __set($name, $value) {
		
		if(!isset($this->{$name})) {
			throw new \Exception('Invalid Property');
		}

        $this->$name = $value;
    }

    // private function validate() {
    // 	echo $this->command->getCommand() . PHP_EOL;
    // 	switch($this->command->getCommand()) {
    // 		case Command::CONNECT:
    				
    // 			break;
    // 		case Command::DISCONNECT:
    // 			echo "thhere";
    // 			break;
    // 	}
    // }

    private function getFixedHeader() {
    	return $this->command;
    }

    private function getVariableHeader() {
    	$len = strlen($this->body);
    	$result = null;
    		while($len > 0) {
    			$len = $len - 254;
    			if($len <= 0) {
    				$len = $len + 254;
    				$result .= sprintf("%'08b", $len);
    				$len = 0;
    			} else {
    				$result .= sprintf("%'08b", 254);
    			}
    		}
    	
    	return $result;
    }

    private function prepFrame() {
    	return $this->getFixedHeader().$this->getVariableHeader().$this->body;
    }

	public function __toString() {
		return sprintf($this->prepFrame());
	}
		
}