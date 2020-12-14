<?php
/**
 * @author: Mari <m934222258@gmail.com>
 * @since : 28.09.2020
 */

namespace GepurIt\RpcApiBundle\Actions;

use GepurIt\RpcApiBundle\Request\RequestData\RequestDataInterface;
use GepurIt\RpcApiBundle\Response\RpcResponse;

/**
 * Interface ActionInterface
 * @package GepurIt\RpcApiBundle\Actions
 */
interface ActionInterface
{
    /**
     * @param RequestDataInterface $data
     *
     * @return RpcResponse
     */
    public function dispatch(RequestDataInterface $data): RpcResponse;
}
