<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 07.08.16
 * Time: 13:12
 */

namespace Dopamedia\StateMachine\Observer;

use Magento\Framework\Event\ObserverInterface;

class ProcessInjectorObserver implements ObserverInterface
{
    /**
     * @var \Dopamedia\StateMachine\Model\StateMachine\BuilderInterface
     */
    private $builder;

    /**
     * @param \Dopamedia\StateMachine\Model\StateMachine\BuilderInterface $builder
     */
    public function __construct(
        \Dopamedia\StateMachine\Model\StateMachine\BuilderInterface $builder
    )
    {
        $this->builder = $builder;
    }

    /**
     * @inheritDoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Framework\Model\AbstractModel $object */
        $object = $observer->getData('object');
        $processName = $object->getData('state_machine_process_name');

        if (is_null($processName)) return null;

        $process = $this->builder->createProcess($processName);
        $object->setData('state_machine_process', $process);
    }
}