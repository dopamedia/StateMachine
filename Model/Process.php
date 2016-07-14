<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 14.07.16
 * Time: 20:12
 */

namespace Dopamedia\StateMachine\Model;

use Magento\Framework\Api\AbstractSimpleObject;
use Dopamedia\StateMachine\Api\Data\ProcessInterface;

class Process extends AbstractSimpleObject implements ProcessInterface
{
    /**#@+
     * Constants
     */
    const KEY_NAME = 'name';
    /**#@-*/

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->_get(self::KEY_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        return $this->setData(self::KEY_NAME, $name);
    }
}