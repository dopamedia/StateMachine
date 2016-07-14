<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 14.07.16
 * Time: 20:12
 */

namespace Dopamedia\StateMachine\Model;

use Dopamedia\StateMachine\Api\ProcessListInterface;
use Dopamedia\StateMachine\Model\Processes\ConfigInterface;

class ProcessList implements ProcessListInterface
{
    /**
     * @var ConfigInterface
     */
    private $processConfig;

    /**
     * @var \Dopamedia\StateMachine\Api\Data\ProcessInterfaceFactory
     */
    private $processFactory;

    /**
     * @var array|\Dopamedia\StateMachine\Api\Data\ProcessInterface[]
     */
    private $processes;

    /**
     * ConfigList constructor.
     * @param ConfigInterface $processConfig
     * @param \Dopamedia\StateMachine\Api\Data\ProcessInterfaceFactory $processFactory
     */
    public function __construct(
        ConfigInterface $processConfig,
        \Dopamedia\StateMachine\Api\Data\ProcessInterfaceFactory $processFactory
    )
    {
        $this->processConfig = $processConfig;
        $this->processFactory = $processFactory;
    }

    /**
     * @inheritDoc
     */
    public function getProcesses()
    {
        if ($this->processes === null) {
            $processes = [];
            foreach ($this->processConfig->getAll() as $processData) {
                /** @var \Dopamedia\StateMachine\Api\Data\ProcessInterface $process */
                $process = $this->processFactory->create();
                $process->setName($processData['name']);
                $processes[] = $process;
            }
            $this->processes = $processes;
        }
        return $this->processes;
    }
}