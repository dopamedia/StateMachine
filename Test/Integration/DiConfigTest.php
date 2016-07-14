<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 06.07.16
 * Time: 20:39
 */

namespace Dopamedia\StateMachine;

use Magento\Framework\ObjectManager\ConfigInterface as ObjectManagerConfig;
use Magento\TestFramework\ObjectManager;

/**
 * Class DiConfigTest
 * @package Dopamedia\StateMachine
 */
class DiConfigTest extends \PHPUnit_Framework_TestCase
{
    protected $configType = Model\Config\Virtual::class;
    protected $readerType = Model\Config\Reader\Virtual::class;
    protected $schemaLocatorType = Model\Config\SchemaLocator\Virtual::class;
    protected $converterType = Model\Config\Converter::class;

    /**
     * @return ObjectManagerConfig
     */
    protected function getDiConfig()
    {
        return ObjectManager::getInstance()->get(ObjectManagerConfig::class);
    }

    /**
     * @param string $expectedType
     * @param string $type
     */
    protected function assertVirtualType($expectedType, $type)
    {
        $this->assertSame($expectedType, $this->getDiConfig()->getInstanceType($type));
    }

    /**
     * @param mixed $expected
     * @param string $type
     * @param string $argumentName
     */
    protected function assertDiArgumentSame($expected, $type, $argumentName)
    {
        $arguments = $this->getDiConfig()->getArguments($type);
        if (!isset($arguments[$argumentName])) {
            $this->fail(sprintf('No argument "%s" configured for %s', $argumentName, $type));
        }
        $this->assertSame($expected, $arguments[$argumentName]);
    }


    /**
     * @param string $expectedType
     * @param string $type
     * @param string $argumentName
     */
    protected function assertDiArgumentInstance($expectedType, $type, $argumentName)
    {
        $arguments = $this->getDiConfig()->getArguments($type);

        if (!isset($arguments[$argumentName])) {
            $this->fail(sprintf('No argument "%s" configured for %s', $argumentName, $type));
        }

        if (!isset($arguments[$argumentName]['instance'])) {
            $this->fail(sprintf('Argument "%s" for %s is not xsi:type="object"', $argumentName, $type));
        }

        $this->assertSame($expectedType, $arguments[$argumentName]['instance']);
    }

    /**
     * @test
     */
    public function testConfigDataDiConfig()
    {
        $this->assertVirtualType(\Magento\Framework\Config\Data::class, $this->configType);
        $this->assertDiArgumentSame('dopamedia_state_machine', $this->configType, 'cacheId');
        $this->assertDiArgumentInstance($this->readerType, $this->configType, 'reader');
    }

    /**
     * @test
     */
    public function testConfigReaderDiConfig()
    {
        $this->assertVirtualType(\Magento\Framework\Config\Reader\Filesystem::class, $this->readerType);
        $this->assertDiArgumentSame('state_machine.xml', $this->readerType, 'fileName');
        $this->assertDiArgumentInstance($this->schemaLocatorType, $this->readerType, 'schemaLocator');
        $this->assertDiArgumentInstance($this->converterType, $this->readerType, 'converter');
    }

    /**
     * @test
     */
    public function testConfigSchemaLocatorDiConfig()
    {
        $this->assertVirtualType(\Magento\Framework\Config\GenericSchemaLocator::class, $this->schemaLocatorType);
        $this->assertDiArgumentSame('Dopamedia_StateMachine', $this->schemaLocatorType, 'moduleName');
        $this->assertDiArgumentSame('state_machine.xsd', $this->schemaLocatorType, 'schema');
    }

    /**
     * @test
     */
    public function testDataCanBeAccessed()
    {
        $this->markTestSkipped(
            'since the module itself does not contain a state_machine.xml this test could not be passed'
        );
        /** @var \Magento\Framework\Config\DataInterface $stateMachineConfig */
        $stateMachineConfig = ObjectManager::getInstance()->create($this->configType);
        $configData = $stateMachineConfig->get(null);
        $this->assertInternalType('array', $configData);
        $this->assertNotEmpty($configData);
    }
}