<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 25.07.16
 * Time: 10:18
 */

namespace Dopamedia\StateMachine\Model\StateMachine;

use Dopamedia\StateMachine\Api\ProcessProcessInterface;
use Magento\Framework\Exception\LocalizedException;

interface BuilderInterface
{
    /**
     * @param string $processName
     * @return ProcessProcessInterface
     * @throws LocalizedException
     */
    public function createProcess($processName);
}