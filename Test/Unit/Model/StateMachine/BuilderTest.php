<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 17.07.16
 * Time: 17:23
 */

namespace Dopamedia\StateMachine\Model\StateMachine;

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected static $configurationData = [
        'identifier' => [
            'states' => [
                'start' => [],
                'end' => []
            ],
            'transitions' => [
                [
                    'source' => 'start',
                    'target' => 'end',
                    'event' => 'finish'
                ]
            ],
            'events' => [
                'finish' => []
            ]
        ]
    ];

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Dopamedia\StateMachine\Model\Configuration
     */
    protected $configurationMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Dopamedia\StateMachine\Model\StateMachine\Builder
     */
    protected $builder;

    protected function setUp()
    {
        $this->configurationMock = $this->getMock(
            'Dopamedia\StateMachine\Model\Configuration',
            [],
            [],
            '',
            false
        );

        $this->builder = new Builder(
            new \Dopamedia\StateMachine\Model\Process\Event(),
            new \Dopamedia\StateMachine\Model\Process\Process(),
            new \Dopamedia\StateMachine\Model\Process\State(),
            new \Dopamedia\StateMachine\Model\Process\Transition(),
            $this->configurationMock
        );
    }

    public function testCreateProcessReturnsProcessInstance()
    {
        $this->configurationMock->expects($this->any())
            ->method('getAll')
            ->willReturn(self::$configurationData);

        $process = $this->builder->createProcess('identifier');
        $this->assertInstanceOf(\Dopamedia\StateMachine\Api\ProcessProcessInterface::class, $process);
    }

    public function testCreateProcessShouldIncludeAllStatesFromConfiguration()
    {
        $this->configurationMock->expects($this->any())
            ->method('getAll')
            ->willReturn(self::$configurationData);

        $process = $this->builder->createProcess('identifier');
        $this->assertCount(2, $process->getStates());
        $this->assertInstanceOf(\Dopamedia\StateMachine\Model\Process\State::class, $process->getStates()['end']);
    }

    public function testCreateProcessShouldIncludeAllTransitions()
    {
        $this->configurationMock->expects($this->any())
            ->method('getAll')
            ->willReturn(self::$configurationData);

        $process = $this->builder->createProcess('identifier');

        $this->assertCount(1, $process->getTransitions());
        $this->assertInstanceOf(\Dopamedia\StateMachine\Model\Process\Transition::class, $process->getTransitions()[0]);
    }

    public function testCreateProcessShouldIncludeAllSubProcesses()
    {
        $this->markTestSkipped('not implemented yet');
    }

    public function testCreateProcessShouldFlagMainProcess()
    {
        $this->markTestSkipped('not implemented yet');
    }
}