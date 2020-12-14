<?php
/**
 * @author: Mari <m934222258@gmail.com>
 * @since: 28.09.2020
 */
declare(strict_types=1);

namespace GepurIt\RpcApiBundle\Request;

use GepurIt\RpcApiBundle\Request\RequestData\DefaultRequestData;
use GepurIt\RpcApiBundle\Request\RequestData\RequestDataInterface;

/**
 * Class RequestDataResolver
 * @package GepurIt\RpcApiBundle\Request
 */
class RequestDataResolver
{
    /**
     * @var string[]
     */
    private array  $requestList = [];

    /**
     * @param IncomingRequest $request
     *
     * @return RequestDataInterface
     */
    public function resolve(IncomingRequest $request): RequestDataInterface
    {
        $actionName = $request->actionName;
        if (!array_key_exists($actionName, $this->requestList)) {
            return DefaultRequestData::fromRequest($request);
        }

        return $this->requestList[$actionName]::fromRequest($request);
    }

    /**
     * @param string $action
     * @param string $requestDataClass
     */
    public function registerRequest(string $action, string $requestDataClass): void
    {
        $this->requestList[$action] = $requestDataClass;
    }
}
