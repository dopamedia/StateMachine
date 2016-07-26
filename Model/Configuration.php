<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 16.07.16
 * Time: 14:46
 */

namespace Dopamedia\StateMachine\Model;

use Dopamedia\StateMachine\Api\ProcessProcessInterface;

class Configuration extends \Magento\Framework\Config\Data implements ConfigurationInterface
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
        return $this->get('processes');
    }

    /**
     * @inheritDoc
     */
    public function getProcess($processName)
    {
        return $this->get('processes/' . $processName, []) ?: null;
    }
}