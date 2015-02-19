<?php

namespace Atom\Protocol\Topic;

abstract class AbstractTopic implements TopicInterface {
	
	private $name;

	abstract public function __get($name);
}