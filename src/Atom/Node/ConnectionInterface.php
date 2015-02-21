<?php

namespace Atom\Node;

use Evenement\EventEmitterInterface;
use React\Stream\ReadableStreamInterface;
use React\Stream\WritableStreamInterface;

interface ConnectionInterface extends ReadableStreamInterface, WritableStreamInterface
{
    public function getRemoteAddress();
}
