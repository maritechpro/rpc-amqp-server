<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 15.04.20
 */
declare(strict_types=1);

namespace GepurIt\RpcApiBundle\Exception;

/**
 * Class InvalidAmqpMessageException
 * @package GepurIt\RpcApiBundle\Exception
 */
class InvalidAmqpMessageException extends RpcException
{
    public function __construct()
    {
        parent::__construct("RPC message should contain message_id and reply_to", 0, null);
    }
}
