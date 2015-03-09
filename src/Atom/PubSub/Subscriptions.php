<?php

namespace Atom\PubSub;

use Evenement\EventEmitter;

class Subscriptions extends EventEmitter {

	private $map;
	private $topics;
	private $peers;

	public function __construct($loop) {

		$this->loop = $loop;
		$this->map = array();
		$this->peers = new \Atom\Node\Container($this->loop);
        $this->topics = new \Atom\Protocol\Topic\Container($this->loop);
        
	}

	public function addTopic(\Atom\Protocol\Topic\TopicInterface $topic) {
		$this->topics->attach($topic);

        $that = $this;

        $topic->on('published', function($data, $topic) use($that) {
            if(isset($that->map[$topic->name])) {
                foreach($that->map[$topic->name] as $id) {
                    $this->peers->send($data, $id);
                }
                echo $topic->name . " : " . $data . PHP_EOL;
            }
        });
	}

	public function subscribe($topic_id, $node_id) {
		
		if(!isset($this->map[$topic_id])) {
			$this->map[$topic_id] = array();
		}

		array_push($this->map[$topic_id], $node_id);

	}

	public function unsubscribe($topic_id, $node_id) {

		if(isset($this->map[$topic_id])) {
			
			$keys = array_keys($this->subscriptions[$topic_id], $node->getId());
            foreach($keys as $key) {
            	unset($this->subscriptions[$topic_id][$key]);
            }

            return true;

		}

	}


	public function publish($time, $topic, $data) {
        
        if(is_callable($data)) {
            $data =  call_user_func($data);
        }

        $this->topics->publish($time, $topic, $data);
    }

	public function broadcast($topic_id) {
		
		foreach($this->map[$topic_id] as $row) {
				
		}

	}

}