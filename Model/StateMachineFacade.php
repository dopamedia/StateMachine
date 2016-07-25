<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 25.07.16
 * Time: 11:28
 */

namespace Dopamedia\StateMachine\Model;

class StateMachineFacade implements StateMachineFacadeInterface
{
    /**
     * @var StateMachineFactory
     */
    private $factory;

    /**
     * @var StateMachine\BuilderInterface
     */
    private $stateMachineBuilder;
    
    /**
     * @param StateMachineFactory $factory
     * @param StateMachine\BuilderInterface $stateMachineBuilder
     */
    public function __construct(
        StateMachineFactory $factory,
        StateMachine\BuilderInterface $stateMachineBuilder
    )
    {
        $this->factory = $factory;
        $this->stateMachineBuilder = $stateMachineBuilder;
    }

    /**
     * @inheritDoc
     */
    public function drawProcess($processName, $highlightState = null, $format = null, $fontSize = null)
    {
        $process = $this->stateMachineBuilder
            ->createProcess($processName);

        return $this->factory
            ->createGraphDrawer($processName)
            ->draw($process, $highlightState, $format, $fontSize);
    }
}