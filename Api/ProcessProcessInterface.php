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
     * @param ProcessProcessInterface[] $subProcesses
     *
     * @return void
     */
    public function setSubProcesses(array $subProcesses);

    /**
     * @return ProcessProcessInterface[]
     */
    public function getSubProcesses();

    /**
     * @return bool
     */
    public function hasSubProcesses();

    /**
     * @param ProcessProcessInterface $subProcess
     *
     * @return void
     */
    public function addSubProcess(ProcessProcessInterface $subProcess);

    /**
     * @param mixed $main
     *
     * @return void
     */
    public function setMain($main);

    /**
     * @return mixed
     */
    public function getMain();

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

    /**
     * @param mixed $file
     *
     * @return void
     */
    public function setFile($file);

    /**
     * @return bool
     */
    public function hasFile();

    /**
     * @return mixed
     */
    public function getFile();
}