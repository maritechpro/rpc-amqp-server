<?php
/**
 * @author: Marina Mileva m934222258@gmail.com
 * @since : 28.09.2020
 */
declare(strict_types=1);

namespace GepurIt\RpcApiBundle\Rabbit;

use AMQPExchange;
use AMQPQueue;

/**
 * Interface ConsumerExchangeProviderInterface
 * @package GepurIt\RpcApiBundle\Rabbit
 */
interface ConsumerExchangeProviderInterface
{
    /**
     * @param string $replyTo
     *
     * @return AMQPExchange
     */
    public function getReplyToExchange(string $replyTo): AMQPExchange;

    /**
     * @return AMQPQueue
     */
    public function getConsumingQueue(): AMQPQueue;

    /**
     * @param callable $callback
     *
     * @return mixed
     */
    public function consume(callable $callback);
}