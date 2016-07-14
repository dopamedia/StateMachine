<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 14.07.16
 * Time: 20:00
 */

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

/**
 * @SuppressWarnings(PHPMD.ShortMethodName)
 */
function __()
{
    return $argc = func_get_args();
}