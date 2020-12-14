<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 16.04.20
 */
declare(strict_types=1);

namespace GepurIt\RpcApiBundle\Exception;

/**
 * Class ActionNotFoundException
 * @package GepurIt\RpcApiBundle\Exception
 */
class ActionNotFoundException extends RpcException
{
    /**
     * ActionNotFoundException constructor.
     *
     * @param $actionName
     */
    public function __construct(string $actionName)
    {
        $message = "action {$actionName} not registered";
        parent::__construct($message, 0, null);
    }
}
