<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 25.07.16
 * Time: 11:09
 */

namespace Dopamedia\StateMachine\Model\Graph\Adapter;

use Dopamedia\StateMachine\Model\Graph\GraphInterface;

interface GraphAdapterInterface extends GraphInterface
{
    const GRAPH = 'graph';
    const GRAPH_STRICT = 'strict graph';
    const DIRECTED_GRAPH = 'digraph';
    const DIRECTED_GRAPH_STRICT = 'strict digraph';
    const SUB_GRAPH = 'subgraph';
    const SUB_GRAPH_STRICT = 'strict subgraph';

    /**
     * @param string $name
     * @param array $attributes
     * @param bool $directed
     * @param bool $strict
     *
     * @return $this
     */
    public function create($name, array $attributes = [], $directed = true, $strict = true);

}