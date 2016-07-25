<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 25.07.16
 * Time: 10:32
 */

namespace Dopamedia\StateMachine\Model\Graph;

use Dopamedia\StateMachine\Api\ProcessProcessInterface;
use Dopamedia\StateMachine\Api\ProcessStateInterface;
use Dopamedia\StateMachine\Api\ProcessTransitionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Dopamedia\StateMachine\Helper\Generator\StringGenerator;

class Drawer implements DrawerInterface
{
    const ATTRIBUTE_FONT_SIZE = 'fontsize';

    const EDGE_UPPER_HALF = 'upper half';
    const EDGE_LOWER_HALF = 'lower half';
    const EDGE_FULL = 'edge full';
    const HIGHLIGHT_COLOR = '#FFFFCC';
    const HAPPY_PATH_COLOR = '#70ab28';

    /**
     * @var array
     */
    protected $attributesProcess = [
        'fontname' => 'Verdana',
        'fillcolor' => '#cfcfcf',
        'style' => 'filled',
        'color' => '#ffffff',
        'fontsize' => 12,
        'fontcolor' => 'black'
    ];

    /**
     * @var array
     */
    protected $attributesState = [
        'fontname' => 'Verdana',
        'fontsize' => 14,
        'style' => 'filled',
        'fillcolor' => '#f9f9f9'
    ];

    /**
     * @var array
     */
    protected $attributesDiamond = [
        'fontname' => 'Verdana',
        'label' => '?',
        'shape' => 'diamond',
        'fontcolor' => 'white',
        'fontsize' => '1',
        'style' => 'filled',
        'fillcolor' => '#f9f9f9'
    ];

    /**
     * @var array
     */
    protected $attributesTransition = [
        'fontname' => 'Verdana',
        'fontsize' => 12
    ];

    /**
     * @var string
     */
    protected $brLeft = '<br align="left" />  ';

    /**
     * @var string
     */
    protected $notImplemented = '<font color="red">(not implemented)</font>';

    /**
     * @var string
     */
    protected $br = '<br/>';

    /**
     * @var string
     */
    protected $format = 'svg';

    /**
     * @var int
     */
    protected $fontSizeBig = null;

    /**
     * @var int
     */
    protected $fontSizeSmall = null;

    /**
     * @var \Dopamedia\StateMachine\Model\Graph\GraphInterface
     */
    protected $graph;

    /**
     * @param \Dopamedia\StateMachine\Model\Graph\GraphInterface $graph
     */
    public function __construct(
        \Dopamedia\StateMachine\Model\Graph\GraphInterface $graph
    )
    {
        $this->graph = $graph;
    }

    /**
     * @inheritDoc
     */
    public function draw(ProcessProcessInterface $process, $highlightState = null, $format = null, $fontSize = null)
    {
        $this->init($format, $fontSize);
        $this->drawClusters($process);
        $this->drawStates($process, $highlightState);
        $this->drawTransitions($process);

        return $this->graph->render($this->format);
    }

    /**
     * @inheritDoc
     */
    public function drawStates(ProcessProcessInterface $process, $highlightState = null)
    {
        $states = $process->getAllStates();
        foreach ($states as $state) {
            $isHighlighted = $highlightState === $state->getName();
            $this->addNode($state, [], null, $isHighlighted);
        }
    }

    /**
     * @inheritDoc
     */
    public function drawTransitions(ProcessProcessInterface $process)
    {
        $states = $process->getAllStates();
        foreach ($states as $state) {
            $this->drawTransitionsEvents($state);
            $this->drawTransitionsConditions($state);
        }
    }

    /**
     * @return string
     */
    protected function getDiamondId()
    {
        return StringGenerator::generateRandomString(16);
    }

