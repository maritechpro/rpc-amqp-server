<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 11.12.18
 */

namespace GepurIt\RpcApiBundle\Response;

/**
 * Class RpcResponse
 * @package GepurIt\RpcApiBundle\Response
 */
class RpcResponse
{
    const STATUS__OK     = 'OK';
    const STATUS__FAILED = 'Failed';

    /** @var mixed */
    public $payload;

    public string $status = self::STATUS__OK;

    public function __construct($payload = [], string $status = self::STATUS__OK)
    {
        $this->status  = $status;
        $this->payload = $payload;
    }
}
