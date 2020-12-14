<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 11.12.18
 */

namespace GepurIt\RpcApiBundle\Request;

use GepurIt\RpcApiBundle\Exception\InvalidAmqpMessageException;
use AMQPEnvelope;

/**
 * Class IncomingRequest
 * @package GepurIt\RpcApiBundle\Request
 */
class IncomingRequest
{
    /** @var string */
    public string $actionName = '';

    /** @var mixed */
    public $payload;

    /** @var string */
    public ?string $correlationId = null;

    /** @var string */
    public ?string $replyTo = null;

    /**
     * IncomingRequest constructor.
     *
     * @param $correlationId
     * @param $route
     * @param $actionName
     * @param $payload
     */
    public function __construct($correlationId, $route, $actionName, $payload)
    {
        $this->correlationId = $correlationId;
        $this->replyTo   = $route;
        $this->payload   = $payload;
        $this->actionName = $actionName;
    }

    /**
     * @param AMQPEnvelope $envelope
     *
     * @return IncomingRequest
     */
    public static function fromAMQPEnvelope(AMQPEnvelope $envelope): IncomingRequest
    {
        if (empty($envelope->getMessageId()) || empty($envelope->getReplyTo())) {
            throw new InvalidAmqpMessageException();
        }

        $body = json_decode($envelope->getBody());

        return new self($envelope->getMessageId(), $envelope->getReplyTo(), ($body->action) ?? '', ($body->payload) ?? null);
    }
}
