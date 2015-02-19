<?php

namespace Atom\Protocol;

use Atom\Protocol\Command as Command;

class Frame {

	private $command;
	private $fixedHeader;
	private $variableHeader;
	private $body;
	
	function __construct(\Atom\Protocol\Command\CommandInterface $command, $options = array()) {
		$this->command = $command;
	}

    public function setFlags(\Atom\Protocol\Flag\FlagCollectionInterface $flags) {

    }
    
    public function setBody($body) {
        $this->body = $body;
    }

	public function __set($name, $value) {
		if(!isset($this->{$name})) {
			throw new \Exception('Invalid Property');
		}

        $this->$name = $value;
    }

    private function isValid() {
    	return true;
    }

    private function getFixedHeader() {
    	return $this->command . '0000';

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
    	return bindec($this->getFixedHeader().$this->getVariableHeader()).$this->body;
    }

	public function __toString() {
		return sprintf($this->prepFrame());
	}
		
}