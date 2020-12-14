<?php
/**
 * @author: Marina Mileva m934222258@gmail.com
 * @since : 28.09.20 17:45
 */
declare(strict_types=1);

namespace GepurIt\RpcApiBundle\Request\RequestData;

use GepurIt\RpcApiBundle\Request\IncomingRequest;

/**
 * Class DefaultRequestData
 * @package GepurIt\RpcApiBundle\Request\RequestData
 */
class DefaultRequestData implements RequestDataInterface
{
    /**
     * @inheritDoc
     */
    public static function fromRequest(IncomingRequest $request): RequestDataInterface
    {
        $requestData = new self();
        foreach ($request->payload as $field => $value) {
            $requestData->$field = $value;
        }

        return $requestData;
    }
}