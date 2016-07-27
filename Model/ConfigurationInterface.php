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
     * @return array|null
     */
    public function getTransitions($processName);

    /**
     * @param string $processName
     * @return array|null
     */
    public function getEvents($processName);

    /**
     * @param string $processName
     * @param string $eventName
     * @return string
     */
    public function getEventCommand($processName, $eventName);

    /**
     * @param string $processName
     * @param string $eventName
     * @return bool
     */
    public function getEventManual($processName, $eventName);

    /**
     * @param string $processName
     * @param string $eventName
     * @return bool
     */
    public function getEventOnEnter($processName, $eventName);

    /**
     * @param string $processName
     * @param string $eventName
     * @return string
     */
    public function getEventTimeout($processName, $eventName);
}