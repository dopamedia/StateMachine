<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 16.07.16
 * Time: 14:29
 */

namespace Dopamedia\StateMachine\Api;


interface ProcessEventInterface
{
    /**
     * @param bool $manual
     *
     * @return void
     */
    public function setManual($manual);

    /**
     * @return bool
     */
    public function isManual();

    /**
     * @param string $command
     *
     * @return void
     */
    public function setCommand($command);

    /**
     * @return string
     */
    public function getCommand();

    /**
     * @return bool
     */
    public function hasCommand();

    /**
     * @param bool $onEnter
     *
     * @return void
     */
    public function setOnEnter($onEnter);

    /**
     * @return bool
     */
    public function isOnEnter();

    /**
     * @param string $id
     *
     * @return void
     */
    public function setName($id);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getEventTypeLabel();

    /**
     * @param ProcessTransitionInterface $transition
     *
     * @return void
     */
    public function addTransition(ProcessTransitionInterface $transition);

    /**
     * @param ProcessStateInterface $sourceState
     *
     * @return ProcessTransitionInterface[]
     */
    public function getTransitionsBySource(ProcessStateInterface $sourceState);

    /**
     * @return ProcessTransitionInterface[]
     */
    public function getTransitions();

    /**
     * @param string $timeout
     *
     * @return void
     */
    public function setTimeout($timeout);

    /**
     * @return string
     */
    public function getTimeout();

    /**
     * @return bool
     */
    public function hasTimeout();
}