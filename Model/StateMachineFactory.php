<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 25.07.16
 * Time: 10:25
 */

namespace Dopamedia\StateMachine\Model;

class StateMachineFactory
{
    /**
     * Object Manager
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Construct
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $className
     * @param array $data
     * @return mixed
     */
    public function create($className, array $data = [])
    {
        return $this->objectManager->create($className, $data);
    }

    /**
     * @return \Dopamedia\StateMachine\Model\StateMachine\BuilderInterface
     */
    public function createStateMachineBuilder()
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Model\StateMachine\Builder', [
            'event' => $this->createProcessEvent(),
            'process' => $this->createProcessState(),
            'state' => $this->createProcessTransition(),
            'transition' => $this->createProcessProcess(),
            'configuration' => $this->getConfiguration()
        ]);
    }

    /**
     * @return \Dopamedia\StateMachine\Api\ProcessEventInterface
     */
    public function createProcessEvent()
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Api\ProcessEventInterface');
    }

    /**
     * @return \Dopamedia\StateMachine\Api\ProcessStateInterface
     */
    public function createProcessState()
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Api\ProcessStateInterface');
    }

    /**
     * @return \Dopamedia\StateMachine\Api\ProcessTransitionInterface
     */
    public function createProcessTransition()
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Api\ProcessTransitionInterface');
    }

    /**
     * @return \Dopamedia\StateMachine\Api\ProcessProcessInterface
     */
    public function createProcessProcess()
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Api\ProcessProcessInterface');
    }

    /**
     * @return ConfigurationInterface
     */
    public function getConfiguration()
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Model\ConfigurationInterface');
    }

    /**
     * @param string $stateMachineName
     * @return \Dopamedia\StateMachine\Model\Graph\DrawerInterface
     */
    public function createGraphDrawer($stateMachineName)
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Model\Graph\DrawerInterface', [
            'graph' => $this->createGraph($stateMachineName)
        ]);
    }

    /**
     * @param string $processName
     * @return \Dopamedia\StateMachine\Model\Graph\GraphInterface
     */
    public function createGraph($processName)
    {
        return $this->objectManager->create('\Dopamedia\StateMachine\Model\Graph\GraphInterface',
            ['name' => $processName]
        );
    }

}