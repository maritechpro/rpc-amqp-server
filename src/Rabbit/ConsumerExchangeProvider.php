<?php
/**
 * @author: Marina Mileva m934222258@gmail.com
 * @since : 28.09.2020
 */
declare(strict_types=1);

namespace GepurIt\RpcApiBundle\Rabbit;

use AMQPExchange;
use AMQPQueue;
use AMQPChannelException;
use AMQPConnectionException;
use AMQPExchangeException;
use AMQPQueueException;
use GepurIt\RabbitMqBundle\RabbitInterface;

/**
 * Class ConsumerExchangeProvider
 * @package GepurIt\RpcApiBundle\Rabbit
 */
class ConsumerExchangeProvider implements ConsumerExchangeProviderInterface
{
    private RabbitInterface $rabbit;
    private string $queue;

    /** @var array | AMQPExchange[] */
    private array $replyToExchanges = [];
    private ?AMQPQueue $consumingQueue = null;
    /** @var array | AMQPQueue[] */
    private array $replyToQueues = [];

    /**
     * ExchangeProvider constructor.
     *
     * @param RabbitInterface $rabbit
     * @param string          $queue
     */
    public function __construct(RabbitInterface $rabbit, string $queue)
    {
        $this->rabbit = $rabbit;
        $this->queue = $queue;
    }

    /**
     * @param string $replyTo
     *
     * @return AMQPExchange
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws AMQPExchangeException
     * @throws AMQPQueueException
     */
    public function getReplyToExchange(string $replyTo): AMQPExchange
    {
        if (isset($this->replyToExchanges[$replyTo]) && (null !== $this->replyToExchanges[$replyTo])) {
            return $this->replyToExchanges[$replyTo];
        }

        $channel = $this->rabbit->getChannel();
        $exchange = new AMQPExchange($channel);
        $exchange->setName($replyTo);
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();

        $queue = new AMQPQueue($channel);
        $queue->setName($replyTo);
        $queue->setFlags(AMQP_DURABLE);
        $queue->setArgument('x-message-ttl', 60000);
        $queue->declareQueue();
        $queue->bind($replyTo, $replyTo);

        $this->replyToExchanges[$replyTo] = $exchange;
        $this->replyToQueues[$replyTo] = $queue;

        return $exchange;
    }

    /**
     * @return AMQPQueue
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws AMQPExchangeException
     * @throws AMQPQueueException
     */
    public function getConsumingQueue(): AMQPQueue
    {
        if ($this->consumingQueue !== null) {
            return $this->consumingQueue;
        }

        $channel = $this->rabbit->getChannel();
        $exchange = new AMQPExchange($channel);
        $exchange->setName($this->queue);
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();

        $this->consumingQueue = new \AMQPQueue($channel);
        $this->consumingQueue->setName($this->queue);
        $this->consumingQueue->setFlags(AMQP_DURABLE);
        $this->consumingQueue->declareQueue();

        $this->consumingQueue->bind($this->queue, $this->queue);


        return $this->consumingQueue;
    }

    /**
     * @param callable $callback
     *
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws AMQPExchangeException
     * @throws AMQPQueueException
     * @throws \AMQPEnvelopeException
     */
    public function consume(callable $callback)
    {
        $this->getConsumingQueue()->consume($callback);
    }
}