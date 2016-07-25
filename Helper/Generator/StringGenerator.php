<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 25.07.16
 * Time: 19:52
 */

namespace Dopamedia\StateMachine\Helper\Generator;

class StringGenerator extends \Zend\Math\Rand
{
    const CHAR_LIST = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_.-';

    /**
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length)
    {
        return parent::getString($length, self::CHAR_LIST);
    }
}