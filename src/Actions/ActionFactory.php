<?php
/**
 * @author: Mari <m934222258@gmail.com>
 * @since: 28.09.2020
 */
declare(strict_types=1);

namespace GepurIt\RpcApiBundle\Actions;

use GepurIt\RpcApiBundle\Exception\ActionNotFoundException;

/**
 * Class ActionFactory
 * @package GepurIt\RpcApiBundle\Actions
 */
class ActionFactory
{
    /** @var ActionInterface[] */
    private array $actionList = [];

    /**
     * @param string $actionName
     *
     * @return ActionInterface
     * @throws ActionNotFoundException
     */
    public function getAction(string $actionName): ActionInterface
    {
        if (!array_key_exists($actionName, $this->actionList)) {
            throw new ActionNotFoundException($actionName);
        }

        return $this->actionList[$actionName];
    }

    /**
     * @param string          $actionName
     * @param ActionInterface $action
     */
    public function registerAction(string $actionName, ActionInterface $action)
    {
        $this->actionList[$actionName] = $action;
    }
}
