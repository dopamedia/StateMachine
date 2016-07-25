<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 25.07.16
 * Time: 10:35
 */

namespace Dopamedia\StateMachine\Model\Graph;

use Dopamedia\StateMachine\Api\ProcessProcessInterface;
use Dopamedia\StateMachine\Api\ProcessStateInterface;

interface DrawerInterface
{
    /**
     * @param ProcessProcessInterface $process
     * @param null $highlightState
     * @param null $format
     * @param null $fontSize
     * @return mixed
     */
    public function draw(ProcessProcessInterface $process, $highlightState = null, $format = null, $fontSize = null);

    /**
     * @param ProcessProcessInterface $process
     * @param string|null $highlightState
     * @return void
     */
    public function drawStates(ProcessProcessInterface $process, $highlightState = null);

    /**
     * @param ProcessProcessInterface $process
     * @return void
     */
    public function drawTransitions(ProcessProcessInterface $process);

    /**
     * @param ProcessStateInterface $state
     * @return void
     */
    public function drawTransitionsEvents(ProcessStateInterface $state);

    /**
     * @param ProcessStateInterface $state
     *
     * @return void
     */
    public function drawTransitionsConditions(ProcessStateInterface $state);

    /**
     * @param ProcessProcessInterface $process
     * @return void
     */
    public function drawClusters(ProcessProcessInterface $process);
}