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
        $this->objectManager->create('Dopamedia\StateMachine\Model\StateMachine\Builder', [
            $this->createProcessEvent(),
            $this->createProcessState(),
            $this->createProcessTransition(),
            $this->createProcessProcess(),
            $this->getConfig()
        ]);
    }

    /**
     * @return ConfigurationInterface
     */
    public function getConfig()
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Model\Configuration');
    }

    /**
     * @return \Dopamedia\StateMachine\Api\ProcessEventInterface
     */
    public function createProcessEvent()
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Model\Process\Event');
    }

    /**
     * @return \Dopamedia\StateMachine\Api\ProcessStateInterface
     */
    public function createProcessState()
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Model\Process\State');
    }

    /**
     * @return \Dopamedia\StateMachine\Api\ProcessTransitionInterface
     */
    public function createProcessTransition()
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Model\Process\Transition');
    }

    /**
     * @return \Dopamedia\StateMachine\Api\ProcessProcessInterface
     */
    public function createProcessProcess()
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Model\Process\Process');
    }

    /**
     * @param string $stateMachineName
     * @return \Dopamedia\StateMachine\Model\Graph\DrawerInterface
     */
    public function createGraphDrawer($stateMachineName)
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Model\Graph\Drawer', $stateMachineName);
    }
}