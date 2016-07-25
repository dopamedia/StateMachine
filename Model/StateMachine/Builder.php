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
use Dopamedia\StateMachine\Api\ProcessTransitionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class Builder implements BuilderInterface
{
    const STATE_NAME_ATTRIBUTE = 'name';
    const STATE_DISPLAY_ATTRIBUTE = 'display';

    const PROCESS_NAME_ATTRIBUTE = 'name';
    const PROCESS_FILE_ATTRIBUTE = 'file';
    const PROCESS_MAIN_FLAG_ATTRIBUTE = 'main';

    const EVENT_COMMAND_ATTRIBUTE = 'command';
    const EVENT_MANUAL_ATTRIBUTE = 'manual';
    const EVENT_ON_ENTER_ATTRIBUTE = 'onEnter';
    const EVENT_TIMEOUT_ATTRIBUTE = 'timeout';

    const TRANSITION_CONDITION_ATTRIBUTE = 'condition';
    const TRANSITION_HAPPY_PATH_ATTRIBUTE = 'happy';

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
     * @param \Dopamedia\StateMachine\Model\Configuration $configuration
     */
    public function __construct(
        \Dopamedia\StateMachine\Api\ProcessEventInterface $event,
        \Dopamedia\StateMachine\Api\ProcessProcessInterface $process,
        \Dopamedia\StateMachine\Api\ProcessStateInterface $state,
        \Dopamedia\StateMachine\Api\ProcessTransitionInterface $transition,
        \Dopamedia\StateMachine\Model\Configuration $configuration
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

        list($processMap, $mainProcess) = $this->createMainSubProcess();

        $stateToProcessMap = $this->createStates($processMap);

        $this->createSubProcesses($processMap);

        $eventMap = $this->createEvents();

        $this->createTransitions($stateToProcessMap, $processMap, $eventMap);

        self::$processBuffer[$processName] = $mainProcess;
        
        return $mainProcess;
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    protected function createMainSubProcess()
    {
        $mainProcess = null;
        $processMap = [];

        foreach ($this->configuration->getAll() as $processName => $processConfiguration) {
            $process = clone $this->process;
            $process->setName($processName);
            $processMap[$processName] = $process;

            /**
             * @TODO::identify main-process
             */
            $mainProcess = $process;
        }

        if ($mainProcess === null) {
            throw new LocalizedException(__('Main process could not be created.'));
        }

        return [$processMap, $mainProcess];
    }

    /**
     * @param array $processMap
     * @return array
     */
    protected function createStates(array $processMap)
    {
        $stateToProcessMap = [];

        foreach ($this->configuration->getAll() as $processName => $processConfiguration) {
            /** @var ProcessProcessInterface $process */
            $process = $processMap[$processName];

            if (empty($processConfiguration['states'])) {
                continue;
            }

            foreach ($processConfiguration['states'] as $stateName => $stateConfiguration) {
                $state = $this->createState($stateName, $stateConfiguration, $process);
                $process->addState($state);

                $stateToProcessMap[$stateName] = $process;
            }
        }
        return $stateToProcessMap;
    }

    /**
     * @TODO::add configuration to state
     *
     * @param string $stateName
     * @param array $stateConfiguration
     * @param ProcessProcessInterface $process
     * @return \Dopamedia\StateMachine\Api\ProcessStateInterface
     */
    protected function createState($stateName, array $stateConfiguration, ProcessProcessInterface $process)
    {
        $state = clone $this->state;
        $state->setName($stateName);
        $state->setProcess($process);
        return $state;
    }

    /**
     * @TODO::implement logic for subProcesses
     * @param array $processMap
     */
    protected function createSubProcesses(array $processMap)
    {
        foreach ($this->configuration->getAll() as $processName => $processConfiguration) {
            $process = $processMap[$processName];

            if (isset($processConfiguration['subprocesses'])) {

            }
        }
    }

    protected function createEvents()
    {
        $eventMap = [];
        foreach ($this->configuration->getAll() as $processName => $processConfiguration) {
            if (!isset($processConfiguration['events'])) {
                continue;
            }
            $eventsConfiguration = $processConfiguration['events'];
            foreach ($eventsConfiguration as $eventName => $eventConfiguration) {
                $event = $this->createEvent($eventName, $eventConfiguration);
                if ($event === null) {
                    continue;
                }
                $eventMap[$eventName] = $event;
            }
        }
        return $eventMap;
    }

    /**
     * @TODO::add configuration to event
     *
     * @param string $eventName
     * @param array $eventConfiguration
     * @return \Dopamedia\StateMachine\Api\ProcessEventInterface
     */
    protected function createEvent($eventName, array $eventConfiguration)
    {
        $event = clone $this->event;
        $event->setName($eventName);
        return $event;
    }

    /**
     * @param array $stateToProcessMap
     * @param array $processMap
     * @param array $eventMap
     */
    protected function createTransitions(array $stateToProcessMap, array $processMap, array $eventMap)
    {
        foreach ($this->configuration->getAll() as $processName => $processConfiguration) {
            if (!isset($processConfiguration['transitions'])) {
                continue;
            }

            foreach ($processConfiguration['transitions'] as $transitionConfiguration) {
                $transition = $this->createTransition($stateToProcessMap, $eventMap, $transitionConfiguration);
                $processMap[$processName]->addTransition($transition);
            }
        }
    }

    /**
     * @todo::add configuration to transition
     *
     * @param array $stateToProcessMap
     * @param array $eventMap
     * @param array $transitionConfiguration
     * @return \Dopamedia\StateMachine\Api\ProcessTransitionInterface
     */
    protected function createTransition(array $stateToProcessMap, array $eventMap, array $transitionConfiguration)
    {

        $transition = clone $this->transition;

        if (isset($transitionConfiguration['condition'])) {
            // @TODO::implement condition
        }

        if (isset($transitionConfiguration['happy'])) {
            // @TODO:implement happy-case
        }

        $sourceState = $transitionConfiguration['source'];

        $this->setTransitionSource($stateToProcessMap, $sourceState, $transition);
        $this->setTransitionTarget($stateToProcessMap, $transitionConfiguration, $sourceState, $transition);
        $this->setTransitionEvent($eventMap, $transitionConfiguration, $sourceState, $transition);

        return $transition;
    }

    /**
     * @param ProcessProcessInterface[] $stateToProcessMap
     * @param string $sourceName
     * @param ProcessTransitionInterface $transition
     *
     * @return void
     */
    protected function setTransitionSource(
        array $stateToProcessMap,
        $sourceName,
        ProcessTransitionInterface $transition
    ) {

        $sourceProcess = $stateToProcessMap[$sourceName];
        $sourceState = $sourceProcess->getState($sourceName);
        $transition->setSourceState($sourceState);
        $sourceState->addOutgoingTransition($transition);
    }

    /**
     * @param ProcessProcessInterface[] $stateToProcessMap
     * @param array $transitionConfiguration
     * @param string $sourceName
     * @param ProcessTransitionInterface $transition
     *
     * @throws LocalizedException
     *
     * @return void
     */
    protected function setTransitionTarget(
        array $stateToProcessMap,
        array $transitionConfiguration,
        $sourceName,
        $transition
    ) {
        $targetStateName = $transitionConfiguration['target'];
        if (!isset($stateToProcessMap[$targetStateName])) {
            throw new LocalizedException(
                new Phrase(
                    'Target: "%1" does not exist from source: "%2"',
                    [$targetStateName, $sourceName]
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
     * @param array $transitionConfiguration
     * @param string $sourceState
     * @param ProcessTransitionInterface $transition
     *
     * @throws LocalizedException
     *
     * @return void
     */
    protected function setTransitionEvent(array $eventMap, array $transitionConfiguration, $sourceState, $transition)
    {
        if (isset($transitionConfiguration['event'])) {
            $eventName = $transitionConfiguration['event'];

            $this->assertEventExists($eventMap, $sourceState, $eventName);

            $event = $eventMap[$eventName];
            $event->addTransition($transition);
            $transition->setEvent($event);
        }
    }

    /**
     * @param array $eventMap
     * @param string $sourceName
     * @param string $eventName
     *
     * @throws LocalizedException
     *
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