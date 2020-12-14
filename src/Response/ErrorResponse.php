<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 16.04.20
 */
declare(strict_types=1);

namespace GepurIt\RpcApiBundle\Response;

/**
 * Class ErrorResponse
 * @package GepurIt\RpcApiBundle\Response
 */
class ErrorResponse extends RpcResponse
{
    public function __construct(string $error)
    {
        $payload = ['error' => $error];
        parent::__construct($payload, self::STATUS__FAILED);
    }
}
