<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 25.07.16
 * Time: 11:28
 */

namespace Dopamedia\StateMachine\Model;

interface StateMachineFacadeInterface
{
    /**
     * @param string $processName
     * @param string $highlightState
     * @param string $format
     * @param int $fontSize
     * @return string
     */
    public function drawProcess($processName, $highlightState = null, $format = null, $fontSize = null);
}