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
     * @param StateMachineFactory $factory
     */
    public function __construct(
        StateMachineFactory $factory
    )
    {
        $this->factory = $factory;
    }

    /**
     * @inheritDoc
     */
    public function drawProcess($processName, $highlightState = null, $format = null, $fontSize = null)
    {
        $process = $this->factory
            ->createStateMachineBuilder()
            ->createProcess($processName);

        return $this->factory
            ->createGraphDrawer('***TODO::replace me***')
            ->draw($process, $highlightState, $format, $fontSize);
    }
}