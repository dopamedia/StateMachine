<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 25.07.16
 * Time: 20:07
 */

namespace Dopamedia\StateMachine\Test\Unit\Helper\Generator;

use Dopamedia\StateMachine\Helper\Generator\StringGenerator;

class StringGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateRandomStringReturnsValidString()
    {
        $string = StringGenerator::generateRandomString(16);
        $this->assertEquals(16, strlen($string));
        $this->assertRegExp('/[a-zA-Z0-9_.-]/', $string);
    }
}