<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 06.07.16
 * Time: 22:38
 */

namespace Dopamedia\StateMachine\Model\Config\StateMachine;


use Magento\Framework\Config\ConverterInterface;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Converter
     */
    protected $converter;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->converter = new Converter();
    }

    /**
     * @param string $xml
     * @return \DOMDocument
     */
    protected function createSource($xml)
    {
        $source = new \DOMDocument();
        $source->loadXML($xml);
        return $source;
    }

    /**
     * @test
     */
    public function testCanBeInstantiated()
    {
        $this->assertInstanceOf(ConverterInterface::class, $this->converter);
    }

    /**
     * @test
     */
    public function testReturnEmptyArrayForEmptyDocument()
    {
        $source = $this->createSource('<empty/>');
        $this->assertSame([], $this->converter->convert($source));
    }

    /**
     * @test
     */
    public function testAddsProcessSubArrayKey()
    {
        $xml = <<<XML
<state_machine>
    <process name="simple">
        <states>
            <state name="start" />
            <state name="end" />
        </states>
        <transitions>
            <transition>
                <source>start</source>
                <target>end</target>
                <event>finish</event>
            </transition>
        </transitions>
        <events>
            <event name="finish" />
        </events>
    </process>
</state_machine>
XML;
        $result = $this->converter->convert($this->createSource($xml));
        $this->assertArrayHasKey('simple', $result);
        $this->assertArrayHasKey('states', $result['simple']);
        $this->assertArrayHasKey('transitions', $result['simple']);
        $this->assertArrayHasKey('events', $result['simple']);
    }
}