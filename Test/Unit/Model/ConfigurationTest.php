<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 14.07.16
 * Time: 21:44
 */

namespace Dopamedia\StateMachine\Model;


class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\Config\ReaderInterface
     */
    private $readerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\Config\CacheInterface
     */
    private $cacheMock;

    /**
     * @var \Dopamedia\StateMachine\Model\Configuration
     */
    private $model;

    protected function setUp()
    {
        $this->readerMock = $this->getMock(
            'Magento\Framework\Config\ReaderInterface',
            [],
            [],
            '',
            false
        );

        $this->cacheMock = $this->getMock('Magento\Framework\Config\CacheInterface');
    }

    public function testGetAll()
    {
        $expected = ['Expected Data'];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals($expected, $this->model->getAll());
    }

    public function testGetProcess()
    {
        $expected = ['process_name' => ['process_configuration']];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals(['process_configuration'], $this->model->getProcess('process_name'));
    }

    public function testGetStates()
    {
        $expected = ['process_name' =>
            ['states' => ['first', 'second']]
        ];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals(['first', 'second'], $this->model->getStates('process_name'));
    }

    public function testGetStateFlags()
    {
        $expected = ['process_name' =>
            ['states' => ['state_name' => [
                'flags' => ['flag1', 'flag2', 'flag3']
            ]]]
        ];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals(['flag1', 'flag2', 'flag3'], $this->model->getStateFlags('process_name', 'state_name'));
    }

    public function testGetTransitions()
    {
        $expected = ['process_name' =>
            ['transitions' => ['first', 'second']]
        ];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals(['first', 'second'], $this->model->getTransitions('process_name'));
    }

    public function testGetTransitionHappy()
    {
        $expected = ['process_name' =>
            ['transitions' => [[
                'happy' => true
            ]]]
        ];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertTrue($this->model->getTransitionHappy('process_name', 0));
    }

    public function testGetTransitionCondition()
    {
        $expected = ['process_name' =>
            ['transitions' => [[
                'condition' => '\Example\Condition'
            ]]]
        ];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals('\Example\Condition', $this->model->getTransitionCondition('process_name', 0));
    }

    public function testGetTransitionSource()
    {
        $expected = ['process_name' =>
            ['transitions' => [[
                'source' => 'source_state'
            ]]]
        ];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals('source_state', $this->model->getTransitionSource('process_name', 0));
    }

    public function testGetTransitionTarget()
    {
        $expected = ['process_name' =>
            ['transitions' => [[
                'target' => 'target_state'
            ]]]
        ];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals('target_state', $this->model->getTransitionTarget('process_name', 0));
    }

    public function testGetTransitionEvent()
    {
        $expected = ['process_name' =>
            ['transitions' => [[
                'event' => 'event_name'
            ]]]
        ];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals('event_name', $this->model->getTransitionEvent('process_name', 0));
    }

    public function testGetEvents()
    {
        $expected = ['process_name' =>
            ['events' => ['first', 'second']]
        ];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals(['first', 'second'], $this->model->getEvents('process_name'));
    }

    public function testGetEventCommand()
    {
        $expected = ['process_name' =>
            ['events' => ['event_name' => ['command' => '\Example\Command']]]
        ];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals('\Example\Command', $this->model->getEventCommand('process_name', 'event_name'));
    }

    public function testGetEventManual()
    {
        $expected = ['process_name' =>
            ['events' => ['event_name' => ['manual' => false]]]
        ];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');

        $this->assertFalse($this->model->getEventManual('process_name', 'event_name'));
    }

    public function testGetEventOnEnter()
    {
        $expected = ['process_name' =>
            ['events' => ['event_name' => ['onEnter' => true]]]
        ];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertTrue($this->model->getEventOnEnter('process_name', 'event_name'));
    }

    public function testGetOnTimeout()
    {
        $expected = ['process_name' =>
            ['events' => ['event_name' => ['timeout' => '1 day, 1 hour, 1 min']]]
        ];
        $this->cacheMock->expects(
            $this->once()
        )->method(
            'load'
        )->will(
            $this->returnValue(serialize(['processes' => $expected]))
        );
        $this->model = new \Dopamedia\StateMachine\Model\Configuration($this->readerMock, $this->cacheMock, 'cache_id');
        $this->assertEquals('1 day, 1 hour, 1 min', $this->model->getEventTimeout('process_name', 'event_name'));
    }
}