<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 16.07.16
 * Time: 14:30
 */

namespace Dopamedia\StateMachine\Api;


interface ProcessTransitionInterface
{
    /**
     * @param mixed $happy
     *
     * @return void
     */
    public function setHappyCase($happy);

    /**
     * @return bool
     */
    public function isHappyCase();

    /**
     * @param string $condition
     *
     * @return void
     */
    public function setCondition($condition);

    /**
     * @return string
     */
    public function getCondition();

    /**
     * @return bool
     */
    public function hasCondition();

    /**
     * @param ProcessEventInterface $event
     *
     * @return void
     */
    public function setEvent(ProcessEventInterface $event);

    /**
     * @return ProcessEventInterface
     */
    public function getEvent();

    /**
     * @return bool
     */
    public function hasEvent();

    /**
     * @param ProcessStateInterface $source
     *
     * @return void
     */
    public function setSourceState(ProcessStateInterface $source);

    /**
     * @return ProcessStateInterface
     */
    public function getSourceState();

    /**
     * @param ProcessStateInterface $target
     *
     * @return void
     */
    public function setTargetState(ProcessStateInterface $target);

    /**
     * @return ProcessStateInterface
     */
    public function getTargetState();
}