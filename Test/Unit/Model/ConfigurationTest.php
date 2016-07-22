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
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $readerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
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
}