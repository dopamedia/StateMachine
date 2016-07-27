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
    protected static $processData = [
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
            'finish' => [
                'command' => '\Example\Command',
                'manual' => true,
                'onEnter' => false,
                'timeout' => null
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
        $this->configurationMock = $this->getMock('Dopamedia\StateMachine\Model\ConfigurationInterface');

        $this->builder = new Builder(
            new \Dopamedia\StateMachine\Model\Process\Event(),
            new \Dopamedia\StateMachine\Model\Process\Process(),
            new \Dopamedia\StateMachine\Model\Process\State(),
            new \Dopamedia\StateMachine\Model\Process\Transition(),
            $this->configurationMock
        );

        // disable the processBuffer
        $refObject = new \ReflectionObject($this->builder);
        $refProperty = $refObject->getProperty('processBuffer');
        $refProperty->setAccessible(true);
        $refProperty->setValue(null, []);
    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     * @expectedExceptionMessage Process "absent_process" does not exist
     */
    public function testCreateProcessWithAbsentProcessNameThrowsException()
    {
        $this->configurationMock->expects($this->once())
            ->method('getProcess')
            ->willReturn(null);

        $this->builder->createProcess('absent_process');
    }

    public function testCreateProcessReturnsProcessInstance()
    {
        $this->configurationMock->expects($this->once())
            ->method('getProcess')
            ->willReturn(self::$processData);

        $this->configurationMock->expects($this->once())
            ->method('getStates')
            ->willReturn(self::$processData['states']);

        $this->configurationMock->expects($this->once())
            ->method('getEvents')
            ->willReturn(self::$processData['events']);

        $process = $this->builder->createProcess('identifier');
        $this->assertInstanceOf(\Dopamedia\StateMachine\Api\ProcessProcessInterface::class, $process);
    }

    public function testCreateProcessShouldIncludeAllStatesFromConfiguration()
    {
        $this->configurationMock->expects($this->once())
            ->method('getProcess')
            ->willReturn(self::$processData);

        $this->configurationMock->expects($this->once())
            ->method('getStates')
            ->willReturn(self::$processData['states']);

        $this->configurationMock->expects($this->once())
            ->method('getEvents')
            ->willReturn(self::$processData['events']);

        $process = $this->builder->createProcess('identifier');
        $this->assertCount(2, $process->getStates());
        $this->assertInstanceOf(\Dopamedia\StateMachine\Model\Process\State::class, $process->getStates()['end']);
    }

    public function testCreateProcessShouldIncludeAllTransitions()
    {
        $this->configurationMock->expects($this->once())
            ->method('getProcess')
            ->willReturn(self::$processData);

        $this->configurationMock->expects($this->once())
            ->method('getStates')
            ->willReturn(self::$processData['states']);

        $this->configurationMock->expects($this->once())
            ->method('getEvents')
            ->willReturn(self::$processData['events']);

        $this->configurationMock->expects($this->once())
            ->method('getTransitions')
            ->will($this->returnValue(self::$processData['transitions']));

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