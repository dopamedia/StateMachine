<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 14.07.16
 * Time: 21:01
 */

namespace Dopamedia\StateMachine\Model\Processes;

class Config extends \Magento\Framework\Config\Data implements ConfigInterface
{
    /**
     * @param \Magento\Framework\Config\ReaderInterface $reader
     * @param \Magento\Framework\Config\CacheInterface $cache
     * @param string $cacheId
     */
    public function __construct(
        \Magento\Framework\Config\ReaderInterface $reader,
        \Magento\Framework\Config\CacheInterface $cache,
        $cacheId = 'dopamedia_state_machine'
    )
    {
        parent::__construct($reader, $cache, $cacheId);
    }

    /**
     * @inheritdoc
     */
    public function getAll()
    {
        // TODO: Implement getAll() method.
    }
}