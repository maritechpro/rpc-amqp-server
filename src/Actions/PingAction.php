<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 11.12.18
 */

namespace GepurIt\RpcApiBundle\Actions;

use GepurIt\RpcApiBundle\Request\RequestData\DefaultRequestData;
use GepurIt\RpcApiBundle\Request\RequestData\RequestDataInterface;
use GepurIt\RpcApiBundle\Response\RpcResponse;

/**
 * Class PingAction
 * @package GepurIt\RpcApiBundle\Actions
 */
class PingAction implements ActionInterface
{
    /**
     * @param RequestDataInterface|DefaultRequestData $data
     *
     * @return RpcResponse
     */
    public function dispatch(RequestDataInterface $data): RpcResponse
    {
        return new RpcResponse('pong', RpcResponse::STATUS__OK);
    }
}
