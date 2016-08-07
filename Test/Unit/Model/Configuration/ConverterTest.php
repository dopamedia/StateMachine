<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 06.07.16
 * Time: 22:38
 */

namespace Dopamedia\StateMachine\Model\Configuration;

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
    <process name="simple" object="Vendor\Namespace\Model\Object">
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
        $this->assertArrayHasKey('processes', $result);
        $this->assertArrayHasKey('simple', $result['processes']);
        $this->assertArrayHasKey('objectClass', $result['processes']['simple']);
        $this->assertArrayHasKey('states', $result['processes']['simple']);
        $this->assertArrayHasKey('transitions', $result['processes']['simple']);
        $this->assertArrayHasKey('events', $result['processes']['simple']);
    }
}