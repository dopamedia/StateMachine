<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 16.07.16
 * Time: 14:31
 */

namespace Dopamedia\StateMachine\Api;


interface ProcessStateInterface
{
    /**
     * @param ProcessTransitionInterface[] $incomingTransitions
     *
     * @return $this
     */
    public function setIncomingTransitions(array $incomingTransitions);

    /**
     * @return ProcessTransitionInterface[]
     */
    public function getIncomingTransitions();

    /**
     * @return bool
     */
    public function hasIncomingTransitions();

    /**
     * @param array $outgoingTransitions
     * @return ProcessStateInterface
     */
    public function setOutgoingTransitions(array $outgoingTransitions);

    /**
     * @return ProcessTransitionInterface[]
     */
    public function getOutgoingTransitions();

    /**
     * @return bool
     */
    public function hasOutgoingTransitions();

    /**
     * @param ProcessEventInterface $event
     *
     * @return ProcessTransitionInterface[]
     */
    public function getOutgoingTransitionsByEvent(ProcessEventInterface $event);

    /**
     * @return ProcessEventInterface[]
     */
    public function getEvents();

    /**
     * @param string $eventName
     *
     * @throws \Exception
     *
     * @return ProcessEventInterface
     */
    public function getEvent($eventName);

    /**
     * @param string $id
     *
     * @return bool
     */
    public function hasEvent($id);

    /**
     * @return bool
     */
    public function hasAnyEvent();

    /**
     * @param ProcessTransitionInterface $transition
     *
     * @return void
     */
    public function addIncomingTransition(ProcessTransitionInterface $transition);

    /**
     * @param ProcessTransitionInterface $transition
     *
     * @return void
     */
    public function addOutgoingTransition(ProcessTransitionInterface $transition);

    /**
     * @param string $name
     * @return ProcessStateInterface
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param ProcessProcessInterface $process
     * @return ProcessStateInterface
     */
    public function setProcess(ProcessProcessInterface $process);

    /**
     * @return ProcessProcessInterface
     */
    public function getProcess();

    /**
     * @return bool
     */
    public function hasOnEnterEvent();

    /**
     * @throws \Exception
     *
     * @return ProcessEventInterface
     */
    public function getOnEnterEvent();

    /**
     * @return bool
     */
    public function hasTimeoutEvent();

    /**
     * @throws \Exception
     *
     * @return ProcessEventInterface[]
     */
    public function getTimeoutEvents();

    /**
     * @param string $flag
     *
     * @return ProcessStateInterface
     */
    public function addFlag($flag);

    /**
     * @param string $flag
     *
     * @return bool
     */
    public function hasFlag($flag) ;

    /**
     * @return bool
     */
    public function hasFlags();

    /**
     * @return array
     */
    public function getFlags();
}