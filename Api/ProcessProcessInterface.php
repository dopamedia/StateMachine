<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 16.07.16
 * Time: 14:32
 */

namespace Dopamedia\StateMachine\Api;


interface ProcessProcessInterface
{
    /**
     * @param mixed $name
     *
     * @return void
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param string $objectClass
     * @return void
     */
    public function setObjectClass($objectClass);

    /**
     * @return mixed
     */
    public function getObjectClass();

    /**
     * @param ProcessStateInterface[] $states
     *
     * @return void
     */
    public function setStates(array $states);

    /**
     * @param ProcessStateInterface $state
     *
     * @return void
     */
    public function addState(ProcessStateInterface $state);

    /**
     * @param string $stateId
     *
     * @return ProcessStateInterface
     */
    public function getState($stateId);

    /**
     * @param string $stateId
     *
     * @return bool
     */
    public function hasState($stateId);

    /**
     * @param string $stateName
     *
     * @throws \Exception
     *
     * @return ProcessStateInterface
     */
    public function getStateFromAllProcesses($stateName);

    /**
     * @return ProcessStateInterface[]
     */
    public function getStates();

    /**
     * @return bool
     */
    public function hasStates();

    /**
     * @param ProcessTransitionInterface $transition
     *
     * @return void
     */
    public function addTransition(ProcessTransitionInterface $transition);

    /**
     * @param ProcessTransitionInterface[] $transitions
     *
     * @return void
     */
    public function setTransitions(array $transitions);

    /**
     * @return ProcessTransitionInterface[]
     */
    public function getTransitions();

    /**
     * @return bool
     */
    public function hasTransitions();

    /**
     * @return ProcessStateInterface[]
     */
    public function getAllStates();

    /**
     * @return ProcessTransitionInterface[]
     */
    public function getAllTransitions();

    /**
     * @return ProcessTransitionInterface[]
     */
    public function getAllTransitionsWithoutEvent();

    /**
     * @return ProcessEventInterface[]
     */
    public function getManualEvents();

    /**
     * @return array
     */
    public function getManualEventsBySource();

    /**
     * @return ProcessProcessInterface[]
     */
    public function getAllProcesses();
}