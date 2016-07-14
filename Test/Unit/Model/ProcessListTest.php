<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 14.07.16
 * Time: 21:29
 */

namespace Dopamedia\StateMachine\Test\Unit\Model;

use \Dopamedia\StateMachine\Model\ProcessList;

class ProcessListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProcessList
     */
    private $model;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Dopamedia\StateMachine\Model\Processes\ConfigInterface
     */
    private $processConfigMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Dopamedia\StateMachine\Api\Data\ProcessInterfaceFactory
     */
    private $processConfigFactoryMock;

    protected function setUp()
    {
        $this->processConfigMock = $this->getMock('Dopamedia\StateMachine\Model\Processes\ConfigInterface');
        $this->processConfigFactoryMock = $this->getMock(
            'Dopamedia\StateMachine\Api\Data\ProcessInterfaceFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->model = new ProcessList(
            $this->processConfigMock,
            $this->processConfigFactoryMock
        );
    }

    public function testGetProcesses()
    {
        $process = [
            'name' => 'process'
        ];

        $processTypeData = [
            $process
        ];

        $processMock = $this->getMock('\Dopamedia\StateMachine\Api\Data\ProcessInterface');
        $this->processConfigMock->expects($this->any())->method('getAll')->will($this->returnValue($processTypeData));

        $this->processConfigFactoryMock->expects($this->any())->method('create')->willReturn($processMock);

        $processMock->expects($this->once())
            ->method('setName')
            ->with($process['name'])
            ->willReturnSelf();

        $processes = $this->model->getProcesses();
        $this->assertCount(1, $process);
        $this->assertContains($processMock, $processes);


    }
}