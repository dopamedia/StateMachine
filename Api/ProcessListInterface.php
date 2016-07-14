<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 14.07.16
 * Time: 20:22
 */

namespace Dopamedia\StateMachine\Api;


interface ProcessListInterface
{
    /**
     * @return \Dopamedia\StateMachine\Api\Data\ConfigInterface[]
     */
    public function getConfigs();
}