    /**
     * @inheritDoc
     */
    public function drawTransitionsEvents(ProcessStateInterface $state)
    {
        $events = $state->getEvents();
        foreach ($events as $event) {
            $transitions = $state->getOutgoingTransitionsByEvent($event);

            $currentTransition = current($transitions);
            if (!$currentTransition) {
                throw new LocalizedException(
                    new Phrase(
                        'Transitions container seems to be empty.'
                    )
                );
            }

            if (count($transitions) > 1) {
                $diamondId = $this->getDiamondId();

                $this->graph->addNode($diamondId, $this->attributesDiamond, $state->getProcess()->getName());
                $this->addEdge($currentTransition, self::EDGE_UPPER_HALF, [], null, $diamondId);

                foreach ($transitions as $transition) {
                    $this->addEdge($transition, self::EDGE_LOWER_HALF, [], $diamondId);
                }
            } else {
                $this->addEdge($currentTransition, self::EDGE_FULL);
            }
        }
    }

    /**
     * @param ProcessStateInterface $state
     *
     * @return void
     */
    public function drawTransitionsConditions(ProcessStateInterface $state)
    {
        $transitions = $state->getOutgoingTransitions();
        foreach ($transitions as $transition) {
            if ($transition->hasEvent()) {
                continue;
            }
            $this->addEdge($transition);
        }
    }

    /**
     * @inheritDoc
     */
    public function drawClusters(ProcessProcessInterface $process)
    {
        $processes = $process->getAllProcesses();
        foreach ($processes as $subProcess) {
            $group = $subProcess->getName();
            $attributes = $this->attributesProcess;
            $attributes['label'] = $group;
            $this->graph->addCluster($group, $attributes);
        }
    }

    /**
     * @param ProcessStateInterface $state
     * @param array $attributes
     * @param string|null $name
     * @param bool $highlighted
     *
     * @return void
     */
    protected function addNode(ProcessStateInterface $state, $attributes = [], $name = null, $highlighted = false)
    {
        $name = $name === null ? $state->getName() : $name;

        $label = [];
        $label[] = str_replace(' ', $this->br, trim($name));

        if ($state->hasFlags()) {
            $flags = implode(', ', $state->getFlags());
            $label[] = '<font color="violet" point-size="' . $this->fontSizeSmall . '">' . $flags . '</font>';
        }

        $attributes['label'] = implode($this->br, $label);

        if (!$state->hasOutgoingTransitions() || $this->hasOnlySelfReferences($state)) {
            $attributes['peripheries'] = 2;
        }

        if ($highlighted) {
            $attributes['fillcolor'] = self::HIGHLIGHT_COLOR;
        }

        $attributes = array_merge($this->attributesState, $attributes);
        $this->graph->addNode($name, $attributes, $state->getProcess()->getName());
    }

    /**
     * @param ProcessStateInterface $state
     *
     * @return bool
     */
    protected function hasOnlySelfReferences(ProcessStateInterface $state)
    {
        $hasOnlySelfReferences = true;
        $transitions = $state->getOutgoingTransitions();
        foreach ($transitions as $transition) {
            if ($transition->getTargetState()->getName() !== $state->getName()) {
                $hasOnlySelfReferences = false;
                break;
            }
        }
        return $hasOnlySelfReferences;
    }

    /**
     * @param ProcessTransitionInterface $transition
     * @param string $type
     * @param array $attributes
     * @param string|null $fromName
     * @param string|null $toName
     *
     * @return void
     */
    protected function addEdge(ProcessTransitionInterface $transition, $type = self::EDGE_FULL, $attributes = [], $fromName = null, $toName = null)
    {
        $label = [];

        if ($type !== self::EDGE_LOWER_HALF) {
            $label = $this->addEdgeEventText($transition, $label);
        }

        if ($type !== self::EDGE_UPPER_HALF) {
            $label = $this->addEdgeConditionText($transition, $label);
        }

        $label = $this->addEdgeElse($label);
        $fromName = $this->addEdgeFromState($transition, $fromName);
        $toName = $this->addEdgeToState($transition, $toName);
        $attributes = $this->addEdgeAttributes($transition, $attributes, $label, $type);

        $this->graph->addEdge($fromName, $toName, $attributes);
    }

