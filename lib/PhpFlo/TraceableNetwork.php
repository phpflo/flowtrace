<?php
/*
 * This file is part of the phpflo/phpflo-flowtrace package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpFlo;

use PhpFlo\Common\AbstractNetworkAdapter;
use PhpFlo\Common\HookableNetworkInterface;
use PhpFlo\Common\Network;
use PhpFlo\Common\NetworkAdapterinterface;
use PhpFlo\Common\NetworkInterface;
use PhpFlo\Exception\FlowException;
use PhpFlo\Exception\InvalidDefinitionException;
use PhpFlo\Exception\InvalidTypeException;
use Psr\Log\LoggerInterface;

/**
 * Network for tracing events.
 *
 * @package PhpFlo
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class TraceableNetwork extends AbstractNetworkAdapter implements NetworkAdapterinterface
{
    private $logger;

    public function __construct(NetworkInterface $network, LoggerInterface $logger)
    {
        parent::__construct($network);

        $this->logger = $logger;
        $this->network->hook('data', 'flowtrace', $this->trace());
    }

    /**
     * Wrap the creation of the callback
     *
     * @return \Closure
     */
    private function trace()
    {
        /*
if src.process
    return "#{src.process} #{src.port.toUpperCase()} -> #{tgt.port.toUpperCase()} #{tgt.node}"
  else
    return "-> #{tgt.port.toUpperCase()} #{tgt.node}"
         */

        $trace = function ($data, $socket) {
            serialize($data);

            $this->logger->debug();
        };

        return $trace->bindTo($this);
    }
}
