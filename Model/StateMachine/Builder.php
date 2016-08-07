<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 16.07.16
 * Time: 15:12
 */

namespace Dopamedia\StateMachine\Model\StateMachine;

use Dopamedia\StateMachine\Api\ProcessEventInterface;
use Dopamedia\StateMachine\Api\ProcessProcessInterface;
use Dopamedia\StateMachine\Api\ProcessStateInterface;
use Dopamedia\StateMachine\Api\ProcessTransitionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class Builder implements BuilderInterface
{
    /**
     * @var ProcessProcessInterface[]
     */
    protected static $processBuffer = [];

    /**
     * @var \Dopamedia\StateMachine\Api\ProcessEventInterface
     */
    private $event;

    /**
     * @var \Dopamedia\StateMachine\Api\ProcessStateInterface
     */
    private $state;

    /**
     * @var \Dopamedia\StateMachine\Api\ProcessTransitionInterface
     */
    private $transition;

    /**
     * @var \Dopamedia\StateMachine\Api\ProcessProcessInterface
     */
    private $process;

    /**
     * @var \Dopamedia\StateMachine\Model\Configuration
     */
    protected $configuration;

    /**
     * @var array
     */
    protected $currentConfiguration;

    /**
     * Builder constructor.
     * @param \Dopamedia\StateMachine\Api\ProcessEventInterface $event
     * @param \Dopamedia\StateMachine\Api\ProcessProcessInterface $process
     * @param \Dopamedia\StateMachine\Api\ProcessStateInterface $state
     * @param \Dopamedia\StateMachine\Api\ProcessTransitionInterface $transition
     * @param \Dopamedia\StateMachine\Model\ConfigurationInterface $configuration
     */
    public function __construct(
        \Dopamedia\StateMachine\Api\ProcessEventInterface $event,
        \Dopamedia\StateMachine\Api\ProcessProcessInterface $process,
        \Dopamedia\StateMachine\Api\ProcessStateInterface $state,
        \Dopamedia\StateMachine\Api\ProcessTransitionInterface $transition,
        \Dopamedia\StateMachine\Model\ConfigurationInterface $configuration
    )
    {
        $this->event = $event;
        $this->state = $state;
        $this->transition = $transition;
        $this->process = $process;
        $this->configuration = $configuration;
    }

    /**
     * @inheritdoc
     */
    public function createProcess($processName)
    {
        if (isset(self::$processBuffer[$processName])) {
            return self::$processBuffer[$processName];
        }

        if (is_null($this->getProcessConfiguration($processName))) {
            throw new LocalizedException(
                new Phrase('Process "%1" does not exist', [$processName])
            );
        }

        list($processMap, $mainProcess) = $this->createMainProcess($processName);

        $stateToProcessMap = $this->createStates($processName, $processMap);
        
        $eventMap = $this->createEvents($processName);

        $this->createTransitions($processName, $stateToProcessMap, $processMap, $eventMap);

        self::$processBuffer[$processName] = $mainProcess;
        
        return $mainProcess;
    }

    /**
     * @param $processName
     * @return array|null
     */
    protected function getProcessConfiguration($processName)
    {
        return $this->configuration->getProcess($processName);
    }


    /**
     * @param string $processName
     * @return array
     * @throws LocalizedException
     */
    protected function createMainProcess($processName)
    {
        $processMap = [];
        $process = clone $this->process;
        $process->setName($processName);
        $process->setObjectClass($this->configuration->getProcessObjectClass($processName));
        $processMap[$processName] = $process;
        return [$processMap, $process];
    }

    /**
     * @param string $processName
     * @param array $processMap
     * @return array
     */
    protected function createStates($processName, array $processMap)
    {
        $stateToProcessMap = [];
        $process = $processMap[$processName];

        if ($statesConfiguration = $this->configuration->getStates($processName)) {
            foreach ($statesConfiguration as $stateName => $stateConfiguration) {
                $state = $this->createState($processName, $stateName, $process);
                $process->addState($state);
                $stateToProcessMap[$stateName] = $process;
            }
        }
        return $stateToProcessMap;
    }

    /**
     * @param string $processName
     * @param string $stateName
     * @param ProcessProcessInterface $process
     * @return \Dopamedia\StateMachine\Api\ProcessStateInterface
     */
    protected function createState($processName, $stateName, ProcessProcessInterface $process)
    {
        $state = clone $this->state;
        $state->setName($stateName);
        $state->setProcess($process);

        if (!is_null($flags = $this->configuration->getStateFlags($processName, $stateName))) {
            $state = $this->addStateFlags($state, $flags);
        }

        return $state;
    }

    /**
     * @param ProcessStateInterface $state
     * @param array $flags
     * @return ProcessStateInterface
     */
    protected function addStateFlags(ProcessStateInterface $state, array $flags)
    {
        foreach ($flags as $flag) {
            $state->addFlag($flag);
        }
        return $state;
    }

    /**
     * @param string $processName
     * @return array
     */
    protected function createEvents($processName)
    {
        $eventMap = [];

        if ($eventsConfiguration = $this->configuration->getEvents($processName)) {
            foreach ($eventsConfiguration as $eventName => $eventConfiguration) {
                $event = $this->createEvent($processName, $eventName);
                if ($event === null) {
                    continue;
                }
                $eventMap[$eventName] = $event;
            }
        }
        return $eventMap;
    }

    /**
     * @param string $processName
     * @param string $eventName
     * @return \Dopamedia\StateMachine\Api\ProcessEventInterface
     */
    protected function createEvent($processName, $eventName)
    {
        $event = clone $this->event;

        if (!is_null($command = $this->configuration->getEventCommand($processName, $eventName))) {
            $event->setCommand($command);
        }

        if (!is_null($manual = $this->configuration->getEventManual($processName, $eventName))) {
            $event->setManual($manual);
        }

        if (!is_null($onEnter = $this->configuration->getEventOnEnter($processName, $eventName))) {
            $event->setOnEnter($onEnter);
        }

        if (!is_null($timeout = $this->configuration->getEventTimeout($processName, $eventName))) {
            $event->setTimeout($timeout);
        }

        $event->setName($eventName);
        return $event;
    }

    /**
     * @param string $processName
     * @param array $stateToProcessMap
     * @param array $processMap
     * @param array $eventMap
     */
    protected function createTransitions($processName, array $stateToProcessMap, array $processMap, array $eventMap)
    {
        if ($transitionsConfiguration = $this->configuration->getTransitions($processName)) {
            foreach (array_keys($transitionsConfiguration) as $transitionIndex) {
                $transition = $this->createTransition($stateToProcessMap, $eventMap, $processName, $transitionIndex);
                $processMap[$processName]->addTransition($transition);
            }
        }
    }

    /**
     * @param array $stateToProcessMap
     * @param array $eventMap
     * @param int $transitionIndex
     * @param string $processName
     * @return \Dopamedia\StateMachine\Api\ProcessTransitionInterface
     */
    protected function createTransition(array $stateToProcessMap, array $eventMap, $processName, $transitionIndex)
    {

        $transition = clone $this->transition;

        if (!is_null($condition = $this->configuration->getTransitionCondition($processName, $transitionIndex))) {
            $transition->setCondition($condition);
        }

        if (!is_null($happy = $this->configuration->getTransitionHappy($processName, $transitionIndex))) {
            $transition->setHappyCase($happy);
        }

        $sourceStateName = $this->configuration->getTransitionSource($processName, $transitionIndex);
        $this->setTransitionSource($stateToProcessMap, $sourceStateName, $transition);

        $targetStateName = $this->configuration->getTransitionTarget($processName, $transitionIndex);
        $this->setTransitionTarget($stateToProcessMap, $targetStateName, $sourceStateName, $transition);

        $transitionEventName = $this->configuration->getTransitionEvent($processName, $transitionIndex);
        $this->setTransitionEvent($eventMap, $transitionEventName, $sourceStateName, $transition);

        return $transition;
    }

    /**
     * @param ProcessProcessInterface[] $stateToProcessMap
     * @param string $sourceStateName
     * @param ProcessTransitionInterface $transition
     *
     * @return void
     */
    protected function setTransitionSource(
        array $stateToProcessMap,
        $sourceStateName,
        ProcessTransitionInterface $transition
    ) {
        $sourceProcess = $stateToProcessMap[$sourceStateName];
        $sourceState = $sourceProcess->getState($sourceStateName);
        $transition->setSourceState($sourceState);
        $sourceState->addOutgoingTransition($transition);
    }

    /**
     * @param ProcessProcessInterface[] $stateToProcessMap
     * @param string $targetStateName
     * @param string $sourceStateName
     * @param ProcessTransitionInterface $transition
     *
     * @throws LocalizedException
     *
     * @return void
     */
    protected function setTransitionTarget(
        array $stateToProcessMap,
        $targetStateName,
        $sourceStateName,
        $transition
    ) {
        if (!isset($stateToProcessMap[$targetStateName])) {
            throw new LocalizedException(
                new Phrase(
                    'Target: "%1" does not exist from source: "%2"',
                    [$targetStateName, $sourceStateName]
                )
            );
        }

        $targetProcess = $stateToProcessMap[$targetStateName];
        $targetState = $targetProcess->getState($targetStateName);
        $transition->setTargetState($targetState);
        $targetState->addIncomingTransition($transition);
    }

    /**
     * @param ProcessEventInterface[] $eventMap
     * @param string|null $transitionEventName
     * @param string $sourceState
     * @param ProcessTransitionInterface $transition
     * @throws LocalizedException
     * @return void
     */
    protected function setTransitionEvent(
        array $eventMap,
        $transitionEventName,
        $sourceState,
        $transition
    )
    {
        if (!is_null($transitionEventName)) {
            $this->assertEventExists($eventMap, $sourceState, $transitionEventName);
            $event = $eventMap[$transitionEventName];
            $event->addTransition($transition);
            $transition->setEvent($event);
        }
    }

    /**
     * @param array $eventMap
     * @param string $sourceName
     * @param string $eventName
     * @throws LocalizedException
     * @return void
     */
    protected function assertEventExists(array $eventMap, $sourceName, $eventName)
    {
        if (!isset($eventMap[$eventName])) {
            throw new LocalizedException(
                new Phrase(
                    'Event: "%1" does not exist from source: "%2"',
                    [$eventName, $sourceName]
                )
            );
        }
    }
}