    /**
     * @param ProcessTransitionInterface $transition
     * @param array $label
     *
     * @return array
     */
    protected function addEdgeConditionText(ProcessTransitionInterface $transition, array $label)
    {
        if ($transition->hasCondition()) {
            $conditionLabel = $transition->getCondition();

            #if (!isset($this->stateMachineHandler->getConditionPlugins()[$transition->getCondition()])) {
                $conditionLabel .= ' ' . $this->notImplemented;
            #}

            $label[] = $conditionLabel;
        }

        return $label;
    }

    /**
     * @param ProcessTransitionInterface $transition
     * @param array $label
     *
     * @return array
     */
    protected function addEdgeEventText(ProcessTransitionInterface $transition, array $label)
    {
        if ($transition->hasEvent()) {
            $event = $transition->getEvent();

            if ($event->isOnEnter()) {
                $label[] = '<b>' . $event->getName() . ' (on enter)</b>';
            } else {
                $label[] = '<b>' . $event->getName() . '</b>';
            }

            if ($event->hasTimeout()) {
                $label[] = 'timeout: ' . $event->getTimeout();
            }

            if ($event->hasCommand()) {
                $commandLabel = 'command:' . $event->getCommand();

                #if (!isset($this->stateMachineHandler->getCommandPlugins()[$event->getCommand()])) {
                    $commandLabel .= ' ' . $this->notImplemented;
                #}
                $label[] = $commandLabel;
            }

            if ($event->isManual()) {
                $label[] = 'manually executable';
            }
        } else {
            $label[] = '&infin;';
        }

        return $label;
    }

    /**
     * @param array $label
     *
     * @return string
     */
    protected function addEdgeElse(array $label)
    {
        if (!empty($label)) {
            $label = implode($this->brLeft, $label);
        } else {
            $label = 'else';
        }

        return $label;
    }

    /**
     * @param ProcessTransitionInterface $transition
     * @param array $attributes
     * @param string $label
     * @param string $type
     *
     * @return array
     */
    protected function addEdgeAttributes(ProcessTransitionInterface $transition, array $attributes, $label, $type = self::EDGE_FULL)
    {
        $attributes = array_merge($this->attributesTransition, $attributes);
        $attributes['label'] = '  ' . $label;

        if ($transition->hasEvent() === false) {
            $attributes['style'] = 'dashed';
        }

        if ($type === self::EDGE_FULL || $type === self::EDGE_UPPER_HALF) {
            if ($transition->hasEvent() && $transition->getEvent()->isOnEnter()) {
                $attributes['arrowtail'] = 'crow';
                $attributes['dir'] = 'both';
            }
        }

        if ($transition->isHappyCase()) {
            $attributes['weight'] = '100';
            $attributes['color'] = self::HAPPY_PATH_COLOR;
        } elseif ($transition->hasEvent()) {
            $attributes['weight'] = '10';
        } else {
            $attributes['weight'] = '1';
        }

        return $attributes;
    }

    /**
     * @param ProcessTransitionInterface $transition
     * @param string $fromName
     *
     * @return string
     */
    protected function addEdgeFromState(ProcessTransitionInterface $transition, $fromName)
    {
        $fromName = $fromName !== null ? $fromName : $transition->getSourceState()->getName();

        return $fromName;
    }

    /**
     * @param ProcessTransitionInterface $transition
     * @param string|null $toName
     *
     * @return string
     */
    protected function addEdgeToState(ProcessTransitionInterface $transition, $toName)
    {
        $toName = $toName !== null ? $toName : $transition->getTargetState()->getName();

        return $toName;
    }

    /**
     * @param string|null $format
     * @param int|null $fontSize
     */
    protected function init($format, $fontSize)
    {
        if ($format !== null) {
            $this->format = $format;
        }

        if ($fontSize !== null) {
            $this->attributesState[self::ATTRIBUTE_FONT_SIZE] = $fontSize;
            $this->attributesProcess[self::ATTRIBUTE_FONT_SIZE] = $fontSize - 2;
            $this->attributesTransition[self::ATTRIBUTE_FONT_SIZE] = $fontSize - 2;
            $this->fontSizeBig = $fontSize;
            $this->fontSizeSmall = $fontSize - 2;
        }
    }
}