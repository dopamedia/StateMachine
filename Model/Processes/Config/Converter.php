<?php
/**
 * Created by PhpStorm.
 * User: pandi
 * Date: 06.07.16
 * Time: 22:46
 */

namespace Dopamedia\StateMachine\Model\Processes\Config;

class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * @inheritDoc
     */
    public function convert($document)
    {
        $result = [];
        $rootElement = $this->getRootElement($document);
        foreach ($this->getChildrenByName($rootElement, 'process') as $processNode) {
            $processName = $processNode->attributes->getNamedItem('name')->nodeValue;
            $result[$processName] = [
                'states' => $this->gatherStates($processNode),
                'transitions' => $this->gatherTransitions($processNode),
                'events' => $this->gatherEvents($processNode)
            ];
        }
        return $result;
    }

    /**
     * @param \DOMElement $parent
     * @param string $name
     * @return \DOMElement
     */
    public function getFirstChildByName(\DOMElement $parent, $name)
    {
        $children = $this->getChildrenByName($parent, $name);
        return reset($children);
    }

    /**
     * @param \DOMElement $parent
     * @param string $name
     * @return \DomElement[]
     */
    public function getChildrenByName(\DOMElement $parent, $name)
    {
        return array_filter($this->getAllChildElements($parent), function(\DOMElement $child) use ($name) {
            return $child->nodeName === $name;
        });
    }

    /**
     * @param \DOMDocument $document
     * @return \DOMElement
     */
    private function getRootElement(\DOMDocument $document)
    {
        return $this->getAllChildElements($document)[0];
    }

    /**
     * @param \DOMNode $parent
     * @return \DOMElement[]
     */
    private function getAllChildElements(\DOMNode $parent)
    {
        return array_filter(iterator_to_array($parent->childNodes), function(\DOMNode $child) {
            return $child->nodeType === \XML_ELEMENT_NODE;
        });
    }

    /**
     * @param \DOMElement $parentNode
     * @return array
     */
    protected function gatherStates(\DOMElement $parentNode)
    {
        $states = [];
        foreach ($this->getChildrenByName($parentNode, 'states') as $statesNode) {
            foreach ($this->getAllChildElements($statesNode) as $stateNode) {
                $stateName = $stateNode->attributes->getNamedItem('name')->nodeValue;
                $states[$stateName] = [];
            }
        }
        return $states;
    }

    /**
     * @param \DOMElement $parentNode
     * @return array
     */
    protected function gatherTransitions(\DOMElement $parentNode)
    {
        $transitions = [];
        foreach ($this->getChildrenByName($parentNode, 'transitions') as $transitionsNode) {
            foreach ($this->getAllChildElements($transitionsNode) as $transitionNode) {
                $transitions[] = [
                    'source' => $this->getFirstChildByName($transitionNode, 'source')->nodeValue,
                    'target' => $this->getFirstChildByName($transitionNode, 'target')->nodeValue,
                    'event' => $this->getFirstChildByName($transitionNode, 'event')->nodeValue
                ];
            }
        }
        return $transitions;
    }

    /**
     * @param \DOMElement $parentNode
     * @return array
     */
    protected function gatherEvents(\DOMElement $parentNode)
    {
        $events = [];
        foreach ($this->getChildrenByName($parentNode, 'events') as $eventsNode) {
            foreach ($this->getAllChildElements($eventsNode) as $eventNode) {
                $eventName = $eventNode->attributes->getNamedItem('name')->nodeValue;
                $events[$eventName] = [];
            }
        }
        return $events;
    }
}