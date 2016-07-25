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
     * @param string $processName
     * @return \Dopamedia\StateMachine\Model\Graph\DrawerInterface
     */
    public function createGraphDrawer($processName)
    {
        return $this->objectManager->create('Dopamedia\StateMachine\Model\Graph\DrawerInterface', [
            'graph' => $this->createGraph($processName)
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