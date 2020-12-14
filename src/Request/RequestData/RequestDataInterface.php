<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 15.04.20
 */
declare(strict_types=1);

namespace GepurIt\RpcApiBundle\Request\RequestData;

use GepurIt\RpcApiBundle\Request\IncomingRequest;

/**
 * Class RequestDataInterface
 * @package GepurIt\RpcApiBundle\Request\RequestData
 */
interface RequestDataInterface
{
    /**
     * @param IncomingRequest $request
     *
     * @return RequestDataInterface
     */
    public static function fromRequest(IncomingRequest $request): RequestDataInterface;
}
