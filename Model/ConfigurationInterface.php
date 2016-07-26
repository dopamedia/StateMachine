<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 16.07.16
 * Time: 14:47
 */

namespace Dopamedia\StateMachine\Model;

interface ConfigurationInterface
{
    public function getAll();

    /**
     * @param string $processName
     * @return array|null
     */
    public function getProcess($processName);
}