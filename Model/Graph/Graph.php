<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 25.07.16
 * Time: 10:42
 */

namespace Dopamedia\StateMachine\Model\Graph;

use Dopamedia\StateMachine\Model\Graph\Adapter\GraphAdapterInterface;

class Graph implements GraphInterface
{
    /**
     * @var GraphAdapterInterface
     */
    private $adapter;

    /**
     * @param GraphAdapterInterface $adapter
     * @param string $name
     * @param array $attributes
     * @param bool $directed
     * @param bool $strict
     */
    public function __construct(GraphAdapterInterface $adapter, $name, array $attributes = [], $directed = true, $strict = true)
    {
        $this->adapter = $adapter;
        $this->adapter->create($name, $attributes, $directed, $strict);
    }

    /**
     * @inheritDoc
     */
    public function addNode($name, $attributes = [], $group = self::DEFAULT_GROUP)
    {
        $this->adapter->addNode($name, $attributes, $group);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addEdge($fromNode, $toNode, $attributes = [])
    {
        $this->adapter->addEdge($fromNode, $toNode, $attributes);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addCluster($name, $attributes = [])
    {
        $this->adapter->addCluster($name, $attributes);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render($type, $fileName = null)
    {
        return $this->adapter->render($type, $fileName);
    }
}