<?php

namespace Atom\Node;

use Evenement\EventEmitterInterface;

/** @event connection */
interface NodeInterface extends EventEmitterInterface
{
    public function listen();
    public function getPort();
    public function shutdown();
}
