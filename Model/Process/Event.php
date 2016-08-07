<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 16.07.16
 * Time: 14:54
 */

namespace Dopamedia\StateMachine\Model\Process;

use Dopamedia\StateMachine\Api\ProcessEventInterface;
use Dopamedia\StateMachine\Api\ProcessStateInterface;
use Dopamedia\StateMachine\Api\ProcessTransitionInterface;

class Event implements ProcessEventInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var ProcessTransitionInterface[]
     */
    protected $transitions = [];

    /**
     * @var bool
     */
    protected $onEnter = false;

    /**
     * @var string
     */
    protected $command;

    /**
     * @var string
     */
    protected $timeout;

    /**
     * @var bool
     */
    protected $manual = false;

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setManual($manual)
    {
        $this->manual = $manual;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function isManual()
    {
        return $this->manual;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @inheritDoc
     */
    public function hasCommand()
    {
        return isset($this->command);
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setOnEnter($onEnter)
    {
        $this->onEnter = $onEnter;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function isOnEnter()
    {
        return $this->onEnter;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setName($id)
    {
        $this->id = $id;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getEventTypeLabel()
    {
        if ($this->isOnEnter()) {
            return ' (on enter)';
        }

        if ($this->isManual()) {
            return ' (manual)';
        }

        if ($this->hasTimeout()) {
            return ' (timeout)';
        }

        return '';
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function addTransition(ProcessTransitionInterface $transition)
    {
        $this->transitions[] = $transition;
    }

    /**
     * @inheritDoc
     */
    public function getTransitionsBySource(ProcessStateInterface $sourceState)
    {
        $transitions = [];
        foreach ($this->transitions as $transition) {
            if ($transition->getSourceState()->getName() !== $sourceState->getName()) {
                continue;
            }
            $transitions[] = $transition;
        }
        return $transitions;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getTransitions()
    {
        return $this->transitions;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @inheritDoc
     */
    public function hasTimeout()
    {
        return isset($this->timeout);
    }
}