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
     * @var ProcessStateInterface[]
     */
    protected $states = [];

    /**
     * @var ProcessTransitionInterface[]
     */
    protected $transitions = [];

    /**
     * @var bool
     */
    protected $main;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var ProcessProcessInterface[]
     */
    protected $subProcesses = [];

    /**
     * @inheritDoc
     */
    public function setSubProcesses(array $subProcesses)
    {
        $this->subProcesses = $subProcesses;
    }

    /**
     * @inheritDoc
     */
    public function getSubProcesses()
    {
        return $this->subProcesses;
    }

    /**
     * @inheritDoc
     */
    public function hasSubProcesses()
    {
        return count($this->subProcesses) > 0;
    }

    /**
     * @inheritDoc
     */
    public function addSubProcess(ProcessProcessInterface $subProcess)
    {
        $this->subProcesses[] = $subProcess;
    }

    /**
     * @inheritDoc
     */
    public function setMain($main)
    {
        $this->main = $main;
    }

    /**
     * @inheritDoc
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function setStates(array $states)
    {
        $this->states = $states;
    }

    /**
     * @inheritDoc
     */
    public function addState(ProcessStateInterface $state)
    {
        $this->states[$state->getName()] = $state;
    }

    /**
     * @inheritDoc
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
     */
    public function addTransition(ProcessTransitionInterface $transition)
    {
        $this->transitions[] = $transition;
    }

    /**
     * @inheritDoc
     */
    public function setTransitions(array $transitions)
    {
        $this->transitions = $transitions;
    }

    /**
     * @inheritDoc
     */
    public function getTransitions()
    {
        return $this->transitions;
    }

    /**
     * @inheritDoc
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

        if (!$this->hasSubProcesses()) {
            return $states;
        }

        foreach ($this->getSubProcesses() as $subProcess) {
            if (!$subProcess->hasStates()) {
                continue;
            }
            $states = array_merge($states, $subProcess->getStates());
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
        foreach ($this->getSubProcesses() as $subProcess) {
            if ($subProcess->hasTransitions()) {
                $transitions = array_merge($transitions, $subProcess->getTransitions());
            }
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
        $processes = [];
        $processes[] = $this;
        $processes = array_merge($processes, $this->getSubProcesses());
        return $processes;
    }

    /**
     * @inheritDoc
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @inheritDoc
     */
    public function hasFile()
    {
        return isset($this->file);
    }

    /**
     * @inheritDoc
     */
    public function getFile()
    {
        return $this->file;
    }
}