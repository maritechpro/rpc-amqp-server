<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 14.04.20
 */
declare(strict_types=1);

namespace GepurIt\RpcApiBundle\Response;

/**
 * Class BadRequestResponse
 * @package GepurIt\RpcApiBundle\Response
 */
class BadRequestResponse extends RpcResponse
{
    public function __construct($errors = [])
    {
        $payload = [
            'code'    => 400,
            'message' => "Bad Request",
            'errors'  => $errors,
        ];
        parent::__construct($payload, self::STATUS__FAILED);
    }
}
