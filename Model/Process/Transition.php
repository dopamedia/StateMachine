<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 16.07.16
 * Time: 14:56
 */

namespace Dopamedia\StateMachine\Model\Process;

use Dopamedia\StateMachine\Api\ProcessEventInterface;
use Dopamedia\StateMachine\Api\ProcessStateInterface;
use Dopamedia\StateMachine\Api\ProcessTransitionInterface;

class Transition implements ProcessTransitionInterface
{
    /**
     * @var ProcessEventInterface
     */
    protected $event;

    /**
     * @var string
     */
    protected $condition;

    /**
     * @var bool
     */
    protected $happy;

    /**
     * @var ProcessStateInterface
     */
    protected $source;

    /**
     * @var ProcessStateInterface
     */
    protected $target;

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setHappyCase($happy)
    {
        $this->happy = $happy;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function isHappyCase()
    {
        return $this->happy;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @inheritDoc
     */
    public function hasCondition()
    {
        return isset($this->condition);
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setEvent(ProcessEventInterface $event)
    {
        $this->event = $event;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @inheritDoc
     */
    public function hasEvent()
    {
        return isset($this->event);
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setSourceState(ProcessStateInterface $source)
    {
        $this->source = $source;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getSourceState()
    {
        return $this->source;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setTargetState(ProcessStateInterface $target)
    {
        $this->target = $target;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getTargetState()
    {
        return $this->target;
    }
}