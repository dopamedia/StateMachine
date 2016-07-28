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

    /**
     * @param string $processName
     * @return array|null
     */
    public function getStates($processName);

    /**
     * @param string $processName
     * @param string $stateName
     * @return array|null
     */
    public function getStateFlags($processName, $stateName);

    /**
     * @param string $processName
     * @return array|null
     */
    public function getTransitions($processName);

    /**
     * @param string $processName
     * @param string $transitionName
     * @return bool|null
     */
    public function getTransitionHappy($processName, $transitionName);

    /**
     * @param string $processName
     * @param int $transitionIndex
     * @return string|null
     */
    public function getTransitionCondition($processName, $transitionIndex);

    /**
     * @param string $processName
     * @param int $transitionIndex
     * @return string|null
     */
    public function getTransitionSource($processName, $transitionIndex);

    /**
     * @param string $processName
     * @param int $transitionIndex
     * @return string|null
     */
    public function getTransitionTarget($processName, $transitionIndex);

    /**
     * @param string $processName
     * @param int $transitionIndex
     * @return string|null
     */
    public function getTransitionEvent($processName, $transitionIndex);

    /**
     * @param string $processName
     * @return array|null
     */
    public function getEvents($processName);

    /**
     * @param string $processName
     * @param string $eventName
     * @return string|null
     */
    public function getEventCommand($processName, $eventName);

    /**
     * @param string $processName
     * @param string $eventName
     * @return bool|null
     */
    public function getEventManual($processName, $eventName);

    /**
     * @param string $processName
     * @param string $eventName
     * @return bool|null
     */
    public function getEventOnEnter($processName, $eventName);

    /**
     * @param string $processName
     * @param string $eventName
     * @return string|null
     */
    public function getEventTimeout($processName, $eventName);
}