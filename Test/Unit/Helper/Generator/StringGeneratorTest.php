<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 25.07.16
 * Time: 20:07
 */

namespace Dopamedia\StateMachine\Helper\Generator;

class StringGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StringGenerator
     */
    protected $stringGenerator;

    protected function setUp()
    {
        $this->stringGenerator = new StringGenerator();
    }

    public function testGenerateRandomStringReturnsValidString()
    {
        $string = $this->stringGenerator->generateRandomString(16);
        $this->assertEquals(16, strlen($string));
        $this->assertRegExp('/[a-zA-Z0-9_.-]/', $string);
    }
}