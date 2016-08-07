<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 07.08.16
 * Time: 13:24
 */

namespace Dopamedia\StateMachine\Observer;

use Dopamedia\StateMachine\Model\Process\Process;

class ProcessInjectorObserverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\Event\Observer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $observerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Dopamedia\StateMachine\Model\StateMachine\Builder
     */
    protected $builderMock;

    /**
     * @var ProcessInjectorObserver
     */
    protected $model;

    protected function setUp()
    {
        $this->observerMock = $this
            ->getMockBuilder('Magento\Framework\Event\Observer')
            ->disableOriginalConstructor()
            ->getMock();

        $this->builderMock = $this->getMock('Dopamedia\StateMachine\Model\StateMachine\BuilderInterface');
        $this->model = new ProcessInjectorObserver($this->builderMock);
    }

    public function testExecuteWithoutReturnsNothing()
    {
        /** @var \Magento\Framework\Model\AbstractModel|\PHPUnit_Framework_MockObject_MockObject $object */
        $object = $this->getMockBuilder('Magento\Framework\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->getMock();

        $object->expects($this->once())
            ->method('getData')
            ->with('state_machine_process_name')
            ->willReturn(null);

        $this->observerMock->expects($this->once())
            ->method('getData')
            ->with('object')
            ->willReturn($object);

        $this->assertNull($this->model->execute($this->observerMock));
    }

    public function testExecuteWithoutInjectsProcess()
    {
        /** @var \Magento\Framework\Model\AbstractModel|\PHPUnit_Framework_MockObject_MockObject $object */
        $object = $this->getMockBuilder('Magento\Framework\Model\AbstractModel')
            ->disableOriginalConstructor()
            ->getMock();

        $object->expects($this->once())
            ->method('getData')
            ->with('state_machine_process_name')
            ->willReturn('process_name');

        $this->observerMock->expects($this->once())
            ->method('getData')
            ->with('object')
            ->willReturn($object);

        $process = new Process();

        $this->builderMock->expects($this->once())
            ->method('createProcess')
            ->with('process_name')
            ->willReturn($process);

        $object->expects($this->once())
            ->method('setData')
            ->with('state_machine_process', $process);

        $this->model->execute($this->observerMock);
    }
}