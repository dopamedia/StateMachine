<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 16.07.16
 * Time: 14:55
 */

namespace Dopamedia\StateMachine\Model\Process;

use Dopamedia\StateMachine\Api\ProcessEventInterface;
use Dopamedia\StateMachine\Api\ProcessProcessInterface;
use Dopamedia\StateMachine\Api\ProcessStateInterface;
use Dopamedia\StateMachine\Api\ProcessTransitionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class State implements ProcessStateInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $display;

    /**
     * @var ProcessProcessInterface
     */
    protected $process;

    /**
     * @var array
     */
    protected $flags = [];

    /**
     * @var ProcessTransitionInterface[]
     */
    protected $outgoingTransitions = [];

    /**
     * @var ProcessTransitionInterface[]
     */
    protected $incomingTransitions = [];

    /**
     * @inheritDoc
     */
    public function setIncomingTransitions(array $incomingTransitions)
    {
        $this->incomingTransitions = $incomingTransitions;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIncomingTransitions()
    {
        return $this->incomingTransitions;
    }

    /**
     * @inheritDoc
     */
    public function hasIncomingTransitions()
    {
        return (bool)$this->incomingTransitions;
    }

    /**
     * @inheritDoc
     */
    public function setOutgoingTransitions(array $outgoingTransitions)
    {
        $this->outgoingTransitions = $outgoingTransitions;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOutgoingTransitions()
    {
        return $this->outgoingTransitions;
    }

    /**
     * @inheritDoc
     */
    public function hasOutgoingTransitions()
    {
        return (bool)$this->outgoingTransitions;
    }

    /**
     * @inheritDoc
     */
    public function getOutgoingTransitionsByEvent(ProcessEventInterface $event)
    {
        $transitions = [];
        foreach ($this->outgoingTransitions as $transition) {
            if ($transition->hasEvent()) {
                if ($transition->getEvent()->getName() === $event->getName()) {
                    $transitions[] = $transition;
                }
            }
        }
        return $transitions;
    }

    /**
     * @inheritDoc
     */
    public function getEvents()
    {
        $events = [];
        foreach ($this->outgoingTransitions as $transition) {
            if ($transition->hasEvent()) {
                $events[$transition->getEvent()->getName()] = $transition->getEvent();
            }
        }
        return $events;
    }

    /**
     * @inheritDoc
     */
    public function getEvent($eventName)
    {
        foreach ($this->outgoingTransitions as $transition) {
            if ($transition->hasEvent()) {
                $event = $transition->getEvent();
                if ($event->getName() === $eventName) {
                    return $event;
                }
            }
        }

        throw new LocalizedException(
            new Phrase('Event "%1" not found. Have you added this event to transition?', [$eventName])
        );
    }

    /**
     * @inheritDoc
     */
    public function hasEvent($id)
    {
        foreach ($this->outgoingTransitions as $transition) {
            if ($transition->hasEvent()) {
                $event = $transition->getEvent();
                if ($event->getName() === $id) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function hasAnyEvent()
    {
        foreach ($this->outgoingTransitions as $transition) {
            if ($transition->hasEvent()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function addIncomingTransition(ProcessTransitionInterface $transition)
    {
        $this->incomingTransitions[] = $transition;
    }

    /**
     * @inheritDoc
     */
    public function addOutgoingTransition(ProcessTransitionInterface $transition)
    {
        $this->outgoingTransitions[] = $transition;
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setProcess(ProcessProcessInterface $process)
    {
        $this->process = $process;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @inheritDoc
     */
    public function hasOnEnterEvent()
    {
        $transitions = $this->getOutgoingTransitions();
        foreach ($transitions as $transition) {
            if ($transition->hasEvent()) {
                if ($transition->getEvent()->isOnEnter() === true) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getOnEnterEvent()
    {
        $transitions = $this->getOutgoingTransitions();
        foreach ($transitions as $transition) {
            if ($transition->hasEvent()) {
                if ($transition->getEvent()->isOnEnter() === true) {
                    return $transition->getEvent();
                }
            }
        }

        throw new LocalizedException(
            new Phrase(
                'There is no onEnter event for state "%1"',
                [$this->getName()]
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function hasTimeoutEvent()
    {
        $transitions = $this->getOutgoingTransitions();
        foreach ($transitions as $transition) {
            if ($transition->hasEvent()) {
                if ($transition->getEvent()->hasTimeout() === true) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getTimeoutEvents()
    {
        $events = [];
        $transitions = $this->getOutgoingTransitions();
        foreach ($transitions as $transition) {
            if ($transition->hasEvent()) {
                if ($transition->getEvent()->hasTimeout() === true) {
                    $events[] = $transition->getEvent();
                }
            }
        }
        return $events;
    }

    /**
     * @inheritDoc
     */
    public function addFlag($flag)
    {
        $this->flags[] = $flag;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hasFlag($flag)
    {
        return in_array($flag, $this->flags);
    }

    /**
     * @inheritDoc
     */
    public function hasFlags()
    {
        return count($this->flags) > 0;
    }

    /**
     * @inheritDoc
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @inheritDoc
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * @inheritDoc
     */
    public function setDisplay($display)
    {
        $this->display = $display;
        return $this;
    }
}