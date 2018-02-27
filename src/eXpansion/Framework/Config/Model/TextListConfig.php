<?php


namespace eXpansion\Framework\Config\Model;


/**
 * Class TextConfig
 *
 * @package eXpansion\Framework\Config\Model;
 * @author oliverde8
 */
class TextListConfig extends TextConfig
{

    /**
     * Add a new value to the list.
     *
     * @param $element
     *
     * @return $this
     */
    public function add($element) {
        $currentValue = $this->getRawValue();

        if (!in_array($element, $currentValue)) {
            $currentValue[] = $element;
            $this->setRawValue($currentValue);
        }

        return $this;
    }

    /**
     * Remove an element from the list.
     *
     * @param $element
     */
    public function remove($element)
    {
        $currentValue = $this->getRawValue();

        if (($key = array_search($element, $currentValue)) !== false) {
            unset($currentValue[$key]);
            $this->setRawValue($currentValue);
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode(',', $this->get());
    }
}
