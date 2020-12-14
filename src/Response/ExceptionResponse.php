<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 15.04.20
 */
declare(strict_types=1);

namespace GepurIt\RpcApiBundle\Response;

use Throwable;

/**
 * Class ExceptionResponse
 * @package GepurIt\RpcApiBundle\Response
 */
class ExceptionResponse extends RpcResponse
{
    /**
     * ExceptionResponseBody constructor.
     *
     * @param Throwable $exception
     */
    public function __construct(Throwable $exception)
    {
        $trace = [];
        //is workaround cuz backtrace can down to recursion
        foreach ($exception->getTrace() as $key => $value) {
            if (false !== json_encode($value)) {
                $trace[$key] = $value;
            }
        }
        $payload = [
            'error' => $exception->getMessage(),
            'trace' => $trace,
        ];

        return parent::__construct($payload, self::STATUS__FAILED);
    }
}
