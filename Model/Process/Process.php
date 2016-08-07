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

class Process implements ProcessProcessInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $objectClass;

    /**
     * @var ProcessStateInterface[]
     */
    protected $states = [];

    /**
     * @var ProcessTransitionInterface[]
     */
    protected $transitions = [];

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setObjectClass($objectClass)
    {
        return $this->objectClass = $objectClass;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function setStates(array $states)
    {
        $this->states = $states;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function addState(ProcessStateInterface $state)
    {
        $this->states[$state->getName()] = $state;
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getState($stateId)
    {
        return $this->states[$stateId];
    }

    /**
     * @inheritDoc
     */
    public function hasState($stateId)
    {
        return array_key_exists($stateId, $this->states);
    }

    /**
     * @inheritDoc
     */
    public function getStateFromAllProcesses($stateName)
    {
        $processes = $this->getAllProcesses();
        foreach ($processes as $process) {
            if ($process->hasState($stateName)) {
                return $process->getState($stateName);
            }
        }

        throw new LocalizedException(
            new Phrase(
                'State "%1" not found in any of state machine processes. Is state defined in xml definition file?',
                [$stateName]
            )
        );    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * @inheritDoc
     */
    public function hasStates()
    {
        return (bool)$this->states;
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
     * @codeCoverageIgnore
     */
    public function setTransitions(array $transitions)
    {
        $this->transitions = $transitions;
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
    public function hasTransitions()
    {
        return (bool)$this->transitions;
    }

    /**
     * @inheritDoc
     */
    public function getAllStates()
    {
        $states = [];
        if ($this->hasStates()) {
            $states = $this->getStates();
        }
        return $states;
    }

    /**
     * @inheritDoc
     */
    public function getAllTransitions()
    {
        $transitions = [];
        if ($this->hasTransitions()) {
            $transitions = $this->getTransitions();
        }
        return $transitions;
    }

    /**
     * @inheritDoc
     */
    public function getAllTransitionsWithoutEvent()
    {
        $transitions = [];
        $allTransitions = $this->getAllTransitions();
        foreach ($allTransitions as $transition) {
            if ($transition->hasEvent() === true) {
                continue;
            }
            $transitions[] = $transition;
        }

        return $transitions;
    }

    /**
     * @inheritDoc
     */
    public function getManualEvents()
    {
        $manuallyExecutableEventList = [];
        $transitions = $this->getAllTransitions();
        foreach ($transitions as $transition) {
            if ($transition->hasEvent()) {
                $event = $transition->getEvent();
                if ($event->isManual()) {
                    $manuallyExecutableEventList[] = $event;
                }
            }
        }
        return $manuallyExecutableEventList;
    }

    /**
     * @inheritDoc
     */
    public function getManualEventsBySource()
    {
        $events = $this->getManualEvents();

        $eventsBySource = [];
        foreach ($events as $event) {
            $transitions = $event->getTransitions();
            $eventsBySource = $this->groupTransitionsBySourceName(
                $transitions,
                $eventsBySource,
                $event
            );
        }

        return $eventsBySource;
    }

    /**
     * @param array $transitions
     * @param array $eventsBySource
     * @param ProcessEventInterface $event
     * @return array
     */
    protected function groupTransitionsBySourceName(array $transitions, array $eventsBySource, ProcessEventInterface $event)
    {
        foreach ($transitions as $transition) {
            $sourceName = $transition->getSourceState()->getName();
            if (!isset($eventsBySource[$sourceName])) {
                $eventsBySource[$sourceName] = [];
            }
            if (!in_array($event->getName(), $eventsBySource[$sourceName], true)) {
                $eventsBySource[$sourceName][] = $event->getName();
            }
        }
        return $eventsBySource;
    }

    /**
     * @inheritDoc
     */
    public function getAllProcesses()
    {
        $processes[] = $this;
        return $processes;
    }
}